FROM php:7.4-fpm-buster

RUN apt-get update && apt-get install -y wget zlib1g-dev libicu-dev libpq-dev g++ \
  && docker-php-ext-configure intl \
  && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
  && docker-php-ext-install intl pdo pdo_pgsql pgsql

# Composer stuff
COPY install-composer.sh /usr/local/bin/

RUN chmod +x /usr/local/bin/install-composer.sh && /usr/local/bin/install-composer.sh
