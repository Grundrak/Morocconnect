#!/bin/sh
set -e

# Create storage link if it doesn't exist
if [ ! -d /var/www/public/storage ]; then
    php artisan storage:link
fi

# Start PHP-FPM
php-fpm