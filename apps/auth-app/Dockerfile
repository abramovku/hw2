FROM composer:2.4 as vendor
WORKDIR /app
COPY ./src/composer.json composer.json

RUN composer install

COPY ./src .
RUN composer dump-autoload

FROM php:8.2-cli
COPY ./src /usr/src/myapp
WORKDIR /usr/src/myapp

# Copy Composer dependencies
COPY --from=vendor app/vendor/ ./vendor/

# Install Postgre PDO
RUN apt-get update
RUN apt-get install -y libpq-dev \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql

EXPOSE 80

CMD ["php", "-S", "0.0.0.0:80", "-t", "/usr/src/myapp", "/usr/src/myapp/index.php"]