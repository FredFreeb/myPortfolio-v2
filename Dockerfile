FROM php:8.4-fpm-bookworm

ENV COMPOSER_ALLOW_SUPERUSER=1
ENV APP_ENV=prod
ENV TRUSTED_PROXIES=127.0.0.1,REMOTE_ADDR

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        git \
        unzip \
        libicu-dev \
        libpq-dev \
        libzip-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libwebp-dev \
    && docker-php-ext-configure gd \
        --with-jpeg \
        --with-webp \
    && docker-php-ext-install \
        intl \
        pdo_pgsql \
        opcache \
        gd \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY docker/php/conf.d/app.ini /usr/local/etc/php/conf.d/zz-app.ini
COPY docker/php/fpm.d/zz-app.conf /usr/local/etc/php-fpm.d/zz-app.conf

WORKDIR /app

COPY . /app

RUN composer install --prefer-dist --no-dev --no-interaction --no-progress --optimize-autoloader \
    && php bin/console cache:clear --env=prod --no-debug \
    && php bin/console asset-map:compile --env=prod --no-debug \
    && chown -R www-data:www-data var public/uploads

EXPOSE 9000
