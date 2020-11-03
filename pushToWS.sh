#!/bin/bash

WORKING_DIR=/root/tmp/bb-worker/worker/runtests/build

echo "Deleting previous test from Testing"
rm -rf /var/www/testing
mkdir -p /var/www/testing

echo "Moving files to Testing"
cp $WORKING_DIR/*.html    /var/www/testing
cp $WORKING_DIR/*.php     /var/www/testing
cp $WORKING_DIR/*.js      /var/www/testing
#cp $WORKING_DIR/.htaccess /var/www/testing
echo "Moving folders to Testing"
#cp -R $WORKING_DIR/data /var/www/testing
cp -R $WORKING_DIR/css       /var/www/testing
cp -R $WORKING_DIR/downloads /var/www/testing


echo "Re-owning files"
#touch /var/www/testing/output.tab
chown -R apache. /var/www/testing
chmod +x $WORKING_DIR/getDBinfo.sh

echo "Reseting MMW Testing databases"
mysql --user=$MSQL_UNAME --password=$MSQL_PASS < $WORKING_DIR/resetDB.sql > /var/www/testing/output.tab

echo "Finished!"
exit 0
