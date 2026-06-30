<?php
require_once __DIR__ . '/../config/config.php';
if (isset($_SESSION['usuario_id'])) {
    header('Location: ../index.php');
    exit;
}
$token = $_GET['token'] ?? '';
if (!$token) {
    $_SESSION['mensaje'] = 'Token inválido.';
    $_SESSION['tipo_mensaje'] = 'danger';
    header('Location: login.php');
    exit;
}
$titulo = 'Restablecer Contraseña';
require_once __DIR__ . '/../includes/header.php';
?>
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow">
            <div class="card-header bg-primary text-white text-center">
                <h4><i class="bi bi-lock"></i> Restablecer Contraseña</h4>
            </div>
            <div class="card-body">
                <form action="../api/restablecer.php" method="POST">
                    <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
                    <div class="mb-3">
                        <label class="form-label">Nueva Contraseña</label>
                        <input type="password" name="password" class="form-control" minlength="6" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirmar Contraseña</label>
                        <input type="password" name="confirmar_password" class="form-control" minlength="6" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-check-circle"></i> Restablecer
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
