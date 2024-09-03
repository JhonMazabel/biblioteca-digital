<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

include_once '../config/db.php';

$database = new Database();
$db = $database->getConnection();

try {
    // Consulta para obtener los tipos de colección
    $query = "SELECT * FROM type_collection"; // Asegúrate de que el nombre de la tabla sea correcto
    $stmt = $db->prepare($query);
    $stmt->execute();

    $num = $stmt->rowCount();
    
    if ($num > 0) {
        $collectionTypes = array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $collectionType = array(
                "id" => $row['id'],
                "name" => $row['name']
            );
            array_push($collectionTypes, $collectionType);
        }
        http_response_code(200);
        echo json_encode($collectionTypes);
    } else {
        http_response_code(404);
        echo json_encode(array("message" => "No se encontraron tipos de colección."));
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(array("message" => "Error al obtener tipos de colección.", "error" => $e->getMessage()));
}
?>
