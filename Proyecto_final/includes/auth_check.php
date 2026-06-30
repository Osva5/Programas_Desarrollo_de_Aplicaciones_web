<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['usuario_id'])) {
    $_SESSION['mensaje'] = 'Debe iniciar sesión para acceder a esta página.';
    $_SESSION['tipo_mensaje'] = 'warning';
    header('Location: ../pages/login.php');
    exit;
}

function esAdmin() {
    return isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === 'admin';
}
