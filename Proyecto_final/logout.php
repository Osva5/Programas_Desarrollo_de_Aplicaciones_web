<?php
require_once __DIR__ . '/config/config.php';
$_SESSION = [];
session_destroy();
header('Location: ' . SITE_URL . '/index.php');
exit;
