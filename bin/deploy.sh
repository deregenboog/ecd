#!/bin/bash

# Setup file permissions
HTTPDUSER=`ps axo user,comm | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1`
sudo setfacl -R -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX app/tmp
sudo setfacl -R -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX var
sudo setfacl -dR -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX app/tmp
sudo setfacl -dR -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX var

# Backup configures database
bin/console app:database:backup --env=prod

# Pull code and install dependencies
git pull
./composer.phar install

# Migrations: dry run shows queries to be executed
bin/console doctrine:migrations:migrate --dry-run --env=prod

# Migrations: interactive mode asks confirmation
bin/console doctrine:migrations:migrate --env=prod

# Warmup cache
bin/console cache:clear --env=prod

# Remove development docroot
rm -rf app/webroot-dev

