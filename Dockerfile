FROM php:8.3.4-apache as php

# Where encountered an error with "RUN a2enmod rewrite"
# FROM php:8.3.4 as php
# FROM php:8.2.0 as php
# FROM php:8.1.0 as php
WORKDIR /var/www/html/

# RUN apt update -y

# RUN sudo chmod -R 777 /var/www/html/
# Mod Rewrite
RUN a2enmod rewrite

# Linux Library
RUN apt-get update -y && apt-get install -y \
    libicu-dev \
    libmariadb-dev \
    unzip zip \
    zlib1g-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev
# RUN sudo chmod -R 777 /var/www/html

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# PHP Extension
RUN docker-php-ext-install gettext intl pdo_mysql gd

RUN docker-php-ext-configure gd --enable-gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd