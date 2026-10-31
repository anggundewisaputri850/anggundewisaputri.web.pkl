<?php $menuFolder = strtolower($role); ?>
<aside class="sidebar" id="sidebar">
    <a class="brand" href="<?php echo BASE_URL ?>/<?php echo $menuFolder ?>/dashboard.php">
        <span class="brand-mark brand-logo"><img src="<?php echo BASE_URL ?>/assets/img/logo.png" alt="Logo Kabupaten Langkat"></span>
        <span class="brand-name"><strong>SISTEM PENGARSIPAN SURAT</strong><small>Pengelolaan Surat Digital</small></span>
    </a>
    <div class="sidebar-label">MENU UTAMA</div>
    <nav class="side-nav" aria-label="Menu utama">
        <a class="nav-item <?php echo $activeMenu === 'dashboard' ? 'active' : '' ?>" href="<?php echo BASE_URL ?>/<?php echo $menuFolder ?>/dashboard.php"><span class="nav-icon">⌂</span><span>Dashboard</span></a>
        <a class="nav-item <?php echo $activeMenu === 'masuk' ? 'active' : '' ?>" href="<?php echo BASE_URL ?>/<?php echo $menuFolder ?>/surat_masuk/index.php"><span class="nav-icon">▣</span><span>Surat Masuk</span></a>
        <a class="nav-item <?php echo $activeMenu === 'status' ? 'active' : '' ?>" href="<?php echo BASE_URL ?>/<?php echo $menuFolder ?>/status_surat/index.php"><span class="nav-icon">☷</span><span>Status Surat</span></a>
        <a class="nav-item <?php echo $activeMenu === 'keluar' ? 'active' : '' ?>" href="<?php echo BASE_URL ?>/<?php echo $menuFolder ?>/surat_keluar/index.php"><span class="nav-icon">➤</span><span>Surat Keluar</span></a>
        <a class="nav-item <?php echo $activeMenu === 'arsip' ? 'active' : '' ?>" href="<?php echo BASE_URL ?>/<?php echo $menuFolder ?>/arsip_surat/index.php"><span class="nav-icon">▰</span><span>Arsip Surat</span></a>
    </nav>
    <div class="sidebar-bottom">
        <form action="<?php echo BASE_URL ?>/logout.php" method="post" class="logout-form">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
            <button class="nav-item logout-item" type="submit"><span class="nav-icon">↪</span><span>Logout</span></button>
        </form>
    </div>
</aside>
