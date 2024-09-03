<?php
// controllers/LoanController.php

include_once '../config/db.php';
include_once '../models/Loan.php';

class LoanController {
    private $db;
    private $loan;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->loan = new Loan($this->db);
    }

    public function getLoans() {
        $stmt = $this->loan->read();
        $num = $stmt->rowCount();

        if ($num > 0) {
            $loans_arr = array();
            $loans_arr["records"] = array();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $loan_item = array(
                    "id" => $id,
                    "user_id" => $user_id,
                    "book_id" => $book_id,
                    "loan_date" => $loan_date,
                    "return_date" => $return_date,
                    "status" => $status
                );

                array_push($loans_arr["records"], $loan_item);
            }

            http_response_code(200);
            echo json_encode($loans_arr);
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "No se encontraron préstamos."));
        }
    }

    public function requestLoan($data) {
        if ($this->loan->create($data)) {
            http_response_code(201);
            echo json_encode(array("message" => "Préstamo solicitado exitosamente."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Error al solicitar préstamo."));
        }
    }

    public function updateLoan($id, $data) {
        if ($this->loan->update($id, $data)) {
            http_response_code(200);
            echo json_encode(array("message" => "Préstamo actualizado exitosamente."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Error al actualizar préstamo."));
        }
    }

    public function deleteLoan($id) {
        if ($this->loan->delete($id)) {
            http_response_code(200);
            echo json_encode(array("message" => "Préstamo eliminado exitosamente."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Error al eliminar préstamo."));
        }
    }

    // Otros métodos de gestión de préstamos pueden añadirse aquí...
}

// Inicialización del controlador y manejo de las solicitudes
$loanController = new LoanController();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $loanController->getLoans();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $loanController->requestLoan($data);
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    parse_str(file_get_contents("php://input"), $put_vars);
    $loanController->updateLoan($put_vars['id'], $put_vars);
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    parse_str(file_get_contents("php://input"), $delete_vars);
    $loanController->deleteLoan($delete_vars['id']);
}
?>
