FROM php:8.2-apache

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    libonig-dev \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql mbstring

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy application
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Configure Apache
COPY docker/apache.conf /etc/apache/sites-available/000-default.conf

# Set permissions
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Build frontend assets (only if package.json changed)
RUN apt-get install -y nodejs npm \
    && npm ci \
    && npm run production \
    && rm -rf node_modules
