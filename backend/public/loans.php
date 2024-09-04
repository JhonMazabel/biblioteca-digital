<?php
header('Access-Control-Allow-Origin: *'); // Permite solicitudes desde cualquier origen
header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE'); // Métodos permitidos
header('Access-Control-Allow-Headers: Content-Type, Authorization'); // Encabezados permitidos
header('Content-Type: application/json; charset=UTF-8'); // Asegura que todas las respuestas sean JSON

include_once '../controllers/LoanController.php';
$loanController = new LoanController();

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    // Responde con el estatus 200 OK a las solicitudes OPTIONS
    http_response_code(200);
    exit();
}

try {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $loanController->getLoans();
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents("php://input"), true);
        if ($data) {
            $loanController->requestLoan($data);
        } else {
            http_response_code(400); // Bad Request
            echo json_encode(['message' => 'Datos de solicitud inválidos.']);
        }
    } elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
        $put_vars = json_decode(file_get_contents("php://input"), true);
        if (isset($put_vars['id'])) {
            $loanController->updateLoan($put_vars['id'], $put_vars);
        } else {
            http_response_code(400); // Bad Request
            echo json_encode(['message' => 'ID no especificado.']);
        }
    } elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        // Obtener el ID de la URL
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $loanController->deleteLoan($id);
        } else {
            echo json_encode(['message' => 'ID no especificado.']);
        }
        $query = "DELETE FROM loans WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        
        if ($stmt->execute()) {
            echo json_encode(['message' => 'Préstamo eliminado exitosamente.']);
        } else {
            echo json_encode(['message' => 'Error al eliminar el préstamo.']);
        }



    } else {
        http_response_code(405); // Method Not Allowed
        echo json_encode(['message' => 'Método no permitido.']);
    }
} catch (Exception $e) {
    http_response_code(500); // Internal Server Error
    echo json_encode(['message' => 'Error del servidor: ' . $e->getMessage()]);
}
?>
