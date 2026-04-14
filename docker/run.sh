#!/bin/sh

# Clear and cache Laravel configuration and routes
echo "Caching configuration and routes..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations if database is ready
echo "Running migrations..."
php artisan migrate --force

# Start FrankenPHP using php-server mode
# We use exec to replace the shell process with the server process
echo "Starting FrankenPHP..."
exec frankenphp php-server --port "$PORT" --root public/
