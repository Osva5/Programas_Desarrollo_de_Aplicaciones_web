<?php
$titulo = 'Usuarios';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/auth_check.php';
if (!esAdmin()) { header('Location: ../index.php'); exit; }
require_once __DIR__ . '/../classes/Usuario.php';
require_once __DIR__ . '/../classes/Historial.php';
$usuarioModel = new Usuario();
$historial = new Historial();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';
    if ($accion === 'editar') {
        $usuarioModel->actualizar($_POST['id'], $_POST);
        $historial->registrar($_SESSION['usuario_id'], 'Usuario actualizado', "Usuario #{$_POST['id']} actualizado");
        $_SESSION['mensaje'] = 'Usuario actualizado.'; $_SESSION['tipo_mensaje'] = 'success';
    } elseif ($accion === 'password') {
        $usuarioModel->cambiarPassword($_POST['id'], $_POST['password']);
        $historial->registrar($_SESSION['usuario_id'], 'Password cambiado', "Password de usuario #{$_POST['id']} cambiado");
        $_SESSION['mensaje'] = 'Contraseña actualizada.'; $_SESSION['tipo_mensaje'] = 'success';
    } elseif ($accion === 'eliminar') {
        if ($_POST['id'] == $_SESSION['usuario_id']) {
            $_SESSION['mensaje'] = 'No puedes eliminar tu propio usuario.';
            $_SESSION['tipo_mensaje'] = 'danger';
        } else {
            $usuarioModel->eliminar($_POST['id']);
            $historial->registrar($_SESSION['usuario_id'], 'Usuario eliminado', "Usuario #{$_POST['id']} eliminado");
            $_SESSION['mensaje'] = 'Usuario eliminado.'; $_SESSION['tipo_mensaje'] = 'warning';
        }
    }
    header('Location: usuarios.php'); exit;
}

$usuarios = $usuarioModel->obtenerTodos();
require_once __DIR__ . '/../includes/header.php';
?>
<h3><i class="bi bi-people"></i> Usuarios Registrados</h3>
<hr>
<div class="table-responsive">
    <table class="table table-hover table-striped">
        <thead class="table-dark">
            <tr><th>ID</th><th>Nombre</th><th>Email</th><th>Teléfono</th><th>Rol</th><th>Activo</th><th>Registro</th><th>Acciones</th></tr>
        </thead>
        <tbody>
            <?php foreach ($usuarios as $u): ?>
            <tr>
                <td><?php echo $u['id']; ?></td>
                <td><?php echo htmlspecialchars($u['nombre']); ?></td>
                <td><?php echo $u['email']; ?></td>
                <td><?php echo $u['telefono'] ?? '-'; ?></td>
                <td>
                    <span class="badge bg-<?php echo $u['rol'] === 'admin' ? 'danger' : 'primary'; ?>">
                        <?php echo ucfirst($u['rol']); ?>
                    </span>
                </td>
                <td>
                    <span class="badge bg-<?php echo $u['activo'] ? 'success' : 'secondary'; ?>">
                        <?php echo $u['activo'] ? 'Sí' : 'No'; ?>
                    </span>
                </td>
                <td><small><?php echo date('d/m/Y', strtotime($u['fecha_registro'])); ?></small></td>
                <td>
                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalEditar<?php echo $u['id']; ?>">
                        <i class="bi bi-pencil"></i>
                    </button>
                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalPassword<?php echo $u['id']; ?>">
                        <i class="bi bi-key"></i>
                    </button>
                    <?php if ($u['id'] != $_SESSION['usuario_id']): ?>
                    <form method="POST" style="display:inline" onsubmit="return confirm('¿Eliminar usuario #<?php echo $u['id']; ?> (<?php echo htmlspecialchars($u['nombre']); ?>)?\nSe eliminarán también sus reservaciones.')">
                        <input type="hidden" name="accion" value="eliminar">
                        <input type="hidden" name="id" value="<?php echo $u['id']; ?>">
                        <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                    </form>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php foreach ($usuarios as $u): ?>
<div class="modal fade" id="modalEditar<?php echo $u['id']; ?>" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" class="modal-content">
            <input type="hidden" name="accion" value="editar">
            <input type="hidden" name="id" value="<?php echo $u['id']; ?>">
            <div class="modal-header bg-primary text-white"><h5 class="modal-title">Editar Usuario</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="mb-3"><label class="form-label">Nombre</label><input type="text" name="nombre" class="form-control" value="<?php echo htmlspecialchars($u['nombre']); ?>" required></div>
                <div class="mb-3"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="<?php echo $u['email']; ?>" required></div>
                <div class="mb-3"><label class="form-label">Teléfono</label><input type="text" name="telefono" class="form-control" value="<?php echo $u['telefono']; ?>"></div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Rol</label>
                        <select name="rol" class="form-select">
                            <option value="cliente" <?php echo $u['rol'] === 'cliente' ? 'selected' : ''; ?>>Cliente</option>
                            <option value="admin" <?php echo $u['rol'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Activo</label>
                        <select name="activo" class="form-select">
                            <option value="1" <?php echo $u['activo'] ? 'selected' : ''; ?>>Sí</option>
                            <option value="0" <?php echo !$u['activo'] ? 'selected' : ''; ?>>No</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modalPassword<?php echo $u['id']; ?>" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" class="modal-content">
            <input type="hidden" name="accion" value="password">
            <input type="hidden" name="id" value="<?php echo $u['id']; ?>">
            <div class="modal-header bg-warning"><h5 class="modal-title">Cambiar Contraseña</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Nueva Contraseña</label>
                    <input type="password" name="password" class="form-control" minlength="6" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-warning">Cambiar Contraseña</button>
            </div>
        </form>
    </div>
</div>
<?php endforeach; ?>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
