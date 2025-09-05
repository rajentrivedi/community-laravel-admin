#!/bin/bash

# Test script for Laravel Octane with FrankenPHP

echo "Testing Laravel Octane with FrankenPHP setup..."

# Check if Docker is installed
if ! command -v docker &> /dev/null
then
    echo "Docker is not installed. Please install Docker first."
    exit 1
fi

# Check if Docker Compose is installed
if ! command -v docker-compose &> /dev/null
then
    echo "Docker Compose is not installed. Please install Docker Compose first."
    exit 1
fi

# Build and start the containers
echo "Building and starting containers..."
docker-compose up -d

# Wait for containers to start
echo "Waiting for containers to start..."
sleep 10

# Check if the Laravel service is running
if docker-compose ps | grep -q "laravel.test"; then
    echo "Laravel service is running."
else
    echo "Laravel service is not running."
    exit 1
fi

# Check if the PostgreSQL service is running
if docker-compose ps | grep -q "pgsql"; then
    echo "PostgreSQL service is running."
else
    echo "PostgreSQL service is not running."
    exit 1
fi

# Check if the Adminer service is running
if docker-compose ps | grep -q "adminer"; then
    echo "Adminer service is running."
else
    echo "Adminer service is not running."
    exit 1
fi

echo "All services are running successfully!"

# Test the application
echo "Testing the application..."
curl -s http://localhost:8000 > /dev/null

if [ $? -eq 0 ]; then
    echo "Application is accessible at http://localhost:8000"
else
    echo "Application is not accessible."
    exit 1
fi

echo "Setup test completed successfully!"