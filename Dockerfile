FROM php:7.0.22-apache

# System dependencies
RUN apt-get update && apt-get install -y libmcrypt-dev git zip mysql-client
RUN docker-php-ext-install -j$(nproc) mcrypt pdo_mysql
RUN a2enmod rewrite

WORKDIR /var/www

# Install composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php -r "if (hash_file('SHA384', 'composer-setup.php') === '669656bab3166a7aff8a7506b8cb2d1c292f042046c5a994c43155c0be6190fa0355160742ab2e1c88d40d5be660b410') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
RUN php composer-setup.php
RUN php -r "unlink('composer-setup.php');"

EXPOSE 80

ADD . /var/www

# Apache
ADD apache/000-default.conf /etc/apache2/sites-available/000-default.conf
ADD apache/prod.htaccess /var/www/public/.htaccess

# Switch your UID to match the one developing
ARG userid=1000
ARG groupid=1000
RUN usermod -u ${userid} www-data
RUN groupmod -g ${groupid} www-data

# Don't be root
RUN chown -R www-data:www-data /var/www
USER www-data

# Install dependencies
RUN php composer.phar update
RUN php composer.phar install

USER root
