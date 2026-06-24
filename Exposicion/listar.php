<?php
require_once 'conexion.php';

$sql = "SELECT id, nombre, email, edad FROM usuarios ORDER BY id DESC";
$resultado = $conexion->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Usuarios registrados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="card shadow-sm">
            <div class="card-body">
                <h1 class="h4 mb-4">Usuarios registrados</h1>
                <?php if ($resultado->num_rows > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th><th>Nombre</th><th>Email</th><th>Edad</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($fila = $resultado->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= $fila['id'] ?></td>
                                        <td><?= htmlspecialchars($fila['nombre']) ?></td>
                                        <td><?= htmlspecialchars($fila['email']) ?></td>
                                        <td><?= $fila['edad'] ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info mb-0">No hay usuarios registrados todavía.</div>
                <?php endif; ?>
                <p class="mt-3 mb-0">
                    <a href="index.html" class="btn btn-primary">Registrar nuevo usuario</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
