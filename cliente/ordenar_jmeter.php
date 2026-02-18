<?php
// Simula el envío de datos al endpoint real
$url = "http://localhost/cliente/ordenar.php"; 

$data = [
    'nombre' => 'Cliente Prueba',
    'email' => 'cliente'.rand(1,999).'@gmail.com',
    'telefono' => '3001234567',
    'direccion' => 'Calle '.rand(10,99).' #'.rand(1,20).'-'.rand(1,50)
];

$options = [
    'http' => [
        'header'  => "Content-Type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data)
    ]
];

$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);

if ($result === FALSE) {
    echo "❌ Error al enviar datos.";
} else {
    echo "✅ Solicitud enviada correctamente.";
}
?>