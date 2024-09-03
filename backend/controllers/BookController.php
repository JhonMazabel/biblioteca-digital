<?php
// controllers/BookController.php

include_once '../config/db.php';
include_once '../models/Book.php';

class BookController {
    private $db;
    private $book;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->book = new Book($this->db);
    }

    public function getBooks() {
        $stmt = $this->book->read();
        $num = $stmt->rowCount();
    
        if($num > 0) {
            $books_arr = array();
            $books_arr["records"] = array();
    
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $book_item = array(
                    "id" => $row['id'],
                    "title" => $row['title'],
                    "author" => $row['author'],
                    "genre" => $row['genre'],
                    "publication_year" => $row['publication_year'],
                    "isbn" => $row['isbn']
                );
    
                array_push($books_arr["records"], $book_item);
            }
    
            http_response_code(200);
            echo json_encode($books_arr);
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "No se encontraron libros."));
        }
    }
    

    public function addBook($data) {
        if (empty($data['title']) || empty($data['author']) || empty($data['genre']) || empty($data['publication_year']) || empty($data['isbn']) || empty($data['id_type'])) {
            http_response_code(400);
            echo json_encode(array("message" => "Datos incompletos para agregar un libro."));
            return;
        }
    
        if ($this->book->create($data)) {
            http_response_code(201);
            echo json_encode(array("message" => "Libro agregado exitosamente."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Error al agregar libro."));
        }
    }
    
    public function updateBook($id, $data) {
        if (empty($id)) {
            http_response_code(400);
            echo json_encode(array("message" => "ID de libro no proporcionado."));
            return;
        }
    
        if (empty($data['id_type']) || !is_numeric($data['id_type'])) {
            http_response_code(400);
            echo json_encode(array("message" => "Tipo inválido o faltante."));
            return;
        }
    
        if ($this->book->update($id, $data)) {
            http_response_code(200);
            echo json_encode(array("message" => "Libro actualizado exitosamente."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Error al actualizar libro."));
        }
    }
    
    

    public function deleteBook($id) {
        if (empty($id)) {
            http_response_code(400);
            echo json_encode(array("message" => "ID de libro no proporcionado."));
            return;
        }

        if ($this->book->delete($id)) {
            http_response_code(200);
            echo json_encode(array("message" => "Libro eliminado exitosamente."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Error al eliminar libro."));
        }
    }
}

// Inicialización del controlador y manejo de las solicitudes
$bookController = new BookController();

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $bookController->getBooks();
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        $bookController->addBook($data);
        break;

    case 'PUT':
        parse_str(file_get_contents("php://input"), $put_vars);
        if (isset($put_vars['id'])) {
            $bookController->updateBook($put_vars['id'], $put_vars);
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "ID de libro no proporcionado para actualización."));
        }
        break;

    case 'DELETE':
        parse_str(file_get_contents("php://input"), $delete_vars);
        if (isset($delete_vars['id'])) {
            $bookController->deleteBook($delete_vars['id']);
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "ID de libro no proporcionado para eliminación."));
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(array("message" => "Método no permitido."));
        break;
}
?>
