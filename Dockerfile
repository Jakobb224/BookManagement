FROM docker.io/library/php:8.2-apache

# MySQL/MariaDB Extensions installieren
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Apache mod_rewrite aktivieren
RUN a2enmod rewrite

# Schreibrechte für www-data (Apache-User)
RUN chown -R www-data:www-data /var/www/html

# Arbeitsverzeichnis setzen
WORKDIR /var/www/html