# --- Etapa de construcción (Builder) ---
FROM php:8.2-cli-alpine AS builder
# Instalamos las extensiones de PHP que CodeIgniter necesita
RUN apk add --no-cache \
    icu-dev \
    libzip-dev \
    && docker-php-ext-install intl zip

# Copiamos Composer a nuestra nueva imagen base.
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY composer.json composer.lock ./
# Ahora, composer install se ejecutará en un entorno idéntico al de producción.
RUN composer install --no-dev --no-interaction --optimize-autoloader

COPY . .

# --- Etapa Final ---
# Usamos una imagen ligera de PHP-FPM basada en Alpine para producción
FROM php:8.0-fpm-alpine

WORKDIR /var/www/html
# Instalamos las extensiones de PHP que CodeIgniter necesita
# gd para imágenes, intl para internacionalización, pdo_mysql para la base de datos
RUN apk --no-cache add libzip-dev icu-dev libpng-dev libjpeg-turbo-dev freetype-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd intl pdo_mysql zip bcmath bz2 calendar iconv mbstring opcache mysqli zip


# Copiamos los archivos de la aplicación y las dependencias desde la etapa de construcción
COPY --from=builder /app .

# Damos permisos al servidor web sobre los directorios necesarios
RUN chown -R www-data:www-data /var/www/html/writable

# Exponemos el puerto de PHP-FPM y arrancamos el servicio
EXPOSE 9000
CMD ["php-fpm"]