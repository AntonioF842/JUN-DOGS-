<?php
session_start();
require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../frontend/InicioSesion.html");
        exit;
    }

    $user_id = $_SESSION['user_id'];
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];
    $mascota = $_POST['mascota'];
    $motivo = $_POST['motivo'];

    $database = new Database();
    $conn = $database->getConnection();

    if ($conn) {
        try {
            // Fetch the animal_id based on the mascota name
            $sql = "SELECT animal_id FROM animales WHERE nombre = :mascota";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':mascota', $mascota);
            $stmt->execute();
            $animal = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($animal) {
                $animal_id = $animal['animal_id'];

                // Insert the new appointment with default estado_cita as 'Pendiente'
                $sql = "INSERT INTO citas (user_id, animal_id, fecha_cita, motivo, estado_cita) VALUES (:user_id, :animal_id, :fecha_cita, :motivo, 'Pendiente')";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':user_id', $user_id);
                $stmt->bindParam(':animal_id', $animal_id);
                $fecha_cita = date('Y-m-d H:i:s', strtotime("$fecha $hora"));
                $stmt->bindParam(':fecha_cita', $fecha_cita);
                $stmt->bindParam(':motivo', $motivo);
                $stmt->execute();

                header("Location: ../frontend/perfildeusuario.php");
                exit();
            } else {
                echo "Mascota no encontrada.";
            }
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