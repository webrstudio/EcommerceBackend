<?php
require_once '../../../vendor/autoload.php';
require_once '../../../models/DatabaseModel.php';

\Stripe\Stripe::setApiKey('');

// Recibir payload del webhook
$payload = @file_get_contents('php://input');
$sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';
$endpoint_secret = 'whsec_6ba7064da178abbdc364f5647b3a93b70d8565c52485af9d9533b6420bba11bb';

try {
    $event = \Stripe\Webhook::constructEvent($payload, $sig_header, $endpoint_secret);
} catch (\UnexpectedValueException $e) {
    http_response_code(400);
    exit();
} catch (\Stripe\Exception\SignatureVerificationException $e) {
    http_response_code(400);
    exit();
}

if ($event->type === 'checkout.session.completed') {
    $session = $event->data->object;

    // Extraer metadata
    $usuario_id = $session->metadata->usuario_id ?? null;
    $usuario_carrito = json_decode($session->metadata->usuario_carrito, true);

    if ($usuario_id && is_array($usuario_carrito)) {
        try {
            $pdo = Database::getInstance();
            $pdo->beginTransaction();

            // Actualizar estado del pedido
            $stmtPedido = $pdo->prepare("UPDATE pedidos SET pedido_estado = 'paid' WHERE usuario_id = :usuario_id AND pedido_estado = 'pending'");
            $stmtPedido->bindParam(':usuario_id', $usuario_id);
            $stmtPedido->execute();

            // Actualizar inventario por cada producto
            $stmtUpdate = $pdo->prepare("UPDATE productos SET producto_inventario = producto_inventario - :cantidad WHERE producto_id = :producto_id");

            foreach ($usuario_carrito as $item) {
                $cantidad = $item['cantidad'] ?? 1;
                $producto_id = $item['producto_id'];

                $stmtUpdate->bindParam(':cantidad', $cantidad, PDO::PARAM_INT);
                $stmtUpdate->bindParam(':producto_id', $producto_id, PDO::PARAM_INT);
                $stmtUpdate->execute();
            }

            $pdo->commit();
        } catch (Exception $e) {
            $pdo->rollBack();
            error_log("Error en webhook: " . $e->getMessage());
        }
    }
}

http_response_code(200);