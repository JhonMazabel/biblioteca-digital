<?php
include_once '../config/db.php';
include_once '../models/CollectionType.php';

class CollectionTypeController {
    private $db;
    private $collectionType;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->collectionType = new CollectionType($this->db);
    }

    public function getCollectionTypes() {
        $stmt = $this->collectionType->read();
        $num = $stmt->rowCount();

        if($num > 0) {
            $collectionTypes_arr = array();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $collectionType_item = array(
                    "id" => $row['id'],
                    "name" => $row['name']
                );

                array_push($collectionTypes_arr, $collectionType_item);
            }

            http_response_code(200);
            echo json_encode($collectionTypes_arr);
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "No se encontraron tipos de colección."));
        }
    }
}
?>
