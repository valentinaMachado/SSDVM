<?php
session_start();
header("Content-Type: application/json; charset=UTF-8");

// Verificar que los datos lleguen por POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Recibir datos (Postman -> Body -> form-data o x-www-form-urlencoded)
    $nombre    = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
    $email     = isset($_POST['email']) ? trim($_POST['email']) : '';
    $telefono  = isset($_POST['telefono']) ? trim($_POST['telefono']) : '';
    $direccion = isset($_POST['direccion']) ? trim($_POST['direccion']) : '';

    if ($nombre !== '' && $email !== '' && $telefono !== '' && $direccion !== '') {
        // Guardar en sesión como en ordenar.php real
        $_SESSION['nombre']    = $nombre;
        $_SESSION['email']     = $email;
        $_SESSION['telefono']  = $telefono;
        $_SESSION['direccion'] = $direccion;

        echo json_encode([
            "success" => true,
            "message" => "Datos de envío guardados correctamente",
            "datos" => [
                "nombre"    => $nombre,
                "email"     => $email,
                "telefono"  => $telefono,
                "direccion" => $direccion
            ]
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => " Por favor completa todos los campos requeridos."
        ]);
    }

} else {
    echo json_encode([
        "success" => false,
        "message" => " Acceso no permitido, usa método POST."
    ]);
}