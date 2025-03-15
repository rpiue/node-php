# Usa una imagen de Debian con PHP, Apache y Node.js
FROM debian:latest

# Instala PHP, Apache, Node.js y dependencias necesarias
RUN apt-get update && apt-get install -y \
    apache2 php libapache2-mod-php curl nodejs npm && \
    a2enmod rewrite && \
    echo "ServerName localhost" >> /etc/apache2/apache2.conf && \
    service apache2 restart

# Define el directorio de trabajo para Apache
WORKDIR /var/www/html

# 🔥 Elimina archivos HTML predeterminados de Debian/Apache
RUN rm -f /var/www/html/index.html /var/www/html/index.php

# Copia los archivos PHP al directorio web de Apache
COPY public/ /var/www/html/

# Define el directorio de trabajo para Node.js
WORKDIR /app

# Copia los archivos de Node.js
COPY index.js package.json /app/

# Instala dependencias de Node.js
RUN npm install

# Expone el puerto 80 para Apache y el 3000 para Node.js
EXPOSE 80 3000

# Comando para iniciar Apache y Node.js
CMD service apache2 start && node /app/index.js
