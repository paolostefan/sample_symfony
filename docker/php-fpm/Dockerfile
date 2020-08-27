FROM php:7.4-fpm-buster

RUN apt-get update && apt-get install -y wget zlib1g-dev libicu-dev libpq-dev libzip-dev g++ unzip \
  && docker-php-ext-configure intl \
  && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
  && docker-php-ext-configure zip \
  && docker-php-ext-install intl pdo pdo_pgsql pgsql zip

RUN rm -rf /var/cache/apt/*

# Composer
COPY --from=composer /usr/bin/composer /usr/bin/composer

# Unprivileged user
RUN groupadd --gid 1000 developer && \
    useradd -d /home/developer -ms /bin/bash -u 1000 -g 1000 developer

# Symfony project dirs
RUN mkdir -p /var/www/html/guybrush/var/cache /var/www/html/guybrush/var/log
RUN chown -R developer: /var/www/html/guybrush/
