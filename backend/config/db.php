<?php
// config/db.php

class Database {
    private $host = "localhost";
    private $db_name = "bibliotecadigital";
    private $username = "root"; // Cambia 'root' si tienes otro usuario configurado
    private $password = ""; // Cambia esto si tienes una contraseña configurada
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        } catch(PDOException $exception) {
            echo "Error de conexión: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
?>
