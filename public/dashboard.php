<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once 'config.php';

if (!isset($_SESSION['user'])) {
  header("Location: $REDIRECT_URL");
  exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/@phosphor-icons/web"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  <link rel="icon" href="/images/favicon.png" type="image/png">

  <style>
    @keyframes gradientAnimation {
      0% {
        background-position: 0% 50%;
      }

      50% {
        background-position: 100% 50%;
      }

      100% {
        background-position: 0% 50%;
      }
    }

    .animated-bg {
      background: linear-gradient(61deg, #011753, #ea33cb, #b81414);
      background-size: 250% 250%;
      animation: gradientAnimation 8s ease infinite;
      font-family: 'Poppins', sans-serif;
      background-position: center;
      background-repeat: no-repeat;
      background-attachment: fixed;
    }

    body {
      background: #050505;

    }

    .cl-os {
      background: #111827;

    }

    .body-cl {
      background-color: #1b040473;

    }
  </style>
</head>

<body class="animated-bg text-white">
  <div class="md:flex h-screen">
    <!-- Overlay (para móviles) -->
    <div class="fixed inset-0 bg-black z-20 bg-opacity-50 hidden" id="overlay"></div>

    <!-- Sidebar -->
    <aside id="sidebar"
      class="w-64 cl-os p-5 z-20 flex flex-col fixed inset-y-0 left-0 transform -translate-x-full transition-transform md:translate-x-0 md:relative">
      <div class="flex justify-between items-start mb-1">
        <div class="flex-col justify-center items-center">
          <img src="/images/favicon.png" class="w-20 mb-2">
          <h2 class="text-2xl font-bold text-red-500">Codex Apps</h2>
        </div>
        <button class="text-white md:hidden" id="close-menu">
          <i class="ph ph-x"></i>
        </button>
      </div>
      <hr class="border-gray-500 my-4">

      <nav>
        <a href="#" data-seccion="inicio"
          class="flex menu-link items-center gap-2 py-2 px-4 hover:bg-gray-700 rounded">


          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
            class="bi bi-house-fill" viewBox="0 0 16 16">
            <path
              d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L8 2.207l6.646 6.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293z" />
            <path d="m8 3.293 6 6V13.5a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 13.5V9.293z" />
          </svg>

          Inicio
        </a>
        <a href="#" data-seccion="servidores"
          class="flex menu-link items-center gap-2 py-2 px-4 hover:bg-gray-700 rounded">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
            class="bi bi-pc-display" viewBox="0 0 16 16">
            <path
              d="M8 1a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H9a1 1 0 0 1-1-1zm1 13.5a.5.5 0 1 0 1 0 .5.5 0 0 0-1 0m2 0a.5.5 0 1 0 1 0 .5.5 0 0 0-1 0M9.5 1a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1zM9 3.5a.5.5 0 0 0 .5.5h5a.5.5 0 0 0 0-1h-5a.5.5 0 0 0-.5.5M1.5 2A1.5 1.5 0 0 0 0 3.5v7A1.5 1.5 0 0 0 1.5 12H6v2h-.5a.5.5 0 0 0 0 1H7v-4H1.5a.5.5 0 0 1-.5-.5v-7a.5.5 0 0 1 .5-.5H7V2z" />
          </svg> Servidores
        </a>
        <a href="#" data-seccion="tienda"
          class="flex menu-link items-center gap-2 py-2 px-4 hover:bg-gray-700 rounded">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
            class="bi bi-shop" viewBox="0 0 16 16">
            <path
              d="M2.97 1.35A1 1 0 0 1 3.73 1h8.54a1 1 0 0 1 .76.35l2.609 3.044A1.5 1.5 0 0 1 16 5.37v.255a2.375 2.375 0 0 1-4.25 1.458A2.37 2.37 0 0 1 9.875 8 2.37 2.37 0 0 1 8 7.083 2.37 2.37 0 0 1 6.125 8a2.37 2.37 0 0 1-1.875-.917A2.375 2.375 0 0 1 0 5.625V5.37a1.5 1.5 0 0 1 .361-.976zm1.78 4.275a1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0 1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0 1.375 1.375 0 1 0 2.75 0V5.37a.5.5 0 0 0-.12-.325L12.27 2H3.73L1.12 5.045A.5.5 0 0 0 1 5.37v.255a1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0M1.5 8.5A.5.5 0 0 1 2 9v6h1v-5a1 1 0 0 1 1-1h3a1 1 0 0 1 1 1v5h6V9a.5.5 0 0 1 1 0v6h.5a.5.5 0 0 1 0 1H.5a.5.5 0 0 1 0-1H1V9a.5.5 0 0 1 .5-.5M4 15h3v-5H4zm5-5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1zm3 0h-2v3h2z" />
          </svg> Tienda
        </a>
        <a href="#" data-seccion="cazados"
          class="flex menu-link items-center gap-2 py-2 px-4 hover:bg-gray-700 rounded">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
            class="bi bi-person" viewBox="0 0 16 16">
            <path
              d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z" />
          </svg> Cazados
        </a>
        <a href="#" data-seccion="reportes"
          class="flex menu-link items-center gap-2 py-2 px-4 hover:bg-gray-700 rounded">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
            class="bi bi-bar-chart" viewBox="0 0 16 16">
            <path
              d="M4 11H2v3h2zm5-4H7v7h2zm5-5v12h-2V2zm-2-1a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zM6 7a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1zm-5 4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1z" />
          </svg> Reportes
        </a>
        <a href="#" data-seccion="inicio"
          class="flex menu-link items-center gap-2 py-2 px-4 hover:bg-gray-700 rounded">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
            class="bi bi-boxes" viewBox="0 0 16 16">
            <path
              d="M7.752.066a.5.5 0 0 1 .496 0l3.75 2.143a.5.5 0 0 1 .252.434v3.995l3.498 2A.5.5 0 0 1 16 9.07v4.286a.5.5 0 0 1-.252.434l-3.75 2.143a.5.5 0 0 1-.496 0l-3.502-2-3.502 2.001a.5.5 0 0 1-.496 0l-3.75-2.143A.5.5 0 0 1 0 13.357V9.071a.5.5 0 0 1 .252-.434L3.75 6.638V2.643a.5.5 0 0 1 .252-.434zM4.25 7.504 1.508 9.071l2.742 1.567 2.742-1.567zM7.5 9.933l-2.75 1.571v3.134l2.75-1.571zm1 3.134 2.75 1.571v-3.134L8.5 9.933zm.508-3.996 2.742 1.567 2.742-1.567-2.742-1.567zm2.242-2.433V3.504L8.5 5.076V8.21zM7.5 8.21V5.076L4.75 3.504v3.134zM5.258 2.643 8 4.21l2.742-1.567L8 1.076zM15 9.933l-2.75 1.571v3.134L15 13.067zM3.75 14.638v-3.134L1 9.933v3.134z" />
          </svg> Codex Apps
        </a>
        <a href="#" data-seccion="wallet"
          class="flex menu-link items-center gap-2 py-2 px-4 hover:bg-gray-700 rounded">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
            class="bi bi-wallet2" viewBox="0 0 16 16">
            <path
              d="M12.136.326A1.5 1.5 0 0 1 14 1.78V3h.5A1.5 1.5 0 0 1 16 4.5v9a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 13.5v-9a1.5 1.5 0 0 1 1.432-1.499zM5.562 3H13V1.78a.5.5 0 0 0-.621-.484zM1.5 4a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h13a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5z" />
          </svg> Wallet
        </a>
        <a href="#" data-seccion="configuraciones"
          class="flex menu-link items-center gap-2 py-2 px-4 hover:bg-gray-700 rounded">
          <i class="ph ph-gear"></i> Configuración
        </a>
        <button id="logout"
          class="flex items-center gap-2 w-full text-left py-2 px-4 hover:bg-gray-700 rounded text-red-500">
          <i class="ph ph-sign-out"></i> Cerrar Sesión
        </button>
      </nav>
    </aside>

    <!-- Contenido Principal -->
    <div class="flex-1 flex flex-col">
      <!-- Header -->
      <header
        class="fixed top-0 left-0 right-0 md:left-64 cl-os p-4 shadow-md flex justify-between items-center z-10">
        <!-- Botón de menú móvil -->
        <button class="md:hidden text-white" id="menu-toggle">
          <i class="ph ph-list"></i>
        </button>

        <!-- Sección de iconos y usuario alineados a la derecha -->
        <div class="ml-auto flex items-center space-x-2">
          <!-- Icono de notificación -->
          <button class="relative text-white hover:text-gray-300">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" fill="currentColor" class="bi bi-bell-fill"
              viewBox="0 0 16 16">
              <path
                d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2m.995-14.901a1 1 0 1 0-1.99 0A5 5 0 0 0 3 6c0 1.098-.5 6-2 7h14c-1.5-1-2-5.902-2-7 0-2.42-1.72-4.44-4.005-4.901" />
            </svg>
            <!-- Indicador de notificación -->
            <span class="absolute -top-1 -right-1 bg-red-500 text-white rounded-full px-1"
              style="font-size: xx-small;">3</span>
          </button>

          <!-- Icono de moneda -->
          <button class="flex items-center text-white hover:text-gray-300">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" fill="currentColor" viewBox="0 0 24 24">
              <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none" />
              <text x="50%" y="50%" text-anchor="middle" dy=".3em" class="text-lg font-bold">$</text>
            </svg>
            <span class="ml-1 font-semibold">150</span>
          </button>

          <!-- Menú de usuario -->
          <div class="m-0">
            <!-- Botón de usuario -->
            <button id="user-menu-toggle"
              class="flex items-center gap-2 px-1 rounded-lg hover:bg-gray-700 transition">
              <div class=" w-8 h-8 rounded-full bg-gray-700 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="80%" fill="currentColor"
                  class="bi bi-person-circle" viewBox="0 0 16 16">
                  <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                  <path fill-rule="evenodd"
                    d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1" />
                </svg>
              </div>
              <span class="text-white sm:text-lg font-semibold"><?php echo "<pre>";
                                                                print_r($_SESSION['user']); // Muestra el contenido en formato legible
                                                                echo "</pre>"; ?></span>
              <i class="ph ph-caret-down text-white"></i>
            </button>

            <!-- Menú desplegable -->
            <div id="user-menu"
              class="absolute right-0 mt-2 w-48 bg-gray-800 text-white rounded-lg shadow-lg opacity-0 scale-95 transform transition-all origin-top-right hidden">
              <a href="#" class="block px-4 py-2 hover:bg-gray-700 transition">👤 Perfil</a>
              <a href="#" class="block px-4 py-2 hover:bg-gray-700 transition">⚙ Configuración</a>
              <button id="logout" class="block w-full text-left px-4 py-2 hover:bg-gray-700 transition">🚪
                Cerrar Sesión</button>
            </div>
          </div>
        </div>
      </header>

      <script>
        const userMenuToggle = document.getElementById("user-menu-toggle");
        const userMenu = document.getElementById("user-menu");

        function toggleUserMenu() {
          userMenu.classList.toggle("hidden");
          userMenu.classList.toggle("opacity-0");
          userMenu.classList.toggle("scale-95");
        }

        function closeUserMenu(event) {
          if (!userMenu.contains(event.target) && !userMenuToggle.contains(event.target)) {
            userMenu.classList.add("hidden", "opacity-0", "scale-95");
          }
        }

        userMenuToggle.addEventListener("click", toggleUserMenu);
        document.addEventListener("click", closeUserMenu);
      </script>



      <!-- Contenido -->
      <main id="contenido" class="p-6 h-screen"
        style="margin-top: 4rem;overflow: hidden;width: initial;overflow-y: auto;">


      </main>

    </div>
  </div>
  <script src="/socket.io/socket.io.js"></script>

  <script>
    const menuToggle = document.getElementById('menu-toggle');
    const closeMenuBtn = document.getElementById('close-menu');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    const logoutBtn = document.getElementById('logout');

    function openMenu() {
      sidebar.classList.remove('-translate-x-full');
      overlay.classList.remove('hidden');
    }

    function closeMenu() {
      sidebar.classList.add('-translate-x-full');
      overlay.classList.add('hidden');
    }

    menuToggle.addEventListener('click', openMenu);
    closeMenuBtn.addEventListener('click', closeMenu);
    overlay.addEventListener('click', closeMenu);

    document.addEventListener('keydown', function(event) {
      if (event.key === 'Escape') closeMenu();
    });

    sidebar.addEventListener('touchstart', function(event) {
      sidebar.dataset.touchStartX = event.touches[0].clientX;
    });

    sidebar.addEventListener('touchmove', function(event) {
      let touchEndX = event.touches[0].clientX;
      let touchStartX = sidebar.dataset.touchStartX;
      if (touchStartX - touchEndX > 50) closeMenu();
    });

    logoutBtn.addEventListener('click', function() {
      if (confirm('¿Estás seguro de que deseas cerrar sesión?')) {
        window.location.href = 'login.html';
      }
    });

    document.addEventListener("DOMContentLoaded", () => {
      const menuLinks = document.querySelectorAll(".menu-link");
      const contenido = document.getElementById("contenido");

      function cargarSeccion(tipo, plataforma = "", cambiarURL = true) {
        // Construir la URL de solicitud basada en el tipo y la plataforma
        let url = `/contenido?tipo=${tipo}`;
        if (plataforma) url += `&plataforma=${plataforma}`;

        fetch(url)
          .then(res => {
            if (!res.ok) throw new Error("Error al cargar el contenido");
            return res.text();
          })
          .then(html => {
            if (!contenido) return;
            contenido.innerHTML = html;

            setTimeout(() => {
              limpiarScripts();
              ejecutarScripts(contenido);
            }, 100);

            marcarSeleccionado(tipo);

            // Actualizar la URL sin recargar la página
            if (cambiarURL) {
              let nuevaURL = `/dashboard/?tipo=${tipo}`;
              if (plataforma) nuevaURL += `&plataforma=${plataforma}`;
              history.pushState({
                tipo,
                plataforma
              }, "", nuevaURL);
            }
          })
          .catch(err => console.error("Error cargando contenido:", err));
      }

      // 🔹 **Eliminar scripts antiguos antes de agregar nuevos**
      function limpiarScripts() {
        document.querySelectorAll("script[data-dinamico]").forEach(script => script.remove());
      }

      function nuevoScript() {
        alert("Cargando el nuevo scriptt..")
        //setTimeout(() => {
        //    limpiarScripts();
        //    ejecutarScripts(contenido);
        //}, 100);
      }

      // 🔹 **Ejecutar los scripts del nuevo contenido**
      function ejecutarScripts(elemento) {
        const scripts = elemento.querySelectorAll("script");

        scripts.forEach(oldScript => {
          let newScript = document.createElement("script");
          newScript.setAttribute("data-dinamico", "true"); // Marcar como dinámico

          if (oldScript.src) {
            newScript.src = oldScript.src + "?v=" + new Date().getTime();
            newScript.async = true;
            document.body.appendChild(newScript);
          } else {
            try {
              const scriptContent = oldScript.textContent;
              new Function(scriptContent)();
            } catch (error) {
              console.error("Error ejecutando script embebido:", error);
            }
          }
        });


      }


      function marcarSeleccionado(seccion) {
        console.log(window.location.pathname);

        menuLinks.forEach(link => link.classList.remove("bg-red-700"));

        const linkSeleccionado = document.querySelector(`.menu-link[data-seccion="${seccion}"]`);

        if (linkSeleccionado) {
          // Agrega la clase hover manualmente por 1 segundo
          linkSeleccionado.classList.remove("hover:bg-gray-700");
          linkSeleccionado.classList.add("bg-red-700");
          setTimeout(() => {
            linkSeleccionado.classList.add("hover:bg-gray-700");
          }, 1000);
          setTimeout(() => {
            closeMenu()
          }, 500);

        }
      }


      // Manejar clics en el menú sin recargar la página
      menuLinks.forEach(link => {
        link.addEventListener("click", event => {
          event.preventDefault();
          const seccion = link.getAttribute("data-seccion");
          cargarSeccion(seccion);
        });
      });

      // Cargar sección correcta al recargar la página
      function detectarURL() {
        const urlParams = new URLSearchParams(window.location.search);
        const tipo = urlParams.get("tipo") || "inicio";
        const p = urlParams.get("plataforma") || "";
        cargarSeccion(tipo, p, false);


      }
      if (!history.state) {
        history.replaceState({
          tipo: "inicio",
          plataforma: null
        }, "", "/dashboard");
      }
      // Escuchar cambios en el historial (botones de atrás/adelante del navegador)
      window.addEventListener("popstate", (event) => {
        if (event.state) {
          const {
            tipo,
            plataforma
          } = event.state;
          detectarURL();
        }
      });


      detectarURL(); // Cargar contenido correcto al inicio
    });
  </script>

</body>

</html>