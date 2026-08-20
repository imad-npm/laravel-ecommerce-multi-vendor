# Use the official PHP image with CLI and Alpine Linux for a lightweight image
FROM php:8.3-cli-alpine

# Install system dependencies required for Laravel
RUN apk add --no-cache \
    bash \
    curl \
    libpng-dev \
    libxml2-dev \
    zip \
    unzip \
    git

# Install PHP extensions required by Laravel
RUN docker-php-ext-install pdo pdo_mysql gd bcmath

# Copy Composer from the official stable image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set the working directory inside the container
WORKDIR /var/www

# Copy the existing application code to the container
COPY . /var/www

# Install Laravel dependencies
RUN composer install --no-interaction --optimize-autoloader --no-dev

# Set permissions for Laravel storage and cache directories
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Expose port 8000 to access the Laravel server outside the container
EXPOSE 8000

# Start the Laravel development server
CMD php artisan serve --host=0.0.0.0 --port=8000
