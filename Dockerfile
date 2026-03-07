FROM serversideup/php:8.4-fpm-nginx

WORKDIR /var/www/html

COPY . .

ENV APP_ENV=production
ENV APP_DEBUG=false
ENV LOG_CHANNEL=stderr
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV APP_URL=https://edu-share-1-9ill.onrender.com
ENV ASSET_URL=https://edu-share-1-9ill.onrender.com

USER root

RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

RUN mkdir -p bootstrap/cache storage/framework/sessions \
    storage/framework/views storage/framework/cache \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 bootstrap/cache storage

RUN npm ci && npm run build

RUN composer install --no-dev --optimize-autoloader

EXPOSE 80
# No CMD — let the image use its default