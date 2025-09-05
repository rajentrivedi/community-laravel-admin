#!/bin/bash

# Run artisan commands in the Laravel container
docker-compose exec -T laravel.test php artisan "$@"