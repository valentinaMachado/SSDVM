<?php
include '../conexion/conexion.php';
header("Content-Type: application/json; charset=UTF-8");

$response = ["success" => false, "message" => "Acción no válida"];
$formulario = "correo"; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar correo
    if (isset($_POST['correo']) && !isset($_POST['nueva'])) {
        $correo = $_POST['correo'];

        $stmt = $conn->prepare("SELECT ID_Usuario FROM vm_usuario WHERE Email=?");
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $result = $stmt->get_result();
        $usuario = $result->fetch_assoc();
        $stmt->close();

        if ($usuario) {
            $response = [
                "success" => true,
                "message" => "Correo válido. Puede actualizar la contraseña.",
                "ID_Usuario" => $usuario['ID_Usuario'],
                "step" => "nueva_contrasena"
            ];
        } else {
            $response = ["success" => false, "message" => "❌ El correo no está registrado."];
        }
    }

    // Guardar nueva contraseña
    if (isset($_POST['nueva']) && isset($_POST['confirmar']) && isset($_POST['id'])) {
        $id = $_POST['id'];
        $nueva = $_POST['nueva'];
        $confirmar = $_POST['confirmar'];

        if ($nueva !== $confirmar) {
            $response = ["success" => false, "message" => "❌ Las contraseñas no coinciden."];
        } else {
            $hash = password_hash($nueva, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE vm_usuario SET Contrasena=? WHERE ID_Usuario=?");
            $stmt->bind_param("si", $hash, $id);

            if ($stmt->execute()) {
                $response = [
                    "success" => true,
                    "message" => " Contraseña actualizada correctamente.",
                    "redirect" => "iniciarsesioncli.html"
                ];
            } else {
                $response = ["success" => false, "message" => "❌ Error al actualizar contraseña."];
            }
            $stmt->close();
        }
    }
}

echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);