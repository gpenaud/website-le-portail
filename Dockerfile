# image: php:8.0-apache
FROM php:7.3-apache-buster

RUN \
  apt-get --yes update && \
  apt-get install --yes libicu-dev

# install mysql extension for php
RUN \
  docker-php-ext-install mysqli && docker-php-ext-enable mysqli && \
  docker-php-ext-install intl && docker-php-ext-enable intl && \
  docker-php-ext-install gettext && docker-php-ext-enable gettext

COPY --chown=www-data:www-data ./app/ /var/www/html/

# apache2 certificates
COPY ./services/apache2/certificates /etc/apache2/certificates

# apache2 vhosts
COPY ./services/apache2/vhost-website.conf /etc/apache2/sites-available/www.leportail.localhost.conf
COPY ./services/apache2/vhost-website.conf /etc/apache2/sites-enabled/www.leportail.localhost.conf
COPY ./services/apache2/vhost-admin.conf /etc/apache2/sites-available/admin.leportail.localhost.conf
COPY ./services/apache2/vhost-admin.conf /etc/apache2/sites-enabled/admin.leportail.localhost.conf

# configurations
COPY --chown=www-data:www-data ./services/website/config.php /var/www/html/dotclear/inc/config.php

RUN \
  # prepare apache2 logs
  ln -sf /proc/self/fd/1 /var/log/apache2/access.log && \
  ln -sf /proc/self/fd/1 /var/log/apache2/error.log

RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"
RUN rm -rf /var/www/html/cache/*

RUN \
  a2enmod ssl && \
  a2enmod rewrite

RUN service apache2 restart
