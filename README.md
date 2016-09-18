System requirements

PHP 5.6 & MySql & Apache

    Modules :
    php5-mysql
    php-apc
    php5-curl
    php5-gd
    php5-ldap
    php5-mysql

Implement following changes on the file system :

    mkdir -p archive
    cp -rp app/tmp_template app/tmp
    cp -rp app/config_template app/config
    cp app/config/config_example1.php.txt  app/config/config.php
    cp app/config/database.php.default app/config/database.php
    chown -R www-data:www-data app/tmp archive    (or your apache usersid)

Create a mysql user and database. ECD is currently authenticating trough an openldap server, in the near future also active directory will be supported.

* Edit config.php, add the ldap server values
* define( 'GROUP_ADMIN', 10021 );  // 10021 is the Posix group id
* Configure the database.php
* cd app/config/schema
* edit load.sh
* Enter the mysql credentials or create a .my.cnf
* Run ./load.sh

This should populate the database


Cronjobs : 

0 2,14 * * * /var/www/html/cake/console/cake -app /var/www/html/app/ unregister_all >>/var/log/ecdcron.log

*/15 * * * * /var/www/html/cake/console/cake -app /var/www/html/app/ send_email >>/var/log/send_email.log

0 0 * * * mysql ecd < /var/www/html/app/config/schema/management_report_views.sql >>/var/log/cron-management_report_views.log 2>&1







