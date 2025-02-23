FROM php:8.2-fpm

USER root

RUN apt-get update && apt-get install -y \
    libonig-dev libxml2-dev libzip-dev \
    zip unzip git \
    && docker-php-ext-install pdo pdo_mysql mbstring xml zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/blendsk

# Étape 1 : Copier uniquement les fichiers nécessaires pour composer install
COPY composer.json composer.lock symfony.lock ./

# Étape 2 : Installer les dépendances sans autoloader
RUN composer install --no-dev --no-autoloader --no-scripts --no-interaction

# Étape 3 : Copier le reste du code
COPY . .

# Étape 4 : Finaliser l'installation
RUN composer dump-autoload --optimize && \
    chmod -R 775 var/

EXPOSE 9000

CMD ["php-fpm"]
