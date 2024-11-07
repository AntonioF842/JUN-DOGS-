<?php
header('Content-Type: application/json');

// Database connection parameters
$host = "localhost";
$user = "root";
$password = "root";
$dbname = "jun_dogs";

// Create a new database connection
$conexion = new mysqli($host, $user, $password, $dbname);

// Check if the connection was successful
if ($conexion->connect_error) {
    // Log the error message and return a JSON response
    error_log("Connection failed: " . $conexion->connect_error);
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit();
} else {
    // Log a success message
    error_log("Database connection successful");
}

// Get the JSON data from the request body
$data = json_decode(file_get_contents('php://input'), true);
$email = $data['email'];
$passwordUser = $data['password'];

// Prepare an SQL statement to select the password hash for the given email
$sql = "SELECT password_hash FROM Usuarios WHERE email = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

// Check if a user with the given email exists
if ($stmt->num_rows > 0) {
    $stmt->bind_result($password_hash);
    $stmt->fetch();
    // Verify the provided password against the stored hash
    if ($passwordUser === $password_hash) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid password']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'User not found']);
}

// Close the statement and the database connection
$stmt->close();
$conexion->close();
?>
