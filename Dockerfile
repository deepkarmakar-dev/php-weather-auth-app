# 1. PHP + Apache base image
FROM php:8.2-apache

# 2. Apache rewrite enable
RUN a2enmod rewrite

# 3. PHP Extensions install
RUN docker-php-ext-install mysqli pdo pdo_mysql

# 4. Curl (Weather API ke liye)
RUN apt-get update && apt-get install -y curl

# 5. Apache AllowOverride enable (.htaccess)
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

# 6. Project copy
COPY . /var/www/html/

# 7. Permissions
RUN chown -R www-data:www-data /var/www/html

# 8. Port
EXPOSE 80
