<?php
// models/Review.php

class Review {
    private $conn;
    private $table_name = "Reviews";

    public $id;
    public $user_id;
    public $book_id;
    public $rating;
    public $review_text;
    public $review_date;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Método para obtener todas las reseñas
    public function read() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Método para crear una nueva reseña
    public function create($data) {
        $query = "INSERT INTO " . $this->table_name . " (user_id, book_id, rating, review_text, review_date)
                  VALUES (:user_id, :book_id, :rating, :review_text, :review_date)";

        $stmt = $this->conn->prepare($query);

        // Sanitizar y enlazar parámetros
        $stmt->bindParam(':user_id', $data['user_id']);
        $stmt->bindParam(':book_id', $data['book_id']);
        $stmt->bindParam(':rating', $data['rating']);
        $stmt->bindParam(':review_text', htmlspecialchars(strip_tags($data['review_text'])));
        $stmt->bindParam(':review_date', $data['review_date']);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Método para actualizar una reseña existente
    public function update($id, $data) {
        $query = "UPDATE " . $this->table_name . "
                  SET user_id = :user_id, book_id = :book_id, rating = :rating, review_text = :review_text, review_date = :review_date
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        // Sanitizar y enlazar parámetros
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':user_id', $data['user_id']);
        $stmt->bindParam(':book_id', $data['book_id']);
        $stmt->bindParam(':rating', $data['rating']);
        $stmt->bindParam(':review_text', htmlspecialchars(strip_tags($data['review_text'])));
        $stmt->bindParam(':review_date', $data['review_date']);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Método para eliminar una reseña
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
