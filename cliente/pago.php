<?php 
include("../conexion/conexion.php");
session_start();

$usuario_id = $_SESSION['usuario_id'] ?? null;
$nombre    = $_SESSION['nombre'] ?? '';
$email     = $_SESSION['email'] ?? '';
$telefono  = $_SESSION['telefono'] ?? '';
$direccion = $_SESSION['direccion'] ?? '';

// Validar que haya sesión
if (!$usuario_id) {
    echo "<p style='color:red; text-align:center;'>❌ Inicie sesión para continuar.</p>";
    exit;
}

// Traer carrito solo del usuario actual
$sql_carrito = "SELECT Nombre, Precio, Cantidad 
                FROM vm_carrito 
                WHERE ID_Usuario = ?";
$stmt = $conn->prepare($sql_carrito);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();

$total = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>PAGAR</title>
   <link rel="icon" href="IMG/favicon.png" type="image/png">

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="stylepagarordenarCli.css">
</head>
<body>
    <header>
        <nav class="barradenavegacion seccioncontenido">
            <div class="logo">
                <img src="IMG/img16.jpg.png" alt="img16" class="img16">
            </div>
            <ul class="menudenavegacion">
                <button id="menu-close-button" class="fas fa-times"></button>
                <li class="itemnav"><a href="pago.php" class="linknav">INICIO</a></li>
                <li class="itemnav"><a href="indexCli.html#seccionacercade" class="linknav">ACERCA DE</a></li>
                <li class="itemnav"><a href="indexCli.html#seccioncontacto" class="linknav">CONTACTO</a></li>
                <li class="itemnav"><a href="iniciarsesioncli.html" class="linknav">INGRESAR</a></li>
                <li class="itemnav"><a href="indexCli.html#secciontestimonio" class="linknav">TESTIMONIOS</a></li>
            </ul>
            <button id="menu-open-button" class="fas fa-bars"></button>
        </nav>
    </header>

    <div class="heading">
        <h3>VERIFICAR</h3>
        <p><a href="menuCli.php">MENÚ</a> <span> / VERIFICAR</span></p>
    </div>

    <section class="checkout">
        <h1 class="title">RESUMEN DEL PEDIDO</h1>

        <form action="procesarpago.php" method="post">
            <div class="cart-items" id="lista_productos">
                <h3>Artículos del carrito</h3>
                <?php while($row = $result->fetch_assoc()): 
                    $subtotal = $row['Precio'] * $row['Cantidad'];
                    $total += $subtotal; ?>
                    <p>
                        <span class="name"><?= htmlspecialchars($row['Nombre']); ?> (x<?= $row['Cantidad']; ?>)</span>
                        <span class="price">$<?= number_format($subtotal, 0, ',', '.'); ?></span>
                    </p>
                <?php endwhile; ?>
                <p class="grand-total">
                    <span class="name">TOTAL:</span>
                    <span class="price">$<?= number_format($total, 0, ',', '.'); ?></span>
                </p>
            </div>

            <div class="user-info" id="info_cliente">
                <h3>Su información</h3>
                <p><i class="fas fa-user"></i> <?= htmlspecialchars($nombre); ?></p>
                <p><i class="fas fa-phone"></i> <?= htmlspecialchars($telefono); ?></p>
                <p><i class="fas fa-envelope"></i> <?= htmlspecialchars($email); ?></p>

                <h3>Dirección de entrega</h3>
                <p><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($direccion); ?></p>
            </div>

            <select name="method" class="box" required>
                <option value="" disabled selected>Seleccione el método de pago</option>
                <option value="cash">Efectivo contra entrega</option>
                <option value="movil">Pago por móvil</option>
            </select>
            <input type="submit" value="PAGAR Y VERIFICAR" class="btn">
        </form>
    </section>
    <!-- JS -->
<script src="docindexCli.js"></script>
</body>
</html>