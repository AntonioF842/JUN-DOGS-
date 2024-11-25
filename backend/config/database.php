<?php
class Database {
    private $host = 'localhost:8889'; // Dirección del servidor
    private $db_name = 'jun_dogs'; // Nombre de la base de datos
    private $username = 'root'; // Usuario de la base de datos
    private $password = 'root'; // Contraseña del usuario
    private $conn;

    // Método para obtener la conexión
    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "Conexión exitosa a la base de datos.";
        } catch (PDOException $e) {
            echo "Error de conexión: " . $e->getMessage(); // Corregido el error tipográfico
        }

        return $this->conn;
    }
}

// Instancia de la clase y prueba de conexión
$db = new Database();
$conn = $db->getConnection();

?>

