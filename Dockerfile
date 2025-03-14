# Usa una imagen de Debian con PHP y Node.js
FROM debian:latest

# Instala PHP, Node.js y dependencias necesarias
RUN apt-get update && apt-get install -y \
    php-cli curl nodejs npm

# Crea y entra en el directorio de la app
WORKDIR /app

# Copia los archivos del proyecto
COPY . .

# Instala dependencias de Node.js
RUN npm install

# Expone el puerto 3000
EXPOSE 3000

# Comando para ejecutar la app
CMD ["node", "index.js"]
