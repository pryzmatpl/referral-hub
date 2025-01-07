# Copyright: PRIZM Piotr Słupski 2024

FROM archlinux:base-devel

LABEL maintainer="Piotr Słupski"
ARG WWWGROUP
ARG NODE_VERSION=18
ARG POSTGRES_VERSION=14

WORKDIR /var/www/html

ENV TZ=UTC

# Set timezone and install packages in a single RUN command
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone && \
    pacman -Syuu --noconfirm \
      curl nginx redis mariadb ffmpeg shadow supervisor git dma \
      libxslt unixodbc freetds tidy aspell enchant libvoikko hspell \
      hunspell nuspell net-snmp libsodium expac jq ninja python-tqdm \
      fakechroot gtest meson python-pip capstone net-snmp composer && \
    mkdir /var/php83libs

# Copy PHP packages and install them
COPY ./php83 /var/php83libs
RUN pacman -U --noconfirm /var/php83libs/*.pkg.tar.zst && \
    rm -rf /var/php83libs

# Add user, create directories, and set permissions
RUN useradd -m -s /bin/bash www-data && \
    mkdir -p /var/www/html/storage /var/www/html/storage/app/public/files /var/www/html/storage/app/chunks /run/php  /var/www/html/storage/logs  && \
    chmod -R 777 /var/www/html/storage /var/www/html/storage/app/public /var/www/html/storage/app/chunks /tmp /run/php && \
    chown www-data:www-data /run/php && \
    ln -sf /usr/bin/php82 /usr/bin/php && \
    cp -r /usr/lib/php82/ /usr/lib/php && \
    ln -sf /usr/bin/php-fpm82 /usr/bin/php-fpm

# Copy configuration files
COPY ./config/nginx.conf /etc/nginx/nginx.conf
COPY ./config/conf.d/default.conf /etc/nginx/conf.d/default-ssl.conf
COPY ./config/fpm-pool.conf /etc/php/php-fpm.d/www.conf
COPY ./config/php.ini /etc/php82/conf.d/custom.ini
COPY ./config/redis.ini /etc/php82/conf.d/redis.ini
COPY ./config/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY ./start-container /usr/local/bin/start-container
COPY ./*.ini /etc/php/conf.d/

# Make sure the start script is executable
RUN chmod +x /usr/local/bin/start-container
RUN chmod -R 777 /var/www/html/storage/logs

EXPOSE 80 443

ENTRYPOINT ["start-container"]
