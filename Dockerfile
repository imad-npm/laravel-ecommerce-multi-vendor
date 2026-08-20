# === Stage 1: Build Tailwind Assets ===
FROM node:20-alpine AS frontend-builder
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
# Compiles Tailwind using Vite (or Mix if on older Laravel)
RUN npm run build

# === Stage 2: Final PHP Application ===
FROM php:8.3-cli-alpine

RUN apk add --no-cache \
    bash \
    curl \
    libpng-dev \
    libxml2-dev \
    zip \
    unzip \
    git

RUN docker-php-ext-install pdo pdo_mysql gd bcmath
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
COPY . /var/www

# Copy the compiled Tailwind assets from Stage 1
COPY --from=frontend-builder /app/public/build /var/www/public/build

RUN composer install --no-interaction --optimize-autoloader --no-dev
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache


EXPOSE 8000

CMD sh -c "mkdir -p database && touch database/database.sqlite && chown -R www-data:www-data database storage bootstrap/cache && php artisan storage:link --force && php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=${PORT:-8000}"