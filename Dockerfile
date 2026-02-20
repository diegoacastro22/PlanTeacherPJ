FROM richarvey/nginx-php-fpm:latest

COPY . .

# Instalar Node.js 22 desde edge/community
RUN apk add --no-cache --repository=https://dl-cdn.alpinelinux.org/alpine/edge/community nodejs npm

# Verificar versión (debería ser 22.x)
RUN node --version && npm --version

# Instalar dependencias de Composer PRIMERO (para tener vendor/)
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Publicar assets de Filament y Livewire
RUN php artisan filament:assets
RUN php artisan livewire:publish --assets

# Instalar dependencias npm
RUN cd /var/www/html && npm ci

# Compilar assets
RUN cd /var/www/html && npm run build

# Verificar que se creó el build
RUN ls -la /var/www/html/public/build/ || echo "Build directory not found"

# Crear configuración de PHP-FPM
RUN printf "[www]\n\
user = nginx\n\
group = nginx\n\
listen = /var/run/php-fpm.sock\n\
listen.owner = nginx\n\
listen.group = nginx\n\
pm = dynamic\n\
pm.max_children = 50\n\
pm.start_servers = 10\n\
pm.min_spare_servers = 5\n\
pm.max_spare_servers = 20\n\
pm.max_requests = 500\n\
pm.process_idle_timeout = 10s\n" > /usr/local/etc/php-fpm.d/www.conf

# Image config
ENV SKIP_COMPOSER 1
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1
ENV REAL_IP_HEADER 1

# Laravel config
ENV APP_ENV production
ENV APP_DEBUG false
ENV LOG_CHANNEL stderr

# Allow composer to run as root
ENV COMPOSER_ALLOW_SUPERUSER 1

# Aumentar memoria
ENV COMPOSER_MEMORY_LIMIT -1
ENV PHP_MEMORY_LIMIT 1024M

CMD ["/start.sh"]


# Prueba queue worker

FROM richarvey/nginx-php-fpm:latest

COPY . .

RUN apk add --no-cache --repository=https://dl-cdn.alpinelinux.org/alpine/edge/community nodejs npm

RUN node --version && npm --version

RUN composer install --no-dev --optimize-autoloader --no-interaction

RUN php artisan filament:assets
RUN php artisan livewire:publish --assets

RUN cd /var/www/html && npm ci
RUN cd /var/www/html && npm run build

RUN ls -la /var/www/html/public/build/ || echo "Build directory not found"

# setup php-fpm
RUN printf "[www]\n\
user = nginx\n\
group = nginx\n\
listen = /var/run/php-fpm.sock\n\
listen.owner = nginx\n\
listen.group = nginx\n\
pm = dynamic\n\
pm.max_children = 50\n\
pm.start_servers = 10\n\
pm.min_spare_servers = 5\n\
pm.max_spare_servers = 20\n\
pm.max_requests = 500\n\
pm.process_idle_timeout = 10s\n" > /usr/local/etc/php-fpm.d/www.conf

ENV SKIP_COMPOSER 1
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1
ENV REAL_IP_HEADER 1

ENV APP_ENV production
ENV APP_DEBUG false
ENV LOG_CHANNEL stderr

ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_MEMORY_LIMIT -1
ENV PHP_MEMORY_LIMIT 1024M

# COPY queue listener script
COPY scripts/ /scripts/
RUN chmod -R +x /scripts/
