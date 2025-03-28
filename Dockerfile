# Usa Debian con PHP, Apache y Node.js
FROM debian:latest

# Instala PHP, Apache, Node.js y dependencias necesarias
RUN apt-get update && apt-get install -y \
    apache2 php libapache2-mod-php \
    php-curl php-json php-mbstring php-xml php-common \
    php-cli php-zip php-tokenizer php-bcmath php-fileinfo \
    curl nodejs npm && \
    a2enmod rewrite headers && \
    echo "ServerName localhost" >> /etc/apache2/apache2.conf

# 🔥 Elimina cualquier archivo HTML/PHP predeterminado
RUN rm -rf /var/www/html/*


# Configuración de PHP para recibir JSON y POST correctamente
RUN echo "display_errors = On" >> /etc/php/8.2/apache2/php.ini && \
    echo "display_startup_errors = On" >> /etc/php/8.2/apache2/php.ini && \
    echo "error_reporting = E_ALL" >> /etc/php/8.2/apache2/php.ini && \
    echo "log_errors = Off" >> /etc/php/8.2/apache2/php.ini && \
    echo "allow_url_fopen = On" >> /etc/php/8.2/apache2/php.ini && \
    echo "post_max_size = 50M" >> /etc/php/8.2/apache2/php.ini && \
    echo "upload_max_filesize = 50M" >> /etc/php/8.2/apache2/php.ini


# Configuración de Apache para permitir POST y CORS
RUN echo "<Directory /var/www/html/>" >> /etc/apache2/apache2.conf && \
    echo "    AllowOverride All" >> /etc/apache2/apache2.conf && \
    echo "    Require all granted" >> /etc/apache2/apache2.conf && \
    echo "    <Limit POST>" >> /etc/apache2/apache2.conf && \
    echo "        Require all granted" >> /etc/apache2/apache2.conf && \
    echo "    </Limit>" >> /etc/apache2/apache2.conf && \
    echo "</Directory>" >> /etc/apache2/apache2.conf


# Habilita CORS y permite cualquier solicitud GET, POST, OPTIONS
RUN echo "<IfModule mod_headers.c>" >> /etc/apache2/apache2.conf && \
    echo "    Header always set Access-Control-Allow-Origin '*'" >> /etc/apache2/apache2.conf && \
    echo "    Header always set Access-Control-Allow-Methods 'GET, POST, OPTIONS'" >> /etc/apache2/apache2.conf && \
    echo "    Header always set Access-Control-Allow-Headers 'Authorization, Content-Type'" >> /etc/apache2/apache2.conf && \
    echo "</IfModule>" >> /etc/apache2/apache2.con


# Define el directorio de trabajo para Apache
WORKDIR /var/www/html

# Copia los archivos PHP al directorio web de Apache
COPY public/ /var/www/html/

RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

# Configuración de Apache para permitir URL amigables
RUN echo "<Directory /var/www/html/>" >> /etc/apache2/apache2.conf && \
    echo "    AllowOverride All" >> /etc/apache2/apache2.conf && \
    echo "    Require all granted" >> /etc/apache2/apache2.conf && \
    echo "</Directory>" >> /etc/apache2/apache2.conf


# 🔧 Configuración de Apache para deshabilitar páginas por defecto
RUN echo "DirectoryIndex index.php index.html" > /etc/apache2/mods-enabled/dir.conf && \
    service apache2 restart

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
CMD service apache2 start 
#&& node /app/index.js && tail -f /dev/null
