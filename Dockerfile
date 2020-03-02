FROM php:7.2.18-apache

EXPOSE 80
#RUN usermod -u 1000 www-data

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

RUN docker-php-ext-configure ldap --with-libdir=lib/x86_64-linux-gnu \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd intl ldap mysqli opcache pdo_mysql zip

# set timezone
RUN echo "Europe/Amsterdam" > /etc/timezone && dpkg-reconfigure -f noninteractive tzdata

# install locale
RUN echo "nl_NL.UTF-8 UTF-8" > /etc/locale.gen
RUN locale-gen

#since docker-sync is not syncing this folder, prepare manually.
RUN mkdir -p /var/www/html/var/cache
RUN mkdir -p /var/www/html/var/logs/dev
RUN touch /var/www/html/var/logs/dev/dev.log
RUN chown -R 1000:www-data /var/www/html/var
RUN chmod 775 /var/www/html/var

# configure apache
COPY docker/vhost.conf /etc/apache2/sites-available/app.conf
RUN a2enmod rewrite headers && a2dissite 000-default && a2ensite app

COPY docker/init.sh /init.sh
RUN chmod +x /init.sh

RUN mkdir /.composer
RUN chmod 777 /.composer
VOLUME /.composer

COPY docker/php.ini /usr/local/etc/php/

COPY docker/xdebug.ini /tmp/xdebug.ini
RUN cat /tmp/xdebug.ini >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && rm /tmp/xdebug.ini
#RUN echo "xdebug.remote_host=host.docker.internal" >> usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
