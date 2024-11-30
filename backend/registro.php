<?php

require_once 'config/database.php';

$database = new Database();
$conexion = $database->getConnection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $nombre = $_POST['nombre'];
    $apellido_paterno = $_POST['apellido_paterno'];
    $apellido_materno = $_POST['apellido_materno'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $direccion = $_POST['direccion'];
    $sexo = $_POST['sexo'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); 

    
    $stmt = $conexion->prepare("INSERT INTO usuarios (nombre, apellido_paterno, apellido_materno, fecha_nacimiento, direccion, sexo, email, contrasena) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bindParam(1, $nombre);
    $stmt->bindParam(2, $apellido_paterno);
    $stmt->bindParam(3, $apellido_materno);
    $stmt->bindParam(4, $fecha_nacimiento);
    $stmt->bindParam(5, $direccion);
    $stmt->bindParam(6, $sexo);
    $stmt->bindParam(7, $email);
    $stmt->bindParam(8, $password);

   
    if ($stmt->execute()) {
        echo "Registro exitoso.";
        header("Location: ../frontend/InicioSesion.html");
        exit();
    } else {
        $errorInfo = $stmt->errorInfo();
        echo "Error: " . $errorInfo[2];
    }

}

$conexion = null;
?>