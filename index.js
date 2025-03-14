const express = require("express");
const { exec } = require("child_process");
const path = require("path");

const app = express();
const PORT = process.env.PORT || 3000;

// Middleware para servir archivos estáticos (CSS, imágenes, etc.)
app.use(express.static(path.join(__dirname, "public")));

// Ruta para ejecutar PHP
app.get("/", (req, res) => {
    exec("php public/index.php", (error, stdout, stderr) => {
        if (error) {
            res.status(500).send(`Error ejecutando PHP: ${stderr}`);
        } else {
            res.send(stdout);
        }
    });
});

// Iniciar servidor
app.listen(PORT, () => {
    console.log(`Servidor corriendo en http://localhost:${PORT}`);
});
