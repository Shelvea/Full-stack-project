FROM php:8.3-fpm

ARG user
ARG uid

RUN apt-get update && apt-get install -y \
    curl \
    unzip \
    libicu-dev \
    libzip-dev \
    libxml2-dev \
    libpng-dev \
    && docker-php-ext-install -j$(nproc) \
    pdo_mysql \
    bcmath \
    intl \
    zip \
    opcache \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

    #Install NodeJS
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

    #install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN useradd -u $uid -ms /bin/bash -g www-data $user

COPY . /var/www

WORKDIR /var/www

RUN composer install

RUN npm install && npm run build

COPY --chown=$user:www-data . /var/www

USER $user

EXPOSE 9000

CMD ["php-fpm"]




