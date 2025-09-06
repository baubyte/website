# --- Etapa de construcción (Builder) ---
# Usamos una imagen oficial de Composer para instalar dependencias de forma segura
FROM composer:2 as builder

WORKDIR /app
# Copiamos solo los archivos de dependencias y las instalamos
# Esto aprovecha el cache de Docker si no cambian las dependencias
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --optimize-autoloader

# Copiamos el resto del código de la aplicación
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