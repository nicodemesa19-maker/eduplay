#!/bin/bash
set -e

# Wait a moment for any services
sleep 2

# Run migrations
php artisan migrate --force --no-interaction

# Seed games if needed
php artisan db:seed --class=GamesTableSeeder --force --no-interaction || true

# Cache configuration for better performance
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start Apache
exec apache2-foreground
