# Usa una imagen con PHP y Node.js ya preinstalados
FROM caddy:2.7.6-builder AS caddy-builder

# Instala PHP y Node.js
RUN apt-get update && apt-get install -y php-cli curl nodejs npm

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
