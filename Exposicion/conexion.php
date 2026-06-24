<?php
$conexion = new mysqli("localhost", "root", "", "mi_bd");
$conexion->set_charset("utf8");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}
?>
