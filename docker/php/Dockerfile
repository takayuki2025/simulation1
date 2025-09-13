FROM php:8.1-fpm

COPY php.ini /usr/local/etc/php/

RUN apt update \
  && apt install -y default-mysql-client zlib1g-dev libzip-dev libpng-dev libjpeg-dev libwebp-dev unzip \
  && docker-php-ext-configure gd --with-jpeg --with-webp \
  && docker-php-ext-install -j$(nproc) gd \
  && docker-php-ext-install pdo_mysql zip

RUN curl -sS https://getcomposer.org/installer | php \
  && mv composer.phar /usr/local/bin/composer \
  && composer self-update

RUN apt-get update \
  && curl -sL https://github.com/mailhog/mhsendmail/releases/download/v0.2.0/mhsendmail_linux_amd64 -o /usr/local/bin/mhsendmail \
  && chmod +x /usr/local/bin/mhsendmail \
  && echo "sendmail_path = /usr/local/bin/mhsendmail --smtp-addr=mailhog:1025" > /usr/local/etc/php/conf.d/mailhog.ini

WORKDIR /var/www