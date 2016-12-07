#!/bin/bash | $MYSQL
MYSQL="mysql -h 172.17.0.1 -u ecd --password=p65Sddpg "
echo drop database ecd | $MYSQL 
echo "create database ecd" | $MYSQL 

../../../cake/console/cake schema create
cat metadata.sql | $MYSQL ecd
cat  postcodegebieden.sql | $MYSQL ecd
cat  stadsdelen.sql | $MYSQL ecd
cat  nationaliteiten.sql | $MYSQL ecd
cat  landen.sql | $MYSQL ecd
cat  groepsactiviteiten_redenen.sql | $MYSQL ecd
cat  zrm_settings.sql | $MYSQL ecd
cat  management_report_views.sql | $MYSQL ecd

cd ../../../app
chown -R www-data:www-data tmp
