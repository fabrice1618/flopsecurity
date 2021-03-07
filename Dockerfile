FROM php:8-apache

COPY servername.conf /etc/apache2/conf-available
RUN a2enconf servername.conf
RUN a2enmod rewrite.load

RUN apt-get update && \
    apt-get install -y libxml2-dev && \
    docker-php-ext-install xml pdo pdo_mysql

# Configuration PHP Dev/Prod
# RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

ENV TZ=Europe/Paris
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone
COPY environment /etc/environment
COPY rcS /etc/default/rcS

