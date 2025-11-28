FROM docker.io/library/php:8.2-apache

# Install mysql Extensions
RUN docker-php-ext-install pdo pdo_mysql mysqli

# activate Apache mod_rewrite 
RUN a2enmod rewrite

# set working directory
WORKDIR /var/www/html