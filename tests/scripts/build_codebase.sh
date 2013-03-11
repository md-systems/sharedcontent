#!/bin/bash

SCRIPT_PATH="`dirname \"$0\"`"
SCRIPT_PATH="`( cd \"$SCRIPT_PATH\" && pwd )`"

drush make --no-cache $SCRIPT_PATH/test.make $WS_PATH

wget https://github.com/jackmoore/colorbox/archive/master.zip
unzip -qq master.zip -d $WS_PATH/sites/all/libraries
mv $WS_PATH/sites/all/libraries/colorbox-master $WS_PATH/sites/all/libraries/colorbox
rm master.zip
