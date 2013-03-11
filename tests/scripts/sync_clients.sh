#!/bin/bash

IFS=',' ;for client in `echo "${SC_CLIENTS}"`
do

  cd ${BUILD_TARGET}/sites/${client}

  IFS=',' ;for server in `echo "${SC_SERVERS}"`
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

  # Sync client
  drush sc-sync
  drush cron

done
