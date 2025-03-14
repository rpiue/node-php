const express = require("express");

const app = express();
const PORT = 3000;  // Puerto interno

app.get("/api/saludo", (req, res) => {
    res.json({ mensaje: "Hola desde Node.js en Render" });
});

app.listen(PORT, () => {
    console.log(`Servidor Node.js en http://localhost:${PORT}`);
});
