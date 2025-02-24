FROM php:8.2-fpm

# Installation des dépendances système
RUN apt-get update && apt-get install -y \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-install pdo pdo_mysql zip opcache \
    && docker-php-ext-configure opcache --enable-opcache

# Configuration PHP pour la production
COPY symfony-docker/docker/php/php.ini /usr/local/etc/php/conf.d/app.ini

# Installation de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/blendsk

# Copie des fichiers du projet
COPY . .

ENV APP_ENV=prod
ENV APP_DEBUG=0

# Installation des dépendances
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev

# Permissions
RUN chown -R www-data:www-data var

EXPOSE 9000

CMD ["php-fpm"]
