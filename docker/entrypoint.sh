#!/bin/bash
set -e

# Run Laravel setup commands
echo "Running composer dump-autoload..."
composer dump-autoload --optimize

echo "Running migrations..."
php artisan migrate --force

echo "Clearing caches..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo "Starting Apache..."
exec "$@"
