# Base PHP 8.2 CLI Image
FROM php:8.2-cli

# Set working directory inside container
WORKDIR /var/www/html

# Install system dependencies & PHP extensions
RUN apt-get update && apt-get install -y \
    netcat-openbsd \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    sqlite3 \
    libsqlite3-dev \
    && docker-php-ext-install pdo pdo_mysql pdo_sqlite mbstring exif pcntl bcmath gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer globally
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy application files
COPY . .

# Install Composer dependencies
RUN composer install --no-interaction --optimize-autoloader --no-dev

# Set permissions for entrypoint script and Laravel storage/cache
RUN chmod +x docker-entrypoint.sh \
    && mkdir -p storage/framework/sessions storage/framework/views storage/framework/cache \
    && chmod -R 777 storage bootstrap/cache

# Expose container port 8000
EXPOSE 8000

# Set entrypoint
ENTRYPOINT ["/var/www/html/docker-entrypoint.sh"]

# Default command to launch application server
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
