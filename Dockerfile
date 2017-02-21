FROM php:5.6-apache

COPY docker/php.ini /usr/local/etc/php/

EXPOSE 80

RUN apt-get update && apt-get install -y zlib1g-dev libicu-dev g++ locales libldap2-dev

# set timezone
RUN echo "Europe/Amsterdam" > /etc/timezone && dpkg-reconfigure -f noninteractive tzdata

# install locale
RUN echo "nl_NL.UTF-8 UTF-8" > /etc/locale.gen
RUN locale-gen

RUN docker-php-ext-configure ldap --with-libdir=lib/x86_64-linux-gnu
RUN docker-php-ext-install mysql pdo_mysql intl ldap zip

RUN pecl install xdebug && docker-php-ext-enable xdebug
COPY docker/xdebug.ini /tmp/xdebug.ini
RUN cat /tmp/xdebug.ini >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && rm /tmp/xdebug.ini

# configure apache
COPY docker/vhost.conf /etc/apache2/sites-available/app.conf
RUN a2enmod rewrite headers && a2dissite 000-default && a2ensite app
