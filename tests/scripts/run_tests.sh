#!/bin/bash

IFS=',' read -a SITES_ARR <<< "$SITES"
IFS=',' read -a TEST_GROUPS_ARR <<< "$TEST_GROUPS"
IFS=',' read -a TEST_CLASSES_ARR <<< "$TEST_CLASSES"

# --------
# Run tests
# --------

echo '================ Running tests ================'

echo "Test site: " ${TESTING_SITE}

cd $WS_PATH/sites/${TESTING_SITE}
drush en --nocolor --yes simpletest

echo "Running test group: " ${TEST_GROUP}
drush test-run --uri="http://${TESTING_SITE}.${DOMAIN}" --nocolor "${TEST_GROUP}"

for test_class in ${TEST_CLASSES_ARR[@]}
do
  echo "Running test class: " ${test_class}
  drush test-run --uri="http://${TESTING_SITE}.${DOMAIN}" --nocolor $test_class
done
