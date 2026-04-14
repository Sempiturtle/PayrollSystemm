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

# Inject the real port into Nginx config
echo "Configuring Nginx to listen on port ${PORT:-10000}..."
sed -i "s/LISTEN_PORT/${PORT:-10000}/g" /etc/nginx/http.d/default.conf

# Start Supervisor (which starts Nginx and PHP-FPM)
echo "Starting Supervisor..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
