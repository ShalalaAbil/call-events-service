FROM php:8.4-cli

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libonig-dev \
    libpq-dev \
    && docker-php-ext-install pdo_mysql pcntl bcmath

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./
RUN composer install --no-interaction --prefer-dist --no-scripts --no-autoloader

COPY . .

RUN composer dump-autoload

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
