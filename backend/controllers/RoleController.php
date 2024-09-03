<?php
// controllers/RoleController.php

include_once '../config/db.php';
include_once '../models/Role.php';

class RoleController {
    private $db;
    private $role;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->role = new Role($this->db);
    }

    public function getRoles() {
        $stmt = $this->role->read();
        $num = $stmt->rowCount();

        if ($num > 0) {
            $roles_arr = array();
            $roles_arr["records"] = array();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $role_item = array(
                    "id" => $id,
                    "name" => $name
                );

                array_push($roles_arr["records"], $role_item);
            }

            http_response_code(200);
            echo json_encode($roles_arr);
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "No se encontraron roles."));
        }
    }

    public function addRole($data) {
        if ($this->role->create($data)) {
            http_response_code(201);
            echo json_encode(array("message" => "Rol añadido exitosamente."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Error al añadir el rol."));
        }
    }

    public function updateRole($id, $data) {
        if ($this->role->update($id, $data)) {
            http_response_code(200);
            echo json_encode(array("message" => "Rol actualizado exitosamente."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Error al actualizar el rol."));
        }
    }

    public function deleteRole($id) {
        if ($this->role->delete($id)) {
            http_response_code(200);
            echo json_encode(array("message" => "Rol eliminado exitosamente."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Error al eliminar el rol."));
        }
    }

    // Otros métodos de gestión de roles pueden añadirse aquí...
}

// Inicialización del controlador y manejo de las solicitudes
$roleController = new RoleController();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $roleController->getRoles();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $roleController->addRole($data);
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    parse_str(file_get_contents("php://input"), $put_vars);
    $roleController->updateRole($put_vars['id'], $put_vars);
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    parse_str(file_get_contents("php://input"), $delete_vars);
    $roleController->deleteRole($delete_vars['id']);
}
?>
