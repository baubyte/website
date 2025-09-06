# --- Etapa de construcción (Builder) ---
FROM php:8.2-cli-bookworm AS builder

# Instalamos las extensiones de PHP necesarias para que Composer se ejecute
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libzip-dev \
    libonig-dev \
    && docker-php-ext-install intl zip mbstring

# Copiamos Composer a nuestra nueva imagen base
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY composer.json composer.lock ./
# Composer instala las dependencias en un entorno de PHP 8.2
RUN composer install --no-dev --no-interaction --optimize-autoloader

COPY . .

# --- Etapa Final ---
FROM php:8.2-fpm-bookworm

WORKDIR /var/www/html

# 2. El comando RUN ahora encontrará la librería gracias a la variable de entorno.
RUN apt-get update && apt-get install -y \
    libzip-dev \
    libicu-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libbz2-dev \
    libonig-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    gd \
    intl \
    pdo_mysql \
    zip \
    iconv \
    bcmath \
    bz2 \
    calendar \
    mbstring \
    opcache \
    mysqli

# Copiamos los archivos de la aplicación desde la etapa de construcción
COPY --from=builder /app .

# Damos permisos al servidor web sobre los directorios necesarios
RUN chown -R www-data:www-data /var/www/html/writable

# Exponemos el puerto de PHP-FPM y arrancamos el servicio
EXPOSE 9000
CMD ["php-fpm"]