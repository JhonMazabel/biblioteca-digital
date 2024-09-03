<?php
// models/Loan.php

class Loan {
    private $conn;
    private $table_name = "Loans";

    public $id;
    public $user_id;
    public $book_id;
    public $loan_date;
    public $return_date;
    public $status;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Método para obtener todos los préstamos
    public function read() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Método para crear un nuevo préstamo
    public function create($data) {
        $query = "INSERT INTO " . $this->table_name . " (user_id, book_id, loan_date, return_date, status)
                  VALUES (:user_id, :book_id, :loan_date, :return_date, :status)";

        $stmt = $this->conn->prepare($query);

        // Sanitizar y enlazar parámetros
        $stmt->bindParam(':user_id', $data['user_id']);
        $stmt->bindParam(':book_id', $data['book_id']);
        $stmt->bindParam(':loan_date', $data['loan_date']);
        $stmt->bindParam(':return_date', $data['return_date']);
        $stmt->bindParam(':status', htmlspecialchars(strip_tags($data['status'])));

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Método para actualizar un préstamo existente
    public function update($id, $data) {
        $query = "UPDATE " . $this->table_name . "
                  SET user_id = :user_id, book_id = :book_id, loan_date = :loan_date, return_date = :return_date, status = :status
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        // Sanitizar y enlazar parámetros
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':user_id', $data['user_id']);
        $stmt->bindParam(':book_id', $data['book_id']);
        $stmt->bindParam(':loan_date', $data['loan_date']);
        $stmt->bindParam(':return_date', $data['return_date']);
        $stmt->bindParam(':status', htmlspecialchars(strip_tags($data['status'])));

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Método para eliminar un préstamo
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
