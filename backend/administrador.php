<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Incluimos la conexión a la base de datos utilizando __DIR__ para una ruta más robusta
require_once __DIR__ . '/config/database.php';

$database = new Database();
$conn = $database->getConnection();

// Verificar la conexión a la base de datos
if (!$conn) {
    die("Conexión fallida: " . mysqli_connect_error());
}

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Subir nueva mascota
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nombre'])) {
    // Verificar que todos los campos están definidos antes de usarlos
    $nombre = isset($_POST['nombre']) ? htmlspecialchars($_POST['nombre']) : '';
    $tipo_animal = isset($_POST['tipo_animal']) ? htmlspecialchars($_POST['tipo_animal']) : '';
    $tamaño = isset($_POST['tamaño']) ? htmlspecialchars($_POST['tamaño']) : '';
    $descripcion = isset($_POST['descripcion']) ? htmlspecialchars($_POST['descripcion']) : '';
    $vacunas = isset($_POST['vacunas']) ? htmlspecialchars($_POST['vacunas']) : '';
    $estado_adopcion = isset($_POST['estado_adopcion']) ? htmlspecialchars($_POST['estado_adopcion']) : ''; // Verificar que el estado_adopcion esté presente

    // Validar subida de foto
    $foto = isset($_FILES['foto']['name']) ? $_FILES['foto']['name'] : '';
    $target_dir = "uploads/"; // Carpeta donde se guardarán las fotos
    $target_file = $target_dir . basename($foto);
    $file_extension = strtolower(pathinfo($foto, PATHINFO_EXTENSION));
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];

    // Verificar si la carpeta 'uploads/' existe, si no, crearla
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0755, true); // Crear directorio si no existe
    }

    if ($foto && !in_array($file_extension, $allowed_extensions)) {
        die("Formato de archivo no permitido. Solo JPG, JPEG, PNG y GIF son válidos.");
    }

    // Mover la foto a la carpeta 'uploads/'
    if ($foto && move_uploaded_file($_FILES['foto']['tmp_name'], $target_file)) {
        // Obtener la URL relativa de la foto (se guarda solo el nombre del archivo)
        $foto_url = $target_dir . basename($foto);

        // Aquí va la consulta de inserción con los cambios que mencionamos
        $sql = "INSERT INTO Animales (nombre, tipo_animal, tamaño, foto_url, estado_adopcion, descripcion, vacunas) 
                VALUES (:nombre, :tipo_animal, :tamaño, :foto_url, :estado_adopcion, :descripcion, :vacunas)";
        $stmt = $conn->prepare($sql);

        // Vincular los parámetros
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':tipo_animal', $tipo_animal);
        $stmt->bindParam(':tamaño', $tamaño);
        $stmt->bindParam(':foto_url', $foto_url);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':vacunas', $vacunas);
        $stmt->bindParam(':estado_adopcion', $estado_adopcion); // Asegúrate de vincular estado_adopcion

        // Verificar si el valor está disponible
        var_dump($nombre, $tipo_animal, $tamaño, $foto_url, $estado_adopcion, $descripcion, $vacunas);

        // Ejecutar la consulta
        try {
            $stmt->execute();
            echo "¡Mascota agregada exitosamente!";
        } catch (PDOException $e) {
            echo "Error al agregar mascota: " . $e->getMessage();
        }
    } else {
        echo "Error al subir la foto.";
    }
}

// Obtener mascotas disponibles
$sql = "SELECT * FROM Animales WHERE estado_adopcion = 'Disponible'";
$stmt_mascotas = $conn->prepare($sql);
$stmt_mascotas->execute();
$mascotas = $stmt_mascotas->fetchAll(PDO::FETCH_ASSOC);

// Obtener próximas citas con el nombre de la mascota
$sql_citas = "
    SELECT animales.nombre, citas.fecha_cita, citas.motivo 
    FROM Citas 
    INNER JOIN Animales ON Citas.animal_id = Animales.animal_id 
    WHERE citas.fecha_cita >= NOW()
";
$stmt_citas = $conn->prepare($sql_citas);
$stmt_citas->execute();
$citas = $stmt_citas->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <link rel="stylesheet" href="../frontend/css/administrador.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="administrador.php">Inicio</a></li>
                <li><a href="logout.php">Cerrar sesión</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <!-- Sección: Agregar Nueva Mascota -->
        <div class="section" id="agregar-mascota">
            <h2>Agregar Nueva Mascota</h2>
            <form action="administrador.php" method="POST" enctype="multipart/form-data">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" required>

                <label for="tipo">Tipo de Animal:</label>
                <select name="tipo_animal" id="tipo">
                    <option value="Perro">Perro</option>
                    <option value="Gato">Gato</option>
                    <option value="Otro">Otro</option>
                </select>

                <label for="tamaño">Tamaño:</label>
                <select name="tamaño" id="tamaño">
                    <option value="Pequeño">Pequeño</option>
                    <option value="Mediano">Mediano</option>
                    <option value="Grande">Grande</option>
                </select>

                <label for="foto">Foto:</label>
                <input type="file" name="foto" id="foto" required>

                <label for="descripcion">Descripción:</label>
                <textarea name="descripcion" id="descripcion" required></textarea>

                <label for="vacunas">Vacunas:</label>
                <input type="text" name="vacunas" id="vacunas" required>

                <!-- Nueva sección para elegir el estado de adopción -->
                <label for="estado_adopcion">Estado de adopción:</label>
                <select name="estado_adopcion" id="estado_adopcion" required>
                    <option value="Disponible">Disponible</option>
                    <option value="Adoptado">Adoptado</option>
                    <option value="Fallecido">Fallecido</option>
                </select>

                <button type="submit">Agregar Mascota</button>
            </form>
        </div>

        <!-- Sección: Mascotas Disponibles -->
        <div class="section" id="mascotas-disponibles">
            <h2>Mascotas Disponibles</h2>
            <table>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Tipo</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($mascotas as $mascota): ?>
                        <tr>
                            <td><?= htmlspecialchars($mascota['nombre']) ?></td>
                            <td><?= htmlspecialchars($mascota['tipo_animal']) ?></td>
                            <td><?= htmlspecialchars($mascota['estado_adopcion']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Sección: Citas -->
        <div class="section" id="citas">
            <h2>Próximas Citas</h2>
            <table>
                <thead>
                    <tr>
                        <th>Nombre de la Mascota</th>
                        <th>Fecha de Cita</th>
                        <th>Motivo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($citas as $cita): ?>
                        <tr>
                            <td><?= htmlspecialchars($cita['nombre']) ?></td>
                            <td><?= htmlspecialchars($cita['fecha_cita']) ?></td>
                            <td><?= htmlspecialchars($cita['motivo']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>

