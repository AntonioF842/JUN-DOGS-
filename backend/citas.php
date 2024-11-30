<?php
require_once 'config/database.php';

$database = new Database();
$conexion = $database->getConnection();

$method = $_SERVER['REQUEST_METHOD'];

if ($method == 'GET') {
    $stmt = $conexion->query("SELECT c.cita_id, u.nombre AS nombre_usuario, u.apellido_paterno, u.apellido_materno, a.nombre AS nombre_mascota, a.foto_url, c.fecha_cita, c.estado_cita
                              FROM citas c
                              JOIN usuarios u ON c.user_id = u.user_id
                              JOIN animales a ON c.animal_id = a.animal_id");
    $appointments = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $appointments[] = $row;
    }
    echo json_encode($appointments);
} elseif ($method == 'PUT') {
    parse_str(file_get_contents("php://input"), $_PUT);
    if (isset($_PUT['id']) && isset($_PUT['status'])) {
        $cita_id = $_PUT['id'];
        $estado_cita = $_PUT['status'];

        $stmt = $conexion->prepare("UPDATE citas SET estado_cita = ? WHERE cita_id = ?");
        $stmt->bindParam(1, $estado_cita);
        $stmt->bindParam(2, $cita_id);

        if ($stmt->execute()) {
            echo "Estado de la cita actualizado a $estado_cita.";
        } else {
            echo "Error: " . $stmt->errorInfo()[2];
        }
    } else {
        echo "Faltan parámetros necesarios.";
    }
}
?>