<?php
require_once '../../../models/DatabaseModel.php';
header("Access-Control-Allow-Origin: *"); 
header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); // Métodos permitidos
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Headers permitidos
header("Content-Type: application/json");
try {
    $pdo = Database::getInstance();
    if (!isset($_GET['marca_id']) || empty($_GET['marca_id'])) {
        echo json_encode(["error" => "Debes proporcionar una marca"]);
        exit;
    }

    // Obtener la marca desde GET
    $brandId = intval($_GET['marca_id']);

    // Preparar la consulta
    $stmt = $pdo->prepare("
        SELECT
        pedidos.pedido_id,
        usuarios.usuario_nombre,
        usuarios.usuario_calle,
        usuarios.usuario_numero_exterior,
        usuarios.usuario_cp,
        usuarios.usuario_municipio,
        usuarios.usuario_estado,
        usuarios.usuario_colonia,
        usuarios.usuario_referencia,
        usuarios.usuario_email,
        usuarios.usuario_telefono,
        productos.producto_nombre,
        productos.producto_imagen
        from pedidos
        inner JOIN
        usuarios
        on pedidos.usuario_id = usuarios.usuario_id
        inner JOIN
        productos
        on pedidos.producto_id = productos.producto_id
        where productos.marca_id=:marca_id
    ");
    $stmt->bindParam(':marca_id', $brandId, PDO::PARAM_INT);
    $stmt->execute();

    // Obtener los resultados
    $orders = $stmt->fetchAll();

    // Enviar respuesta en formato JSON
    header("Content-Type: application/json");
    echo json_encode($orders);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => "Error en el servidor", $e]);
}
?>