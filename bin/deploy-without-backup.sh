#!/bin/bash

# Setup file permissions
bin/setup-file-permissions.sh

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
