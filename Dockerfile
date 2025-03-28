# Usa Debian estable como base
FROM debian:latest

# Actualiza los repositorios y agrega el repositorio de PHP de SURY
RUN apt-get update && apt-get install -y lsb-release apt-transport-https ca-certificates curl && \
    curl -sSL https://packages.sury.org/php/apt.gpg | apt-key add - && \
    echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" | tee /etc/apt/sources.list.d/php.list

# Instala PHP, Apache, Node.js y sus dependencias
RUN apt-get update && apt-get install -y \
    apache2 php8.2 php8.2-fpm libapache2-mod-fcgid \
    php8.2-curl php8.2-json php8.2-mbstring php8.2-xml php8.2-common \
    php8.2-cli php8.2-zip php8.2-tokenizer php8.2-bcmath php8.2-fileinfo \
    curl nodejs npm && \
    a2enmod rewrite headers proxy_fcgi setenvif && \
    a2enconf php8.2-fpm && \
    echo "ServerName localhost" >> /etc/apache2/apache2.conf

# 🔥 Elimina cualquier archivo HTML/PHP predeterminado
RUN rm -rf /var/www/html/*

# Configuración de PHP
RUN mkdir -p /etc/php/8.2/fpm/ && \
    echo "display_errors = On" >> /etc/php/8.2/fpm/php.ini && \
    echo "display_startup_errors = On" >> /etc/php/8.2/fpm/php.ini && \
    echo "error_reporting = E_ALL" >> /etc/php/8.2/fpm/php.ini && \
    echo "log_errors = Off" >> /etc/php/8.2/fpm/php.ini && \
    echo "allow_url_fopen = On" >> /etc/php/8.2/fpm/php.ini && \
    echo "post_max_size = 50M" >> /etc/php/8.2/fpm/php.ini && \
    echo "upload_max_filesize = 50M" >> /etc/php/8.2/fpm/php.ini

# Configuración de Apache para permitir POST y CORS
RUN echo "<Directory /var/www/html/>" >> /etc/apache2/apache2.conf && \
    echo "    AllowOverride All" >> /etc/apache2/apache2.conf && \
    echo "    Require all granted" >> /etc/apache2/apache2.conf && \
    echo "</Directory>" >> /etc/apache2/apache2.conf

# Habilita CORS
RUN echo "<IfModule mod_headers.c>" >> /etc/apache2/apache2.conf && \
    echo "    Header always set Access-Control-Allow-Origin '*'" >> /etc/apache2/apache2.conf && \
    echo "    Header always set Access-Control-Allow-Methods 'GET, POST, OPTIONS'" >> /etc/apache2/apache2.conf && \
    echo "    Header always set Access-Control-Allow-Headers 'Authorization, Content-Type'" >> /etc/apache2/apache2.conf && \
    echo "</IfModule>" >> /etc/apache2/apache2.conf

# Define el directorio de trabajo para Apache
WORKDIR /var/www/html

# Copia los archivos PHP
COPY public/ /var/www/html/

RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

# Configuración de Apache para deshabilitar páginas por defecto
RUN echo "DirectoryIndex index.php index.html" > /etc/apache2/mods-enabled/dir.conf

# Define el directorio de trabajo para Node.js
WORKDIR /app

# Copia los archivos de Node.js
COPY index.js package.json  /app/
COPY DB/ /app/DB

# Instala dependencias de Node.js
RUN npm install --production

# Expone los puertos 80 (Apache) y 3000 (Node.js)
EXPOSE 80 3000

# Iniciar Apache y Node.js correctamente
CMD service php8.2-fpm start && apachectl -D FOREGROUND & node /app/index.js
