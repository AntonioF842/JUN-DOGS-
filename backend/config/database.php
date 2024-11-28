<?php
class Database {
    private $host = "localhost";
    private $port = "8889";  // Agregar el puerto 8889
    private $db_name = "jun_dogs";
    private $username = "root";
    private $password = "root";
    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            // Usar el puerto en la cadena de conexión PDO
            $this->conn = new PDO("mysql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        } catch (PDOException $exception) {
            // Log the error instead of echoing it
            error_log("Connection error: " . $exception->getMessage());
        }

        return $this->conn;
    }
}

?>

