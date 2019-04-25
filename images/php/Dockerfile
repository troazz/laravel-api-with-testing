FROM phpdockerio/php73-fpm:latest

WORKDIR /application

# Fix debconf warnings upon build
ARG DEBIAN_FRONTEND=noninteractive

# Install selected extensions and other stuff
RUN apt-get update \
    && apt-get -y --no-install-recommends install nodejs npm php7.3-mysql php7.3-gd php7.3-bcmath php-imagick php7.3-intl php7.3-xmlrpc git zip \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

RUN curl --silent --show-error https://getcomposer.org/installer | php

RUN usermod -u 1000 www-data && groupmod -g 1000 www-data