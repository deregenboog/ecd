#!/bin/bash

# Setup file permissions
HTTPDUSER=`ps axo user,comm | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1`
sudo setfacl -R -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX app/tmp
sudo setfacl -R -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX var
sudo setfacl -dR -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX app/tmp
sudo setfacl -dR -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX var

# Pull code and install dependencies
git pull
./composer.phar install --no-dev

# Migrations: dry run shows queries to be executed
bin/console doctrine:migrations:migrate --dry-run --env=prod

# Migrations: interactive mode asks confirmation
bin/console doctrine:migrations:migrate --env=prod

# Clear/warmup cache
bin/console cache:clear --env=prod
php -r "if (function_exists('apc_clear_cache')) { apc_clear_cache(); apc_clear_cache('user'); }"
rm -rf app/tmp/cache/*

# Remove development docroot
rm -rf app/webroot-dev
