# Build stage
FROM node:20-alpine AS build
WORKDIR /app
COPY . .
RUN npm install && npm run build

# Production stage
# webdevops/php-nginx is a robust, production-ready image for Laravel
FROM webdevops/php-nginx:8.3-alpine AS production

# Configuration for webdevops image
ENV WEB_DOCUMENT_ROOT=/app/public
ENV PHP_MEMORY_LIMIT=256M
ENV PHP_MAX_EXECUTION_TIME=60

# Install necessary system dependencies for Laravel 12
RUN apk add --no-cache mysql-client bash

# Copy project files
WORKDIR /app
COPY . .
COPY --from=build /app/public/build ./public/build

# Install PHP dependencies
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Set permissions
RUN chown -R application:application storage bootstrap/cache
RUN chmod -R 775 storage bootstrap/cache

# Copy custom PHP configuration
COPY docker/php.ini /opt/docker/etc/php/php.ini

# Expose port (Render uses 8080 or the $PORT variable)
EXPOSE 80
ENV PORT=80

# Run script
RUN chmod +x docker/run.sh
ENTRYPOINT ["/app/docker/run.sh"]
