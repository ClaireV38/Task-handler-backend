FROM php:8.4-apache

# Install PHP extensions needed for Laravel
RUN apt-get update && apt-get install -y \
    zip unzip git libzip-dev \
    && docker-php-ext-install pdo pdo_mysql zip

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Change the DocumentRoot to /var/www/html/public
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Allow configurable ServerName (default = localhost)
ARG SERVER_NAME=localhost
RUN echo "ServerName ${SERVER_NAME}" > /etc/apache2/conf-available/servername.conf \
    && a2enconf servername




