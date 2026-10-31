<?php
require_once __DIR__ . '/include/session.php';
require_once __DIR__ . '/include/config.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/index.php');
    exit;
}
$token = $_POST['csrf_token'] ?? '';
if (!is_string($token) || empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
    $_SESSION['login_error'] = 'Form login kedaluwarsa. Silakan coba kembali.';
    header('Location: ' . BASE_URL . '/index.php');
    exit;
}
$username = trim((string)($_POST['username'] ?? ''));
$password = (string)($_POST['password'] ?? '');
if ($username === '' || $password === '') {
    $_SESSION['login_error'] = 'Username dan password wajib diisi.';
    header('Location: ' . BASE_URL . '/index.php');
    exit;
}
$stmt = $conn->prepare('SELECT id, nama, username, password_hash, role, is_active FROM users WHERE username = ? LIMIT 1');
$stmt->bind_param('s', $username);
$stmt->execute();
$account = $stmt->get_result()->fetch_assoc();
$stmt->close();
if (!$account || (int)$account['is_active'] !== 1 || !password_verify($password, $account['password_hash'])) {
    $_SESSION['login_error'] = 'Username atau password tidak sesuai.';
    header('Location: ' . BASE_URL . '/index.php');
    exit;
}
session_regenerate_id(true);
$_SESSION['user'] = ['id' => (int)$account['id'], 'nama' => $account['nama'], 'username' => $account['username'], 'role' => $account['role']];
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
$target = $account['role'] === 'Admin' ? '/admin/dashboard.php' : '/user/dashboard.php';
header('Location: ' . BASE_URL . $target);
exit;
