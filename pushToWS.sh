#!/bin/sudo bash

WORKING_DIR=/home/buildbot/worker/runtests/build/
echo $USER

echo "Deleting previous test from Testing"
rm -rf /var/www/testing
mkdir /var/www/testing


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
sudo chmod -R 755 /var/www/
chown -R apache. /var/www/testing
#chmod +x $WORKING_DIR/getDBinfo.sh

echo "Reseting UTS Testing databases"
mysql --user=$MSQL_UNAME --password=$MSQL_PASS < $WORKING_DIR/modern_database.sql > /var/www/testing/output.tab
cat /var/www/testing/output.tab

echo "Finished!"
exit 0
