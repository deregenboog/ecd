FROM php:5.6-apache

EXPOSE 80

RUN docker-php-ext-install mysql pdo_mysql

RUN apt-get update \
	&& apt-get install -y libldap2-dev \
	&& docker-php-ext-configure ldap --with-libdir=lib/x86_64-linux-gnu \
	&& docker-php-ext-install ldap

RUN pecl install xdebug && docker-php-ext-enable xdebug

RUN a2enmod rewrite
