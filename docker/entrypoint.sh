#!/bin/sh
set -e

mkdir -p /var/www/html/storage/framework/cache \
  /var/www/html/storage/framework/sessions \
  /var/www/html/storage/framework/views \
  /var/www/html/storage/logs
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

if [ "${SKIP_MIGRATIONS:-0}" != "1" ]; then
  rm -f /var/www/html/database/migrations/2025_09_24_123450_create_artist_genre_table.php \
        /var/www/html/database/migrations/2025_09_24_123450_create_artist_tag_table.php
  php /var/www/html/artisan migrate --force
fi

exec "$@"
