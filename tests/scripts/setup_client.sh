#!/bin/bash

echo "Configuring client: " ${SITE}

# Enable and configure search API
drush en sharedcontent_client search_api_db -y
drush sc-csdb sharedcontent

# Create client features and enable them
# Change permissions so that drush script is able to write inside the site dir
chmod -R 777 ${BUILD_TARGET}/sites/${SITE}
drush sc-features ${BUILD_TAG}${SITE} ${BUILD_TARGET}/sites/${SITE}/modules "article, page"
drush en ${BUILD_TAG}${SITE}_sharedcontent_ui -y
drush en ${BUILD_TAG}${SITE}_sharedcontent_client_rules -y

# Generate testing content
drush en devel_generate -y
drush genc 20 3


