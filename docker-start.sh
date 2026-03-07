#!/bin/sh
php /var/www/html/artisan migrate --force
exec /init