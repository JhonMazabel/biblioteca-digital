<?php
// models/Role.php

class Role {
    private $conn;
    private $table_name = "Roles";

    public $id;
    public $name;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Método para obtener todos los roles
    public function read() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Método para crear un nuevo rol
    public function create($data) {
        $query = "INSERT INTO " . $this->table_name . " (name) VALUES (:name)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', htmlspecialchars(strip_tags($data['name'])));

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Método para actualizar un rol existente
    public function update($id, $data) {
        $query = "UPDATE " . $this->table_name . "
                  SET name = :name
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        // Sanitizar y enlazar parámetros
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', htmlspecialchars(strip_tags($data['name'])));

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Método para eliminar un rol
    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        // Enlazar parámetro
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
}
?>

