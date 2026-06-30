<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../classes/Reservacion.php';
require_once __DIR__ . '/../classes/Pago.php';
require_once __DIR__ . '/../classes/Historial.php';

$reservacionModel = new Reservacion();
$pagoModel = new Pago();
$historial = new Historial();
$id = $_GET['id'] ?? null;

if ($id) {
    $reservacion = $reservacionModel->obtenerPorId($id);
    if ($reservacion && ($reservacion['usuario_id'] == $_SESSION['usuario_id'] || esAdmin())) {
        $reservacionModel->actualizarEstado($id, 'cancelada');
        $historial->registrar($_SESSION['usuario_id'], 'Reservación cancelada', "Reservación #$id cancelada");
        $_SESSION['mensaje'] = 'Reservación cancelada exitosamente.';
        $_SESSION['tipo_mensaje'] = 'warning';
    } else {
        $_SESSION['mensaje'] = 'No tienes permiso para cancelar esta reservación.';
        $_SESSION['tipo_mensaje'] = 'danger';
    }
}
header('Location: ../pages/mis_reservaciones.php');
exit;
