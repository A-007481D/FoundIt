FROM composer:2 as vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader

FROM php:8.1-fpm

RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip git libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo_mysql zip mbstring bcmath

WORKDIR /var/www/html

COPY --from=vendor /app/vendor ./vendor
COPY . .

RUN chown -R www-data:www-data /var/www/html

EXPOSE 9000
CMD ["php-fpm"]
