FROM php:7.3-cli

RUN apt-get update -y && \
    apt-get install -y libpng-dev zlib1g-dev libzip-dev zip git && \
    docker-php-ext-install zip gd

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /app

COPY composer.json ./

RUN composer update --no-dev --optimize-autoloader

RUN composer require --dev phpunit/phpunit:^9.5

COPY . .

RUN chmod +x vendor/bin/phpunit

CMD ["php", "index.php"]
