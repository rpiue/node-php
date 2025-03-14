const express = require("express");
const { createProxyMiddleware } = require("http-proxy-middleware");

const app = express();
const PORT = process.env.PORT || 3000;

// Middleware para servir archivos estáticos
app.use(express.static("public"));

// Proxy para redirigir solicitudes PHP a Apache
app.use(
    "/",
    createProxyMiddleware({
        target: "http://localhost", // Apache corre en el puerto 80
        changeOrigin: true,
    })
);

// Iniciar servidor
app.listen(PORT, () => {
    console.log(`Servidor corriendo en http://localhost:${PORT}`);
});
