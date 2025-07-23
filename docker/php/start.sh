#!/bin/bash
# docker/php/start.sh
# Simple dual FPM startup

echo "Starting dual PHP-FPM setup..."

# Test beide configs
php-fpm -t -y /usr/local/etc/php-fpm.d/pool-fast.conf
php-fpm -t -y /usr/local/etc/php-fpm.d/pool-debug.conf

# Start fast FPM (geen Xdebug)
php-fpm --nodaemonize \
        --fpm-config /usr/local/etc/php-fpm.d/pool-fast.conf \
        --php-ini /usr/local/etc/php/php-fast.ini &

# Start debug FPM (met Xdebug)
php-fpm --nodaemonize \
        --fpm-config /usr/local/etc/php-fpm.d/pool-debug.conf \
        --php-ini /usr/local/etc/php/php-debug.ini &

# Wait for both
wait