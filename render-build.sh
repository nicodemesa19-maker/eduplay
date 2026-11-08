#!/usr/bin/env bash
# exit on error
set -o errexit

# Install Composer dependencies
composer install --no-dev --optimize-autoloader

# Install NPM dependencies and build assets
npm install
npm run build

# Clear and cache config
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Run database migrations
php artisan migrate --force --no-interaction

# Clean up dummy student emails (if any exist from before the migration)
php artisan tinker --execute="DB::table('users')->where('role', 'student')->where('email', 'like', '%@student.local')->update(['email' => null]);" || true

# Seed games table if needed
php artisan db:seed --class=GamesTableSeeder --force --no-interaction || true

# Optimize for production
php artisan optimize
php artisan route:cache
php artisan view:cache
