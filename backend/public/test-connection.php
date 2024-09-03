<?php
include '../config/db.php'; // Cambia esta línea

$db = new Database();
$conn = $db->getConnection();

if ($conn) {
    echo "Conexión exitosa.";
} else {
    echo "Error de conexión.";
}
?>
