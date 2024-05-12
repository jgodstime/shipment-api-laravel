FROM php:8.2-fpm

ARG HOST_UID

WORKDIR /var/www/html

RUN apt-get update -y && apt-get install \
    libonig-dev -y openssl \
     zlib1g-dev \
    libicu-dev \
    libpq-dev \
    libpcre3-dev \
    nano \
    libzip-dev\
    zip \
    unzip \
    git \
    wget \
    gnupg \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libxpm-dev \
    libvpx-dev \
    libwebp-dev \
    libjpeg-dev \
    libpng-dev \
    iproute2 \
    supervisor \
    cron \
    sqlite3 \
    && apt-get install default-mysql-client -y \
    && docker-php-ext-configure zip \
    && docker-php-ext-configure gd --enable-gd --with-jpeg

# install PHP extensions
RUN docker-php-ext-install -j$(nproc) iconv mysqli pdo pdo_mysql mbstring exif pcntl gd bcmath

# Install Phpredis
RUN pecl install -o -f redis \
&&  rm -rf /tmp/pear \
&&  docker-php-ext-enable redis

# Install Composer
ADD ./composer.sh ./
RUN chmod +x ./composer.sh && ./composer.sh


# Install Node.js
RUN curl -sL https://deb.nodesource.com/setup_14.x | bash - \
    && DEBIAN_FRONTEND=noninteractive apt-get install nodejs --no-install-recommends -yqq
RUN usermod -u $HOST_UID www-data && groupmod -g $HOST_UID www-data && chsh -s /bin/bash www-data

# Copy bash_aliases
COPY ./.bashrc /root/.bashrc
RUN cp /root/.bashrc /var/www

# use custom php.ini file
COPY php.ini /usr/local/etc/php/conf.d/custom.ini

# Add cron job configuration
COPY shipment_crontab.sh /var/www/shipment_crontab.sh
RUN chmod +x /var/www/shipment_crontab.sh

#configure supervisor
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]


