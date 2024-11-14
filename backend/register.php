<?php
session_start();
header('Content-Type: application/json');

include '../backend/config/conexion.php';

// Obtener y decodificar los datos JSON
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['email']) || !isset($data['password'])) {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos.']);
    exit;
}

$nombre = $data['nombre'];
$apellido_paterno = $data['apellido_paterno'];
$apellido_materno = $data['apellido_materno'];
$email = $data['email'];
$password = $data['password'];

// Comprobar si el email ya está registrado
$sql_check = "SELECT email FROM Usuarios WHERE email = ?";
$stmt_check = $conexion->prepare($sql_check);
$stmt_check->bind_param("s", $email);
$stmt_check->execute();
$stmt_check->store_result();

if ($stmt_check->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Este correo ya está registrado.']);
    $stmt_check->close();
    $conexion->close();
    exit;
}

$stmt_check->close();

// Hashear la contraseña y registrar el usuario
$password_hash = password_hash($password, PASSWORD_BCRYPT);
$sql = "INSERT INTO Usuarios (nombre, apellido_paterno, apellido_materno, email, password_hash) VALUES (?, ?, ?, ?, ?)";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("sssss", $nombre, $apellido_paterno, $apellido_materno, $email, $password_hash);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Usuario registrado exitosamente.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al registrar el usuario.']);
}

$stmt->close();
$conexion->close();
?>
