#!/bin/bash

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
