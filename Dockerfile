FROM php:7.2-apache

# Install system dependencies & tools
RUN apt-get update && apt-get install -y \
    openssl \
    git \
    zip \
    unzip \
    mysql-client \
	libapache2-modsecurity \
	&& rm -rf /var/lib/apt/lists/*

# Instal php extenstions & enable mod_rewrite
RUN docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        opcache
RUN a2enmod rewrite

# Get the OWASP modsecurity package from git
RUN git clone https://github.com/SpiderLabs/owasp-modsecurity-crs.git /etc/apache2/modsecurity.d
RUN mv /etc/apache2/modsecurity.d/crs-setup.conf.example /etc/apache2/modsecurity.d/crs-setup.conf

# Setup modsecurity
ADD apache/REQUEST-900-EXCLUSION-RULES-BEFORE-CRS.conf /etc/apache2/modsecurity.d/rules/REQUEST-900-EXCLUSION-RULES-BEFORE-CRS.conf
ADD apache/RESPONSE-999-EXCLUSION-RULES-AFTER-CRS.conf /etc/apache2/modsecurity.d/rules/RESPONSE-999-EXCLUSION-RULES-AFTER-CRS.conf
ADD apache/security2.conf /etc/apache2/mods-available/security2.conf
RUN mv /etc/modsecurity/modsecurity.conf-recommended /etc/modsecurity/modsecurity.conf
RUN sed -i \
    -e 's/SecRuleEngine DetectionOnly/SecRuleEngine On/' \
	-e 's/SecResponseBodyAccess On/SecResponseBodyAccess Off/' \
	/etc/modsecurity/modsecurity.conf

WORKDIR /var/www

# Install composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php && \
    php -r "unlink('composer-setup.php');"

EXPOSE 80

ADD . /var/www

# Setup Apache
ADD apache/000-default.conf /etc/apache2/sites-available/000-default.conf
ADD apache/prod.htaccess /var/www/public/.htaccess

# Switch your UID to match the one developing
ARG userid=1000
ARG groupid=1000
RUN usermod -u ${userid} www-data
# RUN groupmod -g ${groupid} www-data

# Install dependencies
RUN php composer.phar install --prefer-dist --no-interaction
# Don't be root
RUN chown -R www-data:www-data /var/www
USER www-data

# Optimizing Laravel
RUN php artisan config:cache && \
    php artisan route:cache

USER root
