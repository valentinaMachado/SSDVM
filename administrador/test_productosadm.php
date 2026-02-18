<?php

header('Content-Type: application/json; charset=utf-8');
include("../conexion/conexion.php"); // debe dejar disponible $conn

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["success" => false, "message" => "Método no permitido. Use POST."]);
    exit;
}

// Validar campos básicos
$required = ['nombre', 'descripcion', 'precio', 'categoria'];
foreach ($required as $k) {
    if (!isset($_POST[$k]) || trim($_POST[$k]) === '') {
        echo json_encode(["success" => false, "message" => "Falta el campo: $k"]);
        exit;
    }
}

$nombre      = trim($_POST['nombre']);
$descripcion = trim($_POST['descripcion']);
$precioRaw   = trim($_POST['precio']);
$categoria   = trim($_POST['categoria']);

// validar precio numérico
if (!is_numeric($precioRaw)) {
    echo json_encode(["success" => false, "message" => "El precio debe ser un número."]);
    exit;
}
$precio = floatval($precioRaw);

// validar archivo
if (!isset($_FILES['imagen']) || $_FILES['imagen']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(["success" => false, "message" => "Falta la imagen o hubo un error al subirla."]);
    exit;
}

// comprobar que sea imagen real
$imgTmp = $_FILES['imagen']['tmp_name'];
$imgInfo = @getimagesize($imgTmp);
if ($imgInfo === false) {
    echo json_encode(["success" => false, "message" => "El archivo subido no es una imagen válida."]);
    exit;
}

// preparar carpeta de destino
$uploadDir = __DIR__ . '/../cliente/IMG/';
if (!is_dir($uploadDir)) {
    if (!mkdir($uploadDir, 0755, true)) {
        echo json_encode(["success" => false, "message" => "No se pudo crear carpeta de imágenes."]);
        exit;
    }
}

// generar nombre único seguro
$ext = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
$ext = preg_replace('/[^a-zA-Z0-9]/', '', $ext); // sanitizar extensión
if ($ext === '') $ext = 'jpg';
$uniqueName = time() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
$destPath = $uploadDir . $uniqueName;

// mover archivo
if (!move_uploaded_file($imgTmp, $destPath)) {
    echo json_encode(["success" => false, "message" => "Error al mover la imagen al destino."]);
    exit;
}

// insertar en BD
$sql = "INSERT INTO vm_producto (nombre, descripcion, precio, categoria, imagen) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    // limpiar archivo subido si falla
    @unlink($destPath);
    echo json_encode(["success" => false, "message" => "Error en la consulta prepare: " . $conn->error]);
    exit;
}

$stmt->bind_param("ssdss", $nombre, $descripcion, $precio, $categoria, $uniqueName);

if ($stmt->execute()) {
    $insertId = $conn->insert_id;
    echo json_encode([
        "success" => true,
        "message" => "Producto añadido correctamente",
        "producto_id" => $insertId,
        "imagen" => $uniqueName,
        "ruta_imagen_relativa" => "cliente/IMG/" . $uniqueName
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    $stmt->close();
    exit;
} else {
    // limpiar archivo subido si falla
    @unlink($destPath);
    echo json_encode(["success" => false, "message" => "Error al insertar en BD: " . $stmt->error]);
    $stmt->close();
    exit;
}