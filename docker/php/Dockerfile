FROM php:8.3-fpm-bullseye

RUN apt update && apt install -y \
        default-mysql-client g++ libfreetype6-dev libicu-dev \
        libldap2-dev libldap-common libsasl2-dev libjpeg62-turbo-dev \
        libpng-dev libzip-dev locales zlib1g-dev \
    && ln -s /usr/include/sasl/sasl.h /usr/lib/x86_64-linux-gnu/sasl.h \
    && docker-php-ext-configure ldap --with-libdir=lib/x86_64-linux-gnu --with-ldap-sasl \
    && docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/ \
    && docker-php-ext-configure opcache --enable-opcache \
    && docker-php-ext-install opcache gd intl ldap mysqli pdo_mysql zip \
    && pecl install xdebug

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

ENV APP_ENV=dev \
    PHP_OPCACHE_VALIDATE_TIMESTAMPS="1" \
    PHP_OPCACHE_MAX_ACCELERATED_FILES="10000" \
    PHP_OPCACHE_MEMORY_CONSUMPTION="192" \
    PHP_OPCACHE_MAX_WASTED_PERCENTAGE="10"

# Copy complete PHP configurations
COPY docker/php/*.ini /usr/local/etc/php/

# Copy FPM pool configurations
COPY docker/php/pool*.conf /usr/local/etc/php-fpm.d/

# Copy startup script
COPY docker/php/start.sh /usr/local/bin/start.sh

RUN echo "Europe/Amsterdam" > /etc/timezone \
    && dpkg-reconfigure -f noninteractive tzdata \
    && echo "nl_NL.UTF-8 UTF-8" > /etc/locale.gen \
    && locale-gen \
    && chmod +x /usr/local/bin/start.sh \
    # REMOVE DEFAULT pools
    && rm -f /usr/local/etc/php-fpm.d/www.conf \
    && rm -f /usr/local/etc/php-fpm.d/docker.conf \
    && rm -f /usr/local/etc/php-fpm.d/zz-docker.conf

ARG UID
ARG GID
USER ${UID}:${GID}

WORKDIR /var/www/html
COPY composer.json composer.lock ./
RUN composer install --no-autoloader --no-scripts

COPY . .
COPY docker/php/.env.dev .env
RUN composer dump-autoload --optimize

CMD ["/usr/local/bin/start.sh"]