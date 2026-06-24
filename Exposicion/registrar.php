<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registro de usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <?php
                        require_once 'conexion.php';

                        $nombre = htmlspecialchars($_POST['nombre']);
                        $email  = htmlspecialchars($_POST['email']);
                        $edad   = (int)$_POST['edad'];

                        $sql = "INSERT INTO usuarios (nombre, email, edad) VALUES (?, ?, ?)";
                        $stmt = $conexion->prepare($sql);
                        $stmt->bind_param("ssi", $nombre, $email, $edad);

                        if ($stmt->execute()) { ?>
                            <h2 class="h4 text-success mb-3">Usuario registrado exitosamente</h2>
                            <p class="mb-1"><strong>Nombre:</strong> <?= $nombre ?></p>
                            <p class="mb-1"><strong>Email:</strong> <?= $email ?></p>
                            <p class="mb-3"><strong>Edad:</strong> <?= $edad ?></p>
                            <a href="index.html" class="btn btn-primary">Volver al formulario</a>
                            <a href="listar.php" class="btn btn-outline-secondary">Ver todos</a>
                        <?php } else { ?>
                            <h2 class="h4 text-danger mb-3">Error</h2>
                            <p><?= $stmt->error ?></p>
                            <a href="index.html" class="btn btn-primary">Volver</a>
                        <?php }

                        $stmt->close();
                        $conexion->close();
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
