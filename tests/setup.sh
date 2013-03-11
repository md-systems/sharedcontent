#!/bin/bash

# /var/www/sc sc.dev root:pass@127.0.0.1

usage()
{
cat << EOF
Call script with arguments: workspace domain db_url build_tag
Example:
./setup.sh /var/www/sc sc.dev root:@127.0.0.1 sc
EOF
}

if [ $# -eq 0 ]; then
  usage
  exit
fi

export WS_PATH=$1
export DOMAIN=$2

export DB_URL=$3
if [ -z $DB_URL ]; then
  DB_URL=root:@127.0.0.1
fi

export BUILD_TAG=$4
if [ -z $BUILD_TAG ]; then
  BUILD_TAG=sc_test
fi

export SITES=s1,c1,sc1,sc2
export SERVERS=s1,sc1,sc2
export CLIENTS=c1,sc1,sc2

# In case the work space path does not exists, build the codebase
if [ ! -d "$WS_PATH" ]; then
  ./scripts/build_codebase.sh
fi

./scripts/setup_sites.sh
