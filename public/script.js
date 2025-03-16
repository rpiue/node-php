//titulo del formulario
const title = document.querySelector("#title");

let formulario = document.querySelector(".formulario");
const contenedorFrom = document.querySelector("#contenedorFrom");
const email = document.querySelector("#email");
const password = document.querySelector("#password");
const telContainer = document.querySelector("#tel");
const telinput = document.querySelector("#telinput");
const botonForm = document.querySelector("#botonform");
const btnRegistro = document.querySelector(".btn-registro");
const btnLogin = document.querySelector(".btn-login");
const btnDinamico = document.querySelector("#btn-dinamico");

// Mensajes de error
const emailError = document.querySelector("#emailError");
const passwordError = document.querySelector("#passwordError");
const telError = document.querySelector("#telError");

// Función para validar
function validarFormulario(e, fromulario, esRegistro = false) {
  e.preventDefault();
  let valid = true;

  // Resetear errores
  document
    .querySelectorAll(".error-message")
    .forEach((el) => (el.style.display = "none"));
  document
    .querySelectorAll("input")
    .forEach((el) => el.classList.remove("error"));

  // Validar email
  if (!/^\S+@\S+\.\S+$/.test(email.value)) {
    emailError.textContent = "Correo inválido";
    emailError.style.display = "block";
    email.classList.add("error");
    valid = false;
  }

  // Validar contraseña
  if (password.value.length < 6) {
    passwordError.textContent = "Mínimo 6 caracteres";
    passwordError.style.display = "block";
    password.classList.add("error");
    valid = false;
  }

  // Validar teléfono si es registro
  if (esRegistro && !/^\d{9,}$/.test(telinput.value)) {
    telError.textContent = "Número inválido";
    telError.style.display = "block";
    telinput.classList.add("error");
    valid = false;
  }


  if (valid) {
    fromulario.submit();
    //const formData = new FormData(formulario);
    //formData.append("tipo", esRegistro ? "registro" : "login"); // Asegurar que 'tipo' se envíe
//
    //fetch(formulario.action, {
    //  method: "POST",
    //  body: formData,
    //})
    //  .then(response => response.json())
    //  .then(data => {
    //    if (data.error) {
    //      alert(data.error); // Mostrar el mensaje de error del servidor
    //    } else {
    //      //alert(data.message); // Mostrar mensaje de éxito
    //      //window.location.href = "/dashboard"; // Redirigir si es necesario
    //    }
    //  })
    //  .catch(error => console.error("Error:", error));
  }//
}

// Validación para login y registro
formulario.addEventListener("submit", function (e) {
  const esRegistro = formulario.classList.contains("registro");
  validarFormulario(e, formulario, esRegistro);
});

// Cambio a registro
btnDinamico.addEventListener("click", function (event) {
  event.preventDefault();
  //alert("rev")
  contenedorFrom.classList.remove("blur-in");

  // Eliminar la clase 'error' solo si existe
  [telinput, email, password].forEach((input) =>
    input.classList.remove("error")
  );
  [telError, emailError, passwordError].forEach(
    (input) => (input.style.display = "none")
  );
  //formulario.classList.add("cambiar");

  setTimeout(() => {
    // alert("agg")
    contenedorFrom.classList.add("blur-in");

    //  formulario.classList.remove("cambiar");
  }, 10); // Se elimina después de 5 segundos

  const esRegistro = btnDinamico.classList.contains("btn-login");
  if (esRegistro) {
    telContainer.style.display = "none";
    btnDinamico.classList.add("btn-registro");
    btnDinamico.classList.remove("btn-login");
    botonForm.innerText = "Entrar";
    title.innerText = "Iniciar sesión";
    btnDinamico.innerText = "Registrar";
    formulario.classList.remove("registro");

    formulario.classList.add("formulario");
  } else {
    title.innerText = "Registro";
    formulario.classList.remove("formulario");

    telContainer.style.display = "block";
    btnDinamico.classList.add("btn-login");
    botonForm.innerText = "Registrar";
    formulario.classList.add("registro");
    btnDinamico.innerText = "Entrar";

    btnDinamico.classList.remove("btn-registro");
  }
});
