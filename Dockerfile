FROM php:7.4-fpm-bullseye

# install dependencies
RUN apt-get update && apt-get install -y \
        default-mysql-client \
        g++ \
        libfreetype6-dev \
        libicu-dev \
        libldap2-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libzip-dev \
        locales \
        zlib1g-dev \
    && pecl install xdebug-3.1.6 && docker-php-ext-enable xdebug \
    && docker-php-ext-install gd intl ldap mysqli pdo_mysql zip
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

# configure
ENV APP_ENV=dev
COPY docker/php/php.ini /usr/local/etc/php/
COPY docker/php/xdebug.ini /tmp/xdebug.ini
RUN cat /tmp/xdebug.ini >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && rm /tmp/xdebug.ini \
    # && echo "xdebug.remote_host=host.docker.internal" >> usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && docker-php-ext-configure ldap --with-libdir=lib/x86_64-linux-gnu \
        && docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/ \
    # set timezone
    && echo "Europe/Amsterdam" > /etc/timezone && dpkg-reconfigure -f noninteractive tzdata \
    # install locale
    && echo "nl_NL.UTF-8 UTF-8" > /etc/locale.gen \
    && locale-gen

WORKDIR /var/www/html
COPY composer.json composer.lock ./
RUN composer install --no-autoloader --no-scripts
COPY . .
COPY docker/php/.env.dev .env
RUN COMPOSER_ALLOW_SUPERUSER=1 composer install
