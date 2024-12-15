// script.js

// Carrusel automático
let currentIndex = 0;
const carouselItems = document.querySelectorAll('.carousel-item');
const totalItems = carouselItems.length;

function moveCarousel() {
    if (totalItems > 0) { // Verificar que existan elementos en el carrusel
        currentIndex = (currentIndex + 1) % totalItems; // Siguiente imagen
        const offset = -currentIndex * 100; // Mueve el carrusel
        document.querySelector('.carousel-container').style.transform = `translateX(${offset}%)`;
    }
}

// Cambia la imagen cada 3 segundos
setInterval(moveCarousel, 3000);

// Mostrar sólo un formulario a la vez
function toggleForm(showFormId, hideFormId) {
    const showForm = document.getElementById(showFormId);
    const hideForm = document.getElementById(hideFormId);

    if (showForm && hideForm) {
        showForm.style.display = "block";
        hideForm.style.display = "none";

        const firstInput = showForm.querySelector('input'); // Foco en el primer campo
        if (firstInput) firstInput.focus();
    } else {
        console.error("No se encontraron los formularios especificados.");
    }
}

// Interceptar el envío del formulario de inicio de sesión
function manejarInicioSesion() {
    const formLogin = document.getElementById("formLogin");
    if (formLogin) {
        formLogin.addEventListener("submit", function (event) {
            event.preventDefault(); // Evitar el envío del formulario por defecto

            const correo = document.getElementById("correoLogin")?.value.trim();
            const contraseña = document.getElementById("contraseñaLogin")?.value.trim();

            if (!correo || !contraseña) {
                alert("Por favor, completa todos los campos.");
                return;
            }

            // Enviar datos al servidor (login.php)
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "login.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onload = function () {
                if (xhr.status === 200) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            alert("Inicio de sesión exitoso.");
                            window.location.href = "dashboard.html"; // Redirige al dashboard
                        } else {
                            alert(response.message || "Error al iniciar sesión.");
                        }
                    } catch (e) {
                        console.error("Error procesando la respuesta del servidor:", e);
                        alert("Ocurrió un error inesperado. Intenta nuevamente.");
                    }
                } else {
                    alert("Error en la comunicación con el servidor. Intenta nuevamente.");
                }
            };
            xhr.onerror = function () {
                alert("No se pudo establecer conexión con el servidor. Verifica tu conexión a Internet.");
            };
            xhr.send(`correo=${encodeURIComponent(correo)}&contraseña=${encodeURIComponent(contraseña)}`);
        });
    } else {
        console.error("No se encontró el formulario con ID 'formLogin'.");
    }
}

// Interceptar el envío del formulario de registro
function manejarRegistro() {
    const formRegistro = document.getElementById("formRegistro");
    if (formRegistro) {
        formRegistro.addEventListener("submit", function (event) {
            event.preventDefault(); // Evitar el envío del formulario por defecto

            const nombre = document.getElementById("nombre")?.value.trim();
            const correo = document.getElementById("correo")?.value.trim();
            const contraseña = document.getElementById("contraseña")?.value.trim();
            const tipoUsuario = document.getElementById("tipoUsuario")?.value;

            if (!nombre || !correo || !contraseña || !tipoUsuario) {
                alert("Por favor, completa todos los campos.");
                return;
            }

            // Enviar datos al servidor (registro.php)
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "registro.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onload = function () {
                if (xhr.status === 200) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            alert("Usuario registrado con éxito.");
                            formRegistro.reset(); // Limpiar el formulario
                            toggleForm("login", "registro"); // Cambiar a formulario de inicio de sesión
                        } else {
                            alert(response.message || "Error al registrar el usuario.");
                        }
                    } catch (e) {
                        console.error("Error procesando la respuesta del servidor:", e);
                        alert("Ocurrió un error inesperado. Intenta nuevamente.");
                    }
                } else {
                    alert("Error en la comunicación con el servidor. Intenta nuevamente.");
                }
            };
            xhr.onerror = function () {
                alert("No se pudo establecer conexión con el servidor. Verifica tu conexión a Internet.");
            };
            xhr.send(`nombre=${encodeURIComponent(nombre)}&correo=${encodeURIComponent(correo)}&contraseña=${encodeURIComponent(contraseña)}&tipoUsuario=${encodeURIComponent(tipoUsuario)}`);
        });
    } else {
        console.error("No se encontró el formulario con ID 'formRegistro'.");
    }
}

// Configurar eventos después de cargar el DOM
document.addEventListener("DOMContentLoaded", function () {
    console.log("DOM completamente cargado.");

    // Mostrar formulario de registro
    const botonesRegistro = document.querySelectorAll('.register');
    botonesRegistro.forEach(boton => {
        boton.addEventListener('click', () => toggleForm("registro", "login"));
    });

    // Mostrar formulario de inicio de sesión
    const botonesLogin = document.querySelectorAll('.login');
    botonesLogin.forEach(boton => {
        boton.addEventListener('click', () => toggleForm("login", "registro"));
    });

    // Manejar envío de formularios
    manejarInicioSesion();
    manejarRegistro();
});
