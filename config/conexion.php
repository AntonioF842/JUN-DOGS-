<?php
$host = "localhost";
$user = "root";
$password = "root";
$dbname = "jun_dogs";

$conexion = new mysqli($host, $user, $password, $dbname);

if ($conexion->connect_error) {
    die("Connection failed: " . $conexion->connect_error);
}

$sql = "SELECT user_id, nombre, email FROM Usuarios";
$result = $conexion->query($sql);

$usuarios = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $usuarios[] = $row;
    }
}

echo json_encode($usuarios);

$conexion->close();
?>