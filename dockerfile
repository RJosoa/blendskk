FROM php:8.2-fpm

# Installer les dépendances système et l'extension pdo_mysql pour Symfony/MySQL
RUN apt-get update && apt-get install -y \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-install pdo pdo_mysql

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/blendsk

# Copier le code source dans le container
COPY . .

COPY . /var/www/blendsk

# Installer les dépendances PHP/Symfony
RUN composer install --no-interaction --prefer-dist

RUN chown -R www-data:www-data /var/www/blendsk

EXPOSE 9000

CMD ["php-fpm"]
