<?php
header('Content-Type: application/json');
include("../conexion/conexion.php"); // tu conexión devuelve $conn

// Validar que el método sea POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        "status" => "error",
        "message" => "Método no permitido, usa POST"
    ]);
    exit;
}

// Capturar datos enviados desde Postman
$nombre = $_POST['Nombre'] ?? '';
$correo = $_POST['Correo'] ?? '';
$password = $_POST['Password'] ?? '';
$tipo_usuario = $_POST['tipo_usuario'] ?? '';

// Validar campos
if (!empty($nombre) && !empty($correo) && !empty($password) && !empty($tipo_usuario)) {

    // Encriptar contraseña
    $password_hash = password_hash($password, PASSWORD_BCRYPT);

    // Verificar conexión
    if (!$conn) {
        echo json_encode([
            "status" => "error",
            "message" => "Error: no se pudo establecer conexión con la base de datos"
        ]);
        exit;
    }

    // Preparar consulta
    $sql = "INSERT INTO vm_usuario (Tipo_usuario, Nombre, Email, Contrasena) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ssss", $tipo_usuario, $nombre, $correo, $password_hash);

        if ($stmt->execute()) {
            echo json_encode([
                "status" => "success",
                "message" => "Usuario registrado correctamente",
                "data" => [
                    "Nombre" => $nombre,
                    "Correo" => $correo,
                    "tipo_usuario" => $tipo_usuario
                ]
            ]);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Error al registrar el usuario: " . $stmt->error
            ]);
        }

        $stmt->close();
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Error al preparar la consulta: " . $conn->error
        ]);
    }

    $conn->close();

} else {
    echo json_encode([
        "status" => "error",
        "message" => "Faltan datos en la petición",
        "received" => $_POST
    ]);
}
?>