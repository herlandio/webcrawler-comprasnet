FROM php:7.4-alpine

RUN apk add --no-cache libpng-dev zlib-dev libzip-dev zip git

RUN docker-php-ext-install zip gd

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /app

COPY composer.json ./

RUN composer update --no-dev --optimize-autoloader

RUN composer require --dev phpunit/phpunit:^9

COPY . .

RUN chmod +x vendor/bin/phpunit

CMD ["php", "index.php"]
