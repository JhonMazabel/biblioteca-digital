<?php
class CollectionType {
    private $conn;
    private $table_name = "type_collection";

    public $id;
    public $name;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Método para leer todos los tipos de colección
    public function read() {
        $query = "SELECT * FROM " . $this->table_name;
    
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            echo "Error al leer los tipos de colección: " . $e->getMessage();
            return null;
        }
    }
    public function create($name) {
        $query = "INSERT INTO " . $this->table_name . " (name) VALUES (:name)";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':name', $name);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error al crear tipo de colección: " . $e->getMessage();
            return false;
        }
    }
    public function update($id, $name) {
        $query = "UPDATE " . $this->table_name . " SET name = :name WHERE id = :id";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':name', $name);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error al actualizar tipo de colección: " . $e->getMessage();
            return false;
        }
    }
    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error al eliminar tipo de colección: " . $e->getMessage();
            return false;
        }
    }
    
}
?>
