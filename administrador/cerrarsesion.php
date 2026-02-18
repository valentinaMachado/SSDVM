
<?php
session_start();

// Destruir variables de sesión
$_SESSION = array();

// Eliminar cookies 
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// destruir la sesión
session_destroy();

// Redirigir a iniciar sesion 
header("Location: Iniciarsesioncli.html");
exit;
?>
