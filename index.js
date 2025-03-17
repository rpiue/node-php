const express = require("express");
const { createProxyMiddleware } = require("http-proxy-middleware");

const app = express();
const PORT = process.env.PORT || 3000;

// Middleware para servir archivos estáticos desde la carpeta "public"
app.use(express.static("public"));

// Redirige "/das" a "dashboard.php" y permite que Apache lo procese
app.use(
    "/dashboard",
    createProxyMiddleware({
        target: "http://localhost/dashboard.php", // Apache manejará la ejecución
        changeOrigin: true,
        selfHandleResponse: true, // Permite modificar la respuesta antes de enviarla al cliente
        onProxyRes: async (proxyRes, req, res) => {
            let body = Buffer.from([]);

            proxyRes.on("data", (chunk) => {
                body = Buffer.concat([body, chunk]); // Acumular datos de la respuesta
            });

            proxyRes.on("end", () => {
                let responseBody = body.toString("utf8");
                
                // Reemplazar {{usuario}} por "Juan"
                responseBody = responseBody.replace("{{usuario}}", "Juan");

                // Enviar la respuesta modificada al cliente
                res.writeHead(proxyRes.statusCode, proxyRes.headers);
                res.end(responseBody);
            });
        },
    })
);

// Proxy SOLO para archivos PHP (redirige todas las solicitudes PHP a Apache)
app.use(
    "/",
    createProxyMiddleware({
        target: "http://localhost", // Apache en el puerto 80
        changeOrigin: true,
    })
);

// Iniciar servidor
app.listen(PORT, () => {
    console.log(`🚀 Servidor corriendo en http://localhost:${PORT}`);
});
