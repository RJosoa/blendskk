FROM php:8.2-fpm

USER root

# Installer les dépendances système et l'extension pdo_mysql pour Symfony/MySQL
RUN apt-get update && apt-get install -y \
    libonig-dev \
    zip \
    unzip \
    git \
 && docker-php-ext-install pdo pdo_mysql

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/blendsk

# Copier le code source dans le conteneur en définissant le propriétaire
COPY --chown=www-data:www-data . .

# Installer les dépendances PHP/Symfony
RUN composer install --no-interaction --prefer-dist

# Ajuster les permissions
RUN chmod -R 775 /var/www/blendsk

EXPOSE 9000

CMD ["php-fpm"]
