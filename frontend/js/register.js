document.getElementById('registerForm').addEventListener('submit', function(event) {
    event.preventDefault();

    const nombre = document.getElementById('nombre').value;
    const apellidoPaterno = document.getElementById('apellido_paterno').value;
    const apellidoMaterno = document.getElementById('apellido_materno').value;
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;

    fetch('http://localhost/backend/register.php', { // Verifica que esta URL sea correcta
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            nombre: nombre,
            apellido_paterno: apellidoPaterno,
            apellido_materno: apellidoMaterno,
            email: email,
            password: password
        })
    })
    
    .then(response => response.json())
    .then(data => {
        const registerMessage = document.getElementById('registerMessage');
        if (data.success) {
            registerMessage.textContent = 'Registro exitoso!';
            registerMessage.classList.add('text-success');
            registerMessage.classList.remove('text-danger');
        } else {
            registerMessage.textContent = data.message;
            registerMessage.classList.add('text-danger');
            registerMessage.classList.remove('text-success');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('registerMessage').textContent = 'Ocurrió un error. Inténtelo de nuevo.';
    });
});
