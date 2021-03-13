FROM php:7.4-fpm-buster

ENV PHP_EXTENSIONS \
    intl \
    opcache \
    pdo \
    pdo_pgsql \
    pgsql \
    zip

ENV PECL_EXTENSIONS xdebug

ENV PACKAGES g++ iproute2 iputils-ping libicu-dev libpq-dev libzip-dev unzip wget zlib1g-dev

RUN apt-get update && apt-get install -y ${PACKAGES} \
  && docker-php-ext-configure intl \
  && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
  && docker-php-ext-configure zip \
  && docker-php-ext-install ${PHP_EXTENSIONS}

RUN pecl install ${PECL_EXTENSIONS}

RUN apt autoremove -y

RUN rm -rf /var/cache/apt/*

# Composer
COPY --from=composer /usr/bin/composer /usr/bin/composer

# Unprivileged user
RUN groupadd --gid 1000 developer && useradd -d /home/developer -ms /bin/bash -u 1000 -g 1000 developer

# Symfony project dirs
RUN mkdir -p /var/www/html/guybrush/ && chown -R developer: /var/www/html/guybrush/

COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod 755 /usr/local/bin/entrypoint.sh
ENTRYPOINT [ "/usr/local/bin/entrypoint.sh", "php-fpm" ]
