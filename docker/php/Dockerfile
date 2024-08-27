FROM php:7.4-fpm-bullseye

# install dependencies
#ldap needs libldap-common otherwise LDAP over SSL wont work due to some update in Debian.
RUN apt update && apt install -y \
        default-mysql-client \
        g++ \
        libfreetype6-dev \
        libicu-dev \
        libldap2-dev \
        libldap-common \
        libsasl2-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libzip-dev \
        locales \
        zlib1g-dev \
    # of dit nodig is weet ik nog niet; keertje bouwen zonder.
    && ln -s /usr/include/sasl/sasl.h /usr/lib/x86_64-linux-gnu/sasl.h \
    && docker-php-ext-configure ldap --with-libdir=lib/x86_64-linux-gnu --with-ldap-sasl \
    && docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/ \
    && docker-php-ext-configure opcache --enable-opcache \
    && docker-php-ext-install opcache gd intl ldap mysqli pdo_mysql zip \
    && pecl install xdebug-3.1.6 && docker-php-ext-enable xdebug
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

# configure
ENV APP_ENV=dev \
    XDEBUG_MODE=debug \
    PHP_OPCACHE_VALIDATE_TIMESTAMPS="1" \
    PHP_OPCACHE_MAX_ACCELERATED_FILES="10000" \
    PHP_OPCACHE_MEMORY_CONSUMPTION="192" \
    PHP_OPCACHE_MAX_WASTED_PERCENTAGE="10"

COPY docker/php/php.ini /usr/local/etc/php/
COPY docker/php/opcache.ini /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini
COPY docker/php/xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

RUN \
    # set timezone
    echo "Europe/Amsterdam" > /etc/timezone \
    && dpkg-reconfigure -f noninteractive tzdata \
    # install locale
    && echo "nl_NL.UTF-8 UTF-8" > /etc/locale.gen \
    && locale-gen

ARG UID
ARG GID
USER ${UID}:${GID}

# install PHP packages
WORKDIR /var/www/html
COPY composer.json composer.lock ./
RUN composer install --no-autoloader --no-scripts

# copy application code
COPY . .
COPY docker/php/.env.dev .env
RUN composer dump-autoload --optimize
