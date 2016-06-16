FROM php:5.6-apache

RUN apt-get update && \
    apt-get install -y \
      git \
      libpq-dev

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer

RUN ln -sf /usr/share/zoneinfo/Europe/Paris /etc/localtime

RUN echo "date.timezone = \"Europe/Paris\"" > /usr/local/etc/php/conf.d/timezone.ini

WORKDIR /app

COPY composer.json ./

RUN composer install --prefer-source --no-interaction

COPY . /var/www/html/