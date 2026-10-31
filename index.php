<?php
    require_once __DIR__ . '/include/session.php';
    require_once __DIR__ . '/include/config.php';
    if (! empty($_SESSION['user'])) {
    $target = ($_SESSION['user']['role'] ?? '') === 'Admin' ? '/admin/dashboard.php' : '/user/dashboard.php';
    header('Location: ' . BASE_URL . $target);
    exit;
    }
    if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    $errorLogin = $_SESSION['login_error'] ?? '';
    unset($_SESSION['login_error']);
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | sistem_arsip_surat</title>
    <link rel="stylesheet" href="<?php echo BASE_URL ?>/include/css/login.css">
</head>
<body class="login-body">
<div class="login-page">
    <div class="login-panel">
        <div class="login-branding">
            <img src="<?php echo BASE_URL ?>/assets/img/logo.png" class="login-logo-image" alt="Logo Sistem Pengarsipan Surat">
            <h1>SISTEM PENGARSIPAN SURAT</h1>
            <p>Pengelolaan surat yang tertata dan terpantau</p>
        </div>
        <?php if ($errorLogin !== ''): ?><div class="login-error"><?php echo htmlspecialchars($errorLogin, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
        <form action="<?php echo BASE_URL ?>/proses_login.php" method="post" class="login-form" autocomplete="on">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
            <div class="login-field"><label for="username">Username</label><input type="text" id="username" name="username" required autocomplete="username" placeholder="Masukkan username"></div>
            <div class="login-field"><label for="password">Password</label><div class="login-password-wrap"><input type="password" id="password" name="password" required autocomplete="current-password" placeholder="Masukkan password"><button type="button" class="login-password-toggle" data-password-toggle="password">Lihat</button></div></div>
            <button type="submit" class="login-submit">Masuk</button>
        </form>
        <p class="login-note">Akses sistem dibatasi berdasarkan peran pengguna.</p>
    </div>
    <div class="login-footer">© <?php echo date('Y') ?> sistem_arsip_surat</div>
</div>
<script src="<?php echo BASE_URL ?>/include/js/script.js"></script>
</body>
</html>
