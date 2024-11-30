<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../frontend/InicioSesion.html");
    exit;
}

require_once '../backend/config/database.php'; // Corrected path
$database = new Database();
$conn = $database->getConnection();

if ($conn && isset($_POST['cita_id'])) {
    try {
        $sql = "DELETE FROM citas WHERE cita_id = :cita_id AND user_id = :user_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':cita_id', $_POST['cita_id']);
        $stmt->bindParam(':user_id', $_SESSION['user_id']);
        $stmt->execute();
        header("Location: ../frontend/perfildeusuario.php");
    } catch (PDOException $e) {
        echo "Error en la base de datos: " . $e->getMessage();
    }
} else {
    header("Location: ../frontend/perfildeusuario.php");
}
?>