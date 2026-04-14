# Build stage
FROM node:20-alpine AS build
WORKDIR /app
COPY . .
RUN npm install && npm run build

# Production stage
FROM dunglas/frankenphp:1.3-php8.4-alpine AS production

# Install system dependencies
RUN apk add --no-cache \
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

# Set permissions
RUN chown -R www-data:www-data storage bootstrap/cache

# Environment optimizations
ENV PHP_INI_SCAN_DIR=":$PHP_INI_SCAN_DIR"
COPY docker/php.ini /usr/local/etc/php/conf.d/app.ini

# Expose port
EXPOSE 8080
ENV PORT=8080

# Run script
RUN chmod +x docker/run.sh
ENTRYPOINT ["docker/run.sh"]
