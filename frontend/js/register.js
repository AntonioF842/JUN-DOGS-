
document.getElementById('registerForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent the default form submission behavior

    // Get the form values
    const nombre = document.getElementById('nombre').value;
    const apellido_paterno = document.getElementById('apellido_paterno').value;
    const apellido_materno = document.getElementById('apellido_materno').value;
    const fecha_nacimiento = document.getElementById('fecha_nacimiento').value;
    const direccion = document.getElementById('direccion').value;
    const sexo = document.getElementById('sexo').value;
    const identificacion_oficial = document.getElementById('identificacion_oficial').value;
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;

    // Send a POST request to the register.php script with the form data
    fetch('http://localhost/JUN-DOGS-/backend/register.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            nombre: nombre,
            apellido_paterno: apellido_paterno,
            apellido_materno: apellido_materno,
            fecha_nacimiento: fecha_nacimiento,
            direccion: direccion,
            sexo: sexo,
            identificacion_oficial: identificacion_oficial,
            email: email,
            password: password
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    }) // Parse the JSON response
    .then(data => {
        const registerMessage = document.getElementById('registerMessage');
        // Display a success or error message based on the response
        if (data.success) {
            registerMessage.textContent = 'Registration successful! You can now log in.';
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
        document.getElementById('registerMessage').textContent = 'An error occurred. Please try again.';
    });
});