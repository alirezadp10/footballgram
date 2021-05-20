FROM php:7.4-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    mariadb-client \
    libpng-dev \
    libzip-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    procps \
    vim \
    unzip \
    git \
    tmux \
    curl

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN pecl channel-update pecl.php.net
RUN pecl install apcu igbinary mongodb
RUN pecl bundle redis && cd redis && phpize && ./configure --enable-redis-igbinary && make && make install

# Install extensions
RUN docker-php-ext-install pdo_mysql zip exif pcntl gd bcmath sockets
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-enable apcu igbinary mongodb opcache redis
RUN docker-php-source delete
RUN rm -r /tmp/* /var/cache/* /var/www/html/*

# Add user for laravel application
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# Set working directory
WORKDIR /var/www

COPY . .

# Copy existing application directory permissions
COPY --chown=www:www . .

# Change current user to www
USER www

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
