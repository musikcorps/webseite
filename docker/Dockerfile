# Dockerfile to build a Wordpress-container with Apache
# using a directory layout similar to uberspace.de hosts

FROM php:7.1-apache
MAINTAINER Johannes Lauinger <johannes@lauinger-it.de>


RUN apt-get clean && apt-get -y update && apt-get install -y git subversion unzip sudo mysql-client

# set recommended PHP.ini settings
# see https://secure.php.net/manual/en/opcache.installation.php
RUN { \
    echo 'opcache.memory_consumption=128'; \
    echo 'opcache.interned_strings_buffer=8'; \
    echo 'opcache.max_accelerated_files=4000'; \
    echo 'opcache.revalidate_freq=2'; \
    echo 'opcache.fast_shutdown=1'; \
    echo 'opcache.enable_cli=1'; \
  } > /usr/local/etc/php/conf.d/opcache-recommended.ini

RUN docker-php-ext-install mysqli && \
    docker-php-ext-install pdo_mysql

RUN a2enmod rewrite expires

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY docker/000-default.conf /etc/apache2/sites-available/


WORKDIR /var/www/virtual/docker/wordpress

RUN mkdir /var/www/virtual/docker/html

COPY wp-cli.yml ./
COPY docker/entrypoint.sh ./
COPY docker/check-and-setup.sh ./
COPY script ./script/
COPY config ./config/
COPY composer.json ./
COPY html ./html/


ENV USER docker

RUN script/uberspace-setup

RUN test -e config/docker.env || cp config/env.example config/docker.env
RUN ln -s config/docker.env .env

RUN chown -R www-data:www-data /var/www


ENTRYPOINT ["/var/www/virtual/docker/wordpress/entrypoint.sh"]

CMD ["apache2-foreground"]
