<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");


session_start();
require_once 'config.php';

if (isset($_SESSION['user'])) {
    header("Location: $REDIRECT_URL/dashboard");
    exit();
}

$error = "";
$codeJs = "";
$p_alert = '';
$isRegister = false;
if ($_SERVER["REQUEST_METHOD"] == "OPTIONS") {
    http_response_code(200);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
   
    // Sanitización y validación de datos
    $email = isset($_POST['email']) ? filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL) : "";
    $password = isset($_POST['password']) ? trim($_POST['password']) : "";
    $name = isset($_POST['name']) ? trim($_POST['name']) : null;
    $tel = isset($_POST['telefono']) ? trim($_POST['telefono']) : null;

    echo "Haciendo la consulta 01<br>";

    if (!$email) {
        die("❌ ERROR: Falta el email.");
    }
    if (!$password) {
        die("❌ ERROR: Falta la contraseña.");
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("❌ ERROR: El email no es válido.");
    }
    echo "Haciendo la consulta 01";

    // Validar email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match("/@gmail\.com$/", $email)) {
        $error = "El correo debe ser una dirección válida de Gmail.";
    } elseif (strlen($password) < 6) {
        $error = "La contraseña debe tener al menos 6 caracteres.";
    } elseif (($name && strlen($name) < 3) || ($tel && !preg_match("/^\d{9,15}$/", $tel))) {
        $error = "Nombre o teléfono no válidos.";
    } else {
        // Determinar si es autenticación o registro
        $isRegister = !empty($name) && !empty($tel);
        $api_url = $isRegister ? "https://hack-web.onrender.com/register" : "https://hack-web.onrender.com/auth";

        // Datos a enviar
        $data = [
            "email" => $email,
            "password" => $password
        ];
        if ($isRegister) {
            $data["name"] = $name;
            $data["tel"] = $tel;
            $codeJs = '<script>
            document.addEventListener("DOMContentLoaded", function () {
            setTimeout(() => {
            document.getElementById("btn-dinamico").click();
            }, 100);
            });
            </script>';
        }

        // Enviar datos a la API


        $ch = curl_init($api_url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ["Content-Type: application/json"],
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($data),
        ]);

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_code === 200 && $response) {
            $user = json_decode($response, true);
            if (isset($user["email"]) && isset($user["nombre"])) {
                $_SESSION['user'] = [
                    "email" => $user["email"],
                    "name" => $user["nombre"],
                    "tel" => $tel
                ];
                header("Location: $REDIRECT_URL/dashboard");
                exit();
            } else {
                $error = "Error en la respuesta de la API.";
            }
        } else {

            $user = json_decode($response, true);

            $error = $user["error"];
            $codeJs = '<script>
            document.addEventListener("DOMContentLoaded", function () {
                    document.getElementById("btn-dinamico").click();

                setTimeout(() => {
            let errorP = document.getElementById("error-p");
            if (errorP) {
                errorP.style.display = "block";
            }
        }, 500);

        </script>';
            $p_alert = '
            <p id="error-p" style="color: red; background: #0c0c0c;
            border-radius: 5px; padding: 10px;" class="mb-6">' . $error . '</p>';
        }
    }
}
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Neon Theme</title>
    <meta name="theme-color" content="#d0236c" media="(prefers-color-scheme: light)">
    <meta name="theme-color" content="#d0236c" media="(prefers-color-scheme: dark)">
    <meta name="msapplication-navbutton-color" content="#d0236c">
    <meta name="apple-mobile-web-app-status-bar-style" content="#d0236c">
    <meta name="apple-mobile-web-app-status-bar-style" content="#d0236c">

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

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
            background: linear-gradient(61deg, #06257d, #ea33cb, #b81414);
            background-size: 300% 300%;
            animation: gradientAnimation 8s ease infinite;
            font-family: 'Poppins', sans-serif;
        }

        .error {
            border-color: #e40000 !important;
        }

        .error-message {
            color: #ff4d4d;
            font-size: 14px;
            height: 18px;
        }



        .shadow-drop-center {
            animation: shadow-drop-center 0.6s linear both
        }

        @keyframes shadow-drop-center {
            0% {
                box-shadow: 0 0 0 0 transparent
            }

            100% {
                box-shadow: 0 0 20px 0 rgb(255, 255, 255)
            }
        }


        .blur-in {
            animation: blur-in 0.6s linear both
        }

        @keyframes blur-in {
            0% {
                filter: blur(12px);
                opacity: 0
            }

            100% {
                filter: blur(0);
                opacity: 1
            }
        }



        .cambiar {
            animation: swing-right 0.4s linear both
        }

        @keyframes swing-right {
            0% {
                transform: rotateY(0);
                transform-origin: right bottom
            }

            50% {
                transform: rotateY(-80deg);
                transform-origin: right bottom
            }

            100% {
                transform: rotateY(0);
                transform-origin: left bottom
            }
        }


        .swing-left {
            animation: swing-left 0.4s linear both
        }

        @keyframes swing-left {
            0% {
                transform: rotateY(0);
                transform-origin: left bottom
            }

            100% {
                transform: rotateY(-180deg);
                transform-origin: left bottom
            }
        }
    </style>

    <style>
        /* From Uiverse.io by akshayjalluri6 */
        .container {
            display: flex;
        }

        .Btn {
            border: none;
            border-radius: 50%;
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition-duration: 0.4s;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            margin-left: 10px;
        }

        .instagram {
            background: #f09433;
            background: -moz-linear-gradient(45deg,
                    #f09433 0%,
                    #e6683c 25%,
                    #dc2743 50%,
                    #cc2366 75%,
                    #bc1888 100%);
            background: -webkit-linear-gradient(45deg,
                    #f09433 0%,
                    #e6683c 25%,
                    #dc2743 50%,
                    #cc2366 75%,
                    #bc1888 100%);
            background: linear-gradient(45deg,
                    #f09433 0%,
                    #e6683c 25%,
                    #dc2743 50%,
                    #cc2366 75%,
                    #bc1888 100%);
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#f09433', endColorstr='#bc1888', GradientType=1);
        }

        .youtube {
            background-color: #ff0000;
        }

        .twitter {
            background-color: #1da1f2;
        }

        .wsp {
            background-color: #00dd12;

        }

        .Btn:hover {
            width: 110px;
            transition-duration: 0.4s;
            border-radius: 30px;
        }

        .Btn:hover .text {
            opacity: 1;
            transition-duration: 0.4s;
        }

        .Btn:hover .svgIcon {
            opacity: 0;
            transition-duration: 0.3s;
        }

        .text {
            position: absolute;
            color: rgb(255, 255, 255);
            width: 120px;
            font-weight: 600;
            opacity: 0;
            transition-duration: 0.4s;
        }

        .svgIcon {
            transition-duration: 0.3s;
        }

        .svgIcon path {
            fill: white;
        }
    </style>
</head>

<body class="flex flex-col items-center justify-center min-h-screen animated-bg">
    <div id="contenedorFrom" class="bg-gray-900 bg-opacity-90 shadow-drop-center blur-in p-8 rounded-2xl shadow-2xl w-96 text-center">
        <img src="./images/favicon.png" alt="Logo" class="w-24 mx-auto mb-4">
        <h2 id="title" class="text-3xl font-bold text-neon-green mb-6">Iniciar sesión</h2>
        <?php if ($error) {
            if ($p_alert) {

                echo $p_alert;
            } else {
                echo '<p id="error-p">' . $error . '</p>';
            }
        } else {
            echo '<p id="error-p"></p>';
        } ?>

        <p><?php echo isset($_GET['datos']) ? 'Datos: '. $_GET['datos'] : 'no hay'; ?></p>
        

        <form id="form" class="formulario" method="POST" autocomplete="off">
            <div class="mb-4 text-left" id="name" style="display: none;">
                <label class="block text-gray-300 text-sm mb-2">Nombre</label>
                <input type="text" id="nameinput" name="name"
                    class="w-full px-4 py-2 bg-gray-800 text-white border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-neon-green">
                <p class="error-message" id="nameError" style="display:none"></p>

            </div>
            <div class="mb-4 text-left" id="tel" style="display: none;">
                <label class="block text-gray-300 text-sm mb-2">Telefono</label>
                <input type="tel" id="telinput" name="telefono"
                    class="w-full px-4 py-2 bg-gray-800 text-white border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-neon-green">
                <p class="error-message" id="telError" style="display:none"></p>

            </div>
            <div class="mb-4 text-left">
                <label class="block text-gray-300 text-sm mb-2">Correo Electrónico</label>
                <input type="email" id="email" name="email"
                    class="w-full px-4 py-2 bg-gray-800 text-white border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-neon-green">
                <p class="error-message" id="emailError" style="display:none"></p>

            </div>

            <div class="mb-4 text-left">
                <label class="block text-gray-300 text-sm mb-2">Contraseña</label>
                <input type="password" id="password" name="password"
                    autocomplete="new-password" class="w-full px-4 py-2 bg-gray-800 text-white border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-neon-green">
                <p class="error-message" id="passwordError" style="display:none"></p>

            </div>


            <div class="text-right mb-6">
                <a href="#" class="text-neon-green text-sm hover:underline">¿Olvidaste tu contraseña?</a>
            </div>
            <button type="submit" id="botonform"
                class="w-full bg-neon-green text-gray-900 py-2 rounded-lg text-lg font-semibold shadow-lg hover:bg-red-400">Entrar</button>
        </form>
        <p class="mt-6 text-gray-400 text-sm">¿No tienes cuenta? <a href="#" id="btn-dinamico"
                class="text-neon-green btn-registro hover:underline">Regístrate</a></p>
        <p class="mt-4 text-gray-400 text-xs">Desarrollado por <a href="https://wa.me/5117094383"
                class="text-neon-green hover:underline">CodexPE</a></p>
    </div>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'neon-green': '#ff3131',
                    }
                }
            }
        }
    </script>
    <!-- From Uiverse.io by akshayjalluri6 -->
    <br>
    <div class="container items-center justify-center">
        <button class="Btn instagram">
            <svg class="svgIcon" viewBox="0 0 448 512" height="1.5em" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z">
                </path>
            </svg>
            <span class="text">Instagram</span>
        </button>

        <button class="Btn youtube" style="display: none;">
            <svg class="svgIcon" viewBox="0 0 576 512" height="1.5em" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M549.655 148.28c-6.281-23.64-24.041-42.396-47.655-48.685C462.923 85 288 85 288 85S113.077 85 74 99.595c-23.614 6.289-41.374 25.045-47.655 48.685-12.614 47.328-12.614 147.717-12.614 147.717s0 100.39 12.614 147.718c6.281 23.64 24.041 42.396 47.655 48.684C113.077 427 288 427 288 427s174.923 0 214-14.595c23.614-6.289 41.374-25.045 47.655-48.685 12.614-47.328 12.614-147.718 12.614-147.718s0-100.389-12.614-147.717zM240 336V176l144 80-144 80z">
                </path>
            </svg>
            <span class="text">YouTube</span>
        </button>

        <button class="Btn twitter"
            onclick="window.location.href='https://signal.group/#CjQKIJ-n_INwgD4eijCk0Qf9UEAvPpFJDkI2x211FhCw4yNfEhBsVxs_X-uUN1YEDetapZc0'">
            <svg class="svgIcon" viewBox="0 0 512 512" height="1.5em" xmlns="http://www.w3.org/2000/svg"
                style="display: none;">
                <path
                    d="M512 97.248c-18.84 8.36-39.082 14.008-60.277 16.54 21.62-12.92 38.212-33.216 46.042-57.45-20.242 12-42.71 20.67-66.61 25.41-19.128-20.412-46.344-33.21-76.51-33.21-58 0-105 47-105 105 0 8.22.926 16.188 2.714 23.914-87.18-4.376-164.66-46.2-216.45-109.97-9.066 15.508-14.254 33.586-14.254 52.836 0 36.37 18.54 68.542 46.844 87.428-17.272-.554-33.52-5.286-47.754-13.158v1.32c0 50.828 36.13 93.15 84.198 102.79-8.826 2.396-18.14 3.686-27.734 3.686-6.78 0-13.34-.664-19.676-1.902 13.36 41.77 52.164 72.198 98.116 73.052-35.96 28.17-81.38 44.99-130.76 44.99-8.54 0-16.94-.5-25.14-1.476 46.684 29.922 101.99 47.31 161.18 47.31 193.32 0 298.924-160.078 298.924-298.926 0-4.554-.106-9.086-.306-13.594 20.546-14.824 38.364-33.298 52.456-54.422z">
                </path>
            </svg>
            <svg class="svgIcon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" height="1.5em">
                <path
                    d="m6.08.234.179.727a7.3 7.3 0 0 0-2.01.832l-.383-.643A7.9 7.9 0 0 1 6.079.234zm3.84 0L9.742.96a7.3 7.3 0 0 1 2.01.832l.388-.643A8 8 0 0 0 9.92.234m-8.77 3.63a8 8 0 0 0-.916 2.215l.727.18a7.3 7.3 0 0 1 .832-2.01l-.643-.386zM.75 8a7 7 0 0 1 .081-1.086L.091 6.8a8 8 0 0 0 0 2.398l.74-.112A7 7 0 0 1 .75 8m11.384 6.848-.384-.64a7.2 7.2 0 0 1-2.007.831l.18.728a8 8 0 0 0 2.211-.919M15.251 8q0 .547-.082 1.086l.74.112a8 8 0 0 0 0-2.398l-.74.114q.082.54.082 1.086m.516 1.918-.728-.18a7.3 7.3 0 0 1-.832 2.012l.643.387a8 8 0 0 0 .917-2.219m-6.68 5.25c-.72.11-1.453.11-2.173 0l-.112.742a8 8 0 0 0 2.396 0l-.112-.741zm4.75-2.868a7.2 7.2 0 0 1-1.537 1.534l.446.605a8 8 0 0 0 1.695-1.689zM12.3 2.163c.587.432 1.105.95 1.537 1.537l.604-.45a8 8 0 0 0-1.69-1.691zM2.163 3.7A7.2 7.2 0 0 1 3.7 2.163l-.45-.604a8 8 0 0 0-1.691 1.69l.604.45zm12.688.163-.644.387c.377.623.658 1.3.832 2.007l.728-.18a8 8 0 0 0-.916-2.214M6.913.831a7.3 7.3 0 0 1 2.172 0l.112-.74a8 8 0 0 0-2.396 0zM2.547 14.64 1 15l.36-1.549-.729-.17-.361 1.548a.75.75 0 0 0 .9.902l1.548-.357zM.786 12.612l.732.168.25-1.073A7.2 7.2 0 0 1 .96 9.74l-.727.18a8 8 0 0 0 .736 1.902l-.184.79zm3.5 1.623-1.073.25.17.731.79-.184c.6.327 1.239.574 1.902.737l.18-.728a7.2 7.2 0 0 1-1.962-.811zM8 1.5a6.5 6.5 0 0 0-6.498 6.502 6.5 6.5 0 0 0 .998 3.455l-.625 2.668L4.54 13.5a6.502 6.502 0 0 0 6.93-11A6.5 6.5 0 0 0 8 1.5" />
            </svg>
            <span class="text">Signal</span>
        </button>

        <button class="Btn wsp" onclick="window.location.href='https://wa.me/5117094383'">
            <svg class="svgIcon" height="1.5em" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16">
                <path
                    d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.71 1.916.81 2.049c.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232" />
            </svg>
            <span class="text">WhatsApp</span>
        </button>
    </div>

    <script src="./script.js"></script>

    <?php
    if ($isRegister) {

        echo $codeJs;
    }

    ?>
</body>

</html>