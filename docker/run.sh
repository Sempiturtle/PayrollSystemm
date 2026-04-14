#!/bin/sh

# Clear and cache Laravel configuration and routes
echo "Caching configuration and routes..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations if database is ready
echo "Running migrations..."
php artisan migrate --force

# Start FrankenPHP
echo "Starting FrankenPHP..."
frankenphp run --config /etc/caddy/Caddyfile --adapter caddyfile --env PORT=$PORT
