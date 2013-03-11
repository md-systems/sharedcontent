#!/bin/bash

export SITES=s1,c1,c2,c3,c4,c5
export SERVERS=s1
export CLIENTS=c1,c2,c3,c4,c5

export TESTING_SITE=c1

export TEST_GROUP="Shared Content"
export TEST_CLASSES=SharedContentC2Tests

./setup_sites.sh

./run_tests.sh

./cleanup.sh
