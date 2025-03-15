const express = require("express");
const { createProxyMiddleware } = require("http-proxy-middleware");
const path = require("path");

const app = express();
const PORT = process.env.PORT || 3000;

// Middleware para servir archivos estáticos desde la carpeta "public"
app.use(express.static("public"));

// Ruta específica para mostrar el dashboard
app.get("/dashboard", (req, res) => {
    res.sendFile(path.join(__dirname, "public", "dashboard.php"));
});

// Proxy SOLO para solicitudes PHP (evita que todas las solicitudes pasen por Apache)
app.use(
    "/", // Solo redirige solicitudes PHP
    createProxyMiddleware({
        target: "http://localhost", // Apache corre en el puerto 80
        changeOrigin: true,
    })
);

// Iniciar servidor
app.listen(PORT, () => {
    console.log(`Servidor corriendo en http://localhost:${PORT}`);
});
