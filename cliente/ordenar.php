<?php 
session_start();

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

        // Redirigir a la página de pago
        header("Location: pago.php");
        exit();
    } else {
        echo "❌ Por favor completa todos los campos requeridos.";
    }

} else {
    echo "❌ Acceso no permitido.";
}
?>