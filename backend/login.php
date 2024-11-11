<?php
session_start();
header('Content-Type: application/json');

// Incluir el archivo de conexión a la base de datos
include '../backend/config/conexion.php';

// Obtener los datos del POST
$data = json_decode(file_get_contents('php://input'), true);
$email = $data['email'];
$password = $data['password'];

// Preparar la consulta SQL
$sql = "SELECT user_id, nombre, apellido_paterno, apellido_materno, email, password_hash FROM Usuarios WHERE email = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

// Verificar si el usuario existe
if ($stmt->num_rows > 0) {
    $stmt->bind_result($user_id, $nombre, $apellido_paterno, $apellido_materno, $email, $password_hash);
    $stmt->fetch();
    // Verificar la contraseña
    if (password_verify($password, $password_hash)) {
        // Almacenar la información del usuario en la sesión
        $_SESSION['user_id'] = $user_id;
        $_SESSION['nombre'] = $nombre;
        $_SESSION['apellido_paterno'] = $apellido_paterno;
        $_SESSION['apellido_materno'] = $apellido_materno;
        $_SESSION['email'] = $email;
        
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid password']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'User not found']);
}

// Cerrar la conexión
$stmt->close();
$conexion->close();
?>