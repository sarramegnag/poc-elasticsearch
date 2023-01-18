FROM php:8.2-fpm

ENV DEBIAN_FRONTEND noninteractive

RUN apt-get update -y && \
    apt-get install -y --no-install-recommends apt-utils git unzip gosu

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

RUN chmod uga+x /usr/local/bin/install-php-extensions && \
    sync && \
    install-php-extensions zip \
                           pdo_pgsql \
                           gd \
                           intl \
                           opcache \
                           xsl

COPY conf.d/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

ENV COMPOSER_HOME /.composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY entrypoint.sh /usr/local/bin/entrypoint.sh

WORKDIR /app

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
