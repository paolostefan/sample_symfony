FROM php:7.4-fpm-buster

RUN apt-get update && apt-get install -y wget zlib1g-dev libicu-dev libpq-dev libzip-dev g++ unzip \
  && docker-php-ext-configure intl \
  && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
  && docker-php-ext-configure zip \
  && docker-php-ext-install intl pdo pdo_pgsql pgsql zip

# Composer stuff
COPY install-composer.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/install-composer.sh && /usr/local/bin/install-composer.sh

# Unprivileged user
RUN addgroup --gid 1000 developer && \
    adduser --home /home/developer --shell /bin/bash --uid 1000 \
    --gid 1000 developer --disabled-login