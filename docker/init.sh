#!/bin/bash

until `mysql -h "database" -u$MYSQL_USER -p$MYSQL_PASSWORD $MYSQL_DATABASE`; do
  >&2 echo "MySQL is unavailable - sleeping"
  sleep 1
done

>&2 echo "MySQL is up - executing command"

bin/console doctrine:database:create
bin/console doctrine:schema:create
bin/console hautelook_alice:doctrine:fixtures:load -n

