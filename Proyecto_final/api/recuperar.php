<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../classes/Usuario.php';
require_once __DIR__ . '/../classes/Mailer.php';

$email = trim($_POST['email'] ?? '');
if (!$email) {
    $_SESSION['mensaje'] = 'Ingresa un correo electrónico.';
    $_SESSION['tipo_mensaje'] = 'danger';
    header('Location: ../pages/recuperar.php');
    exit;
}

$usuarioModel = new Usuario();
$usuario = $usuarioModel->obtenerPorEmail($email);

if (!$usuario) {
    $_SESSION['mensaje'] = 'Si el correo existe, recibirás un enlace de recuperación.';
    $_SESSION['tipo_mensaje'] = 'success';
    header('Location: ../pages/login.php');
    exit;
}

$token = bin2hex(random_bytes(32));
$expira = date('Y-m-d H:i:s', strtotime('+1 hour'));

$db = new PDO(
    "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET,
    DB_USER,
    DB_PASS,
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);

$stmt = $db->prepare("INSERT INTO password_resets (email, token, expira_en) VALUES (:email, :token, :expira_en)");
$stmt->execute([':email' => $email, ':token' => $token, ':expira_en' => $expira]);

$mailer = new Mailer();
$mailer->recuperacionPassword($usuario['nombre'], $email, $token);

$_SESSION['mensaje'] = 'Si el correo existe, recibirás un enlace de recuperación.';
$_SESSION['tipo_mensaje'] = 'success';
header('Location: ../pages/login.php');
exit;
