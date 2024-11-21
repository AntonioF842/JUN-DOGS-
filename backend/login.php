<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Incluir el archivo de conexión a la base de datos
include '../backend/config/conexion.php';

// Eliminar cualquier salida previa
ob_start();

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
    $stmt->bind_result($user_id, $nombre, $apellido_paterno, $apellido_materno, $email, $password_db);
    $stmt->fetch();
    // Verificar la contraseña
    if ($password === $password_db) {
        // Almacenar la información del usuario en la sesión
        $_SESSION['user_id'] = $user_id;
        $_SESSION['nombre'] = $nombre;
        $_SESSION['apellido_paterno'] = $apellido_paterno;
        $_SESSION['apellido_materno'] = $apellido_materno;
        $_SESSION['email'] = $email;
        
        $response = ['success' => true, 'user_id' => $user_id, 'nombre' => $nombre];
    } else {
        $response = ['success' => false, 'message' => 'Invalid password'];
    }
} else {
    $response = ['success' => false, 'message' => 'User not found'];
}

// Cerrar la conexión
$stmt->close();
$conexion->close();

// Limpiar cualquier salida previa
ob_end_clean();

// Enviar la respuesta JSON
echo json_encode($response);
?>