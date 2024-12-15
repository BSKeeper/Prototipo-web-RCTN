document.querySelector('.contact-form').addEventListener('submit', function(e) {
    e.preventDefault(); // Previene el envío del formulario

    const name = document.getElementById('name').value;
    const email = document.getElementById('email').value;
    const message = document.getElementById('message').value;

    if (name && email && message) {
        alert('¡Mensaje enviado con éxito!');
    } else {
        alert('Por favor, completa todos los campos.');
    }
});
