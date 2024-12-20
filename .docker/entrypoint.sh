#!/usr/bin/env bash

set -e

if [ ! -f /var/www/.env  ]; then
    echo "Creating .env file ..."
    cp -a .env.example .env
fi

echo "Setting permissions for storage and bootstrap/cache directories..."
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
chmod -R a+rwx /var/www/storage /var/www/bootstrap/cache

if [ ! -f /var/www/storage/logs/laravel.log ]; then
    touch /var/www/storage/logs/laravel.log
    chown www-data:www-data /var/www/storage/logs/laravel.log
    chmod a+rwx /var/www/storage/logs/laravel.log
fi

if [ ! -d /var/www/vendor ] || [ ! -f /var/www/composer.lock ]; then
    echo "Installing composer packages..."
    exec composer install --optimize-autoloader --no-interaction --no-progress
fi

if [ -z "$APP_KEY" ]; then
    echo "Generating application key..."
    php artisan key:generate --force
fi

exec php artisan serve --host=0.0.0.0 --port=80 --no-interaction
