FROM php:7.1-fpm-alpine
LABEL maintainer="Maarten Bicknese <maarten.bicknese@devmob.com>"

RUN docker-php-ext-install pdo_mysql
