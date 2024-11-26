<?php
require_once 'config/database.php';

ob_start(); // Start output buffering

try {
    $database = new Database();
    $conn = $database->getConnection();

    if ($conn === null) {
        throw new Exception("Failed to connect to the database.");
    }

    $sql = "SELECT * FROM animales WHERE estado_adopcion = 'Disponible'";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $animales = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($animales);
} catch (Exception $e) {
    header('Content-Type: application/json');
    echo json_encode(['error' => $e->getMessage()]);
}

ob_end_flush(); // End output buffering and flush output
?>