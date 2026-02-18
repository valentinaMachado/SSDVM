
<?php
header('Content-Type: application/json');

$conn = include("../conexion/conexion.php");

$response = [];

// Caso 1: eliminar un pedido válido
if (isset($_GET['test']) && $_GET['test'] == "ok") {
    $pedido_id = 1; // <- aquí pon un ID existente en tu BD

    $deleteDetalles = $conn->query("DELETE FROM vm_pedidodetalle WHERE ID_Pedido = '$pedido_id'");
    $deleteOrden = $conn->query("DELETE FROM vm_orden WHERE ID_Pedido = '$pedido_id'");

    if ($deleteOrden) {
        $response = [
            "status" => "success",
            "message" => "Pedido eliminado correctamente",
            "pedido_id" => $pedido_id
        ];
    } else {
        $response = [
            "status" => "error",
            "message" => "No se pudo eliminar el pedido"
        ];
    }
} 
// Caso 2: no se envía ID
else {
    $response = [
        "status" => "error",
        "message" => "ID no proporcionado para prueba"
    ];
}

echo json_encode($response);