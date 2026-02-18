<?php
include("../conexion/conexion.php"); // tu conexión a la BD

if (isset($_POST['AÑADIR_PRODUCTO'])) {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $categoria = $_POST['categoria'];

    // Carpeta del CLIENTE 
    $carpetaCliente = "../cliente/IMG/";

    
    $imagenNombre = time() . "_" . basename($_FILES["imagen"]["name"]);

    // Ruta final de la imagen
    $rutaImagen = $carpetaCliente . $imagenNombre;

    // Subir imagen
    if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $rutaImagen)) {
        
        // Guardar en BD (solo el nombre de la imagen)
        $sql = "INSERT INTO vm_producto (nombre, descripcion, precio, categoria, imagen)
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdss", $nombre, $descripcion, $precio, $categoria, $imagenNombre);

        if ($stmt->execute()) {
            echo "<script>alert('✅ Producto añadido correctamente'); window.location.href='productosadm.php';</script>";
        } else {
            echo "❌ Error al insertar producto: " . $conn->error;
        }
    } else {
        echo "❌ Error al subir la imagen.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>PRODUCTOS</title>
    <link rel="icon" href="IMG/favicon.png" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header class="header">
        <section class="flex">
            <img src="IMG/img2.jpg.png" alt="img2" class="img2">
            <nav class="navbar">
                <a href="paneladm.html">INICIO</a>
                <a href="productosadm.php">PRODUCTOS</a>
                <a href="pedidosadm.php">PEDIDOS</a>
            </nav>
            
            <div class="icons">
                <div id="menu-btn" class="fas fa-bars"></div>
                
            </div>
            
        </section>
    </header>

    <section class="add-products">
        <form action="" method="POST" enctype="multipart/form-data">
            <h3>AÑADIR PRODUCTO</h3>

            <input type="text" required placeholder="Introduzca el nombre" 
                name="nombre" maxlength="100" class="box">

            <textarea name="descripcion" required placeholder="Introduzca una descripción" 
                    class="box" maxlength="255"></textarea>

            <input type="number" min="0" max="9999999999" required 
                placeholder="Introduzca el precio" name="precio" class="box">

            <select name="categoria" class="box" required>
                <option value="" disabled selected>Seleccionar categoría</option>
                <option value="Comida rápida">Comida rápida</option>
                <option value="Bebidas">Bebidas</option>
            </select>

            <input type="file" name="imagen" class="box" accept="image/*" required>

            <input type="submit" value="Añadir producto" name="AÑADIR_PRODUCTO" class="btn">
        </form>
    </section>
    <script src="adm.js"></script>
</body>
</html>