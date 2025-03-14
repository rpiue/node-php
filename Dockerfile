# Usa una imagen base para PHP y Node.js
FROM php:8.2-fpm AS php
WORKDIR /var/www/html
COPY frontend-php/ /var/www/html

FROM node:18 AS node
WORKDIR /app
COPY backend-node/ /app
RUN npm install
CMD ["node", "server.js"]

# Configurar Nginx como proxy inverso
FROM nginx:latest
COPY nginx/default.conf /etc/nginx/conf.d/default.conf
