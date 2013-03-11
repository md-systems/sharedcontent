#!/bin/bash

export SITES=s1,c1
export SERVERS=s1
export CLIENTS=c1

export TESTING_SITE=c1

export TEST_GROUP="Shared Content"
export TEST_CLASSES=SharedContentC1Tests

./scripts/setup_sites.sh

./scripts/run_tests.sh

./scripts/cleanup.sh
