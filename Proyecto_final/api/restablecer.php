<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../classes/Usuario.php';

$token = trim($_POST['token'] ?? '');
$password = $_POST['password'] ?? '';
$confirmar = $_POST['confirmar_password'] ?? '';

if (!$token || !$password) {
    $_SESSION['mensaje'] = 'Todos los campos son obligatorios.';
    $_SESSION['tipo_mensaje'] = 'danger';
    header('Location: ../pages/restablecer.php?token=' . urlencode($token));
    exit;
}

if ($password !== $confirmar) {
    $_SESSION['mensaje'] = 'Las contraseñas no coinciden.';
    $_SESSION['tipo_mensaje'] = 'danger';
    header('Location: ../pages/restablecer.php?token=' . urlencode($token));
    exit;
}

if (strlen($password) < 6) {
    $_SESSION['mensaje'] = 'La contraseña debe tener al menos 6 caracteres.';
    $_SESSION['tipo_mensaje'] = 'danger';
    header('Location: ../pages/restablecer.php?token=' . urlencode($token));
    exit;
}

try {
    $db = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET,
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    $stmt = $db->prepare("SELECT * FROM password_resets WHERE token = :token AND usado = 0 AND expira_en > NOW() LIMIT 1");
    $stmt->execute([':token' => $token]);
    $reset = $stmt->fetch();

    if (!$reset) {
        $_SESSION['mensaje'] = 'El enlace es inválido o ha expirado. Solicita uno nuevo.';
        $_SESSION['tipo_mensaje'] = 'danger';
        header('Location: ../pages/recuperar.php');
        exit;
    }

    $usuarioModel = new Usuario();
    $usuarioModel->cambiarPasswordPorEmail($reset['email'], $password);

    $stmt = $db->prepare("UPDATE password_resets SET usado = 1 WHERE id = :id");
    $stmt->execute([':id' => $reset['id']]);

    $_SESSION['mensaje'] = 'Contraseña restablecida exitosamente. Inicia sesión.';
    $_SESSION['tipo_mensaje'] = 'success';
    header('Location: ../pages/login.php');
    exit;
} catch (PDOException $e) {
    error_log("restablecer.php: " . $e->getMessage());
    $_SESSION['mensaje'] = 'Ocurrió un error. Intenta de nuevo.';
    $_SESSION['tipo_mensaje'] = 'danger';
    header('Location: ../pages/recuperar.php');
    exit;
}
