FROM php:8.0.10-fpm

MAINTAINER Nicolas Canfrere

ENV DEBIAN_FRONTEND noninteractive
RUN apt-get update

ARG deps="git gnupg apt-utils apt-transport-https build-essential openssh-client rsync sqlite3 zip unzip vim \
          libbz2-dev libxml2-dev libenchant-2-dev libjpeg-dev libpng-dev libgmp-dev libc-client-dev libkrb5-dev libzip-dev \
          libfreetype6-dev libldap2-dev freetds-dev firebird-dev libpq-dev libpspell-dev libsqlite3-dev libtidy-dev libxslt-dev"

RUN apt-get install -y --no-install-recommends $deps

ARG modules="bcmath bz2 calendar dba enchant exif gd gettext \
               gmp imap intl ldap mysqli opcache pcntl pdo_dblib \
               pdo_firebird pdo_mysql pdo_pgsql pgsql pspell shmop soap \
                sockets sysvmsg sysvsem sysvshm tidy xsl zip"

RUN ln -s /usr/lib/x86_64-linux-gnu/libsybdb.a /usr/lib/libsybdb.a
RUN ln -s /usr/lib/x86_64-linux-gnu/libsybdb.so /usr/lib/libsybdb.so

RUN docker-php-ext-configure zip
RUN docker-php-ext-configure gmp
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-configure imap --with-imap --with-kerberos --with-imap-ssl

RUN docker-php-ext-install $modules

RUN docker-php-ext-enable sodium

RUN apt-get install -y --no-install-recommends libmagickwand-dev

RUN pecl install imagick-3.5.1 \
    && docker-php-ext-enable imagick
RUN pecl install apcu \
  && docker-php-ext-enable apcu
RUN pecl install redis \
	&& docker-php-ext-enable redis

RUN apt-get install -y --no-install-recommends librabbitmq-dev

RUN pecl install amqp-1.11.0beta  \
	&& docker-php-ext-enable amqp

RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"
RUN curl -Lsf 'https://storage.googleapis.com/golang/go1.8.3.linux-amd64.tar.gz' | tar -C '/usr/local' -xvzf -
ENV PATH /usr/local/go/bin:$PATH
RUN go get github.com/mailhog/mhsendmail
RUN cp /root/go/bin/mhsendmail /usr/bin/mhsendmail
RUN sed -i 's|;sendmail_path =|sendmail_path = /usr/bin/mhsendmail --smtp-addr mailhog:1025|g' $PHP_INI_DIR/php.ini

COPY configs/xxxx-custom.ini $PHP_INI_DIR/conf.d/

RUN cp /usr/share/zoneinfo/Europe/Paris /etc/localtime && echo "Europe/Paris" > /etc/timezone
RUN sed -i -e "s/<policy domain=\"coder\" rights=\"none\" pattern=\"PDF\" \/>/<policy domain=\"coder\" rights=\"read|write\" pattern=\"PDF\" \/>/g" /etc/ImageMagick-6/policy.xml

RUN apt-get clean \
 && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*
