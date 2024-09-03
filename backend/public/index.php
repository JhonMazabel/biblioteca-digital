<?php
// index.php

header("Access-Control-Allow-Origin: http://localhost:4200");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

// Manejar solicitudes preflight (OPTIONS)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', $uri);

// Asegúrate de que la URI tiene al menos dos elementos
if (isset($uri[1])) {
    switch ($uri[1]) {
        case 'books':
            include_once 'controllers/BookController.php';
            break;
        case 'loans':
            include_once 'controllers/LoanController.php';
            break;
        case 'collectionType':
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                include_once 'controllers/CollectionTypeController.php';
            }
            break;
        case 'reviews':
            include_once 'controllers/ReviewController.php';
            break;
        case 'roles':
            include_once 'controllers/RoleController.php';
            break;
        case 'users':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                include_once 'controllers/UserController.php';
            }
            break;
        case 'login':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                include_once 'controllers/UserController.php';
            }
            break;
        case 'register':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                include_once 'controllers/UserController.php';
                $userController = new UserController();
                $data = json_decode(file_get_contents("php://input"), true);
                $userController->addUser($data);
            }
            break;
        case 'change-password':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                include_once 'controllers/UserController.php';
            }
            break;
        default:
            http_response_code(404);
            echo json_encode(array("message" => "Página no encontrada."));
            break;
    }
} else {
    http_response_code(404);
    echo json_encode(array("message" => "Página no encontrada."));
}
?>
