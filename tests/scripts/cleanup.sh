#!/bin/bash

IFS='@' read -a db_data <<< "$DB_URL"
IFS=':' read -a db_credentials <<< "$db_data"

IFS=',' read -a SITES_ARR <<< "$SITES"

# --------
# Clean up
# --------

echo '================ Clean up ================'

for site in ${SITES_ARR[@]}
do
  echo "Cleaning up site: " ${site}

  mysqladmin -u ${db_credentials[0]} -p${db_credentials[1]} -f drop "${BUILD_TAG}_${site}"

  chmod -R 777 $WS_PATH/sites/${site}
  rm -R $WS_PATH/sites/${site}

done
