
<?php
session_start();

// Siempre devolver JSON
header('Content-Type: application/json');

// Verificar que los datos vengan por POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Validar que los campos estén definidos 
    if (
        isset($_POST['nombre'], $_POST['email'], $_POST['telefono'], $_POST['direccion']) &&
        !empty(trim($_POST['nombre'])) &&
        !empty(trim($_POST['email'])) &&
        !empty(trim($_POST['telefono'])) &&
        !empty(trim($_POST['direccion']))
    ) {
        // Guardar los datos de envío en la sesión
        $_SESSION['nombre']    = trim($_POST['nombre']);
        $_SESSION['email']     = trim($_POST['email']);
        $_SESSION['telefono']  = trim($_POST['telefono']);
        $_SESSION['direccion'] = trim($_POST['direccion']);

        echo json_encode([
            "status" => "success",
            "message" => " Datos de envío guardados correctamente",
            "datos" => [
                "nombre" => $_SESSION['nombre'],
                "email" => $_SESSION['email'],
                "telefono" => $_SESSION['telefono'],
                "direccion" => $_SESSION['direccion']
            ]
        ]);
        exit();
    } else {
        echo json_encode([
            "status" => "error",
            "message" => " Por favor completa todos los campos requeridos."
        ]);
    }

} else {
    echo json_encode([
        "status" => "error",
        "message" => " Acceso no permitido"
    ]);
}