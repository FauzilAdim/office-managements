#!/bin/sh
set -e

# Configure port dynamically based on Render environment ($PORT)
PORT=${PORT:-80}
sed -i "s/listen 80;/listen ${PORT};/g" /etc/nginx/http.d/default.conf
sed -i "s/listen \[::\]:80;/listen [::]:${PORT};/g" /etc/nginx/http.d/default.conf

# Setup SQLite if using sqlite
if [ "${DB_CONNECTION:-sqlite}" = "sqlite" ]; then
    mkdir -p /var/www/html/database
    touch /var/www/html/database/database.sqlite
    chmod -R 777 /var/www/html/database
fi

# Ensure storage directories exist and have proper permissions
mkdir -p /var/www/html/storage/framework/sessions \
         /var/www/html/storage/framework/views \
         /var/www/html/storage/framework/cache \
         /var/www/html/storage/logs \
         /var/www/html/bootstrap/cache
chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache

# Run Laravel setup commands
cd /var/www/html

# Generate key if not set
if [ -z "$APP_KEY" ]; then
    echo "Warning: APP_KEY is empty. Generating temporary key..."
    php artisan key:generate --force
fi

echo "Running migrations..."
php artisan migrate --force

echo "Running seeders..."
php artisan db:seed --force

echo "Caching configurations..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Office Management ready! Starting Nginx and PHP-FPM on port ${PORT}..."
exec /usr/bin/supervisord -c /etc/supervisord.conf

