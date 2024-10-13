FROM php:8.3-fpm

# Install packages
RUN apt-get update && apt-get install -y \
    libpq-dev \
    git \
    curl \
    unzip \
    && docker-php-ext-install pdo pdo_pgsql

# Xdebug
ARG INSTALL_XDEBUG=false
RUN if [ ${INSTALL_XDEBUG} = true ]; \
    then \
      pecl install xdebug && docker-php-ext-enable xdebug; \
    fi;
COPY ./docker/php/conf.d/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
#CMD ["/bin/sh", "./docker/php-fpm/run.sh"]