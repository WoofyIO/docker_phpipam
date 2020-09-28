FROM php:apache

RUN apt-get update \
    && apt-get install -y git \
    &&git clone --recursive --single-branch --depth 1 --no-tags https://github.com/phpipam/phpipam.git /var/www/html/ \
    && cd /var/www/html/ \
    && git submodule update --init --recursive \
    && apt-get purge -y git \
    && apt-get autoremove -y \
    && rm -rf /var/lib/apt/lists/*

EXPOSE 80