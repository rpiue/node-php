# Usa una imagen de Debian con PHP, Apache y Node.js
FROM debian:latest

# Instala PHP, Apache, Node.js y dependencias necesarias
RUN apt-get update && apt-get install -y \
    apache2 php libapache2-mod-php curl nodejs npm && \
    a2enmod rewrite && \
    echo "ServerName localhost" >> /etc/apache2/apache2.conf && \
    rm -rf /var/lib/apt/lists/*

# Define el directorio de trabajo para Apache
WORKDIR /var/www/html

# Copia los archivos PHP al directorio web de Apache
COPY public/ /var/www/html/

# Define el directorio de trabajo para Node.js
WORKDIR /app

# Copia los archivos de Node.js
COPY index.js package.json /app/

# Instala dependencias de Node.js
RUN npm install --production

# Expone los puertos 80 (Apache) y 3000 (Node.js)
EXPOSE 80 3000

# Iniciar Apache y Node.js correctamente
CMD service apache2 start && node /app/index.js && tail -f /dev/null
