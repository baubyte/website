# --- Etapa de construcción (Builder) ---
FROM php:8.2-cli-bookworm AS builder

# Añadimos libonig-dev también aquí para asegurar la compatibilidad con composer
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
# Recibimos los IDs del host como argumentos de construcción
ARG UID=1001
ARG GID=1001

# Creamos un grupo y un usuario ('appuser') con los IDs del host
# y lo añadimos al grupo 'www-data' para compatibilidad.
RUN groupadd -g ${GID} appgroup
RUN useradd -u ${UID} -g appgroup -m appuser
RUN usermod -a -G www-data appuser

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

# Le decimos a PHP-FPM que sus procesos deben correr como nuestro nuevo usuario
RUN sed -i "s/user = www-data/user = appuser/g" /usr/local/etc/php-fpm.d/www.conf
RUN sed -i "s/group = www-data/group = appgroup/g" /usr/local/etc/php-fpm.d/www.conf

# El contenedor se ejecutará como nuestro nuevo usuario, que coincide con el propietario de los archivos
USER appuser

# Exponemos el puerto de PHP-FPM y arrancamos el servicio
EXPOSE 9000
CMD ["php-fpm"]