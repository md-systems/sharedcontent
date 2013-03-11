#!/bin/bash

# --------
# Prepare environment
# --------

cp $WS_PATH/sites/example.sites.php $WS_PATH/sites/sites.php

cd $WS_PATH

IFS='@' read -a db_data <<< "$DB_URL"
IFS=':' read -a db_credentials <<< "$db_data"

IFS=',' read -a SITES_ARR <<< "$SITES"
IFS=',' read -a SERVERS_ARR <<< "$SERVERS"
IFS=',' read -a CLIENTS_ARR <<< "$CLIENTS"

echo '================ Install sites ================'
for site in ${SITES_ARR[@]}
do
  echo "Initiating site: " ${site}

  # Create DB
  mysqladmin -u ${db_credentials[0]} -p${db_credentials[1]} create "${BUILD_TAG}_${site}"
  # Configure sites.php
  echo "\$sites['${site}.$DOMAIN'] = '${site}'; " >> $WS_PATH/sites/sites.php
  # Install site
  drush si --yes --db-url="mysql://${DB_URL}/${BUILD_TAG}_${site}" --sites-subdir="${site}" --site-name="Shared Content ${site}" --account-name=admin --account-pass=q
done

# --------
# Configure servers
# --------

echo '================ Configure servers ================'
for server in ${SERVERS_ARR[@]}
do
  echo "Configuring server: " ${server}

  cd $WS_PATH/sites/${server}

  # Install past logging
  drush en past_db -y

  # Install sharedcontent server modules
  drush en sharedcontent_server_feature -y
  drush vset sharedcontent_include_local TRUE
  drush cc all
  # Change permissions so that drush script is able to write inside the site dir
  chmod -R 777 $WS_PATH/sites/${server}
  drush sc-features $BUILD_TAG${server} $WS_PATH/sites/${server}/modules "article, page"
  drush en $BUILD_TAG${server}_sharedcontent_server_rules -y
  drush cc all

  # Create user and role with perms to be able to access SC services
  drush ucrt sc-client-user --password="sc-client-user"
  drush ev "
    \$role = (object) array('name' => 'sc-client-role');
    user_role_save(\$role);
    \$account = user_load_by_name('sc-client-user');
    user_role_grant_permissions(\$role->rid, array('access endpoint restricted'));"
  drush urol sc-client-role sc-client-user

  # Generate content
  php -r "print json_encode(array('node' => array('article' => 1, 'page' => 1)));" | drush vset --format=json sharedcontent_indexed_entities -
  drush sc-keywords add field_tags article
  drush en devel_generate -y
  drush genc 30 3
  drush cron

  # Configure the output
  drush en stark -y
  php -r "print json_encode(array('toggle_slogan' => 0, 'toggle_node_user_picture' => 0, 'toggle_comment_user_picture' => 0, 'toggle_comment_user_verification' => 0, 'toggle_main_menu' => 0, 'toggle_secondary_menu' => 0));" | drush vset --format=json theme_stark_settings:  -
  drush ev "db_update('block')->fields(array('status' => 0))->condition('theme', 'stark')->condition('delta', array('help','main'), 'NOT IN')->execute();"
  drush vset sharedcontent_overlay_theme stark

done

# --------
# Configure clients
# --------

echo '================ Configure clients ================'
for client in ${CLIENTS_ARR[@]}
do
  echo "Configuring client: " ${client}

  cd $WS_PATH/sites/${client}

  # Install past logging
  drush en past_db -y

  # Enable and configure search API
  drush en sharedcontent_client search_api_solr -y

  # Create client features and enable them
  # Change permissions so that drush script is able to write inside the site dir
  chmod -R 777 $WS_PATH/sites/${client}
  drush sc-features $BUILD_TAG${client} $WS_PATH/sites/${client}/modules "article, page"
  drush en $BUILD_TAG${client}_sharedcontent_ui -y
  drush en $BUILD_TAG${client}_sharedcontent_client_rules -y

  # Generate testing content
  drush en devel_generate -y
  drush genc 20 3

  # Create SC connections to servers
  for server in ${SERVERS_ARR[@]}
  do
    # Avoid creating connection to itself
    if [ "$client" != "$server" ]; then
      echo "Creating connection to http://${server}.${DOMAIN}/sharedcontent"
      drush sc-conc http://${server}.${DOMAIN}/sharedcontent sc-client-user sc-client-user "System ${server}" system_${server}
    fi
  done

  # Add linkable content
  drush cc all
  drush sc-link add article
  drush sc-link add page

done

# --------
# Sync clients
# --------

echo '================ Sync clients ================'
for client in ${CLIENTS_ARR[@]}
do
  echo "Sync client: " ${client}

  cd $WS_PATH/sites/${client}

  drush sc-sync
  drush cron
done
