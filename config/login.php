<?php
$host = "localhost";
$user = "root";
$password = "root";
$dbname = "jun_dogs";

$conexion = new mysqli($host, $user, $password, $dbname);

if ($conexion->connect_error) {
    die("Connection failed: " . $conexion->connect_error);
}

$data = json_decode(file_get_contents('php://input'), true);
$email = $data['email'];
$password = $data['password'];

$sql = "SELECT password_hash FROM Usuarios WHERE email = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($password_hash);
    $stmt->fetch();
    if (password_verify($password, $password_hash)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid password']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'User not found']);
}

$stmt->close();
$conexion->close();
?>
