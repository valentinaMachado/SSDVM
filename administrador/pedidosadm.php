
<?php
$conn = include("../conexion/conexion.php");

// Procesar actualización de estado
if (isset($_POST['update_payment'])) {
    $order_id = $_POST['order_id'];
    $estado = $_POST['payment_status'];

    $update = "UPDATE vm_orden SET Estado='$estado' WHERE ID_Pedido='$order_id'";
    $conn->query($update);
}

// Obtener pedidos
$sql = "SELECT * FROM vm_orden ORDER BY Fecha DESC";
$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>PEDIDOS</title>
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

<section class="placed-orders">
    <h1 class="heading">PEDIDOS REALIZADOS</h1>
    <div class="box-container">

    <?php
    if ($resultado->num_rows > 0) {
        while ($row = $resultado->fetch_assoc()) {
            echo '
            <div class="box">
                <p><strong>ID Pedido:</strong> ' . $row['ID_Pedido'] . '</p>
                <p><strong>ID Usuario:</strong> ' . $row['ID_Usuario'] . '</p>
                <p><strong>Nombre:</strong> ' . $row['Nombre'] . '</p>
                <p><strong>Email:</strong> ' . $row['Email'] . '</p>
                <p><strong>Teléfono:</strong> ' . $row['Telefono'] . '</p>
                <p><strong>Dirección:</strong> ' . $row['Direccion'] . '</p>
                <p><strong>Fecha:</strong> ' . $row['Fecha'] . '</p>
                <p><strong>Total pedido:</strong> $' . number_format($row['Total'], 2) . '</p>
                <p><strong>Estado actual:</strong> ' . $row['Estado'] . '</p>
                
                <p><strong>Productos del pedido:</strong></p>
                <ul>';
                
                $id_pedido = $row['ID_Pedido'];
                $sql_productos = "SELECT * FROM vm_pedidodetalle WHERE ID_Pedido = $id_pedido";
                $productos = $conn->query($sql_productos);

                if ($productos->num_rows > 0) {
                    while ($prod = $productos->fetch_assoc()) {
                        $subtotal = $prod['Precio'] * $prod['Cantidad'];
                        echo "<li>" . $prod['NombreProducto'] . 
                             " - Cantidad: " . $prod['Cantidad'] . 
                             " - Precio unitario: $" . number_format($prod['Precio'], 2) . 
                             " - Subtotal: $" . number_format($subtotal, 2) . "</li>";
                    }
                } else {
                    echo "<li>No hay productos para este pedido.</li>";
                }

            echo '</ul>

                <form method="POST">
                    <input type="hidden" name="order_id" value="' . $row['ID_Pedido'] . '"/>
                    <select name="payment_status" class="drop-down">
                        <option value="Pendiente"' . ($row['Estado'] == 'Pendiente' ? ' selected' : '') . '>PENDIENTE</option>
                        <option value="Completado"' . ($row['Estado'] == 'Completado' ? ' selected' : '') . '>COMPLETADO</option>
                    </select>
                    <div class="flex-btn">
                        <input type="submit" name="update_payment" class="btn" value="ACTUALIZAR">
                        <a href="borrarpedido.php?id=' . $row['ID_Pedido'] . '" class="delete-btn" onclick="return confirm(\'¿Estás seguro de borrar este pedido?\')">BORRAR</a>
                    </div>
                </form>
            </div>';
        }
    } else {
        echo "<p>No hay pedidos registrados.</p>";
    }

    $conn->close();
    ?>

    </div>
</section>
 <script src="adm.js"></script>
</body>
</html>