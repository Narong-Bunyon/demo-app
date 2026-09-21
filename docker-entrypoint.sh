#!/bin/sh
set -e

echo "🚀 Starting Docker Entrypoint for Laravel..."

# Copy .env.example to .env if .env does not exist
if [ ! -f .env ]; then
    echo "📋 .env file not found. Creating from .env.example..."
    cp .env.example .env
fi

# Ensure APP_KEY is set
if ! grep -q "^APP_KEY=base64:" .env; then
    echo "🔑 Generating Application Key..."
    php artisan key:generate --force
fi

# Fix storage and cache directory permissions
echo "🔒 Setting permissions for storage and bootstrap/cache..."
chmod -R 777 storage bootstrap/cache

# Wait for database connection if DB_HOST is set
if [ "$DB_CONNECTION" = "mysql" ] && [ -n "$DB_HOST" ]; then
    echo "⏳ Waiting for MySQL database at $DB_HOST:$DB_PORT to be ready..."
    until nc -z -v -w30 "$DB_HOST" "${DB_PORT:-3306}" 2>/dev/null; do
        echo "Waiting for MySQL server connection..."
        sleep 2
    done
    echo "✅ MySQL server is reachable!"
fi

# Run database migrations and seeders
echo "📦 Running database migrations..."
php artisan migrate --force

echo "🌱 Seeding database..."
php artisan db:seed --force || true

echo "✨ Application initialization complete! Starting Laravel server on 0.0.0.0:8000..."
exec "$@"
