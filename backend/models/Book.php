<?php
// models/Book.php

class Book {
    private $conn;
    private $table_name = "books";

    public $id;
    public $title;
    public $author;
    public $genre;
    public $publication_year;
    public $isbn;
    public $id_type; // Actualizar a id_type

    public function __construct($db) {
        $this->conn = $db;
    }
    public function read() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    public function create($data) {
        // Verificar si el ISBN ya existe
        $checkQuery = "SELECT COUNT(*) as count FROM " . $this->table_name . " WHERE isbn = :isbn";
        $checkStmt = $this->conn->prepare($checkQuery);
        $checkStmt->bindParam(':isbn', $data['isbn']);
        $checkStmt->execute();
        $row = $checkStmt->fetch(PDO::FETCH_ASSOC);
    
        if ($row['count'] > 0) {
            // Retornar un mensaje de error si el ISBN ya existe
            http_response_code(400);
            echo json_encode(array("message" => "Error: El ISBN ya existe."));
            return false;
        }
    
        // Si el ISBN no existe, proceder con la creación del libro
        $query = "INSERT INTO " . $this->table_name . " SET title=:title, author=:author, genre=:genre, publication_year=:publication_year, isbn=:isbn, id_type=:id_type";
        
        $stmt = $this->conn->prepare($query);
    
        $stmt->bindParam(":title", $data['title']);
        $stmt->bindParam(":author", $data['author']);
        $stmt->bindParam(":genre", $data['genre']);
        $stmt->bindParam(":publication_year", $data['publication_year']);
        $stmt->bindParam(":isbn", $data['isbn']);
        $stmt->bindParam(":id_type", $data['id_type']); // Usar id_type en lugar de type
    
        if ($stmt->execute()) {
            return true;
        }
    
        echo json_encode(array("message" => "Error al ejecutar la consulta.", "error" => $stmt->errorInfo()));
        return false;
    }
    
    public function update($id, $data) {
        $query = "UPDATE " . $this->table_name . " SET title=:title, author=:author, genre=:genre, publication_year=:publication_year, isbn=:isbn, id_type=:id_type WHERE id=:id";
        
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":title", $data['title']);
        $stmt->bindParam(":author", $data['author']);
        $stmt->bindParam(":genre", $data['genre']);
        $stmt->bindParam(":publication_year", $data['publication_year']);
        $stmt->bindParam(":isbn", $data['isbn']);
        $stmt->bindParam(":id_type", $data['id_type']); // Usar id_type en lugar de type

        if ($stmt->execute()) {
            return true;
        }

        echo json_encode(array("message" => "Error al ejecutar la consulta.", "error" => $stmt->errorInfo()));
        return false;
    }
    
    

    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
    
        // Asignar el resultado de htmlspecialchars(strip_tags($id)) a una variable
        $clean_id = htmlspecialchars(strip_tags($id));
        
        // Usar la variable para bindParam
        $stmt->bindParam(':id', $clean_id);
    
        if ($stmt->execute()) {
            return true;
        }
    
        return false;
    }
}
?>
