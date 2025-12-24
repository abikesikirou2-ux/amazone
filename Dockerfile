# Dockerfile multi-stage pour Mini Amazone (Laravel)
# Stages: build assets (node) -> install deps (composer) -> runtime (php)

# 1) Build front-end assets
FROM node:20-alpine AS node_builder
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci --silent
COPY resources ./resources
COPY vite.config.js postcss.config.js tailwind.config.js ./
RUN npm run build

# 2) Install PHP dependencies using official composer image
FROM composer:2 AS composer_builder
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --no-progress --no-interaction --optimize-autoloader
COPY . .
RUN composer dump-autoload --optimize

# 3) Production image
FROM php:8.2-cli-alpine

WORKDIR /var/www/html

# System deps & PHP extensions
RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS git icu-dev libzip-dev zlib-dev libpng-dev libjpeg-turbo-dev freetype-dev libxml2-dev oniguruma-dev bash ca-certificates \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" pdo pdo_mysql mbstring bcmath zip gd xml pcntl \
    && pecl install redis || true \
    && docker-php-ext-enable redis || true \
    && apk del .build-deps || true

# Copy application files and built assets
COPY --from=composer_builder /app /var/www/html
COPY --from=node_builder /app/public/build /var/www/html/public/build

# Entrypoint script
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Permissions (attempt; may be adjusted by runtime user)
RUN mkdir -p /var/www/html/storage /var/www/html/bootstrap/cache \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache || true

ENV PATH="/root/.composer/vendor/bin:${PATH}"

EXPOSE 8000

CMD ["/usr/local/bin/docker-entrypoint.sh"]
