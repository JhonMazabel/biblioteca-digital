<?php
// index.php

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', $uri);

// Aquí puedes añadir diferentes rutas para otros controladores
if ($uri[1] === 'books') {
    include_once 'controllers/bookController.php';
    exit();
}

http_response_code(404);
echo json_encode(array("message" => "Página no encontrada."));
?>
