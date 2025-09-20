FROM dunglas/frankenphp:1-php8.4

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libzip-dev \
    libexif-dev \
    libpq-dev \
    libfreetype6-dev \
    libjpeg-dev \
    libpng-dev \
    libwebp-dev \
    unzip \
    git \
    && docker-php-ext-install intl zip exif pdo pdo_pgsql pgsql \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j$(nproc) gd \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install Redis extension
RUN pecl install redis \
    && docker-php-ext-enable redis

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy composer files
COPY composer.json composer.lock ./

# Install dependencies (prod first for speed)
RUN composer install --no-dev --no-scripts --prefer-dist --optimize-autoloader

# Copy application files
COPY . .

# Install development dependencies
RUN composer install

RUN chmod -R 777 /app/storage /app/bootstrap/cache


# Copy Caddyfile
COPY Caddyfile /etc/caddy/Caddyfile

# Expose ports
EXPOSE 8000
EXPOSE 2019

# Start FrankenPHP server
CMD ["frankenphp", "run"]
