<?php
require_once __DIR__ . '/session.php';
require_once __DIR__ . '/config.php';
if (empty($_SESSION['user'])) {
    header('Location: ' . BASE_URL . '/index.php');
    exit;
}
if (($_SESSION['user']['role'] ?? '') !== 'Admin') {
    header('Location: ' . BASE_URL . '/user/dashboard.php');
    exit;
}
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
