<?php
session_start(); // Iniciar sesión para almacenar información del usuario

require_once 'config/database.php'; // Incluir la configuración de la base de datos

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Capturar datos del formulario
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Crear una instancia de la conexión a la base de datos
    $database = new Database();
    $conn = $database->getConnection();

    if ($conn) {
        try {
            // Consulta para obtener el usuario por email
            $sql = "SELECT user_id, nombre, contraseña FROM usuarios WHERE email = :email";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $usuarioData = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($usuarioData && password_verify($password, $usuarioData['contraseña'])) {
                // Credenciales válidas, guardar información en la sesión
                $_SESSION['user_id'] = $usuarioData['user_id'];
                $_SESSION['nombre'] = $usuarioData['nombre'];

                // Redirigir a la página de adopciones
                header("Location: ../frontend/Adopciones.html");
                exit;
            } else {
                // Credenciales inválidas
                $_SESSION['error'] = "Usuario o contraseña incorrectos.";
                header("Location: ../frontend/InicioSesion.html");
                exit;
            }
        } catch (PDOException $e) {
            // Manejar errores de la base de datos
            echo "Error en la base de datos: " . $e->getMessage();
        }
    } else {
        // Error en la conexión a la base de datos
        echo "No se pudo conectar a la base de datos.";
    }
} else {
    // Método no permitido
    $_SESSION['error'] = "Método no permitido.";
    header("Location: ../frontend/InicioSesion.html");
    exit;
}
?>
