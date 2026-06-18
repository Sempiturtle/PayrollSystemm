#!/bin/bash

# Clear and cache Laravel configuration and routes
echo "Caching configuration and routes..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations if database is ready
echo "Running migrations..."
php artisan migrate --force

# Seed database only if it hasn't been seeded yet (e.g., if no users exist)
USER_COUNT=$(php artisan tinker --execute="echo \App\Models\User::count();" | tr -d '\r\n[:space:]')
if [ -z "$USER_COUNT" ] || [ "$USER_COUNT" -eq "0" ]; then
    echo "First time setup: Seeding database..."
    php artisan db:seed --force
else
    echo "Database already has data (Users: $USER_COUNT). Skipping seeding."
fi

# Ensure cache directories exist and have the correct ownership/permissions
mkdir -p /app/storage/framework/cache/laravel-excel
chown -R www-data:www-data /app/storage /app/bootstrap/cache
chmod -R 775 /app/storage /app/bootstrap/cache

# Inject the real port into Nginx config
echo "Configuring Nginx to listen on port ${PORT:-10000}..."
sed -i "s/LISTEN_PORT/${PORT:-10000}/g" /etc/nginx/http.d/default.conf

# Pre-cleanup the socket to prevent conflicts
rm -f /tmp/php.sock

# Start Supervisor (which starts Nginx and PHP-FPM)
echo "Starting Supervisor Services..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
