FROM ubuntu:16.04

ENV DEBIAN_FRONTEND=noninteractive
ENV LANG=en_US.UTF-8
ENV PHP_VERSION=7.1
ENV TIMEZONE=UTC

# Configure locale and timezone and install base packages
COPY locale.gen /etc/locale.gen
RUN apt-get update && \
    apt-get -yq install --no-install-recommends \
        ca-certificates locales bzip2 git curl zip unzip acl && \
    apt-get -y clean && \
    echo $TIMEZONE > /etc/timezone && \
    ln -fs /usr/share/zoneinfo/$TIMEZONE /etc/localtime && \
    # Install php and base packages
    echo "deb http://ppa.launchpad.net/ondrej/php/ubuntu xenial main" >> /etc/apt/sources.list && \
    apt-key adv --keyserver hkp://keyserver.ubuntu.com:80 --recv-keys E5267A6C && \
    apt-get update && \
    apt-get -yq upgrade && \
    apt-get -yq install --no-install-recommends \
        php${PHP_VERSION}-cli \
        php${PHP_VERSION}-curl \
        php${PHP_VERSION}-fpm \
        php${PHP_VERSION}-mbstring \
        php${PHP_VERSION}-mysql \
        php${PHP_VERSION}-intl \
        php${PHP_VERSION}-json \
        php${PHP_VERSION}-sqlite \
        php${PHP_VERSION}-xdebug \
        php${PHP_VERSION}-xml && \
    apt-get -y clean && \
    rm -rf /var/lib/apt/lists/* && \
    # Configure Xdebug
    rm /etc/php/${PHP_VERSION}/fpm/conf.d/20-xdebug.ini && \
    echo "xdebug.remote_enable=1\nxdebug.remote_connect_back=1\n" > /etc/php/${PHP_VERSION}/fpm/conf.d/20-xdebug.ini && \
    # Configure FPM
    sed -i 's@^error_log .*@error_log = /proc/self/fd/2@' /etc/php/${PHP_VERSION}/fpm/php-fpm.conf && \
    sed -i 's@^;clear_env .*@clear_env = no@' /etc/php/${PHP_VERSION}/fpm/pool.d/www.conf && \
    sed -i 's@^listen .*@listen = 0.0.0.0:9000@; s@^access.log .*@access.log = /proc/self/fd/2@' /etc/php/${PHP_VERSION}/fpm/pool.d/www.conf && \
    ln -sf /usr/sbin/php-fpm${PHP_VERSION} /usr/sbin/php-fpm && \
    mkdir -p /run/php

ADD server/run.sh /run.sh

WORKDIR /var/www
COPY bin bin
COPY src src
COPY var var
COPY vendor vendor
COPY web web
COPY autoload.php autoload.php

EXPOSE 9000

ENV SYMFONY_ENV=prod
ENV SYMFONY_DEBUG=0

CMD ["/run.sh"]
