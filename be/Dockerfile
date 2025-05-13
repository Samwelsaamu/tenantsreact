FROM php:8.1.17

RUN apt-get update \
    && docker-php-ext-install mysqli pdo pdo_mysql \
    && docker-php-ext-enable pdo_mysql

RUN apt-get update \
    && docker-php-ext-install opcache

RUN apt-get update \
    && apt-get install -y \
        librabbitmq-dev \
        libssh-dev \
    && pecl install amqp \
    && docker-php-ext-enable amqp
RUN apt-get install -y\
        libzip-dev \
        zip\
    &&  docker-php-ext-install zip   
# RUN docker-php-ext-install zip --with- libzip
# RUN apt-get update && apt-get install -y php-zip
# RUN apt-get update && apt-get install -y unzip
# RUN curl -sS https://github.com/magento/zip/archive/refs/heads/master.zip
# COPY master.zip .
# RUN unzip master.zip

RUN apt-get update -y && apt-get install -y libmcrypt-dev openssl zip git

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
# RUN docker-php-ext-install pdo mbstring pdo_mysql


WORKDIR /app/be
COPY . .
# RUN composer install

# COPY . .

RUN php artisan key:generate

EXPOSE 8000
CMD php artisan serve --host=0.0.0.0
