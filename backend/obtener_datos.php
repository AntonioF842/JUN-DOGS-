<?php
// Incluir la clase Database para la conexión
include('config/database.php');  // Asegúrate de que la ruta sea correcta

header('Content-Type: application/json');

// Crear una instancia de la clase Database
$database = new Database();
$conn = $database->getConnection();  // Obtener la conexión

// Verificar si la conexión fue exitosa
if (!$conn) {
    echo json_encode(['error' => 'No se pudo conectar a la base de datos']);
    exit;
}

// Obtener las mascotas disponibles
try {
    // Consulta para obtener los animales disponibles
    $mascotasQuery = $conn->query("SELECT nombre, tipo_animal, estado_adopcion FROM animales WHERE estado_adopcion = 'Disponible'");
    $mascotas = $mascotasQuery->fetchAll(PDO::FETCH_ASSOC);

    // Obtener las próximas citas con los nombres de las mascotas
    $citasQuery = $conn->query("
        SELECT animales.nombre, citas.fecha_cita, citas.motivo 
        FROM citas 
        INNER JOIN animales ON citas.animal_id = animales.animal_id 
        WHERE citas.fecha_cita >= CURDATE()
    ");
    $citas = $citasQuery->fetchAll(PDO::FETCH_ASSOC);

    // Retornar ambos conjuntos de datos como JSON
    echo json_encode([
        'mascotas' => $mascotas,
        'citas' => $citas
    ]);
} catch (Exception $e) {
    echo json_encode(['error' => 'Error en la consulta de la base de datos: ' . $e->getMessage()]);
}
?>



