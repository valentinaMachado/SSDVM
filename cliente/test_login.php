<?php
header("Content-Type: application/json");
session_start();
include("../conexion/conexion.php"); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capturar los datos del formulario 
    $correo = $_POST['Correo'] ?? '';
    $contrasena = $_POST['Password'] ?? ''; 
    $tipousuario = $_POST['Tipo_usuario'] ?? '';

    // Verificar conexión
    if (!$conn) {
        echo json_encode([
            "success" => false,
            "mensaje" => "Error: no se pudo conectar con la base de datos"
        ]);
        exit;
    }

    // Preparar consulta para buscar usuario
    $sql = "SELECT * FROM vm_usuario WHERE Email = ? AND Tipo_usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $correo, $tipousuario);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();

        // Verificar la contraseña encriptada
        if (password_verify($contrasena, $usuario['Contrasena'])) {
            // Crear sesión
            $_SESSION['usuario_id'] = $usuario['ID_Usuario'];
            $_SESSION['tipo_usuario'] = $usuario['Tipo_usuario'];
            $_SESSION['nombre'] = $usuario['Nombre'];
            $_SESSION['correo'] = $usuario['Email'];

            echo json_encode([
                "success" => true,
                "mensaje" => "Inicio de sesión exitoso",
                "usuario" => [
                    "id" => $usuario['ID_Usuario'],
                    "nombre" => $usuario['Nombre'],
                    "correo" => $usuario['Email'],
                    "tipo" => $usuario['Tipo_usuario']
                ]
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "mensaje" => "Contraseña incorrecta"
            ]);
        }
    } else {
        echo json_encode([
            "success" => false,
            "mensaje" => "No existe una cuenta registrada con ese correo o tipo de usuario"
        ]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode([
        "success" => false,
        "mensaje" => "Método no permitido, usa POST"
    ]);
}
?>