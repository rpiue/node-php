# Base con PHP, Node.js y Nginx
FROM debian:latest

# Instalar paquetes necesarios
RUN apt-get update && apt-get install -y \
    nginx \
    php8.2-fpm \
    php8.2-cli \
    php8.2-curl \
    php8.2-mbstring \
    php8.2-xml \
    php8.2-zip \
    php8.2-mysql \
    nodejs \
    npm \
    && rm -rf /var/lib/apt/lists/*

# Configurar directorios de trabajo
WORKDIR /var/www/html
COPY frontend-php/ /var/www/html

WORKDIR /app
COPY backend-node/ /app
RUN npm install

# Copiar configuración de Nginx
COPY nginx/default.conf /etc/nginx/conf.d/default.conf

# Exponer los puertos
EXPOSE 80

# Comandos para iniciar todo
CMD service php8.2-fpm start && service nginx start && node /app/index.js
