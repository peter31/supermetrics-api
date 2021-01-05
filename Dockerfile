FROM wyveo/nginx-php-fpm:php74
ENV LANG=C.UTF-8
RUN apt-get update && \
    apt-get install -y telnet net-tools php7.4-xdebug && \
    apt-get clean

COPY docker/php/default.conf /etc/nginx/conf.d/default.conf

WORKDIR /srv/app/

