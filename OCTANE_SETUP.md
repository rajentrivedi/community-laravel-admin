# Laravel Octane with FrankenPHP Setup

This document summarizes the changes made to configure the application to run with Laravel Octane using FrankenPHP as the server.

## Changes Made

### 1. Updated docker-compose.yml
- Replaced the Laravel Sail setup with a FrankenPHP-based configuration
- Updated the laravel.test service to use the dunglas/frankenphp image
- Added OCTANE_SERVER environment variable
- Removed supervisord configuration
- Kept PostgreSQL and Adminer services unchanged

### 2. Created Dockerfile
- Created a custom Dockerfile based on dunglas/frankenphp:1-php8.4
- Added steps to install dependencies and copy application files
- Configured the container to start Octane with FrankenPHP

### 3. Updated .env.example
- Added OCTANE_SERVER=frankenphp environment variable

### 4. Updated README.md
- Added instructions for running the application with Octane and FrankenPHP
- Provided both Docker and Artisan command options

### 5. Updated routes/web.php
- Added simple test routes to verify the setup

### 6. Removed unnecessary files
- Removed supervisord.conf as it's not needed with FrankenPHP
- Removed frankenphp.conf as we're using the artisan command directly

### 7. Created test script
- Created a test script to verify the setup works correctly

## How to Run

### Using Docker (Recommended)
```bash
./vendor/bin/sail up
# Or if you have the sail alias
sail up
```

The application will be available at http://localhost:8000

### Using Artisan Command
```bash
# Install dependencies (if not already done)
composer install

# Start Octane with FrankenPHP
php artisan octane:start --server=frankenphp --host=127.0.0.1 --port=8000
```

The application will be available at http://127.0.0.1:8000

## Testing the Setup

Run the test script to verify everything is working:
```bash
./test-octane-setup.sh
```

## Benefits of Using FrankenPHP with Octane

1. **Performance**: FrankenPHP is a modern PHP application server built on top of Caddy, providing excellent performance.
2. **Simplicity**: Unlike Swoole or RoadRunner, FrankenPHP doesn't require installing additional PHP extensions.
3. **Docker Integration**: FrankenPHP has excellent Docker support with official images.
4. **HTTP/2 and HTTPS**: Built-in support for modern web protocols.
5. **Static File Serving**: Efficiently serves static files without additional configuration.