#!/bin/bash

echo "Configuring server: " ${SITE}

# Install sharedcontent server modules
drush en sharedcontent_server_feature -y
drush vset sharedcontent_include_local TRUE
drush cc all
# Change permissions so that drush script is able to write inside the site dir
echo "${BUILD_TARGET} build target"
chmod -R 777 ${BUILD_TARGET}/sites/${SITE}
drush sc-features ${BUILD_TAG}${SITE} ${BUILD_TARGET}/sites/${SITE}/modules "article, page"
drush en ${BUILD_TAG}${SITE}_sharedcontent_server_rules -y
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
drush sc-keywords add field_tags article
drush en devel_generate -y
drush genc 30 3
drush cron

# Configure the output
drush en stark -y
php -r "print json_encode(array('toggle_slogan' => 0, 'toggle_node_user_picture' => 0, 'toggle_comment_user_picture' => 0, 'toggle_comment_user_verification' => 0, 'toggle_main_menu' => 0, 'toggle_secondary_menu' => 0));" | drush vset --format=json theme_stark_settings:  -
drush ev "db_update('block')->fields(array('status' => 0))->condition('theme', 'stark')->condition('delta', array('help','main'), 'NOT IN')->execute();"
drush vset sharedcontent_overlay_theme stark
