FROM dunglas/frankenphp:1-php8.4

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libzip-dev \
    libexif-dev \
    libpq-dev \
    && docker-php-ext-install intl zip exif pdo pdo_pgsql pgsql \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy composer files
COPY composer.json composer.lock ./

# Install dependencies
RUN composer install --no-dev --no-scripts --prefer-dist --optimize-autoloader

# Copy application files
COPY . .

# Install development dependencies
RUN composer install

# Set permissions
RUN chmod -R 775 storage bootstrap/cache

# Copy Caddyfile
COPY Caddyfile /etc/caddy/Caddyfile

# Expose ports
EXPOSE 8000
EXPOSE 2019

# Start FrankenPHP server
CMD ["frankenphp", "run"]