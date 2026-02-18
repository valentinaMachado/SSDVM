<?php
session_start();
include("../conexion/conexion.php");

// Verificar el usuario 
if (!isset($_SESSION['usuario_id'])) {
    die("Error: Debe iniciar sesión para procesar el pago.");
}

$usuario_id = $_SESSION['usuario_id'];
$nombre = $_SESSION['nombre'] ?? '';
$email = $_SESSION['email'] ?? '';
$telefono = $_SESSION['telefono'] ?? '';
$direccion = $_SESSION['direccion'] ?? '';

// Obtener productos del carrito
$sql_carrito = "SELECT Nombre, Precio, Cantidad FROM vm_carrito WHERE ID_Usuario = ?";
$stmt = $conn->prepare($sql_carrito);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result_carrito = $stmt->get_result();

if ($result_carrito->num_rows == 0) {
    die("El carrito está vacío.");
}

// Calcular total
$total = 0;
$productos = [];
while ($row = $result_carrito->fetch_assoc()) {
    $productos[] = $row;
    $total += $row['Precio'] * $row['Cantidad'];
}

//  Insertar en vm_orden
$sql_orden = "INSERT INTO vm_orden (Nombre, Email, Telefono, Direccion, Fecha, Estado, Total, ID_Usuario)
              VALUES (?, ?, ?, ?, NOW(), 'Pendiente', ?, ?)";
$stmt = $conn->prepare($sql_orden);
$stmt->bind_param("ssssdi", $nombre, $email, $telefono, $direccion, $total, $usuario_id);

if (!$stmt->execute()) {
    die("Error al registrar el pedido: " . $stmt->error);
}

$id_pedido = $stmt->insert_id; // ID del nuevo pedido

// Insertar detalles del pedido
$sql_detalle = "INSERT INTO vm_pedidodetalle (ID_Pedido, NombreProducto, Precio, Cantidad)
                VALUES (?, ?, ?, ?)";
$stmt_detalle = $conn->prepare($sql_detalle);

foreach ($productos as $producto) {
    $stmt_detalle->bind_param("isdi", $id_pedido, $producto['Nombre'], $producto['Precio'], $producto['Cantidad']);
    $stmt_detalle->execute();
}

// 4. Vaciar carrito
$sql_vaciar = "DELETE FROM vm_carrito WHERE ID_Usuario = ?";
$stmt = $conn->prepare($sql_vaciar);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();

// 5. Cerrar sesión y redirigir
session_destroy();
header("Location: gracias.php?pedido_id=" . $id_pedido);
exit;
?>