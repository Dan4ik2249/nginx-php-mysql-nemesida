FROM php:8.3-fpm

WORKDIR /var/www/public/

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && docker-php-ext-install pdo mysqli pdo_mysql

RUN chown www-data:www-data /var/www/public/
RUN chmod 775 /var/www/public/