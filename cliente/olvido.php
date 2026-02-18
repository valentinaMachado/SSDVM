<?php
include '../conexion/conexion.php';
$mensaje = "";
$formulario = "correo"; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //  validar correo
    if (isset($_POST['correo']) && !isset($_POST['nueva'])) {
        $correo = $_POST['correo'];

        $stmt = $conn->prepare("SELECT ID_Usuario FROM vm_usuario WHERE Email=?");
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $result = $stmt->get_result();
        $usuario = $result->fetch_assoc();
        $stmt->close();

        if ($usuario) {
            $formulario = "nueva"; // formulario de nueva contraseña
            $id = $usuario['ID_Usuario'];
        } else {
            $mensaje = "❌ El correo no está registrado.";
        }
    }

    //  guardar nueva contraseña
    if (isset($_POST['nueva']) && isset($_POST['confirmar'])) {
        $id = $_POST['id'];
        $nueva = $_POST['nueva'];
        $confirmar = $_POST['confirmar'];

        if ($nueva !== $confirmar) {
            $mensaje = "❌ Las contraseñas no coinciden.";
            $formulario = "nueva";
        } else {
            $hash = password_hash($nueva, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE vm_usuario SET Contrasena=? WHERE ID_Usuario=?");
            $stmt->bind_param("si", $hash, $id);
            $stmt->execute();
            $stmt->close();

            $mensaje = "✅ Contraseña actualizada correctamente. <a href='Iniciarsesioncli.html'>Inicia sesión aquí</a>";
            $formulario = "ninguno"; 
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Restablecer Contraseña</title>
   <link rel="icon" href="IMG/favicon.png" type="image/png">
   <link rel="stylesheet" href="styleiniciosesion.css">
</head>
<body>
   <section class="form-container">

      <?php if ($mensaje): ?>
         <?php if (strpos($mensaje, '✅') !== false): ?>
            <div style="
               display:block;
               padding:15px 20px;
               margin:20px auto;
               width:90%;
               max-width:480px;
               border-radius:10px;
               text-align:center;
               font-weight:600;
               font-size:15px;
               background:#e6ffed;
               color:#2e7d32;
               border-left:6px solid #28a745;
               box-shadow:0 3px 8px rgba(0,0,0,0.2);
            ">
               <?= $mensaje ?>
            </div>
         <?php else: ?>
            <div style="
               display:block;
               padding:15px 20px;
               margin:20px auto;
               width:90%;
               max-width:480px;
               border-radius:10px;
               text-align:center;
               font-weight:600;
               font-size:15px;
               background:#fdecea;
               color:#b71c1c;
               border-left:6px solid #dc3545;
               box-shadow:0 3px 8px rgba(0,0,0,0.2);
            ">
               <?= $mensaje ?>
            </div>
         <?php endif; ?>
      <?php endif; ?>

      <?php if ($formulario === "correo"): ?>
      <!--  Ingresar correo -->
      <form action="" method="POST">
         <h3>¿Olvidaste tu contraseña?</h3>
         <input type="email" name="correo" maxlength="50" required placeholder="Ingrese su correo" class="box">
         <input type="submit" value="Continuar" class="btn">
      </form>
      <?php endif; ?>

      <?php if ($formulario === "nueva"): ?>
      <!--  Crear nueva contraseña -->
      <form action="" method="POST">
         <h3>Crear nueva contraseña</h3>
         <input type="hidden" name="id" value="<?= $id ?>">
         <input type="password" name="nueva" maxlength="20" required placeholder="Nueva contraseña" class="box">
         <input type="password" name="confirmar" maxlength="20" required placeholder="Confirmar contraseña" class="box">
         <input type="submit" value="Actualizar" class="btn">
      </form>
      <?php endif; ?>

   </section>
</body>
</html>