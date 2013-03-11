<?php
/**
 * @file
 * DSB config file.
 */

$config['build tag'] = 'sc';

$config['build target'] = '/var/www/sharedcontent';

$config['db url'] = 'mysql://root:q@localhost';
$config['domain'] = 'sc.dev';

$config['makefile'] = 'scripts/test.make';

$colorbox_dl = 'wget http://www.jacklmoore.com/colorbox/colorbox.zip
unzip -qq colorbox.zip -d ' . $config['build target'] . '/sites/all/libraries
rm colorbox.zip
';

$config['scenarios']['sca'] = array(
  'custom variables' => array(
    'SC_SERVERS' => 's1_sca',
    'SC_CLIENTS' => 'c1_sca',
  ),
  'build script' => $colorbox_dl,
  'cleanup script' => "chmod -R 777 \$BUILD_TARGET\nrm -R \$BUILD_TARGET",
  'post install script' => 'scripts/sync_clients.sh',
  'hosts' => array(
    's1_sca' => array(
      'setup script' => 'scripts/setup_server.sh'
    ),
    'c1_sca' => array(
      'setup script' => 'scripts/setup_client.sh'
    ),
  ),
);

$config['scenarios']['scb'] = array(
  'domain' => 'sc.dev',
  'custom variables' => array(
    'SC_SERVERS' => 's1_scb',
    'SC_CLIENTS' => 'c1_scb,c2_scb,c3_scb,c4_scb,c5_scb',
  ),
  'build script' => $colorbox_dl,
  'cleanup script' => "chmod -R 777 \$BUILD_TARGET\nrm -R \$BUILD_TARGET",
  'post install script' => 'scripts/sync_clients.sh',
  'hosts' => array(
    's1_scb' => array(
      'setup script' => 'scripts/setup_server.sh'
    ),
    'c1_scb' => array(
      'setup script' => 'scripts/setup_client.sh'
    ),
    'c2_scb' => array(
      'setup script' => 'scripts/setup_client.sh'
    ),
    'c3_scb' => array(
      'setup script' => 'scripts/setup_client.sh'
    ),
    'c4_scb' => array(
      'setup script' => 'scripts/setup_client.sh'
    ),
    'c5_scb' => array(
      'setup script' => 'scripts/setup_client.sh'
    ),
  ),
);

$config['scenarios']['scc'] = array(
  'domain' => 'sc.dev',
  'custom variables' => array(
    'SC_SERVERS' => 'sc1_scc,sc2_scc,sc3_scc',
    'SC_CLIENTS' => 'sc1_scc,sc2_scc,sc3_scc',
  ),
  'build script' => $colorbox_dl,
  'cleanup script' => "chmod -R 777 \$BUILD_TARGET\nrm -R \$BUILD_TARGET",
  'post install script' => 'scripts/sync_clients.sh',
  'hosts' => array(
    'sc1_scc' => array(
      'setup script' => array(
        'scripts/setup_server.sh',
        'scripts/setup_client.sh',
      )
    ),
    'sc2_scc' => array(
      'setup script' => array(
        'scripts/setup_server.sh',
        'scripts/setup_client.sh',
      )
    ),
//    'sc3_scc' => array(
//      'setup script' => array(
//        'scripts/setup_server.sh',
//        'scripts/setup_client.sh',
//      )
//    ),
  ),
);

$config['tests']['c1_sca'] = array(
  'test objects' => array('"Shared Content"'),
);

// Include local config file with config overrides.
if (file_exists(dirname(__FILE__) . '/local.config.php')) {
  include dirname(__FILE__) . '/local.config.php';
}
