<?php
require_once 'config/database.php';

$database = new Database();
$conexion = $database->getConnection();

$method = $_SERVER['REQUEST_METHOD'];

if ($method == 'GET') {
    if (isset($_GET['id'])) {
        $stmt = $conexion->prepare("SELECT * FROM animales WHERE animal_id = ?");
        $stmt->bindParam(1, $_GET['id']);
        $stmt->execute();
        echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
    } else {
        $stmt = $conexion->query("SELECT * FROM animales");
        $animals = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $animals[] = $row;
        }
        echo json_encode($animals);
    }
} elseif ($method == 'POST') {
    $animal_id = $_POST['animal_id'] ?? null;
    $nombre = $_POST['nombre'];
    $tipo_animal = $_POST['tipo_animal'];
    $tamaño = $_POST['tamaño'];
    $foto_url = $_POST['foto_url'];
    $descripcion = $_POST['descripcion'];
    $vacunas = $_POST['vacunas'];
    $estado_adopcion = $_POST['estado_adopcion'];

    if ($animal_id) {
        $stmt = $conexion->prepare("UPDATE animales SET nombre = ?, tipo_animal = ?, tamaño = ?, foto_url = ?, descripcion = ?, vacunas = ?, estado_adopcion = ? WHERE animal_id = ?");
        $stmt->bindParam(1, $nombre);
        $stmt->bindParam(2, $tipo_animal);
        $stmt->bindParam(3, $tamaño);
        $stmt->bindParam(4, $foto_url);
        $stmt->bindParam(5, $descripcion);
        $stmt->bindParam(6, $vacunas);
        $stmt->bindParam(7, $estado_adopcion);
        $stmt->bindParam(8, $animal_id);
    } else {
        $stmt = $conexion->prepare("INSERT INTO animales (nombre, tipo_animal, tamaño, foto_url, descripcion, vacunas, estado_adopcion) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bindParam(1, $nombre);
        $stmt->bindParam(2, $tipo_animal);
        $stmt->bindParam(3, $tamaño);
        $stmt->bindParam(4, $foto_url);
        $stmt->bindParam(5, $descripcion);
        $stmt->bindParam(6, $vacunas);
        $stmt->bindParam(7, $estado_adopcion);
    }

    if ($stmt->execute()) {
        echo "Operación exitosa.";
    } else {
        echo "Error: " . $stmt->errorInfo()[2];
    }
} elseif ($method == 'DELETE') {
    try {
        $conexion->beginTransaction();
        $stmt = $conexion->prepare("DELETE FROM citas WHERE animal_id = ?");
        $stmt->bindParam(1, $_GET['id']);
        $stmt->execute();

        $stmt = $conexion->prepare("DELETE FROM animales WHERE animal_id = ?");
        $stmt->bindParam(1, $_GET['id']);
        $stmt->execute();

        $conexion->commit();
        echo "Animal y sus citas eliminados.";
    } catch (PDOException $e) {
        $conexion->rollBack();
        echo "Error: " . $e->getMessage();
    }
}
?>