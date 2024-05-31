FROM php:8.2-apache

RUN apt-get update && apt-get upgrade -y && apt-get install -y git zip unzip

RUN docker-php-ext-install pdo pdo_mysql

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Copier les fichiers SSL
COPY ssl/server.crt /etc/ssl/certs/
COPY ssl/server.key /etc/ssl/private/

# Activer les modules SSL
RUN a2enmod ssl && a2enmod rewrite

WORKDIR /var/www/symfony

CMD bash -c "composer install && apache2-foreground"