FROM php:8-fpm

RUN apt-get clean
RUN apt-get update

RUN apt-get install -y git libzip-dev zip \
    && docker-php-ext-install zip
