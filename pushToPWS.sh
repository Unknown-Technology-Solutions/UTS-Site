#!/bin/sudo bash

TESTING_DIR=/var/www/testing/
PRODUCT_DIR=/var/www/production/

echo "Remember, this is pushing to PRODUCTION!!!"

sleep 0

echo "Pushing to production!"

echo "Step 1, archive old production build"
NAME=$(date '+%Y-%m-%d__%H_%M_%S')
tar -zcvf "$NAME.tar.gz" $TESTING_DIR*

echo "Step 2, move testing to production"
rm -rf $PRODUCT_DIR
mkdir -p $PRODUCT_DIR
mv $TESTING_DIR* $PRODUCT_DIR
