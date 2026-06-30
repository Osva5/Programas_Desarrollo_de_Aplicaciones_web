<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../classes/Reservacion.php';
require_once __DIR__ . '/../classes/Cancha.php';
require_once __DIR__ . '/../classes/Historial.php';

$reservacionModel = new Reservacion();
$canchaModel = new Cancha();
$historial = new Historial();

$canchaId = $_POST['cancha_id'] ?? null;
$fecha = $_POST['fecha'] ?? null;
$horaInicio = $_POST['hora_inicio'] ?? null;
$horaFin = $_POST['hora_fin'] ?? null;
$precioHora = $_POST['precio_por_hora'] ?? 0;
$tipoUso = $_POST['tipo_uso'] ?? '';
$observaciones = $_POST['observaciones'] ?? '';

if (!$canchaId || !$fecha || !$horaInicio) {
    $_SESSION['mensaje'] = 'Faltan datos requeridos.';
    $_SESSION['tipo_mensaje'] = 'danger';
    header('Location: ../pages/reservar.php?cancha_id=' . $canchaId);
    exit;
}

$hoy = date('Y-m-d');
$horaActual = date('H:i:s');

if ($fecha < $hoy) {
    $_SESSION['mensaje'] = 'No se pueden hacer reservaciones en fechas pasadas.';
    $_SESSION['tipo_mensaje'] = 'danger';
    header('Location: ../pages/reservar.php?cancha_id=' . $canchaId);
    exit;
}
if ($fecha === $hoy && $horaInicio < $horaActual) {
    $_SESSION['mensaje'] = 'No se pueden reservar horarios que ya pasaron.';
    $_SESSION['tipo_mensaje'] = 'danger';
    header('Location: ../pages/reservar.php?cancha_id=' . $canchaId);
    exit;
}

$cancha = $canchaModel->obtenerPorId($canchaId);
if (!$cancha || $cancha['estado'] !== 'disponible') {
    $_SESSION['mensaje'] = 'La cancha no está disponible.';
    $_SESSION['tipo_mensaje'] = 'danger';
    header('Location: ../pages/canchas.php');
    exit;
}

if (!$reservacionModel->verificarDisponibilidad($canchaId, $fecha, $horaInicio, $horaFin)) {
    $_SESSION['mensaje'] = 'El horario seleccionado ya está reservado.';
    $_SESSION['tipo_mensaje'] = 'danger';
    header('Location: ../pages/reservar.php?cancha_id=' . $canchaId);
    exit;
}

$total = floatval($precioHora);
$horaFin = $horaFin ?: date('H:i:s', strtotime($horaInicio) + 3600);

$reservacionId = $reservacionModel->crear([
    'usuario_id' => $_SESSION['usuario_id'],
    'cancha_id' => $canchaId,
    'fecha' => $fecha,
    'hora_inicio' => $horaInicio,
    'hora_fin' => $horaFin,
    'tipo_uso' => $tipoUso,
    'total' => $total,
    'observaciones' => $observaciones
]);

$historial->registrar($_SESSION['usuario_id'], 'Reservación creada', "Reservación #$reservacionId - {$cancha['nombre']} - $fecha $horaInicio");

$_SESSION['mensaje'] = 'Reservación creada exitosamente. Procede al pago para confirmarla.';
$_SESSION['tipo_mensaje'] = 'success';
header('Location: ../pages/pago.php?reservacion_id=' . $reservacionId);
exit;
