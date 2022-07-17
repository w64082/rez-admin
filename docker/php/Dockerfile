FROM php:7.4-apache
RUN rmdir /var/www/html
COPY ./docker/apache2/000-default.conf /etc/apache2/sites-enabled/000-default.conf
#RUN docker-php-ext-install curl
WORKDIR /var/www/
CMD ["apache2ctl", "-D", "FOREGROUND"]