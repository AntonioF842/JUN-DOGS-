<?php
class Database {
    private $host = 'localhost:8889'; 
    private $db_name = 'jun_dogs'; 
    private $username = 'root'; 
    private $password = 'root'; 
    private $conn;

   
    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "Conexión exitosa a la base de datos.";
        } catch (PDOException $e) {
            echo "Error de conexión: " . $e->getMessage(); 
        }

        return $this->conn;
    }
}


$db = new Database();
$conn = $db->getConnection();

?>

