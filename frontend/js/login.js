// Add an event listener to the login form to handle form submission
document.getElementById('loginForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent the default form submission behavior

    // Get the email and password values from the form inputs
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;

    // Send a POST request to the login.php script with the email and password
    fetch('../backend/login.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ email: email, password: password })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    }) // Parse the JSON response
    .then(data => {
        const loginMessage = document.getElementById('loginMessage');
        // Display a success or error message based on the response
        if (data.success) {
            loginMessage.textContent = `Login successful! Welcome, ${data.nombre} (ID: ${data.user_id})`;
            loginMessage.classList.add('text-success');
            loginMessage.classList.remove('text-danger');
        } else {
            loginMessage.textContent = data.message;
            loginMessage.classList.add('text-danger');
            loginMessage.classList.remove('text-success');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('loginMessage').textContent = 'An error occurred. Please try again.';
    });
});