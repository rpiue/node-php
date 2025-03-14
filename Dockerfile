# Usa una imagen con Node.js y PHP ya instalados
FROM php:8.2-cli

# Instala Node.js y npm
RUN apt-get update && apt-get install -y curl \
    && curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

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
