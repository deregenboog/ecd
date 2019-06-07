FROM php:7.1-apache

COPY docker/php.ini /usr/local/etc/php/

EXPOSE 80
RUN usermod -u 1000 www-data

RUN apt-get update && apt-get install -y \
    g++ \
    libfreetype6-dev \
    libicu-dev \
    libldap2-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    locales \
    mysql-client \
    zlib1g-dev

RUN pecl install xdebug && docker-php-ext-enable xdebug
COPY docker/xdebug.ini /tmp/xdebug.ini
RUN cat /tmp/xdebug.ini >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && rm /tmp/xdebug.ini

RUN docker-php-ext-configure ldap --with-libdir=lib/x86_64-linux-gnu \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install gd intl ldap mysqli pdo_mysql zip

# set timezone
RUN echo "Europe/Amsterdam" > /etc/timezone && dpkg-reconfigure -f noninteractive tzdata

# install locale
RUN echo "nl_NL.UTF-8 UTF-8" > /etc/locale.gen
RUN locale-gen

# configure apache
COPY docker/vhost.conf /etc/apache2/sites-available/app.conf
RUN a2enmod rewrite headers && a2dissite 000-default && a2ensite app

#since docker-sync is not syncing this folder, prepare manually.
RUN mkdir -p /var/www/html/var/cache
RUN mkdir -p /var/www/html/var/logs/dev
RUN touch /var/www/html/var/logs/dev/dev.log
RUN chown -R 1000:www-data /var/www/html/var
RUN chmod 775 /var/www/html/var

#somehow php composer.phar install looks for console in the app directory.
RUN ln -s /var/www/html/bin/console /var/www/html/app/console

COPY docker/init.sh /init.sh
RUN chmod +x /init.sh
