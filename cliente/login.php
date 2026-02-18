<?php
session_start();
include("../conexion/conexion.php");
 //traer datos del formuluario iniciar sesion
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $_POST['Correo'];
    $contrasena = $_POST['Contrasena'];
    $tipousuario = $_POST['Tipo_usuario']; 

    //consultar en Bd

    $sql = "SELECT * FROM vm_usuario WHERE Email = ? AND Tipo_usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $correo, $tipousuario);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();

        //verificar contraseña

        if (password_verify($contrasena, $usuario['Contrasena'])) {

            //Guardar datos en la sesion
            $_SESSION['usuario_id'] = $usuario['ID_Usuario'];
            $_SESSION['tipo_usuario'] = $usuario['Tipo_usuario'];
            $_SESSION['nombre'] = $usuario['Nombre'];
            $_SESSION['correo'] = $usuario['Email'];

            // Redirigir según tipo de usuario
            if ($usuario['Tipo_usuario'] === 'ADMINISTRADOR') {
                header("Location: ../administrador/paneladm.html"); //administrador 
                exit; 
            } else {
                header("Location: menuCli.php"); //cliente 
            }
        } else {
            echo "❌ Contraseña incorrecta.";
        }
    } else {
        echo "❌ No existe una cuenta registrada con ese correo.";
    }

    $stmt->close();
    $conn->close();
}
?>