
<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Incluir el archivo de conexión a la base de datos
include '../backend/config/conexion.php';

// Obtener los datos del POST
$data = json_decode(file_get_contents('php://input'), true);
$nombre = $data['nombre'];
$apellido_paterno = $data['apellido_paterno'];
$apellido_materno = $data['apellido_materno'];
$fecha_nacimiento = $data['fecha_nacimiento'];
$direccion = $data['direccion'];
$sexo = $data['sexo'];
$identificacion_oficial = $data['identificacion_oficial'];
$email = $data['email'];
$password = $data['password'];

// Hash the password
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// Preparar la consulta SQL
$sql = "INSERT INTO Usuarios (nombre, apellido_paterno, apellido_materno, fecha_nacimiento, direccion, sexo, identificacion_oficial, email, password_hash) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("sssssssss", $nombre, $apellido_paterno, $apellido_materno, $fecha_nacimiento, $direccion, $sexo, $identificacion_oficial, $email, $password_hash);

// Ejecutar la consulta y verificar si se insertó correctamente
if ($stmt->execute()) {
    $response = ['success' => true, 'message' => 'User registered successfully'];
} else {
    $response = ['success' => false, 'message' => 'Error registering user: ' . $stmt->error];
}

// Cerrar la conexión
$stmt->close();
$conexion->close();

// Enviar la respuesta JSON
echo json_encode($response);
?>