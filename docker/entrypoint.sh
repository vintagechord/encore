#!/bin/sh
set -e

if [ "${SKIP_MIGRATIONS:-0}" != "1" ]; then
  rm -f /var/www/html/database/migrations/2025_09_24_123450_create_artist_genre_table.php \
        /var/www/html/database/migrations/2025_09_24_123450_create_artist_tag_table.php
  php /var/www/html/artisan migrate --force
fi

exec "$@"
