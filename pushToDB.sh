#/bin/bash

WORKING_DIR=/home/buildbot/worker/runtests/build/

echo "Reseting UTS Testing databases"
mysql --user=$MSQL_UNAME --password=$MSQL_PASS < $WORKING_DIR/modern_database.sql > $HOME/output.tab
cat $HOME/output.tab

echo "Finished!"
exit 0
