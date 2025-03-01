<?php
require_once '../../../models/DatabaseModel.php';

header("Access-Control-Allow-Origin: *"); 
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

// Manejo de solicitudes OPTIONS (CORS)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

try {
    $pdo = Database::getInstance();
    $pdo->beginTransaction(); // Iniciar transacción

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Leer datos JSON del frontend
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['usuario_nombre'], $data['usuario_direccion'], $data['usuario_cp'], $data['usuario_estado'], $data['usuario_municipio'], $data['usuario_colonia'], $data['usuario_email'], $data['usuario_telefono'], $data['usuario_carrito']) || !is_array($data['usuario_carrito'])) {
            http_response_code(400);
            echo json_encode(["error" => "Datos inválidos o incompletos"]);
            exit;
        }

        // Insertar usuario en la tabla `usuarios`
        $stmtUser = $pdo->prepare("INSERT INTO usuarios (usuario_nombre, usuario_direccion, usuario_cp, usuario_estado, usuario_municipio, usuario_colonia, usuario_email, usuario_telefono) VALUES (:usuario_nombre, :usuario_direccion, :usuario_cp, :usuario_estado, :usuario_municipio, :usuario_colonia, :usuario_email, :usuario_telefono)");
        $stmtUser->bindParam(":usuario_nombre", $data['usuario_nombre']);
        $stmtUser->bindParam(":usuario_direccion", $data['usuario_direccion']);
        $stmtUser->bindParam(":usuario_cp", $data['usuario_cp']);
        $stmtUser->bindParam(":usuario_estado", $data['usuario_estado']);
        $stmtUser->bindParam(":usuario_municipio", $data['usuario_municipio']);
        $stmtUser->bindParam(":usuario_colonia", $data['usuario_colonia']);
        $stmtUser->bindParam(":usuario_email", $data['usuario_email']);
        $stmtUser->bindParam(":usuario_telefono", $data['usuario_telefono']);
        
        $stmtUser->execute();
        $usuario_id = $pdo->lastInsertId();

        if (!$usuario_id) {
            throw new Exception("No se pudo insertar el usuario.");
        }

        // Insertar cada producto del carrito en la tabla `pedidos`
        $stmtPedido = $pdo->prepare("INSERT INTO pedidos (usuario_id, producto_id, pedido_fecha) VALUES (:usuario_id, :producto_id, :pedido_fecha)");

        foreach ($data['usuario_carrito'] as $item) {
            if (!isset($item['producto_id'], $item['pedido_fecha'])) {
                throw new Exception("Datos de producto incorrectos.");
            }
            $stmtPedido->bindParam(":usuario_id", $usuario_id);
            $stmtPedido->bindParam(":producto_id", $item['producto_id']);
            $stmtPedido->bindParam(":pedido_fecha", $item['pedido_fecha']);
            $stmtPedido->execute();
        }

        $pdo->commit(); // Confirmar transacción

        echo json_encode([
            "mensaje" => "Pedido registrado con éxito",
            "usuario_id" => $usuario_id
        ]);
    } else {
        http_response_code(405);
        echo json_encode(["error" => "Método no permitido"]);
    }
} catch (Exception $e) {
    $pdo->rollBack(); // Revertir cambios en caso de error
    http_response_code(500);
    echo json_encode(["error" => "Error en el servidor", "detalle" => $e->getMessage()]);
}
?>