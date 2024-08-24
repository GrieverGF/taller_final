FROM php:8.2-apache

RUN docker-php-ext-install mysqli pdo pdo_mysql
COPY ./Apache/php.ini /usr/local/etc/php/
COPY ./Apache/000-default.conf /etc/apache2/sites-enabled/000-default.conf
COPY ./src /var/www/html
