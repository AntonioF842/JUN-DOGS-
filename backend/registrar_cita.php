
<?php
session_start();
require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];
    $mascota = $_POST['mascota'];

    $database = new Database();
    $conn = $database->getConnection();

    if ($conn) {
        try {
            $sql = "INSERT INTO citas (user_id, animal_id, fecha_cita, motivo) VALUES (:user_id, (SELECT animal_id FROM animales WHERE nombre = :mascota), :fecha_cita, :motivo)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':mascota', $mascota);
            $stmt->bindParam(':fecha_cita', $fecha . ' ' . $hora);
            $stmt->bindParam(':motivo', $motivo);
            $stmt->execute();

            header("Location: ../frontend/perfildeusuario.php");
            exit;
        } catch (PDOException $e) {
            echo "Error en la base de datos: " . $e->getMessage();
        }
    } else {
        echo "No se pudo conectar a la base de datos.";
    }
} else {
    header("Location: ../frontend/perfildeusuario.php");
    exit;
}
?>