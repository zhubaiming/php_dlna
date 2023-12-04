FROM php:8.2.12-fpm-alpine3.18
LABEL authors="zhubaiming"

RUN apk add --no-cache --update --virtual .phpize-deps $PHPIZE_DEPS \
    && docker-php-source extract \
    && apk update \
    && docker-php-ext-install pdo_mysql \
    && pecl install redis-6.0.2 \
    && docker-php-ext-enable redis \
    && cd /tmp \
    && php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/bin --filename=composer \
    && php -r "unlink('composer-setup.php');" \
    && rm -rf /tmp/* \
    && apk del --purge .phpize-deps

WORKDIR /var/www/html
