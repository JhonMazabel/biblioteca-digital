<?php
// change-password.php

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
if (isset($data['email']) && isset($data['currentPassword']) && isset($data['newPassword'])) {
    $email = $data['email'];
    $currentPassword = $data['currentPassword'];
    $newPassword = $data['newPassword'];

    // Verificar la contraseña actual
    $userData = $user->verifyLogin($email, $currentPassword);

    if ($userData) {
        // Cambiar la contraseña
        if ($user->changePassword($userData['id'], $newPassword)) {
            http_response_code(200);
            echo json_encode(array("message" => "Contraseña cambiada con éxito."));
        } else {
            http_response_code(500);
            echo json_encode(array("message" => "No se pudo cambiar la contraseña."));
        }
    } else {
        http_response_code(401);
        echo json_encode(array("message" => "Contraseña actual incorrecta."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Todos los campos son requeridos."));
}
?>
