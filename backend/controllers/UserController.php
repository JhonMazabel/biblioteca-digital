<?php
// controllers/UserController.php

include_once '../config/db.php';
include_once '../models/User.php';

class UserController {
    private $db;
    private $user;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->user = new User($this->db);
    }

    public function getUsers() {
        $stmt = $this->user->read();
        $num = $stmt->rowCount();

        if($num > 0) {
            $users_arr = array();
            $users_arr["records"] = array();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $user_item = array(
                    "id" => $row['id'],
                    "username" => $row['username'],
                    "email" => $row['email'],
                    "role_id" => $row['role_id'],
                    "first_name" => $row['first_name'],
                    "last_name" => $row['last_name']
                );

                array_push($users_arr["records"], $user_item);
            }

            http_response_code(200);
            echo json_encode($users_arr);
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "No se encontraron usuarios."));
        }
    }


    
    public function addUser($data) {
        // Validación de datos
        if (empty($data['username']) || empty($data['email']) || empty($data['password'])) {
            http_response_code(400);
            echo json_encode(array("message" => "Datos incompletos para registrar un usuario."));
            return;
        }

        if ($this->user->create($data)) {
            http_response_code(201);
            echo json_encode(array("message" => "Usuario registrado exitosamente."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Error al registrar usuario."));
        }
    }

    public function updateUser($id, $data) {
        if (empty($id)) {
            http_response_code(400);
            echo json_encode(array("message" => "ID de usuario no proporcionado."));
            return;
        }

        if ($this->user->update($id, $data)) {
            http_response_code(200);
            echo json_encode(array("message" => "Usuario actualizado exitosamente."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Error al actualizar usuario."));
        }
    }

    public function deleteUser($id) {
        if (empty($id)) {
            http_response_code(400);
            echo json_encode(array("message" => "ID de usuario no proporcionado."));
            return;
        }

        if ($this->user->delete($id)) {
            http_response_code(200);
            echo json_encode(array("message" => "Usuario eliminado exitosamente."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Error al eliminar usuario."));
        }
    }

    public function login($email, $password) {
        if (empty($email) || empty($password)) {
            http_response_code(400);
            echo json_encode(array("message" => "Correo y contraseña son requeridos."));
            return;
        }

        $user = $this->user->verifyLogin($email, $password);

        if ($user) {
            http_response_code(200);
            echo json_encode(array("message" => "Inicio de sesión exitoso", "user" => $user));
        } else {
            http_response_code(401);
            echo json_encode(array("message" => "Correo o contraseña incorrectos"));
        }
    }
}

// Inicialización del controlador y manejo de las solicitudes
$userController = new UserController();

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $userController->getUsers();
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['email']) && isset($data['password'])) {
            $userController->login($data['email'], $data['password']);
        } else {
            $userController->addUser($data);
        }
        break;

    case 'PUT':
        parse_str(file_get_contents("php://input"), $put_vars);
        if (isset($put_vars['id'])) {
            $userController->updateUser($put_vars['id'], $put_vars);
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "ID de usuario no proporcionado para actualización."));
        }
        break;

    case 'DELETE':
        parse_str(file_get_contents("php://input"), $delete_vars);
        if (isset($delete_vars['id'])) {
            $userController->deleteUser($delete_vars['id']);
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "ID de usuario no proporcionado para eliminación."));
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(array("message" => "Método no permitido."));
        break;
}
?>
