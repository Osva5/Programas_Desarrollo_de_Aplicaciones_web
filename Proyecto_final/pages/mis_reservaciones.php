<?php
$titulo = 'Mis Reservaciones';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../classes/Reservacion.php';
require_once __DIR__ . '/../classes/Pago.php';
$reservacionModel = new Reservacion();
$pagoModel = new Pago();
$reservaciones = $reservacionModel->obtenerPorUsuario($_SESSION['usuario_id']);
require_once __DIR__ . '/../includes/header.php';
?>
<h2><i class="bi bi-calendar-check"></i> Mis Reservaciones</h2>
<hr>

<?php if (empty($reservaciones)): ?>
<div class="alert alert-info">
    <i class="bi bi-info-circle"></i> No tienes reservaciones activas.
    <a href="canchas.php" class="alert-link">Reserva una cancha ahora</a>.
</div>
<?php else: ?>
<div class="table-responsive">
    <table class="table table-hover table-striped">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Cancha</th>
                <th>Tipo</th>
                <th>Uso</th>
                <th>Fecha</th>
                <th>Horario</th>
                <th>Total</th>
                <th>Estado</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reservaciones as $r): ?>
            <tr>
                <td><?php echo $r['id']; ?></td>
                <td><?php echo htmlspecialchars($r['cancha_nombre']); ?></td>
                <td><span class="badge bg-info"><?php echo htmlspecialchars($r['cancha_tipo']); ?></span></td>
                <td><?php echo $r['tipo_uso'] ? '<span class="badge bg-secondary">' . htmlspecialchars($r['tipo_uso']) . '</span>' : '-'; ?></td>
                <td><?php echo date('d/m/Y', strtotime($r['fecha'])); ?></td>
                <td><?php echo substr($r['hora_inicio'], 0, 5); ?> - <?php echo substr($r['hora_fin'], 0, 5); ?></td>
                <td class="fw-bold">$<?php echo number_format($r['total'], 2); ?></td>
                <td>
                    <?php
                    $estados = [
                        'pendiente' => ['warning', 'Pendiente'],
                        'confirmada' => ['success', 'Confirmada'],
                        'cancelada' => ['danger', 'Cancelada'],
                        'completada' => ['secondary', 'Completada']
                    ];
                    $e = $estados[$r['estado']] ?? ['secondary', $r['estado']];
                    ?>
                    <span class="badge bg-<?php echo $e[0]; ?>"><?php echo $e[1]; ?></span>
                </td>
                <td>
                    <?php if ($r['estado'] === 'pendiente'): ?>
                    <a href="pago.php?reservacion_id=<?php echo $r['id']; ?>" class="btn btn-success btn-sm">
                        <i class="bi bi-credit-card"></i> Pagar
                    </a>
                    <a href="../api/cancelar_reservacion.php?id=<?php echo $r['id']; ?>"
                       class="btn btn-danger btn-sm"
                       onclick="return confirm('¿Cancelar esta reservación?')">
                        <i class="bi bi-x-circle"></i>
                    </a>
                    <?php endif; ?>
                    <?php if ($r['estado'] === 'confirmada'): ?>
                    <a href="pago.php?reservacion_id=<?php echo $r['id']; ?>&ver=1" class="btn btn-info btn-sm">
                        <i class="bi bi-receipt"></i> Ver Pago
                    </a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
