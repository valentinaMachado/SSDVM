<?php
session_start();

$pedido_id = $_GET['pedido_id'] ?? 'Desconocido';

// Destruir sesión después de mostrar el mensaje
session_destroy();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gracias por su compra</title>
    <link rel="icon" href="IMG/favicon.png" type="image/png">
    <meta http-equiv="refresh" content="5;url=index.html">
    <style>
        body { font-family: Arial, sans-serif; text-align: center; margin-top: 100px; }
        .mensaje { background: #f8f8f8; display: inline-block; padding: 30px; border-radius: 8px; }
    </style>
</head>
<body>
    <div class="mensaje">
        <h1>🎉 ¡Gracias por su compra!</h1>
        <p>Su número de pedido es: <strong><?php echo htmlspecialchars($pedido_id); ?></strong></p>
        <p>Será redirigido en unos segundos</p>
    </div>
</body>
</html>