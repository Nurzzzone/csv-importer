FROM php:8.2.26

RUN apt-get update && apt-get install -y \
    zip \
    unzip \
    libzip-dev \
    git \
    curl \
    default-mysql-client

RUN docker-php-ext-install mysqli pdo pdo_mysql zip pcntl

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY .docker/entrypoint.sh /var/www/.docker/entrypoint.sh

RUN usermod -u 1000 www-data

RUN chmod +x /var/www/.docker/entrypoint.sh

ENTRYPOINT ["/bin/sh", "/var/www/.docker/entrypoint.sh"]
