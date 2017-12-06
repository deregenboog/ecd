#!/bin/bash

# Backup database
bin/console app:database:backup --env=prod

# Deploy
bin/deploy-without-backup.sh
