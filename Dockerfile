# ============================================================
# 1. Frontend build
# ============================================================
FROM node:20-alpine AS frontend

WORKDIR /app

COPY package*.json ./

RUN npm ci

COPY . .

RUN npm run build


# ============================================================
# 2. Composer dependencies
# ============================================================
FROM php:8.2-cli-alpine AS composer

WORKDIR /app

# System dependencies required by PHP extensions / Composer
RUN apk add --no-cache \
    git \
    unzip \
    icu-dev \
    libzip-dev \
    oniguruma-dev \
    postgresql-dev \
    sqlite-dev

# PHP extensions required by Laravel / packages
RUN docker-php-ext-install \
    bcmath \
    intl \
    mbstring \
    pdo \
    pdo_mysql \
    pdo_pgsql \
    pdo_sqlite \
    zip

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy Composer files first for Docker layer caching
COPY composer.json composer.lock ./

# These files are required by Laravel's Composer scripts
COPY artisan ./artisan
COPY app ./app
COPY bootstrap ./bootstrap

# Install production PHP dependencies
RUN composer install \
    --no-dev \
    --no-interaction \
    --no-progress \
    --prefer-dist \
    --optimize-autoloader


# ============================================================
# 3. Production Laravel application
# ============================================================
FROM php:8.2-fpm-alpine

WORKDIR /var/www/html

# System dependencies
RUN apk add --no-cache \
    icu-dev \
    libzip-dev \
    oniguruma-dev \
    postgresql-dev \
    sqlite-dev

# PHP extensions
RUN docker-php-ext-install \
    bcmath \
    intl \
    mbstring \
    opcache \
    pdo \
    pdo_mysql \
    pdo_pgsql \
    pdo_sqlite \
    zip

# Copy Composer dependencies
COPY --from=composer /app/vendor ./vendor

# Copy Laravel application
COPY . .

# Copy production Vite build
COPY --from=frontend /app/public/build ./public/build

# Laravel writable directories
RUN chown -R www-data:www-data \
        storage \
        bootstrap/cache \
    && chmod -R 775 \
        storage \
        bootstrap/cache

# Production PHP configuration
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# OPcache
RUN { \
        echo "opcache.enable=1"; \
        echo "opcache.validate_timestamps=0"; \
        echo "opcache.memory_consumption=128"; \
        echo "opcache.max_accelerated_files=10000"; \
    } > /usr/local/etc/php/conf.d/opcache.ini

EXPOSE 9000

CMD ["php-fpm"]