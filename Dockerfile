FROM serversideup/php:8.4-fpm-nginx

WORKDIR /var/www/html

COPY . .

ENV APP_ENV=production
ENV APP_DEBUG=false
ENV LOG_CHANNEL=stderr
ENV COMPOSER_ALLOW_SUPERUSER=1

# Create required Laravel directories before composer install
RUN mkdir -p bootstrap/cache storage/framework/sessions \
    storage/framework/views storage/framework/cache \
    && chmod -R 775 bootstrap/cache storage

RUN composer install --no-dev --optimize-autoloader

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 80

CMD ["supervisord", "-c", "/etc/supervisor/supervisord.conf"]