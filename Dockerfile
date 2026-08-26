############################################
#
# THE MOST FRAGILE POINT OF THIS DEPLOY: there is exactly ONE Node build
# stage (`build` below). Both the `web` (PHP) and `ssr` (Node) final
# images `COPY --from=build` out of that SAME stage. Never add a second,
# independent `npm run build`/`vite build` invocation anywhere else in this
# file -- two separate builds of the same Svelte components can drift
# (different Vite/dependency resolution, different content hashes) and
# produce a client bundle and an SSR bundle that render different markup,
# which shows up as a hydration mismatch in production. `docker-compose.yaml`
# builds both services from this one Dockerfile using `--target web` /
# `--target ssr` for exactly this reason.
############################################

############################################
# Stage: build (shared by both final images)
############################################
FROM node:24-alpine AS build

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci

COPY resources/ resources/
COPY vite.config.js tailwind.config.js postcss.config.js ./
COPY public/ public/

# Single command, single stage: builds BOTH the client bundle (public/build)
# and the SSR bundle (bootstrap/ssr/ssr.js) from the exact same
# `npm ci`-installed dependency tree -- see package.json's `build` script
# (`vite build && vite build --ssr`).
RUN npm run build

############################################
# Stage: web -- serves the PHP/Inertia app (Traefik-exposed)
############################################
FROM serversideup/php:8.3-fpm-nginx AS web

USER root

RUN install-php-extensions \
    intl \
    zip \
    gd \
    pdo_mysql \
    bcmath \
    opcache

ARG UID=1001
ARG GID=1001

RUN docker-php-serversideup-set-id www-data $UID:$GID && \
    docker-php-serversideup-set-file-permissions --owner $UID:$GID --service nginx

WORKDIR /var/www/html

COPY --chown=www-data:www-data composer.json composer.lock ./

USER root
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress --no-scripts

RUN apt-get update && apt-get install -y \
  weasyprint \
  libjpeg-dev \
  libpng-dev \
  ghostscript \
  fonts-freefont-ttf \
  && rm -rf /var/lib/apt/lists/*

COPY --chown=www-data:www-data . .

# Client build output only -- the `web` image never runs Node and never
# needs the SSR bundle or node_modules.
COPY --chown=www-data:www-data --from=build /app/public/build ./public/build

RUN chown -R www-data:www-data /var/www/html/public && \
    chmod -R 755 /var/www/html/public

USER www-data

# serversideup/php's default CMD starts nginx + php-fpm.

############################################
# Stage: ssr -- internal-only Inertia Node SSR server (NOT Traefik-exposed)
############################################
FROM node:24-alpine AS ssr

WORKDIR /app

# `bootstrap/ssr/ssr.js` still imports its (externalized) runtime
# dependencies -- @inertiajs/core, @inertiajs/svelte, svelte/server, etc.
# -- from node_modules rather than bundling them inline (Vite's SSR build
# only inlines `laravel-vite-plugin` itself by default). Copying the exact
# node_modules the build stage installed and compiled against, instead of
# running a second `npm install` here, is what guarantees this image can
# never end up with different dependency versions than the ones the SSR
# bundle was actually built and tested against.
COPY --from=build /app/node_modules ./node_modules
COPY --from=build /app/bootstrap/ssr ./bootstrap/ssr

EXPOSE 13714

CMD ["node", "bootstrap/ssr/ssr.js"]
