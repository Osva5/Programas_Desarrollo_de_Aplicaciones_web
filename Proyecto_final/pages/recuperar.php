<?php
require_once __DIR__ . '/../config/config.php';
if (isset($_SESSION['usuario_id'])) {
    header('Location: ../index.php');
    exit;
}
$titulo = 'Recuperar Contraseña';
require_once __DIR__ . '/../includes/header.php';
?>
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow">
            <div class="card-header bg-primary text-white text-center">
                <h4><i class="bi bi-key"></i> Recuperar Contraseña</h4>
            </div>
            <div class="card-body">
                <p class="text-muted">Ingresa tu correo electrónico y te enviaremos un enlace para restablecer tu contraseña.</p>
                <form action="../api/recuperar.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Correo Electrónico</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-send"></i> Enviar Enlace
                    </button>
                </form>
                <div class="mt-3 text-center">
                    <a href="login.php">Volver a Iniciar Sesión</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
