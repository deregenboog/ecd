#!/bin/bash

./composer.phar install

until mysql -h "database" -u$MYSQL_USER -p$MYSQL_PASSWORD $MYSQL_DATABASE; do
  >&2 echo "MySQL is unavailable - sleeping"
  sleep 1
done

>&2 echo "MySQL is up - executing command"

bin/console doctrine:migrations:migrate -n

COUNT=`mysql -h "database" -u$MYSQL_USER -p$MYSQL_PASSWORD -e "SELECT (1) FROM medewerkers WHERE id = 1;" $MYSQL_DATABASE`
if [ -z "${COUNT}" ]
then
    >&2 echo "Loading data fixtures"
    bin/console hautelook_alice:doctrine:fixtures:load -n
else
    >&2 echo "Skipping data fixtures"
fi

apache2-foreground
