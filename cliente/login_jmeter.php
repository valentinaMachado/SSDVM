<?php
session_start();
include("../conexion/conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $_POST['Correo'] ?? '';
    $contrasena = $_POST['Contrasena'] ?? '';
    $tipousuario = $_POST['Tipo_usuario'] ?? '';

    $sql = "SELECT * FROM vm_usuario WHERE Email = ? AND Tipo_usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $correo, $tipousuario);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();

        if (password_verify($contrasena, $usuario['Contrasena'])) {
            // Login exitoso
            echo "✅ LOGIN CORRECTO - " . $usuario['Tipo_usuario'];
        } else {
            echo "❌ CONTRASEÑA INCORRECTA";
        }
    } else {
        echo "❌ USUARIO NO ENCONTRADO";
    }

    $stmt->close();
    $conn->close();
}
?>