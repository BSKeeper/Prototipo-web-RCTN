// Función para obtener y mostrar la información del usuario
window.addEventListener('DOMContentLoaded', function() {
    fetch('getUserInfo.php')
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Mostrar los datos en los campos del formulario
            const usuario = data.data;
            document.getElementById('nombre').value = usuario.Nombre;
            document.getElementById('correo').value = usuario.Correo;
            // El campo de contraseña lo dejamos vacío, ya que no se debe mostrar
        } else {
            alert('No se pudo cargar la información del perfil.');
        }
    })
    .catch(error => console.error('Error:', error));

    // Función para manejar el cierre de sesión
    document.getElementById('logout').addEventListener('click', function() {
        if (confirm('¿Deseas cerrar sesión?')) {
            window.location.href = 'logout.php'; // Redirigir a logout.php
        }
    });
});

window.addEventListener('DOMContentLoaded', function() {
    // Función para manejar el cierre de sesión
    document.getElementById('logout').addEventListener('click', function() {
        if (confirm('¿Deseas cerrar sesión?')) {
            window.location.href = 'logout.php'; // Redirigir a logout.php
        }
    });

    // Función para eliminar la cuenta
    document.getElementById('deleteAccount').addEventListener('click', function() {
        if (confirm('¿Estás seguro de que deseas eliminar tu cuenta? Esta acción es irreversible.')) {
            fetch('deleteAccount.php', {
                method: 'POST'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    window.location.href = 'logout.php'; // Redirigir a logout después de eliminar la cuenta
                } else {
                    alert(data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        }
    });
});
