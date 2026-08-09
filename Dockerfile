# syntax=docker/dockerfile:1

##########################
# 1. Frontend build stage
##########################
FROM node:20-alpine AS frontend

WORKDIR /app

COPY package.json package-lock.json* ./
RUN npm ci

COPY resources/ resources/
COPY vite.config.js ./
COPY public/ public/

RUN npm run build


##########################
# 2. PHP dependencies stage
##########################
FROM composer:2 AS vendor

WORKDIR /app

COPY composer.json composer.lock ./

# Install deps without running scripts (artisan isn't available yet)
RUN composer install \
    --no-dev \
    --no-scripts \
    --no-interaction \
    --no-progress \
    --prefer-dist \
    --optimize-autoloader


##########################
# 3. Final runtime image
##########################
FROM php:8.2-fpm-alpine AS app

# ---- system packages ----
RUN apk add --no-cache \
    bash \
    curl \
    git \
    icu-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    oniguruma-dev \
    postgresql-dev \
    supervisor \
    nginx \
    shadow \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        pdo_pgsql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip \
        intl \
        opcache

# ---- php config ----
COPY docker/php/php.ini /usr/local/etc/php/conf.d/99-custom.ini
COPY docker/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

# ---- nginx + supervisor config ----
COPY docker/nginx/default.conf /etc/nginx/http.d/default.conf
COPY docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

WORKDIR /var/www/html

# ---- app code ----
COPY . .

# vendor from composer stage
COPY --from=vendor /app/vendor ./vendor

# built frontend assets
COPY --from=frontend /app/public/build ./public/build

# permissions: www-data needs to own storage & bootstrap/cache
RUN addgroup -g 1000 www \
    && adduser -G www -g www -s /bin/bash -D www \
    && chown -R www:www /var/www/html \
    && chmod -R 775 storage bootstrap/cache

# generate optimized autoloader with dev scripts now that artisan exists
RUN composer dump-autoload --optimize --no-dev

COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 80

ENTRYPOINT ["entrypoint.sh"]
CMD ["supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]