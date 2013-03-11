#!/bin/bash

# scenario_c1 /var/www/sc sc.dev root:pass@127.0.0.1

usage()
{
cat << EOF
Call script with arguments: scenario workspace domain db_url
Example:
./tests.sh scenario_c1 /var/www/sc sc.dev root:@127.0.0.1
EOF
}

if [ $# -eq 0 ]; then
  usage
  exit
fi

export WS_PATH=$2
export DOMAIN=$3

export DB_URL=$4
if [ -z $DB_URL ]; then
  DB_URL=root:q@127.0.0.1
fi

export BUILD_TAG=$5
if [ -z $BUILD_TAG ]; then
  BUILD_TAG=sc_test
fi

# In case the work space path does not exists, build the codebase
if [ ! -d "$WS_PATH" ]; then
  ./scripts/build_codebase.sh
fi

./$1.sh
