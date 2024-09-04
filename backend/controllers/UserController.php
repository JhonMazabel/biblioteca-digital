<?php
// controllers/UserController.php

// Configuración de cabeceras
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json');

// Desactivar la visualización de errores
ini_set('display_errors', 0);
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);

include_once '../config/db.php';
include_once '../models/User.php';
include_once '../models/Role.php';

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

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
                    "id" => $row['id'] ?? null,
                    "username" => $row['username'] ?? null,
                    "email" => $row['email'] ?? null,
                    "role_id" => $row['role_id'] ?? null,
                    "role_name" => $row['role_name'] ?? null, 
                    "first_name" => $row['first_name'] ?? null,
                    "last_name" => $row['last_name'] ?? null
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
        if (empty($data['username']) || empty($data['email']) || empty($data['password'])) {
            http_response_code(400);
            echo json_encode(array("message" => "Datos incompletos para registrar un usuario."));
            return;
        }
    
        $existingUser = $this->user->findByEmail($data['email']);
        if ($existingUser) {
            http_response_code(409);
            echo json_encode(array("message" => "El usuario con este correo ya está registrado."));
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
    
        $query = "SELECT COUNT(*) FROM roles WHERE id = :role_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':role_id', $data['role_id']);
        $stmt->execute();
    
        if ($stmt->fetchColumn() == 0) {
            http_response_code(400);
            echo json_encode(array("message" => "El role_id proporcionado no es válido."));
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
            echo json_encode(array(
                "message" => "Inicio de sesión exitoso", 
                "user" => $user,  // Información del usuario con role_name incluido
            ));
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
        
        if (isset($data['action'])) {
            if ($data['action'] === 'login') {
                if (isset($data['email']) && isset($data['password'])) {
                    $userController->login($data['email'], $data['password']);
                } else {
                    http_response_code(400);
                    echo json_encode(array("message" => "Correo y contraseña son requeridos para el inicio de sesión."));
                }
            } elseif ($data['action'] === 'register') {
                $userController->addUser($data);
            } else {
                http_response_code(400);
                echo json_encode(array("message" => "Acción desconocida."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Acción no especificada."));
        }
        break;

    case 'PUT':
        $id = $_GET['id'] ?? null;
        $put_vars = json_decode(file_get_contents("php://input"), true);

        if ($id && $put_vars) {
            $userController->updateUser($id, $put_vars);
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "ID de usuario o datos no proporcionados para actualización."));
        }
        break;

    case 'DELETE':
        if (isset($_GET['id'])) {
            $userController->deleteUser($_GET['id']);
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
