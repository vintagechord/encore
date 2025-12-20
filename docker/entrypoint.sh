#!/bin/sh
set -e

if [ "${SKIP_MIGRATIONS:-0}" != "1" ]; then
  php /var/www/html/artisan migrate --force
fi

exec "$@"
