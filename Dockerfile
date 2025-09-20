FROM php:8.4-apache

# Installer dépendances
RUN apt-get update && apt-get install -y \
    git unzip libpng-dev libonig-dev libxml2-dev zip curl \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Activer Apache rewrite
RUN a2enmod rewrite

# Change the DocumentRoot to /var/www/html/public
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Définir le dossier de travail sur la racine du projet
WORKDIR /var/www/html

# Copier seulement le composer.json et composer.lock pour installer les dépendances
COPY composer.json composer.lock* /var/www/html/

# Copier tout le reste du projet
COPY . /var/www/html

# Permissions
RUN chown -R www-data:www-data storage bootstrap/cache

EXPOSE 80




