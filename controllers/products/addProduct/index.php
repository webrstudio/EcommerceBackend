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
        
        if (!isset($_FILES['producto_imagen'], $_POST['producto_nombre'], $_POST['producto_precio'], $_POST['marca_id'])) {
            http_response_code(400);
            echo json_encode([
                "error" => "Datos inválidos o incompletos",
            ]);
            exit;
        }

        $producto_nombre = $_POST['producto_nombre'];
        $producto_precio = $_POST['producto_precio'];
        $marca_id = $_POST['marca_id'];
        $imagen = $_FILES['producto_imagen'];

        $directorio = "imagenes/";
        if (!is_dir($directorio)) {
            mkdir($directorio, 0777, true);
        }

        $extension = pathinfo($imagen['name'], PATHINFO_EXTENSION);
        $nombre_archivo = uniqid("img_", true) . "." . $extension;
        $ruta_destino = $directorio . $nombre_archivo;

        if (!move_uploaded_file($imagen['tmp_name'], $ruta_destino)) {
            throw new Exception("Error al subir la imagen");
        }

        $url_imagen = "http://localhost/ecommerce-backend/" . $ruta_destino;

        $stmtUser = $pdo->prepare("INSERT INTO productos (producto_nombre, producto_precio, producto_imagen, marca_id) 
                                   VALUES (:producto_nombre, :producto_precio, :producto_imagen, :marca_id)");

        $stmtUser->bindParam(":producto_nombre", $producto_nombre);
        $stmtUser->bindParam(":producto_precio", $producto_precio);
        $stmtUser->bindParam(":producto_imagen", $url_imagen);
        $stmtUser->bindParam(":marca_id", $marca_id);
        
        $stmtUser->execute();

        $pdo->commit();

        echo json_encode([
            "mensaje" => "Producto registrado con éxito",
            "imagen_url" => $url_imagen
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