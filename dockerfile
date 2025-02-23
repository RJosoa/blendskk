FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    libonig-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-install pdo pdo_mysql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/blendsk

# First copy only composer files
COPY composer.json composer.lock ./

# Set correct permissions
RUN chown -R www-data:www-data . \
    && chmod 755 .

# Install dependencies as www-data
USER www-data
RUN composer install --no-scripts --no-autoloader

# Copy the rest of the application
COPY --chown=www-data:www-data . .

# Generate autoloader and run scripts
RUN composer dump-autoload --optimize

USER root
RUN chmod -R 775 var

EXPOSE 9000

CMD ["php-fpm"]
