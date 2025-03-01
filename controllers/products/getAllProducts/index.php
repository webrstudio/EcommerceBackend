<?php
require_once '../../../models/DatabaseModel.php';
header("Access-Control-Allow-Origin: *"); 
header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); // Métodos permitidos
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Headers permitidos
header("Content-Type: application/json");
try {
    $pdo = Database::getInstance();

    // Preparar la consulta
    $stmt = $pdo->prepare("SELECT * FROM productos");
    $stmt->execute();

    // Obtener los resultados
    $productos = $stmt->fetchAll();

    // Enviar respuesta en formato JSON
    header("Content-Type: application/json");
    echo json_encode($productos);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => "Error en el servidor"]);
}
?>