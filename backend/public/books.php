<?php
// Configuración de cabeceras para permitir el acceso desde tu frontend y métodos HTTP específicos
header("Access-Control-Allow-Origin: http://localhost:4200");  // Permite solicitudes desde tu frontend Angular
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");  // Métodos permitidos
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");  // Encabezados permitidos
header('Content-Type: application/json');  // Asegura que la salida sea en formato JSON

// Manejar solicitudes preflight (OPTIONS)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

include_once '../config/db.php';
include_once '../models/Book.php';

$database = new Database();
$db = $database->getConnection();
$book = new Book($db);

// Verifica el método de la solicitud HTTP
switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $stmt = $book->read();
        $num = $stmt->rowCount();

        if ($num > 0) {
            $books_arr = array();
            $books_arr["records"] = array();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $book_item = array(
                    "id" => $row['id'],
                    "title" => $row['title'],
                    "author" => $row['author'],
                    "genre" => $row['genre'],
                    "publication_year" => $row['publication_year'],
                    "isbn" => $row['isbn'],
                    "id_type" => $row['id_type']  // Usa el campo correcto que corresponda
                );
                array_push($books_arr["records"], $book_item);
            }

            http_response_code(200);
            echo json_encode($books_arr);
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "No se encontraron libros."));
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['title'], $data['author'], $data['genre'], $data['publication_year'], $data['isbn'], $data['id_type'])) {
            if ($book->create($data)) {
                http_response_code(201);
                echo json_encode(array("message" => "Libro creado exitosamente."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Error al crear el libro."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Datos incompletos para crear un libro."));
        }
        break;

        case 'PUT':
            parse_str(file_get_contents("php://input"), $put_vars);
            $data = json_decode(file_get_contents("php://input"), true);
        
            if (isset($put_vars['id']) || isset($data['id'])) {
                $id = isset($put_vars['id']) ? $put_vars['id'] : $data['id'];
                if ($book->update($id, $data)) {
                    http_response_code(200);
                    echo json_encode(array("message" => "Libro actualizado exitosamente."));
                } else {
                    http_response_code(503);
                    echo json_encode(array("message" => "Error al actualizar el libro."));
                }
            } else {
                http_response_code(400);
                echo json_encode(array("message" => "ID de libro no proporcionado para la actualización."));
            }
            break;
        

    case 'DELETE':
        parse_str(file_get_contents("php://input"), $delete_vars);
        if (isset($delete_vars['id'])) {
            if ($book->delete($delete_vars['id'])) {
                http_response_code(200);
                echo json_encode(array("message" => "Libro eliminado exitosamente."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Error al eliminar el libro."));
            }
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
