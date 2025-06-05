FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    libcurl4-openssl-dev \
    libevent-dev \
    zlib1g-dev \
    libicu-dev \
    && docker-php-ext-install pdo pdo_mysql intl \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Установка Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN mkdir /var/run/php-fpm && \
    chmod 777 /var/run/php-fpm && \
    chown -R www-data:www-data /var/www

EXPOSE 8000

COPY . /var/www/html
WORKDIR /var/www/html

CMD ["sh", "-c", "composer install && php artisan optimize && php artisan migrate:fresh --seed && php artisan serve --host=0.0.0.0 --port=8000"]
