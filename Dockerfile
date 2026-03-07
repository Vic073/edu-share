FROM serversideup/php:8.4-fpm-nginx

WORKDIR /var/www/html

COPY . .

ENV APP_ENV=production
ENV APP_DEBUG=false
ENV LOG_CHANNEL=stderr
ENV COMPOSER_ALLOW_SUPERUSER=1

# Switch to root to fix permissions
USER root

RUN mkdir -p bootstrap/cache storage/framework/sessions \
    storage/framework/views storage/framework/cache \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 bootstrap/cache storage

RUN composer install --no-dev --optimize-autoloader

EXPOSE 80

CMD ["supervisord", "-c", "/etc/supervisor/supervisord.conf"]