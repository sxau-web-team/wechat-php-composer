FROM php:5.6-apache

RUN apt-get update && \
    apt-get install -y \
      git \
      libpq-dev

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer

WORKDIR /app

COPY composer.json ./

RUN composer install --prefer-source --no-interaction

COPY . /var/www/html/