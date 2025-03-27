FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo_pgsql

COPY . /var/www
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
RUN composer install

CMD ["php-fpm"]