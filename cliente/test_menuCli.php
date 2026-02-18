<?php
header('Content-Type: application/json');
include("../conexion/conexion.php");

// Recibir datos desde Postman
$action = isset($_POST['action']) ? $_POST['action'] : '';
$usuario_id = isset($_POST['usuario_id']) ? intval($_POST['usuario_id']) : 0;
$nombre_buscar = isset($_POST['nombre']) ? $_POST['nombre'] : '';

// Inicializar respuesta
$respuesta = ["status" => "error", "mensaje" => "Acción no válida"];

if ($action === "listar_productos") {
    $comidas = [];
    $bebidas = [];

    $sql = "SELECT ID_Producto, Nombre, Descripcion, Precio, Categoria, Imagen 
            FROM vm_producto
            ORDER BY Fecha_creacion DESC";
    $res = $conn->query($sql);

    if ($res && $res->num_rows > 0) {
        while ($row = $res->fetch_assoc()) {
            $cat = strtolower(trim($row['Categoria']));
            $esBebida = in_array($cat, ['bebida','bebidas','drink','drinks','coctel','cocteles']);
            $producto = [
                "id" => (int)$row['ID_Producto'],
                "nombre" => $row['Nombre'],
                "descripcion" => $row['Descripcion'],
                "precio" => (float)$row['Precio'],
                "categoria" => $row['Categoria'],
                "imagen" => $row['Imagen']
            ];
            if ($esBebida) { $bebidas[] = $producto; } else { $comidas[] = $producto; }
        }
    }

    $respuesta = ["status" => "success", "comidas" => $comidas, "bebidas" => $bebidas];

} elseif ($action === "buscar_producto" && !empty($nombre_buscar)) {
    $stmt = $conn->prepare("SELECT ID_Producto, Nombre, Descripcion, Precio, Categoria, Imagen 
                            FROM vm_producto 
                            WHERE Nombre LIKE ?");
    $like = "%$nombre_buscar%";
    $stmt->bind_param("s", $like);
    $stmt->execute();
    $res = $stmt->get_result();

    $resultados = [];
    while ($row = $res->fetch_assoc()) {
        $resultados[] = [
            "id" => (int)$row['ID_Producto'],
            "nombre" => $row['Nombre'],
            "descripcion" => $row['Descripcion'],
            "precio" => (float)$row['Precio'],
            "categoria" => $row['Categoria'],
            "imagen" => $row['Imagen']
        ];
    }

    $respuesta = ["status" => "success", "resultados" => $resultados];

} elseif ($action === "agregar_carrito" && isset($_POST['producto_id'], $_POST['cantidad'])) {
    $producto_id = intval($_POST['producto_id']);
    $cantidad = intval($_POST['cantidad']);

    // Traer datos del producto
    $stmt = $conn->prepare("SELECT Nombre, Precio FROM vm_producto WHERE ID_Producto=?");
    $stmt->bind_param("i", $producto_id);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res && $res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $subtotal = $row['Precio'] * $cantidad;
        // Para prueba, solo devolvemos el carrito simulado
        $respuesta = [
            "status" => "success",
            "mensaje" => "Producto agregado al carrito",
            "carrito" => [
                [
                    "producto_id" => $producto_id,
                    "nombre" => $row['Nombre'],
                    "cantidad" => $cantidad,
                    "precio_unitario" => (float)$row['Precio'],
                    "subtotal" => (float)$subtotal
                ]
            ]
        ];
    } else {
        $respuesta = ["status" => "error", "mensaje" => "Producto no encontrado"];
    }
}

echo json_encode($respuesta, JSON_PRETTY_PRINT);
?>