# Imagen base con Debian
FROM debian:latest

# Instalar Nginx, PHP y Node.js sin MySQL
RUN apt-get update && apt-get install -y \
    nginx \
    php8.2-fpm \
    php8.2-cli \
    php8.2-curl \
    php8.2-mbstring \
    php8.2-xml \
    php8.2-zip \
    nodejs \
    npm \
    && rm -rf /var/lib/apt/lists/*

# Configurar directorios de trabajo
WORKDIR /var/www/frontend-php
COPY frontend-php/ /var/www/frontend-php

WORKDIR /app
COPY backend-node/ /app
RUN npm install

# Copiar configuración de Nginx
COPY nginx/default.conf /etc/nginx/conf.d/default.conf

# Exponer puerto 80 para Nginx
EXPOSE 80

# Comando de inicio para PHP, Nginx y Node.js
CMD service php8.2-fpm start && nginx -g 'daemon off;' & node /app/index.js
