<?php
// controllers/LoanController.php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

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



    // En tu controlador o en el método que obtiene los libros:
public function getBooksWithStatus() {
    $books = $this->getAllBooks(); // Obtiene todos los libros desde la base de datos.
    $loans = $this->getAllLoans(); // Obtiene todos los préstamos desde la base de datos.
    
    foreach ($books as &$book) {
        $book['status'] = 'Disponible'; // Por defecto, todos los libros son "Disponibles".
        foreach ($loans as $loan) {
            if ($loan['book_id'] == $book['id'] && $loan['status'] == 'On Loan') {
                $book['status'] = 'On Loan'; // Si el libro está prestado, cambiamos el estado.
                break;
            }
        }
    }
    
    return $books; // Retorna los libros con el estado actualizado.
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
        if (empty($data['user_id']) || empty($data['book_id']) || empty($data['loan_date']) || empty($data['return_date']) || empty($data['status'])) {
            http_response_code(400);
            echo json_encode(array("message" => "Datos incompletos para solicitar un préstamo."));
            return;
        }

        if ($this->loan->isBookOnLoan($data['book_id'])) {
            http_response_code(409);
            echo json_encode(array("message" => "Este libro ya está prestado."));
            return;
        }

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
}
?>
