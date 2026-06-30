<?php
$conexion = new mysqli("127.0.0.1", "expositor", "exponemos123!!", "mi_bd");
$conexion->set_charset("utf8");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}
?>
