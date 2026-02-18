
<?php
$conn = include("../conexion/conexion.php");

if (isset($_GET['id'])) {
    $pedido_id = $_GET['id'];

    // Eliminar detalles del pedido 
    $conn->query("DELETE FROM vm_pedidodetalle WHERE ID_Pedido = '$pedido_id'");

    // Eliminar el pedido
    $conn->query("DELETE FROM vm_orden WHERE ID_Pedido = '$pedido_id'");

    // Redirige de nuevo a la lista de pedidos
    header("Location: pedidosadm.php");
    exit();
} else {
    echo "ID no proporcionado.";
}
?>