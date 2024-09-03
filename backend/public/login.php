<?php
// login.php

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

if (isset($data['email']) && isset($data['password'])) {
    $email = $data['email'];
    $password = $data['password'];

    $userData = $user->verifyLogin($email, $password);

    if ($userData) {
        // Actualizar la última fecha de inicio de sesión
        $user->updateLastLogin($userData['id']);

        unset($userData['password']); // Asegurarse de que la contraseña no se envíe al cliente

        http_response_code(200);
        echo json_encode(array("message" => "Inicio de sesión exitoso", "user" => $userData));
    } else {
        http_response_code(401);
        echo json_encode(array("message" => "Correo o contraseña incorrectos"));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Correo y contraseña son requeridos."));
}
?>
