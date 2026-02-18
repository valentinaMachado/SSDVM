
<?php
session_start();

header("Content-Type: application/json; charset=utf-8");

$pedido_id = $_GET['pedido_id'] ?? null;

// Respuesta JSON
if ($pedido_id) {
    echo json_encode([
        "success" => true,
        "message" => "🎉 ¡Gracias por su compra!",
        "pedido_id" => $pedido_id,
        "redirect" => "index.html"
    ], JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode([
        "success" => false,
        "message" => "No se recibió el ID del pedido"
    ], JSON_UNESCAPED_UNICODE);
}

// Destruir la sesión después de mostrar
session_destroy();
?>