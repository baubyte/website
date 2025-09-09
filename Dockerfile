############################################
# Imagen base optimizada con serversideup/php
############################################

FROM serversideup/php:8.2-fpm-nginx AS base

# Cambiar a root para instalar extensiones PHP adicionales
USER root

# Instalar extensiones PHP requeridas por CodeIgniter 4 que podrían no estar incluidas por defecto
RUN install-php-extensions \
    intl \
    zip \
    gd \
    mysqli \
    pdo_mysql \
    bcmath \
    bz2 \
    calendar \
    opcache

############################################
# Configuración de desarrollo/producción
############################################

# Argumentos para el usuario (compatible con tu configuración actual)
ARG UID=1001
ARG GID=1001

# Configurar el usuario www-data con los IDs correctos del host
RUN docker-php-serversideup-set-id www-data $UID:$GID && \
    docker-php-serversideup-set-file-permissions --owner $UID:$GID --service nginx

# Configurar directorio de trabajo (serversideup ya lo tiene en /var/www/html)
WORKDIR /var/www/html

############################################
# Instalación de dependencias y código
############################################

# Copiar archivos de configuración de Composer primero (para aprovechar cache de Docker)
COPY --chown=www-data:www-data composer.json composer.lock ./

# Instalar dependencias de Composer (como root para tener permisos)
USER root
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress

# Copiar todo el código de la aplicación
COPY --chown=www-data:www-data . .

# Crear el enlace simbólico para uploads
RUN ln -sf /var/www/html/writable/uploads /var/www/html/public/uploads

# Asegurar permisos correctos en directorios críticos
RUN chown -R www-data:www-data /var/www/html/writable && \
    chmod -R 755 /var/www/html/writable && \
    chown -R www-data:www-data /var/www/html/public && \
    chmod -R 755 /var/www/html/public

# Volver al usuario sin privilegios
USER www-data

# Exponer puerto 80 (nginx integrado en serversideup)
EXPOSE 80

# El CMD por defecto de serversideup/php ya está configurado para iniciar nginx + php-fpm
