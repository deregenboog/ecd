FROM php:7.4-apache

ENV APP_ENV=dev

COPY docker/php.ini /usr/local/etc/php/

EXPOSE 80
#RUN usermod -u 1000 www-data

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
    zlib1g-dev

RUN pecl install xdebug-3.1.6 && docker-php-ext-enable xdebug
COPY docker/xdebug.ini /tmp/xdebug.ini

RUN cat /tmp/xdebug.ini >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && rm /tmp/xdebug.ini
#RUN echo "xdebug.remote_host=host.docker.internal" >> usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini


RUN docker-php-ext-configure ldap --with-libdir=lib/x86_64-linux-gnu \
    && docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/ \
    && docker-php-ext-install gd intl ldap mysqli pdo_mysql zip

# set timezone
RUN echo "Europe/Amsterdam" > /etc/timezone && dpkg-reconfigure -f noninteractive tzdata

# install locale
RUN echo "nl_NL.UTF-8 UTF-8" > /etc/locale.gen
RUN locale-gen

WORKDIR /var/www/html

# configure apache
COPY docker/vhost.conf /etc/apache2/sites-available/app.conf
RUN a2enmod rewrite headers && a2dissite 000-default && a2ensite app

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer
COPY composer.json composer.lock ./
RUN composer install --no-autoloader --no-scripts

COPY . .
COPY docker/.env.dev .env
RUN COMPOSER_ALLOW_SUPERUSER=1 composer install
