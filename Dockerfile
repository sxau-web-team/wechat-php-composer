FROM php:5.6

RUN apt-get update && \
    apt-get install -y \
      git \
      libpq-dev \

RUN pear install pear/PHP_CodeSniffer

RUN pecl install hrtime
RUN echo "extension=hrtime.so" > $PHP_INI_DIR/conf.d/hrtime.ini

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer

WORKDIR /app

COPY composer.json ./

RUN composer install --prefer-source --no-interaction

COPY . ./