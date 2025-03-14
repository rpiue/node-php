# Usa una imagen de Debian con PHP, Apache y Node.js
FROM debian:latest

# Instala PHP, Apache, Node.js y dependencias necesarias
RUN apt-get update && apt-get install -y \
    apache2 php libapache2-mod-php curl nodejs npm && \
    a2enmod rewrite && \
    service apache2 restart

# Define el directorio de trabajo
WORKDIR /var/www/html

# Copia los archivos del proyecto
COPY public/ /var/www/html/
COPY index.js /var/www/html/

# Instala dependencias de Node.js
RUN npm install

# Expone el puerto 80 para Apache y el 3000 para Node.js
EXPOSE 80 3000

# Comando para iniciar Apache y Node.js
CMD service apache2 start && node index.js
