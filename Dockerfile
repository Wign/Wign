FROM php:7.0.22-apache

# System dependencies
RUN apt-get update && apt-get install -y libmcrypt-dev git zip mysql-client
RUN docker-php-ext-install -j$(nproc) mcrypt pdo_mysql

EXPOSE 80

# Don't be root
ADD . /var/www

RUN chown -R www-data:www-data /var/www
USER www-data

WORKDIR /var/www

RUN rm -rf html
RUN mv public html

# Install composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php -r "if (hash_file('SHA384', 'composer-setup.php') === '669656bab3166a7aff8a7506b8cb2d1c292f042046c5a994c43155c0be6190fa0355160742ab2e1c88d40d5be660b410') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
RUN php composer-setup.php
RUN php -r "unlink('composer-setup.php');"

# Install dependencies
RUN php composer.phar update
RUN php composer.phar install


# Be root, for apache to use port 80. TODO: Consider changing apache config to avoid root
USER root
