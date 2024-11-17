# docker/php-fpm/Dockerfile

FROM php:8.2-fpm-alpine

COPY wait-for-it.sh /usr/bin/wait-for-it

RUN chmod +x /usr/bin/wait-for-it
RUN apk --update --no-cache add  \
    git  \
    icu-dev  \
    libxslt-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    libwebp-dev \
    libwebp-dev \
    freetype-dev
RUN docker-php-ext-configure intl \
    && docker-php-ext-install intl \
    && docker-php-ext-enable intl
RUN docker-php-ext-install mysqli pdo pdo_mysql xsl
RUN docker-php-ext-configure gd --with-jpeg --with-freetype
RUN docker-php-ext-install gd
RUN apk --no-cache add  \
    libzip-dev  \
    zip \
    && docker-php-ext-install zip

COPY --from=composer /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/
CMD php-fpm

# Создание пользователя с UID и GID как у пользователя хоста
ARG UID=1000
ARG GID=1000
ARG USER="docker"
ARG GROUPNAME=$USER
RUN addgroup --gid "$GID" "$GROUPNAME"
RUN adduser -D -S $USER -G $GROUPNAME -u $UID

USER $USER

EXPOSE 9000
