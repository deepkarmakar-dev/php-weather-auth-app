# 1. PHP aur Apache ka image use karein
FROM php:8.2-apache

# 2. Apache mein rewrite module enable karein (Clean URLs ke liye zaruri hai)
RUN a2enmod rewrite

# 3. Project files ko container ke web directory mein copy karein
COPY . /var/www/html/

# 4. Permissions set karein taaki Apache files read kar sake
RUN chown -R www-data:www-data /var/www/html

# 5. Default port 80 ko open karein
EXPOSE 80
