<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../classes/Horario.php';

$canchaId = $_GET['cancha_id'] ?? null;
$fecha = $_GET['fecha'] ?? null;

if (!$canchaId || !$fecha) {
    echo json_encode([]);
    exit;
}

$horarioModel = new Horario();
$horarios = $horarioModel->obtenerDisponibles($canchaId, $fecha);

$hoy = date('Y-m-d');
$horaActual = (int)date('H');

if ($fecha === $hoy) {
    $horarios = array_filter($horarios, function ($h) use ($horaActual) {
        return (int)substr($h['hora_inicio'], 0, 2) >= $horaActual;
    });
    $horarios = array_values($horarios);
}

echo json_encode($horarios);
