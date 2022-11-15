# Image and version
FROM php:7.4-fpm

# Call PHP images script `docker-php-ext-install` and install language extensions
RUN docker-php-ext-install pdo_mysql

# copy the Composer PHAR from the Composer image into the PHP image
COPY --from=composer /usr/bin/composer /usr/bin/composer
