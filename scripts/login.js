// Add an event listener to the login form to handle form submission
document.getElementById('loginForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent the default form submission behavior

    // Get the email and password values from the form inputs
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;

    // Send a POST request to the login.php script with the email and password
    fetch('config/login.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ email: email, password: password })
    })
    .then(response => response.json()) // Parse the JSON response
    .then(data => {
        const loginMessage = document.getElementById('loginMessage');
        // Display a success or error message based on the response
        if (data.success) {
            loginMessage.textContent = 'Login successful';
            loginMessage.className = 'alert alert-success';
        } else {
            loginMessage.textContent = 'Login failed: ' + data.message;
            loginMessage.className = 'alert alert-danger';
        }
    })
    .catch(error => console.error('Error:', error)); // Log any errors to the console
});
