<?php
require_once '../../../models/DatabaseModel.php';

header("Access-Control-Allow-Origin: *"); 
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

try {
    $pdo = Database::getInstance();
    $pdo->beginTransaction();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents("php://input"), true);
        if (!isset($data['admin_usuario'], $data['admin_contrasena'])) {
            http_response_code(400);
            echo json_encode([
                "error" => "Datos inválidos o incompletos",
                "data" => $data
            ]);
            exit;
        }

        $admin_user = $data['admin_usuario'];
        $admin_password=$data['admin_contrasena'];

        $stmtUser = $pdo->prepare("
            SELECT
            admin_id,
            admin_nombre,
            admin_usuario
            FROM administradores
            WHERE admin_usuario=:admin_usuario
            AND admin_contrasena=:admin_contrasena"
        );

        $stmtUser->bindParam(":admin_usuario", $admin_user);
        $stmtUser->bindParam(":admin_contrasena", $admin_password);

        $stmtUser->execute();

        $adminInfo = $stmtUser->fetchAll(PDO::FETCH_ASSOC);

        http_response_code(200);
        echo json_encode([
            "message" => "Sesión iniciada con éxito",
            "admin_info" => $adminInfo,
        ]);
    } else {
        http_response_code(405);
        echo json_encode(["error" => "Método no permitido"]);
    }
} catch (Exception $e) {
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode(["error" => "Error en el servidor", "detalle" => $e->getMessage()]);
}
?>