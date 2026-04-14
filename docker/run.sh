#!/bin/bash

# Clear and cache Laravel configuration and routes
echo "Caching configuration and routes..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations and seeders if database is ready
echo "Running migrations and seeders..."
php artisan migrate --force
php artisan db:seed --force

# Start the webdevops entrypoint (Nginx + PHP-FPM)
echo "Starting Nginx and PHP-FPM..."
exec /opt/docker/bin/entrypoint.sh supervisord
