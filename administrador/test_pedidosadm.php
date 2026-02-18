
<?php
include("../conexion/conexion.php");

header("Content-Type: application/json");

// Si viene actualización de estado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['order_id'] ?? null;
    $estado   = $_POST['payment_status'] ?? null;

    if ($order_id && $estado) {
        $update = "UPDATE vm_orden SET Estado=? WHERE ID_Pedido=?";
        $stmt = $conn->prepare($update);
        $stmt->bind_param("si", $estado, $order_id);

        if ($stmt->execute()) {
            echo json_encode([
                "success" => true,
                "message" => "Estado actualizado correctamente",
                "order_id" => $order_id,
                "nuevo_estado" => $estado
            ]);
        } else {
            echo json_encode(["success" => false, "message" => $conn->error]);
        }
        exit;
    }
}

// Si viene GET → listar pedidos
$sql = "SELECT * FROM vm_orden ORDER BY Fecha DESC";
$result = $conn->query($sql);

$pedidos = [];
while ($row = $result->fetch_assoc()) {
    $id_pedido = $row['ID_Pedido'];

    // traer productos del detalle
    $sql_prod = "SELECT NombreProducto, Precio, Cantidad 
                 FROM vm_pedidodetalle WHERE ID_Pedido=?";
    $stmt = $conn->prepare($sql_prod);
    $stmt->bind_param("i", $id_pedido);
    $stmt->execute();
    $res_prod = $stmt->get_result();

    $productos = [];
    while ($p = $res_prod->fetch_assoc()) {
        $p['Subtotal'] = $p['Precio'] * $p['Cantidad'];
        $productos[] = $p;
    }

    $row['Productos'] = $productos;
    $pedidos[] = $row;
}

echo json_encode([
    "success" => true,
    "pedidos" => $pedidos
]);
?>