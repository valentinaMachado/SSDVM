<?php
// test_carrito.php
header("Content-Type: application/json");
include("../conexion/conexion.php");
session_start();

// Simulamos un usuario logueado (esto es fijo porque viene de la sesión)
$_SESSION['usuario_id'] = 6; 
$usuario_id = $_SESSION['usuario_id'];

// Leer datos enviados desde Postman (JSON o form-data)
$datos = json_decode(file_get_contents("php://input"), true);
if (!$datos) {
    $datos = $_POST; 
}

if (isset($datos['ID_Producto'], $datos['Nombre'], $datos['Precio'], $datos['Cantidad'])) {
    $id_producto = intval($datos['ID_Producto']);
    $nombre      = $conn->real_escape_string($datos['Nombre']);
    $precio      = floatval($datos['Precio']);
    $cantidad    = intval($datos['Cantidad']);

    // Verificar si ya existe el producto en el carrito
    $sql_check = "SELECT * FROM vm_carrito WHERE ID_Usuario = $usuario_id AND ID_Producto = $id_producto";
    $result = $conn->query($sql_check);

    if ($result && $result->num_rows > 0) {
        // Actualizar cantidad
        $sql_update = "UPDATE vm_carrito 
                       SET Cantidad = Cantidad + $cantidad 
                       WHERE ID_Usuario = $usuario_id AND ID_Producto = $id_producto";
        if ($conn->query($sql_update)) {
            echo json_encode([
                "success" => true,
                "message" => "Producto actualizado en el carrito",
                "ID_Producto" => $id_producto,
                "Cantidad" => $cantidad
            ]);
        } else {
            echo json_encode(["success" => false, "message" => $conn->error]);
        }
    } else {
        // Insertar nuevo
        $sql_insert = "INSERT INTO vm_carrito (ID_Usuario, ID_Producto, Nombre, Precio, Cantidad, Fecha_agregado)
                       VALUES ($usuario_id, $id_producto, '$nombre', $precio, $cantidad, NOW())";
        if ($conn->query($sql_insert)) {
            echo json_encode([
                "success" => true,
                "message" => "Producto agregado al carrito",
                "ID_Producto" => $id_producto,
                "Cantidad" => $cantidad
            ]);
        } else {
            echo json_encode(["success" => false, "message" => $conn->error]);
        }
    }
} else {
    echo json_encode([
        "success" => false,
        "message" => "Faltan datos: ID_Producto, Nombre, Precio, Cantidad"
    ]);
}