--------
Overview
--------

Shell scripts provide a way to easy setup functional testing and development
environment and trigger automated tests.

- Install, setup and configure SC clients, SC servers and SC client/servers
  connect each client to all servers and sync remote content. The result is
  a complete Shared Content setup ready for testing and development.

- Automated tests run. It will run all tests that are in the Shared Content
  group.

- Do cleanup after tests. It will drop all databases and remove testing site
  directories.

--------
Setup
--------

Before you start you need a working code base with all needed modules available.
So either set it up manually, or you can use the drush make script.

Run setup.sh with arguments: workspace domain db_url build_tag.
Example:
@ ./setup.sh /var/www/sharedcontent sc.dev root:pass@127.0.0.1 sc_dev

Last two arguments default to root:@127.0.0.1 and sc_test

The script will:
 - setup three sites: SC Server, SC Client and SC Server/Client
 - create customized features derived from templates in the defaults dir
 - generate testing content
 - create connections from client to servers and sync indexes

Note that running the script will drop all existing table in the target
databases.

--------
Running tests
--------

Running tests starts with a scenario. This scenario defines sites and their
roles to be installed and configured and test groups and tests that will run.

To run tests use tests.sh script with arguments: scenario workspace domain
db_url build_tag.
Example:
@ ./tests.sh scenario_c1 /var/www/sharedcontent sc.dev root:q@127.0.0.1 sc_dev

Last two arguments default to root:@127.0.0.1 and sc_test

The script will:
 - include corresponding scenario
 - run setup script
 - run tests
 - run cleanup

--------
Troubleshooting
--------

 - You might get a drush error saying the site is already installed even if
   the database is empty. In such case remove the site dir.

 - In case you use different build tag than before, you might get sql errors
   saying tables with name having previous build tag does not exists. In such
   case drop databases and remove site dirs.
