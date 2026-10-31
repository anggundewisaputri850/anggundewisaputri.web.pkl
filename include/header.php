<?php
$userInfo = $_SESSION['user'] ?? [];
$flashMessage = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
$pageTitle = $pageTitle ?? 'Dashboard';
$activeMenu = $activeMenu ?? 'dashboard';
$role = $role ?? ($userInfo['role'] ?? 'User');
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?> | sistem_arsip_surat</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/include/css/style.css">
</head>
<body>
<div class="app-shell">
    <?php require __DIR__ . '/sidebar.php'; ?>
    <div class="sidebar-overlay" data-sidebar-close></div>
    <main class="main-area">
        <header class="topbar">
            <button class="sidebar-toggle" type="button" aria-label="Buka atau tutup menu" data-sidebar-toggle>☰</button>
            <div class="topbar-right">
                <div class="topbar-date"><span class="date-icon">▦</span><span><?= date('d-m-Y') ?><small><?= date('H:i') ?> WIB</small></span></div>
                <div class="user-menu"><span class="avatar"><?= htmlspecialchars(strtoupper(substr($userInfo['nama'] ?? 'U', 0, 1)), ENT_QUOTES, 'UTF-8') ?></span><div><strong><?= htmlspecialchars($userInfo['nama'] ?? 'Pengguna', ENT_QUOTES, 'UTF-8') ?></strong><small><?= htmlspecialchars($role, ENT_QUOTES, 'UTF-8') ?></small></div><a href="<?= BASE_URL ?>/ubah_password.php" class="profile-link" title="Ubah kata sandi" aria-label="Ubah kata sandi">⌄</a></div>
            </div>
        </header>
        <section class="page-content">
            <?php if (is_array($flashMessage)): ?>
                <div class="alert alert-<?= ($flashMessage['type'] ?? '') === 'success' ? 'success' : ((($flashMessage['type'] ?? '') === 'info') ? 'info' : 'error') ?>" role="status"><span><?= htmlspecialchars($flashMessage['message'] ?? '', ENT_QUOTES, 'UTF-8') ?></span><button type="button" class="alert-close" data-dismiss-alert aria-label="Tutup">×</button></div>
            <?php endif; ?>
