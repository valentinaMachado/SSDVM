<?php

include('../conexion/conexion.php');
session_start();

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(["success" => false, "message" => "Usuario no autenticado"]);
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

// Recibir datos JSON desde JavaScript
$datos = json_decode(file_get_contents("php://input"), true);

// Validar que llegaron todos los campos
if (isset($datos['ID_Producto'], $datos['Nombre'], $datos['Precio'], $datos['Cantidad'])) {

    $id_producto = intval($datos['ID_Producto']);
    $nombre = $conn->real_escape_string($datos['Nombre']);
    $precio = floatval($datos['Precio']);
    $cantidad = intval($datos['Cantidad']);

    // Verificar si ya existe el producto para este usuario
    $sql_check = "SELECT * FROM vm_carrito WHERE ID_Usuario = $usuario_id AND ID_Producto = $id_producto";
    $result = $conn->query($sql_check);

    if ($result && $result->num_rows > 0) {
        // Si ya existe, sumar la cantidad
        $sql_update = "UPDATE vm_carrito SET Cantidad = Cantidad + $cantidad 
                       WHERE ID_Usuario = $usuario_id AND ID_Producto = $id_producto";
        if ($conn->query($sql_update)) {
            echo json_encode(["success" => true, "message" => "Producto actualizado."]);
        } else {
            echo json_encode(["success" => false, "message" => $conn->error]);
        }
    } else {
        // Insertar nuevo producto
        $sql_insert = "INSERT INTO vm_carrito (ID_Usuario, ID_Producto, Nombre, Precio, Cantidad) 
                       VALUES ($usuario_id, $id_producto, '$nombre', $precio, $cantidad)";
        if ($conn->query($sql_insert)) {
            echo json_encode(["success" => true, "message" => "Producto agregado."]);
        } else {
            echo json_encode(["success" => false, "message" => $conn->error]);
        }
    }
}