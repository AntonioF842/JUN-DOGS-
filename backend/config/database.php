<?php
class Database {
    private $host = "localhost";
    private $db_name = "jun_dogs";
    private $username = "root";
    private $password = "root";
    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        } catch (PDOException $exception) {
            // Log the error instead of echoing it
            error_log("Connection error: " . $exception->getMessage());
        }

        return $this->conn;
    }
}
?>

