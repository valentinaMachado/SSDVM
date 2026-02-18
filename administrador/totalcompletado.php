
<?php
require_once("../conexion/conexion.php");

$sql = "SELECT COUNT(*) AS total FROM vm_orden WHERE estado = ?";
$stmt = $conn->prepare($sql);
$estado = "completado";
$stmt->bind_param("s", $estado);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();
echo $result['total'] ?? 0;

$stmt->close();
$conn->close();