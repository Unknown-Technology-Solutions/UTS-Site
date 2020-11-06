#/bin/bash

WORKING_DIR=/home/buildbot/worker/runtests/build/

echo "Reseting UTS Testing databases"
echo $MSQL_UNAME
echo $MSQL_PASS
mysql --user=$MSQL_UNAME --password=$MSQL_PASS < $WORKING_DIR/modern_database.sql > /var/www/testing/output.tab
cat /var/www/testing/output.tab

echo "Finished!"
exit 0
