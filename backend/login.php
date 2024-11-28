<?php
session_start(); 

require_once 'config/database.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $email = $_POST['email'];
    $password = $_POST['password'];

    $database = new Database();
    $conn = $database->getConnection();

    if ($conn) {
        try {
            // Consulta para obtener los datos del usuario
            $sql = "SELECT user_id, nombre, contraseña FROM usuarios WHERE email = :email";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $usuarioData = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verificamos si el usuario es el administrador con sus credenciales
            if ($email === 'adminJunDogs@gmail.com' && $password === 'Admin1234') {
                // Si es el administrador, lo redirigimos a la página de administración
                $_SESSION['user_id'] = $usuarioData['user_id'];
                $_SESSION['nombre'] = $usuarioData['nombre'];
                header("Location: ../frontend/administrador.html");
                exit;
            }
            // Verificamos si las credenciales son correctas para un usuario común
            elseif ($usuarioData && password_verify($password, $usuarioData['contraseña'])) {
                // Si es un usuario común, lo redirigimos a Adopciones
                $_SESSION['user_id'] = $usuarioData['user_id'];
                $_SESSION['nombre'] = $usuarioData['nombre'];
                header("Location: ../frontend/Adopciones.html");
                exit;
            } else {
                // Si las credenciales no son correctas
                $_SESSION['error'] = "Usuario o contraseña incorrectos.";
                header("Location: ../frontend/InicioSesion.html");
                exit;
            }
        } catch (PDOException $e) {
            echo "Error en la base de datos: " . $e->getMessage();
        }
    } else {
        echo "No se pudo conectar a la base de datos.";
    }
} else {
    $_SESSION['error'] = "Método no permitido.";
    header("Location: ../frontend/InicioSesion.html");
    exit;
}
?>

