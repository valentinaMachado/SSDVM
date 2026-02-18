<?php
include("../conexion/conexion.php");
session_start();
header("Content-Type: application/json; charset=UTF-8");

$usuario_id = $_SESSION['usuario_id'] ?? null;
$nombre    = $_SESSION['nombre'] ?? '';
$email     = $_SESSION['email'] ?? '';
$telefono  = $_SESSION['telefono'] ?? '';
$direccion = $_SESSION['direccion'] ?? '';

if (!$usuario_id) {
    echo json_encode([
        "success" => false,
        "message" => " Inicie sesión para continuar."
    ]);
    exit;
}

// Traer carrito del usuario
$sql_carrito = "SELECT Nombre, Precio, Cantidad 
                FROM vm_carrito 
                WHERE ID_Usuario = ?";
$stmt = $conn->prepare($sql_carrito);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();

$productos = [];
$total = 0;

while ($row = $result->fetch_assoc()) {
    $subtotal = $row['Precio'] * $row['Cantidad'];
    $total += $subtotal;

    $productos[] = [
        "nombre"   => $row['Nombre'],
        "precio"   => (float)$row['Precio'],
        "cantidad" => (int)$row['Cantidad'],
        "subtotal" => $subtotal
    ];
}

echo json_encode([
    "success" => true,
    "cliente" => [
        "nombre"    => $nombre,
        "email"     => $email,
        "telefono"  => $telefono,
        "direccion" => $direccion
    ],
    "carrito" => $productos,
    "total" => $total
], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);