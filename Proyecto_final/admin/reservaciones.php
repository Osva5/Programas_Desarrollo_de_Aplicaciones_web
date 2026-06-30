<?php
$titulo = 'Reservaciones';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/auth_check.php';
if (!esAdmin()) { header('Location: ../index.php'); exit; }
require_once __DIR__ . '/../classes/Reservacion.php';
require_once __DIR__ . '/../classes/Historial.php';
$reservacionModel = new Reservacion();
$historial = new Historial();

$filtroEstado = $_GET['estado'] ?? '';
$reservaciones = $reservacionModel->obtenerTodas();
if ($filtroEstado) {
    $reservaciones = array_filter($reservaciones, function($r) use ($filtroEstado) {
        return $r['estado'] === $filtroEstado;
    });
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';
    if ($accion === 'estado') {
        $reservacionModel->actualizarEstado($_POST['id'], $_POST['estado']);
        $historial->registrar($_SESSION['usuario_id'], 'Reservación actualizada', "Reservación #{$_POST['id']} -> {$_POST['estado']}");
        $_SESSION['mensaje'] = 'Estado de reservación actualizado.'; $_SESSION['tipo_mensaje'] = 'success';
    } elseif ($accion === 'eliminar') {
        $reservacionModel->eliminar($_POST['id']);
        $historial->registrar($_SESSION['usuario_id'], 'Reservación eliminada', "Reservación #{$_POST['id']} eliminada");
        $_SESSION['mensaje'] = 'Reservación eliminada.'; $_SESSION['tipo_mensaje'] = 'warning';
    }
    header('Location: reservaciones.php'); exit;
}

require_once __DIR__ . '/../includes/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3><i class="bi bi-list-check"></i> Todas las Reservaciones</h3>
    <form method="GET" class="d-flex gap-2">
        <select name="estado" class="form-select" onchange="this.form.submit()">
            <option value="">Todos los estados</option>
            <option value="pendiente" <?php echo $filtroEstado === 'pendiente' ? 'selected' : ''; ?>>Pendiente</option>
            <option value="confirmada" <?php echo $filtroEstado === 'confirmada' ? 'selected' : ''; ?>>Confirmada</option>
            <option value="completada" <?php echo $filtroEstado === 'completada' ? 'selected' : ''; ?>>Completada</option>
            <option value="cancelada" <?php echo $filtroEstado === 'cancelada' ? 'selected' : ''; ?>>Cancelada</option>
        </select>
    </form>
</div>

<div class="table-responsive">
    <table class="table table-hover table-striped">
        <thead class="table-dark">
            <tr><th>#</th><th>Usuario</th><th>Cancha</th><th>Uso</th><th>Fecha</th><th>Horario</th><th>Total</th><th>Estado</th><th>Reservado</th><th>Acciones</th></tr>
        </thead>
        <tbody>
            <?php foreach ($reservaciones as $r): ?>
            <tr>
                <td><?php echo $r['id']; ?></td>
                <td><?php echo htmlspecialchars($r['usuario_nombre']); ?><br><small class="text-muted"><?php echo $r['email']; ?></small></td>
                <td><?php echo htmlspecialchars($r['cancha_nombre']); ?></td>
                <td><?php echo $r['tipo_uso'] ? '<span class="badge bg-secondary">' . htmlspecialchars($r['tipo_uso']) . '</span>' : '-'; ?></td>
                <td><?php echo date('d/m/Y', strtotime($r['fecha'])); ?></td>
                <td><?php echo substr($r['hora_inicio'],0,5); ?> - <?php echo substr($r['hora_fin'],0,5); ?></td>
                <td class="fw-bold">$<?php echo number_format($r['total'],2); ?></td>
                <td>
                    <?php
                    $mapa = ['pendiente'=>'warning','confirmada'=>'success','cancelada'=>'danger','completada'=>'secondary'];
                    ?>
                    <span class="badge bg-<?php echo $mapa[$r['estado']] ?? 'secondary'; ?>"><?php echo ucfirst($r['estado']); ?></span>
                </td>
                <td><small><?php echo date('d/m/Y H:i', strtotime($r['fecha_reservacion'])); ?></small></td>
                <td>
                    <form method="POST" style="display:inline">
                        <input type="hidden" name="accion" value="estado">
                        <input type="hidden" name="id" value="<?php echo $r['id']; ?>">
                        <select name="estado" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">Cambiar a...</option>
                            <option value="pendiente">Pendiente</option>
                            <option value="confirmada">Confirmada</option>
                            <option value="completada">Completada</option>
                            <option value="cancelada">Cancelada</option>
                        </select>
                    </form>
                    <form method="POST" style="display:inline" onsubmit="return confirm('¿Eliminar reservación #<?php echo $r['id']; ?>?')">
                        <input type="hidden" name="accion" value="eliminar">
                        <input type="hidden" name="id" value="<?php echo $r['id']; ?>">
                        <button class="btn btn-sm btn-danger mt-1"><i class="bi bi-trash"></i></button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($reservaciones)): ?>
            <tr><td colspan="9" class="text-center text-muted">No hay reservaciones</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
