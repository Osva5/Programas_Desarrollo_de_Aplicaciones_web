<?php
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'canchas_deportivas');
define('DB_USER', 'project_user');
define('DB_PASS', 'TuxKane26!!');
define('DB_CHARSET', 'utf8mb4');

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost:8080';
define('SITE_NAME', 'TU CANCHA - Canchas Deportivas');
define('SITE_URL', $protocol . '://' . $host);
define('UPLOAD_DIR', $_SERVER['DOCUMENT_ROOT'] . '/assets/img/');
define('CANCHAS_IMG_DIR', UPLOAD_DIR . 'canchas/');
define('USUARIOS_IMG_DIR', UPLOAD_DIR . 'usuarios/');

// Configuración de correo
define('MAIL_HOST', 'castelancarpinteyro.com'); //'smtp.gmail.com');
define('MAIL_PORT', 587);
define('MAIL_SMTP_SECURE', 'tls');
define('MAIL_USER', 'webdev@castelancarpinteyro.com');
define('MAIL_PASS', 'PasamosCon10!!');
define('MAIL_FROM', 'webdev@castelancarpinteyro.com');
define('MAIL_FROM_NAME', 'TU CANCHA');

date_default_timezone_set('America/Mexico_City');

session_start();
error_reporting(E_ALL);
ini_set('display_errors', 0);
