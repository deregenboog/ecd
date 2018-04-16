FROM php:7.1-apache

COPY docker/php.ini /usr/local/etc/php/

EXPOSE 80

RUN apt-get update && apt-get install -y zlib1g-dev libicu-dev g++ locales libldap2-dev

# set timezone
RUN echo "Europe/Amsterdam" > /etc/timezone && dpkg-reconfigure -f noninteractive tzdata

# install locale
RUN echo "nl_NL.UTF-8 UTF-8" > /etc/locale.gen
RUN locale-gen

RUN docker-php-ext-configure ldap --with-libdir=lib/x86_64-linux-gnu
RUN docker-php-ext-install mysqli pdo_mysql intl ldap zip

RUN pecl install xdebug-2.5.5 && docker-php-ext-enable xdebug
COPY docker/xdebug.ini /tmp/xdebug.ini
RUN cat /tmp/xdebug.ini >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && rm /tmp/xdebug.ini

RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install gd

RUN apt-get install -y mysql-client

# configure apache
COPY docker/vhost.conf /etc/apache2/sites-available/app.conf
RUN a2enmod rewrite headers && a2dissite 000-default && a2ensite app

COPY docker/init.sh /init.sh
RUN chmod +x /init.sh
