<?php
// controllers/ReviewController.php

include_once '../config/db.php';
include_once '../models/Review.php';

class ReviewController {
    private $db;
    private $review;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->review = new Review($this->db);
    }

    public function getReviews() {
        $stmt = $this->review->read();
        $num = $stmt->rowCount();

        if ($num > 0) {
            $reviews_arr = array();
            $reviews_arr["records"] = array();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $review_item = array(
                    "id" => $id,
                    "user_id" => $user_id,
                    "book_id" => $book_id,
                    "rating" => $rating,
                    "review_text" => $review_text,
                    "review_date" => $review_date
                );

                array_push($reviews_arr["records"], $review_item);
            }

            http_response_code(200);
            echo json_encode($reviews_arr);
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "No se encontraron reseñas."));
        }
    }

    public function addReview($data) {
        if ($this->review->create($data)) {
            http_response_code(201);
            echo json_encode(array("message" => "Reseña añadida exitosamente."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Error al añadir reseña."));
        }
    }

    public function updateReview($id, $data) {
        if ($this->review->update($id, $data)) {
            http_response_code(200);
            echo json_encode(array("message" => "Reseña actualizada exitosamente."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Error al actualizar reseña."));
        }
    }

    public function deleteReview($id) {
        if ($this->review->delete($id)) {
            http_response_code(200);
            echo json_encode(array("message" => "Reseña eliminada exitosamente."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Error al eliminar reseña."));
        }
    }

    // Otros métodos de gestión de reseñas pueden añadirse aquí...
}

// Inicialización del controlador y manejo de las solicitudes
$reviewController = new ReviewController();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $reviewController->getReviews();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $reviewController->addReview($data);
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    parse_str(file_get_contents("php://input"), $put_vars);
    $reviewController->updateReview($put_vars['id'], $put_vars);
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    parse_str(file_get_contents("php://input"), $delete_vars);
    $reviewController->deleteReview($delete_vars['id']);
}
?>
