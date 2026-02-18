
<?php
session_start();
include("../conexion.php");

// Verificar si es adminisitrador
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'ADMINISTRADOR') {
    header("Location: ../cliente/Iniciarsesioncli.html");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_pedido'])) {
    $idPedido = $_POST['order_id'];
    $estado = $_POST['payment_status'];

    $sql = "UPDATE vm_orden SET Estado = ? WHERE ID_Pedido = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $estado, $idPedido);

    if ($stmt->execute()) {
        header("Location: pedidosadm.php?msg=Pedido actualizado");
    } else {
        echo "❌ Error al actualizar pedido: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>
