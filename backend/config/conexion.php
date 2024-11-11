<?php
$host = "localhost";
$user = "root";
$password = "root";
$dbname = "jun_dogs";

$conexion = new mysqli($host, $user, $password, $dbname);

if ($conexion->connect_error) {
    die("Connection failed: " . $conexion->connect_error);
}
?>