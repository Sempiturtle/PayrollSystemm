# Build stage
FROM node:20-alpine AS build
WORKDIR /app
COPY . .
RUN npm install && npm run build

# Production stage
FROM php:8.3-fpm-alpine AS production

# Install system dependencies
RUN apk add --no-cache \
    nginx \
    supervisor \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    icu-dev \
    oniguruma-dev \
    mysql-client \
    bash

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    gd \
    zip \
    intl \
    bcmath \
    pdo_mysql \
    opcache

# Copy project files
WORKDIR /app
COPY . .
COPY --from=build /app/public/build ./public/build

# Install PHP dependencies
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Configuration
COPY docker/nginx.conf /etc/nginx/http.d/default.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/www.conf /usr/local/etc/php-fpm.d/www.conf
COPY docker/php.ini /usr/local/etc/php/conf.d/app.ini

# Set correct permissions
RUN chown -R www-data:www-data /app \
    && chmod -R 775 /app/storage /app/bootstrap/cache

# Fix line endings and permissions for run.sh
RUN sed -i 's/\r$//' docker/run.sh && chmod +x docker/run.sh

# Expose the Render port
EXPOSE 10000
ENV PORT=10000

ENTRYPOINT ["/app/docker/run.sh"]
