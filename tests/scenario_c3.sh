#!/bin/bash

export SITES=sc1,sc2,sc3
export SERVERS=sc1,sc2,sc3
export CLIENTS=sc1,sc2,sc3

export TESTING_SITE=sc1

export TEST_GROUP="Shared Content"
export TEST_CLASSES=SharedContentC3Tests

./setup_sites.sh

./run_tests.sh

./cleanup.sh
