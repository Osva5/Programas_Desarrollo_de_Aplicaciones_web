<?php
require_once __DIR__ . '/../config/config.php';
if (isset($_SESSION['usuario_id'])) {
    header('Location: ../index.php');
    exit;
}
$titulo = 'Registro';
require_once __DIR__ . '/../includes/header.php';
?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-header bg-success text-white text-center">
                <h4><i class="bi bi-person-plus"></i> Crear Cuenta</h4>
            </div>
            <div class="card-body">
                <form action="../api/registro.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Nombre Completo</label>
                        <input type="text" name="nombre" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Correo Electrónico</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Teléfono</label>
                        <input type="text" name="telefono" class="form-control" placeholder="555-1234">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contraseña</label>
                        <input type="password" name="password" class="form-control" minlength="6" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirmar Contraseña</label>
                        <input type="password" name="confirmar_password" class="form-control" minlength="6" required>
                    </div>
                    <button type="submit" class="btn btn-success w-100">
                        <i class="bi bi-person-plus"></i> Registrarse
                    </button>
                </form>
                <div class="mt-3 text-center">
                    <a href="login.php">¿Ya tienes cuenta? Inicia sesión</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
