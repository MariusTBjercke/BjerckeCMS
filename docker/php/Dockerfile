FROM php:8.1-apache
COPY ./vhost/* /etc/apache2/sites-available/
RUN apt update && apt upgrade -y && apt install nano -y \
&& a2enmod rewrite && apt install libmagickwand-dev libmagickcore-dev -y \
&& docker-php-ext-install mysqli pdo_mysql fileinfo \
&& pecl install imagick && docker-php-ext-enable imagick \
&& pecl install xdebug && docker-php-ext-enable xdebug \
&& docker-php-ext-configure intl && docker-php-ext-install intl \
&& docker-php-ext-install opcache && docker-php-ext-enable opcache \
&& a2enmod expires && a2enmod headers && a2enmod ssl && a2ensite docker.conf && a2ensite docker-ssl.conf
