    FROM php:8.2-fpm

    RUN apt-get update && apt-get install -y \
        git \
        unzip \
        libpq-dev \
        libcurl4-openssl-dev \
        libevent-dev \
        zlib1g-dev \
        libicu-dev \
        && pecl install raphf-2.0.1 \
        && docker-php-ext-enable raphf \
        && pecl install pecl_http-4.2.0 \
        && echo "extension=http.so" > /usr/local/etc/php/conf.d/http.ini \
        && docker-php-ext-install pdo pdo_mysql intl \
        && apt-get clean && rm -rf /var/lib/apt/lists/*

    COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

    RUN mkdir /var/run/php-fpm
    RUN chmod 777 /var/run/php-fpm
    RUN chown -R www-data:www-data /var/www

    WORKDIR /var/www/html/app/
    EXPOSE 80

    WORKDIR /var/www/html
    COPY . /var/www/html
    CMD ["sh", "-c", "composer install && php artisan optimize && php artisan migrate:fresh --seed && php artisan serve --host=0.0.0.0 --port=8000"]

