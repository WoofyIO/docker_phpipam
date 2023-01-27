FROM alpine:latest

RUN apk upgrade --no-cache \
    && apk add --no-cache tini git tzdata alpine-conf mariadb-client \
       php php-cli php-pear php-pdo_mysql php-json php-session php-gmp php-gd php-sockets php-gettext php-mbstring \
       php-ctype php-ldap php-curl php-snmp php-openssl php-simplexml php-pcntl php-iconv php-opcache php-posix php-dom \
       iputils fping \
       apache2 php-apache2 \
    && sed -i 's/#LoadModule rewrite_module modules\/mod_rewrite.so/LoadModule rewrite_module modules\/mod_rewrite.so/' /etc/apache2/httpd.conf \
    && sed -i 's/ErrorLog logs\/error.log/ErrorLog \/dev\/stderr/' /etc/apache2/httpd.conf \
    && sed -i 's/CustomLog logs\/access.log combined/CustomLog \/dev\/stdout combined/' /etc/apache2/httpd.conf \
    && git clone --recursive --single-branch --depth 1 --no-tags https://github.com/phpipam/phpipam.git /phpipam \
    && cd /phpipam \
    && git submodule update --init --recursive \
    && find -type f -name ".git*"        -exec rm -f {} \; \
    && find -name ".git*"        -exec rmdir --ignore-fail-on-non-empty {} \; \
    && find -type f              -exec chmod u=rw,go=r {} \; \
    && find -type d              -exec chmod u=rwx,go=rx {} \; \
    && find -type d -name upload -exec chmod a=rwx {} \; \  

ADD crontab /etc/crontabs/apache
ADD apache.conf /etc/apache2/conf.d/
ADD start.sh /
ADD config.php /phpipam/

RUN chmod +x /start.sh
RUN sed -i 's/\r//' /start.sh
EXPOSE 80

CMD ["/start.sh"]
