<?php
require_once '../../../models/DatabaseModel.php';

// Manejo de CORS
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

try {
    $pdo = Database::getInstance();

    if (!$pdo) {
        throw new Exception("Error de conexión a la base de datos");
    }

    // Verificar si se proporcionó un ID en la URL
    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        http_response_code(400);
        echo json_encode(["error" => "ID no válido"]);
        exit;
    }

    $stmt = $pdo->prepare("SELECT * FROM productos WHERE producto_id = :id");
    $stmt->execute(['id' => $_GET['id']]);
    $producto = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($producto) {
        echo json_encode($producto);
    } else {
        http_response_code(404);
        echo json_encode(["error" => "Producto no encontrado"]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => "Error en el servidor", "detalle" => $e->getMessage()]);
}
?>