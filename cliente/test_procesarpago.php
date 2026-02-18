<?php
include("../conexion/conexion.php");

// Recibir datos desde Postman (puede ser JSON o form-data)
$input = json_decode(file_get_contents("php://input"), true);

$usuario_id = $input['usuario_id'] ?? $_POST['usuario_id'] ?? null;
$nombre     = $input['nombre'] ?? $_POST['nombre'] ?? '';
$email      = $input['email'] ?? $_POST['email'] ?? '';
$telefono   = $input['telefono'] ?? $_POST['telefono'] ?? '';
$direccion  = $input['direccion'] ?? $_POST['direccion'] ?? '';

if (!$usuario_id) {
    echo json_encode([
        "success" => false,
        "message" => " Debes enviar el usuario_id"
    ]);
    exit;
}

// 1. Obtener productos del carrito del usuario
$sql_carrito = "SELECT Nombre, Precio, Cantidad FROM vm_carrito WHERE ID_Usuario = ?";
$stmt = $conn->prepare($sql_carrito);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result_carrito = $stmt->get_result();

if ($result_carrito->num_rows == 0) {
    echo json_encode([
        "success" => false,
        "message" => " El carrito está vacío."
    ]);
    exit;
}

// 2. Calcular total
$total = 0;
$productos = [];
while ($row = $result_carrito->fetch_assoc()) {
    $subtotal = $row['Precio'] * $row['Cantidad'];
    $productos[] = [
        "nombre"   => $row['Nombre'],
        "precio"   => $row['Precio'],
        "cantidad" => $row['Cantidad'],
        "subtotal" => $subtotal
    ];
    $total += $subtotal;
}

// 3. Insertar en vm_orden
$sql_orden = "INSERT INTO vm_orden (Nombre, Email, Telefono, Direccion, Fecha, Estado, Total, ID_Usuario)
              VALUES (?, ?, ?, ?, NOW(), 'Pendiente', ?, ?)";
$stmt = $conn->prepare($sql_orden);
$stmt->bind_param("ssssdi", $nombre, $email, $telefono, $direccion, $total, $usuario_id);

if (!$stmt->execute()) {
    echo json_encode([
        "success" => false,
        "message" => " Error al registrar el pedido: " . $stmt->error
    ]);
    exit;
}

$id_pedido = $stmt->insert_id;

// 4. Insertar detalles del pedido
$sql_detalle = "INSERT INTO vm_pedidodetalle (ID_Pedido, NombreProducto, Precio, Cantidad)
                VALUES (?, ?, ?, ?)";
$stmt_detalle = $conn->prepare($sql_detalle);

foreach ($productos as $producto) {
    $stmt_detalle->bind_param("isdi", $id_pedido, $producto['nombre'], $producto['precio'], $producto['cantidad']);
    $stmt_detalle->execute();
}

// 5. Vaciar carrito
$sql_vaciar = "DELETE FROM vm_carrito WHERE ID_Usuario = ?";
$stmt = $conn->prepare($sql_vaciar);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();

// ✅ Respuesta final
echo json_encode([
    "success" => true,
    "message" => " Pedido registrado exitosamente.",
    "pedido_id" => $id_pedido,
    "total" => $total,
    "productos" => $productos
], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);