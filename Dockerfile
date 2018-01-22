FROM php:7.2-apache

# System dependencies
RUN apt-get update && apt-get install -y openssl git zip unzip mysql-client \
	libapache2-modsecurity
# Stopping installing extenstions for a while...
RUN docker-php-ext-install -j$(nproc) pdo_mysql
RUN a2enmod rewrite

# Modsecurity
ADD apache/security2.conf /etc/apache2/mods-available/security2.conf
RUN mv /etc/modsecurity/modsecurity.conf-recommended /etc/modsecurity/modsecurity.conf
# Trying to set the SecRuleEngine on and see what happens...
RUN sed -i \
    -e 's/SecRuleEngine DetectionOnly/SecRuleEngine On/'
	-e 's/SecResponseBodyAccess On/SecResponseBodyAccess Off/' \
	/etc/modsecurity/modsecurity.conf

RUN git clone https://github.com/SpiderLabs/owasp-modsecurity-crs.git /etc/apache2/modsecurity.d
RUN mv /etc/apache2/modsecurity.d/crs-setup.conf.example /etc/apache2/modsecurity.d/crs-setup.conf
ADD apache/REQUEST-900-EXCLUSION-RULES-BEFORE-CRS.conf /etc/apache2/modsecurity.d/rules/REQUEST-900-EXCLUSION-RULES-BEFORE-CRS.conf
ADD apache/RESPONSE-999-EXCLUSION-RULES-AFTER-CRS.conf /etc/apache2/modsecurity.d/rules/RESPONSE-999-EXCLUSION-RULES-AFTER-CRS.conf

# RUN mkdir /usr/share/modsecurity-crs/activated_rules/
# RUN ln -s /usr/share/modsecurity-crs/base_rules/modsecurity_crs_41_sql_injection_attacks.conf \
#	/usr/share/modsecurity-crs/activated_rules
#RUN ln -s /usr/share/modsecurity-crs/base_rules/modsecurity_crs_41_xss_attacks.conf /usr/share/modsecurity-crs/activated_rules

WORKDIR /var/www

# Install composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
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
# RUN groupmod -g ${groupid} www-data

# Don't be root
RUN chown -R www-data:www-data /var/www
USER www-data

# Install dependencies
RUN php composer.phar update
RUN php composer.phar install

# Optimizing Laravel
RUN php artisan route:cache
RUN php artisan config:cache

USER root
