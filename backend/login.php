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
           
            $sql = "SELECT user_id, nombre, apellido_paterno, apellido_materno, direccion, email, contrasena FROM usuarios WHERE email = :email";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $usuarioData = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verificamos si el usuario es el administrador con sus credenciales
            if ($email === 'adminJunDogs@gmail.com' && $password === 'Admin1234') {
                // Si es el administrador, lo redirigimos a la página de administración
                $_SESSION['user_id'] = $usuarioData['user_id'];
                $_SESSION['nombre'] = $usuarioData['nombre'];
                header("Location: ../frontend/Animales.html");
                exit;
            }elseif ($usuarioData && password_verify($password, $usuarioData['contrasena'])) {
                
                $_SESSION['user_id'] = $usuarioData['user_id'];
                $_SESSION['nombre'] = $usuarioData['nombre'];
                $_SESSION['apellido_paterno'] = $usuarioData['apellido_paterno']; 
                $_SESSION['apellido_materno'] = $usuarioData['apellido_materno']; 
                $_SESSION['direccion'] = $usuarioData['direccion'];
                $_SESSION['email'] = $usuarioData['email'];

              
                header("Location: ../frontend/perfildeusuario.php");
                exit;
            } else {
              
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
