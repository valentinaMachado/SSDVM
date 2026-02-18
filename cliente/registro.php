
<?php
include("../conexion/conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    //trear datos del formulario registro 
    $nombre = $_POST['Nombre'];
    $correo = $_POST['Correo'];
    $contrasena = password_hash($_POST['Password'], PASSWORD_DEFAULT);
    $tipo_usuario = $_POST['tipo_usuario'];

    //insertar datos en Bd
    $sql = "INSERT INTO vm_usuario (Tipo_usuario, Nombre, Email, Contrasena) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $tipo_usuario, $nombre, $correo, $contrasena);


    if ($stmt->execute()) {
        //"Usuario registrado con éxito.";
        header("Location:Iniciarsesioncli.html");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}   
?>