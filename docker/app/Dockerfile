FROM php:7.2-fpm

RUN apt-get update && apt-get install -y git zlib1g-dev
RUN curl -sS https://getcomposer.org/installer | php -- --filename=composer --install-dir=/usr/local/bin
RUN docker-php-ext-install -j$(nproc) zip

VOLUME /var/www
WORKDIR /var/www
