<?php
// Conexión
include("../conexion/conexion.php");

// Traer productos
$comidas = [];
$bebidas = [];

$sql = "SELECT ID_Producto, Nombre, Descripcion, Precio, Categoria, Imagen 
        FROM vm_producto
        ORDER BY Fecha_creacion DESC";

$res = $conn->query($sql);

if ($res && $res->num_rows > 0) {

    while ($row = $res->fetch_assoc()) {

        $cat = strtolower(trim($row['Categoria']));

        $esBebida = in_array($cat, [
            'bebida','bebidas','drink','drinks','coctel','cocteles'
        ]);

        if ($esBebida) {
            $bebidas[] = $row;
        } else {
            $comidas[] = $row;
        }
    }
}


//  ruta imagen
function rutaImg($nombreArchivo){

    $rutaClienteAbs = __DIR__ . "/IMG/" . $nombreArchivo;

    if (is_file($rutaClienteAbs)) {
        return "IMG/" . $nombreArchivo;
    }

    return "../administrador/IMG/" . $nombreArchivo;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Sistema de Servicio a Domicilio</title>
    <link rel="icon" href="IMG/favicon.png" type="image/png">

    <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"/>
    <link rel="stylesheet" href="stylemenucli.css"/>

</head>
<body>

<header class="header">

    <img class="logo" src="IMG/img16.jpg.png">

    <nav class="barradenavegacion">

        <a href="menuCli.php">INICIO</a>
        <a href="#comidasrapidas">COMIDAS RÁPIDAS</a>
        <a href="#bebidas">BEBIDAS</a>
        <a href="indexCli.html#seccioncontacto">CONTACTO</a>
        <a href="indexCli.html#secciontestimonio">TESTIMONIOS</a>

    </nav>

    <div class="iconos">

        <div class="fas fa-bars" id="menu-btn"></div>
        <div class="fas fa-search" id="search-btn"></div>
        <div class="fas fa-shopping-cart" id="cart-btn"></div>

    </div>

    <form action="" class="search-form">

        <input type="search" id="search-box" placeholder="Buscar...">

        <label for="search-box" class="fas fa-search"></label>

    </form>

    <div class="shopping-cart"></div>

</header>


<section class="principal">
    <h3>MENÚ CHARCUTERÍA CANAÁN</h3>
</section>


<!-- COMIDAS -->
<section class="comidas" id="comidasrapidas">

    <h1 class="titulo"> COMIDAS <span>RÁPIDAS</span> </h1>

    <div class="swiper comidasslider">

        <div class="swiper-wrapper">

        <?php if (count($comidas) > 0): ?>

            <?php foreach ($comidas as $p): 
                $img = rutaImg($p['Imagen']); ?>

                <div class="swiper-slide box">

                    <img src="<?php echo $img; ?>">

                    <h3><?php echo strtoupper(htmlspecialchars($p['Nombre'])); ?></h3>

                    <p><?php echo nl2br(htmlspecialchars($p['Descripcion'])); ?></p>

                    <div class="precio">
                        $<?php echo number_format($p['Precio'],0,',','.'); ?>
                    </div>

                    <div class="estrellas">

                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>

                    </div>

                    <button class="boton1"
                        onclick="agregarAlCarrito(
                            '<?php echo htmlspecialchars($p['Nombre'],ENT_QUOTES); ?>',
                            <?php echo $p['Precio']; ?>,
                            '<?php echo $img; ?>',
                            <?php echo $p['ID_Producto']; ?>
                        )">

                        AGREGAR AL CARRITO

                    </button>

                </div>

            <?php endforeach; ?>

        <?php else: ?>

            <div class="swiper-slide box">
                <h3>Pronto añadiremos comidas</h3>
            </div>

        <?php endif; ?>

        </div>

    </div>

</section>


<!-- BEBIDAS -->
<section class="bebidas" id="bebidas">

    <h1 class="titulo2"> BEBIDAS <span>COCTELES</span> </h1>

    <div class="swiper bebidasslider">

        <div class="swiper-wrapper">

        <?php if (count($bebidas) > 0): ?>

            <?php foreach ($bebidas as $p): 
                $img = rutaImg($p['Imagen']); ?>

                <div class="swiper-slide box">

                    <img src="<?php echo $img; ?>">

                    <h3><?php echo strtoupper(htmlspecialchars($p['Nombre'])); ?></h3>

                    <p><?php echo nl2br(htmlspecialchars($p['Descripcion'])); ?></p>

                    <div class="precio">
                        $<?php echo number_format($p['Precio'],0,',','.'); ?>
                    </div>

                    <div class="estrellas">

                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>

                    </div>

                    <button class="boton1"
                        onclick="agregarAlCarrito(
                            '<?php echo htmlspecialchars($p['Nombre'],ENT_QUOTES); ?>',
                            <?php echo $p['Precio']; ?>,
                            '<?php echo $img; ?>',
                            <?php echo $p['ID_Producto']; ?>
                        )">

                        AGREGAR AL CARRITO

                    </button>

                </div>

            <?php endforeach; ?>

        <?php else: ?>

            <div class="swiper-slide box">
                <h3>Pronto añadiremos bebidas</h3>
            </div>

        <?php endif; ?>

        </div>

    </div>

</section>


<!-- LIMPIAR CARRITO LOCAL -->
<script>
    localStorage.removeItem("carrito");
</script>


<script src="https://unpkg.com/swiper@7/swiper-bundle.min.js"></script>
<script src="carrito.js"></script>
<script src="docmenuCli.js"></script>

</body>
</html>