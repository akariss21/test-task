FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    && docker-php-ext-install pdo pdo_mysql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . /var/www/html
CMD composer install && php artisan optimize  && php artisan migrate --force &&  php-fpm -R -D && nginx -g "daemon off;" && ["cron", "-f"]

