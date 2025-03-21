<?php
require_once '../../../models/DatabaseModel.php';
header("Access-Control-Allow-Origin: *"); 
header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); 
header("Access-Control-Allow-Headers: Content-Type, Authorization"); 
header("Content-Type: application/json");

try {
    $pdo = Database::getInstance();

    // Verificar si se pasó la marca como parámetro
    if (!isset($_GET['marca_id']) || empty($_GET['marca_id'])) {
        echo json_encode(["error" => "Debes proporcionar una marca"]);
        exit;
    }

    // Obtener la marca desde GET
    $marca = intval($_GET['marca_id']);

    // Preparar la consulta para filtrar solo por marca
    $stmt = $pdo->prepare("SELECT * FROM productos WHERE marca_id = :marca_id");
    $stmt->bindParam(':marca_id', $marca, PDO::PARAM_INT);
    $stmt->execute();

    // Obtener los resultados
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($productos);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => "Error en el servidor"]);
}
?>
