<?php

require_once 'config/database.php';;


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $nombre = $_POST['nombre'];
    $apellido_paterno = $_POST['apellido_paterno'];
    $apellido_materno = $_POST['apellido_materno'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $direccion = $_POST['direccion'];
    $sexo = $_POST['sexo'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); 

    
    $stmt = $conexion->prepare("INSERT INTO usuarios (nombre, apellido_paterno, apellido_materno, fecha_nacimiento, direccion, sexo, email, contraseña) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $nombre, $apellido_paterno, $apellido_materno, $fecha_nacimiento, $direccion, $sexo, $email, $password);

   
    if ($stmt->execute()) {
        echo "Registro exitoso.";
    } else {
        echo "Error: " . $stmt->error;
    }

   
    $stmt->close();
}


$conexion->close();
?>