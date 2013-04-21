<?php
/**
 * @file
 * DSB config file.
 */

$config['build tag'] = 'sharedcontent';

$config['domain'] = 'sharedcontent.dev';

$config['makefile'] = 'sharedcontent.make';

/**
 * Places the colorbox library in the libraries folder.
 */
$colorbox_dl =<<<EOF
cd \${BUILD_TARGET}
wget https://github.com/jackmoore/colorbox/archive/master.zip
unzip -qq master.zip -d \${BUILD_TARGET}/sites/all/libraries
rm master.zip
EOF;

/**
 * Set up script common to both server and client.
 */
$setup_common =<<<EOF
# Enable SC so we have the drush commands available.
drush en -y sharedcontent

# Change permissions so that drush script is able to write inside the site dir
chmod -R 777 \${SITE_DIR}
# Create instance features from template.
drush scf \${BUILD_TAG}\${SITE} \${SITE_DIR}/modules

drush cc all

# Set variables.
drush vset sharedcontent_include_local TRUE
drush vset --format=json sharedcontent_indexed_entities '{"node":{"article":"article","page":"page"}}'

# Generate testing content
drush en devel_generate -y
drush genc 30 3
EOF;


/**
 * Set up script for the server.
 */
$setup_server =<<<EOF
# Create user and role with permissions to access SC services
drush ucrt sc-client-user --password="sc-client-user"
drush ev "
  \\\$role = (object) array('name' => 'sc-client-role');
  user_role_save(\\\$role);
  \\\$account = user_load_by_name('sc-client-user');
  user_role_grant_permissions(\\\$role->rid, array('access endpoint restricted'));"
drush urol sc-client-role sc-client-user

# Install server modules
drush en sharedcontent_server_feature -y
drush en \${BUILD_TAG}\${SITE}_sharedcontent_server_rules -y
drush cc all

# Configure the SC overlay
drush en stark -y
drush vset --format=json theme_stark_settings '{"toggle_slogan":0,"toggle_node_user_picture":0,"toggle_comment_user_picture":0,"toggle_comment_user_verification":0,"toggle_main_menu":0,"toggle_secondary_menu":0}'
drush ev "db_update('block')->fields(array('status' => 0))->condition('theme', 'stark')->condition('delta', array('help','main'), 'NOT IN')->execute();"
drush vset sharedcontent_overlay_theme stark
EOF;

/**
 * Set up script for the client.
 */
$setup_client =<<<EOF
# Enable and configure search API
drush en sharedcontent_client search_api_db -y
drush sc-csdb sharedcontent

# Enable client
drush en \${BUILD_TAG}\${SITE}_sharedcontent_ui -y
drush en \${BUILD_TAG}\${SITE}_sharedcontent_client_rules -y
drush cc all

# Configure linkable content
drush sc-link add article
drush sc-link add page
EOF;


/**
 * Client synchronization.
 *
 * Creates a connection to all server on each client and triggers a
 * synchronization.
 */
$sync_clients =<<<EOF
IFS=',' ;for client in `echo "\${SC_CLIENTS}"`
do
  cd \${BUILD_TARGET}/sites/\${client}

  IFS=',' ;for server in `echo "\${SC_SERVERS}"`
  do
    # Avoid creating connection to itself
    if [ "\$client" != "\$server" ]; then
      echo "Creating connection to http://\${server}.\${DOMAIN}/sharedcontent"
      drush sc-conc http://\${server}.\${DOMAIN}/sharedcontent sc-client-user sc-client-user "System \${server}" system_\${server}
    fi
  done

  # Sync client
  drush sc-sync
  drush cron
done
EOF;

/**
 * Simple development setup.
 *
 * Creates an instance of each SC node type.
 */
$config['scenarios']['dev'] = array(
  'custom variables' => array(
    'SC_SERVERS' => 's1,sc1',
    'SC_CLIENTS' => 'c1,sc1',
  ),
  'build script' => $colorbox_dl,
  'post install script' => $sync_clients,
  'cleanup script' => "chmod -R 777 \$BUILD_TARGET && rm -R \$BUILD_TARGET",
  'hosts' => array(
    's1' => array(
      'setup script' => array($setup_common, $setup_server),
    ),
    'sc1' => array(
      'setup script' => array($setup_common, $setup_server, $setup_client),
    ),
    'c1' => array(
      'setup script' => array($setup_common, $setup_client),
    ),
  ),
);

/**
 * Automated tests.
 */
$config['scenarios']['test'] = array(
  'build script' => $colorbox_dl,
  'cleanup script' => "chmod -R 777 \$BUILD_TARGET && rm -R \$BUILD_TARGET",
  'hosts' => array(
    'test' => array(),
  ),
  'tests' => array(
    'all' => array(
      'test objects' => array('"Shared Content"'),
    ),
  ),
);

// Include local config file with config overrides.
if (file_exists(dirname(__FILE__) . '/local.config.php')) {
  include dirname(__FILE__) . '/local.config.php';
}
