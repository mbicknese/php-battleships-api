#!/bin/bash
set -e

# clear cache
rm -rf /var/www/var/cache/*

# start fpm
if [ "$SYMFONY_ENV" = "dev" ]; then
    exec /usr/sbin/php-fpm -F -d zend_extension=xdebug.so
else
    exec /usr/sbin/php-fpm -F
fi
