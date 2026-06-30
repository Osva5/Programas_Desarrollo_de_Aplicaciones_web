<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/classes/Mailer.php';

$resultados = [];
$destinatarios = ['dantecc10@gmail.com', 'dante@castelancarpinteyro.com'];

// Prueba 1: función fsockopen disponible
$resultados[] = 'fsockopen: ' . (function_exists('fsockopen') ? 'disponible' : 'NO DISPONIBLE');

// Prueba 2: conexión SMTP
$conn = @fsockopen('tcp://' . MAIL_HOST, MAIL_PORT, $errno, $errstr, 10);
if ($conn) {
    $banner = fgets($conn, 515);
    fclose($conn);
    $resultados[] = "Conexión a " . MAIL_HOST . ":" . MAIL_PORT . ": OK ($banner)";
} else {
    $resultados[] = "Conexión a " . MAIL_HOST . ":" . MAIL_PORT . ": FALLÓ - $errstr";
}

$mailer = new Mailer();

// Prueba 3: enviar a cada destinatario de prueba
foreach ($destinatarios as $dest) {
    $r = $mailer->enviar($dest, 'Prueba SMTP desde TU CANCHA', '<h2>Prueba</h2><p>Este correo se envió desde el servidor web a <strong>' . htmlspecialchars($dest) . '</strong>.</p>');
    $resultados[] = 'Envío a ' . htmlspecialchars($dest) . ': ' . ($r ? 'OK' : 'FALLÓ');
}

// Prueba 4: método bienvenida a cada destinatario
foreach ($destinatarios as $dest) {
    $r2 = $mailer->bienvenida('Usuario Prueba', $dest);
    $resultados[] = 'bienvenida() a ' . htmlspecialchars($dest) . ': ' . ($r2 ? 'OK' : 'FALLÓ');
}

// Prueba 5: método confirmacionReservacion a cada destinatario
foreach ($destinatarios as $dest) {
    $r3 = $mailer->confirmacionReservacion('Usuario Prueba', $dest, 'Cancha Fútbol 1', date('Y-m-d'), '10:00:00', '11:00:00', 350.00, 'PAG-TEST-' . uniqid());
    $resultados[] = 'confirmacionReservacion() a ' . htmlspecialchars($dest) . ': ' . ($r3 ? 'OK' : 'FALLÓ');
}

echo '<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><title>Test Mail</title>';
echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">';
echo '</head><body class="bg-light p-5"><div class="container"><div class="card shadow-sm"><div class="card-header bg-primary text-white"><h3>Resultados prueba de correo</h3></div><div class="card-body"><ul class="list-group">';
foreach ($resultados as $r) {
    $ok = str_contains($r, 'OK') || str_contains($r, 'disponible');
    echo '<li class="list-group-item d-flex align-items-center gap-2">';
    echo $ok ? '<span class="text-success fs-5">&#10003;</span>' : '<span class="text-danger fs-5">&#10007;</span>';
    echo htmlspecialchars($r) . '</li>';
}
echo '</ul>';
echo '<hr><p><strong>Remitente:</strong> ' . htmlspecialchars(MAIL_FROM) . ' (' . htmlspecialchars(MAIL_FROM_NAME) . ')</p>';
echo '<p><strong>Destinatarios de prueba:</strong> ' . implode(', ', array_map('htmlspecialchars', $destinatarios)) . '</p>';
echo '<p class="text-muted small">Revisa las bandejas de los destinatarios. Si ves los correos en ' . htmlspecialchars(MAIL_FROM) . ', el SMTP está funcionando pero el servidor no entrega a externos (relay restringido).</p>';
echo '<a href="test_mail.php" class="btn btn-primary mt-2">Re-ejecutar prueba</a>';
echo '</div></div></div></body></html>';
