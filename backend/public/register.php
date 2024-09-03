<?php
// register.php

header("Access-Control-Allow-Origin: http://localhost:4200");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

include_once '../config/db.php';
include_once '../models/User.php';

$database = new Database();
$db = $database->getConnection();
$user = new User($db);

$data = json_decode(file_get_contents("php://input"), true);

// Verificar que se hayan enviado todos los datos necesarios
if (isset($data['username']) && isset($data['email']) && isset($data['password'])) {
    // Asignar valores a las variables
    $data['role_id'] = 2; // Rol predeterminado para estudiantes
    $data['first_name'] = isset($data['firstName']) ? $data['firstName'] : null;
    $data['last_name'] = isset($data['lastName']) ? $data['lastName'] : null;

    if ($user->create($data)) {
        http_response_code(201);
        echo json_encode(array("message" => "Usuario registrado exitosamente."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Error al registrar el usuario."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Todos los campos son requeridos."));
}
?>
