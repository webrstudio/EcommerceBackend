<?php
require_once '../../../vendor/autoload.php';
require_once '../../../models/DatabaseModel.php';

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

// Validar campos mínimos
$requiredFields = ['usuario_nombre','usuario_email','usuario_cp','usuario_estado','usuario_municipio','usuario_colonia','usuario_telefono','usuario_calle','usuario_referencia','usuario_numero_exterior','usuario_carrito','paymentAmount'];
foreach ($requiredFields as $field) {
    if (!isset($input[$field]) || empty($input[$field])) {
        http_response_code(400);
        echo json_encode(['error' => "Falta el campo $field"]);
        exit;
    }
}

$usuario_carrito = $input['usuario_carrito'];
$paymentAmount = $input['paymentAmount'];

\Stripe\Stripe::setApiKey('');

try {
    $pdo = Database::getInstance();
    $pdo->beginTransaction();

    // Insertar usuario
    $stmtUser = $pdo->prepare("
        INSERT INTO usuarios 
            (usuario_nombre, usuario_email, usuario_cp, usuario_estado, usuario_municipio, usuario_colonia, usuario_telefono, usuario_calle, usuario_referencia, usuario_numero_exterior)
        VALUES 
            (:usuario_nombre, :usuario_email, :usuario_cp, :usuario_estado, :usuario_municipio, :usuario_colonia, :usuario_telefono, :usuario_calle, :usuario_referencia, :usuario_numero_exterior)
    ");
    $stmtUser->bindParam(':usuario_nombre', $input['usuario_nombre']);
    $stmtUser->bindParam(':usuario_email', $input['usuario_email']);
    $stmtUser->bindParam(':usuario_cp', $input['usuario_cp']);
    $stmtUser->bindParam(':usuario_estado', $input['usuario_estado']);
    $stmtUser->bindParam(':usuario_municipio', $input['usuario_municipio']);
    $stmtUser->bindParam(':usuario_colonia', $input['usuario_colonia']);
    $stmtUser->bindParam(':usuario_telefono', $input['usuario_telefono']);
    $stmtUser->bindParam(':usuario_calle', $input['usuario_calle']);
    $stmtUser->bindParam(':usuario_referencia', $input['usuario_referencia']);
    $stmtUser->bindParam(':usuario_numero_exterior', $input['usuario_numero_exterior']);
    $stmtUser->execute();
    $usuario_id = $pdo->lastInsertId();

    // Insertar pedidos como pending
    $stmtPedido = $pdo->prepare("INSERT INTO pedidos (usuario_id, producto_id, pedido_fecha, pedido_estado) VALUES (:usuario_id, :producto_id, :pedido_fecha, 'pending')");

    foreach ($usuario_carrito as $item) {
        $fecha = date('Y-m-d H:i:s');
        $stmtPedido->bindParam(':usuario_id', $usuario_id);
        $stmtPedido->bindParam(':producto_id', $item['producto_id']);
        $stmtPedido->bindParam(':pedido_fecha', $fecha);
        $stmtPedido->execute();
    }

    $pdo->commit();

    $line_items = [];
    foreach ($usuario_carrito as $item) {
        $line_items[] = [
            'price_data' => [
                'currency' => 'mxn',
                'product_data' => [
                    'name' => $item['producto_nombre']
                ],
                'unit_amount' => (int) round($input['paymentAmount'] * 100),
            ],
            'quantity' => 1,
        ];
    }

    // Crear sesión de Stripe
    $session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'line_items' => $line_items,
        'mode' => 'payment',
        'success_url' => 'http://localhost:3000/pago/exitoso',
        'cancel_url' => 'http://localhost:3000/',
        'metadata' => [
            'usuario_id' => $usuario_id,
            'usuario_carrito' => json_encode($usuario_carrito)
        ]
    ]);

    echo json_encode(['url' => $session->url]);

} catch (\Exception $e) {
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}