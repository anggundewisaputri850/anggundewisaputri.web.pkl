<?php
require_once __DIR__ . '/../include/auth_admin.php';
$role = 'Admin';
$activeMenu = 'dashboard';
$pageTitle = 'Dashboard';
$baseFolder = 'admin';

$resultIn = $conn->query("SELECT COUNT(*) AS jumlah FROM surat WHERE jenis_surat='Masuk'");
$totalMasuk = (int)$resultIn->fetch_assoc()['jumlah'];
$resultOut = $conn->query("SELECT COUNT(*) AS jumlah FROM surat WHERE jenis_surat='Keluar'");
$totalKeluar = (int)$resultOut->fetch_assoc()['jumlah'];
$resultProcess = $conn->query("SELECT COUNT(*) AS jumlah FROM surat WHERE status NOT IN ('Selesai','Ditolak','Dibatalkan')");
$totalProses = (int)$resultProcess->fetch_assoc()['jumlah'];
$resultArchive = $conn->query('SELECT COUNT(*) AS jumlah FROM arsip_surat');
$totalArsip = (int)$resultArchive->fetch_assoc()['jumlah'];
$recentIn = $conn->query("SELECT id, nomor_surat, perihal, pengirim, tanggal_diterima, status FROM surat WHERE jenis_surat='Masuk' ORDER BY created_at DESC, id DESC LIMIT 5");
$recentOut = $conn->query("SELECT id, nomor_surat, perihal, tujuan, tanggal_surat, status FROM surat WHERE jenis_surat='Keluar' ORDER BY created_at DESC, id DESC LIMIT 5");
$recentLogs = $conn->query('SELECT l.surat_id, l.status_lama, l.status_baru, l.catatan, l.changed_at, s.nomor_surat, s.perihal, u.nama AS pengubah FROM surat_status_logs l JOIN surat s ON s.id=l.surat_id JOIN users u ON u.id=l.changed_by ORDER BY l.changed_at DESC, l.id DESC LIMIT 7');
require __DIR__ . '/../include/header.php';
?>
<div class="page-heading"><div><h1>Dashboard</h1><p>Ringkasan pengelolaan dan pemantauan surat.</p><div class="breadcrumbs">Menu Utama <span>›</span> Dashboard</div></div><div class="heading-date"><?= date('d-m-Y') ?></div></div>
<div class="stat-grid">
    <a class="stat-card" href="<?= BASE_URL ?>/<?= $baseFolder ?>/surat_masuk/index.php"><span class="stat-icon">▣</span><span class="stat-copy"><span>Surat Masuk</span><strong><?= number_format($totalMasuk) ?></strong><small>Total surat masuk tercatat</small></span></a>
    <a class="stat-card" href="<?= BASE_URL ?>/<?= $baseFolder ?>/surat_keluar/index.php"><span class="stat-icon">➤</span><span class="stat-copy"><span>Surat Keluar</span><strong><?= number_format($totalKeluar) ?></strong><small>Total surat keluar tercatat</small></span></a>
    <a class="stat-card" href="<?= BASE_URL ?>/<?= $baseFolder ?>/status_surat/index.php"><span class="stat-icon">◷</span><span class="stat-copy"><span>Dalam Proses</span><strong><?= number_format($totalProses) ?></strong><small>Surat yang belum selesai</small></span></a>
    <a class="stat-card" href="<?= BASE_URL ?>/<?= $baseFolder ?>/arsip_surat/index.php"><span class="stat-icon">▰</span><span class="stat-copy"><span>Arsip Surat</span><strong><?= number_format($totalArsip) ?></strong><small>Surat yang telah selesai</small></span></a>
</div>
<div class="dashboard-grid">
    <section class="panel"><div class="panel-heading"><div><h2>Surat yang Perlu Ditindaklanjuti</h2><p>Surat yang belum berstatus selesai.</p></div><a class="text-link" href="<?= BASE_URL ?>/<?= $baseFolder ?>/status_surat/index.php">Lihat semua</a></div>
    <?php $pending = $conn->query("SELECT id, jenis_surat, nomor_surat, perihal, status, updated_at FROM surat WHERE status NOT IN ('Selesai','Ditolak','Dibatalkan') ORDER BY updated_at DESC LIMIT 6"); ?>
    <?php if ($pending->num_rows > 0): ?><div class="table-wrap"><table><thead><tr><th>Nomor Surat</th><th>Perihal</th><th>Status</th><th>Aksi</th></tr></thead><tbody><?php while ($row = $pending->fetch_assoc()): ?><tr><td class="cell-strong"><?= htmlspecialchars($row['nomor_surat'], ENT_QUOTES, 'UTF-8') ?></td><td><?= htmlspecialchars($row['perihal'], ENT_QUOTES, 'UTF-8') ?></td><td><span class="status-badge <?= $row['status']==='Selesai'?'status-done':(in_array($row['status'],['Dalam Proses'],true)?'status-progress':'status-waiting') ?>"><?= htmlspecialchars($row['status'], ENT_QUOTES, 'UTF-8') ?></span></td><td><a class="button-small" href="<?= BASE_URL ?>/<?= $baseFolder ?>/status_surat/detail.php?id=<?= (int)$row['id'] ?>">Lihat</a></td></tr><?php endwhile; ?></tbody></table></div><?php else: ?><div class="empty-state">Tidak ada surat yang perlu ditindaklanjuti.</div><?php endif; ?>
    </section>
    <section class="panel"><div class="panel-heading"><div><h2>Riwayat Status Terbaru</h2><p>Perubahan status yang dicatat di sistem.</p></div><a class="text-link" href="<?= BASE_URL ?>/<?= $baseFolder ?>/status_surat/index.php">Pantau surat</a></div>
    <?php if ($recentLogs->num_rows > 0): ?><div class="activity-list"><?php while ($log = $recentLogs->fetch_assoc()): ?><div class="activity-item"><span class="activity-dot"></span><div class="activity-main"><strong><?= htmlspecialchars($log['nomor_surat'], ENT_QUOTES, 'UTF-8') ?> · <?= htmlspecialchars($log['perihal'], ENT_QUOTES, 'UTF-8') ?></strong><span><?= htmlspecialchars((string)$log['status_lama'], ENT_QUOTES, 'UTF-8') ?><?= $log['status_lama'] ? ' → ' : '' ?><?= htmlspecialchars($log['status_baru'], ENT_QUOTES, 'UTF-8') ?></span><small><?= htmlspecialchars($log['pengubah'], ENT_QUOTES, 'UTF-8') ?> · <?= date('d-m-Y H:i', strtotime($log['changed_at'])) ?></small></div></div><?php endwhile; ?></div><?php else: ?><div class="empty-state">Belum ada riwayat perubahan status.</div><?php endif; ?>
    </section>
</div>
<section class="panel panel-spaced"><div class="panel-heading"><div><h2>Surat Masuk Terbaru</h2><p>Data surat masuk yang baru dicatat.</p></div><a class="text-link" href="<?= BASE_URL ?>/<?= $baseFolder ?>/surat_masuk/index.php">Lihat semua surat masuk</a></div>
<?php if ($recentIn->num_rows > 0): ?><div class="table-wrap"><table><thead><tr><th>Nomor Surat</th><th>Tanggal Diterima</th><th>Perihal</th><th>Pengirim</th><th>Status</th><th>Aksi</th></tr></thead><tbody><?php while ($row = $recentIn->fetch_assoc()): ?><tr><td class="cell-strong"><?= htmlspecialchars($row['nomor_surat'], ENT_QUOTES, 'UTF-8') ?></td><td><?= $row['tanggal_diterima'] ? date('d-m-Y', strtotime($row['tanggal_diterima'])) : '-' ?></td><td><?= htmlspecialchars($row['perihal'], ENT_QUOTES, 'UTF-8') ?></td><td><?= htmlspecialchars($row['pengirim'], ENT_QUOTES, 'UTF-8') ?></td><td><span class="status-badge <?= $row['status']==='Selesai'?'status-done':($row['status']==='Dalam Proses'?'status-progress':'status-waiting') ?>"><?= htmlspecialchars($row['status'], ENT_QUOTES, 'UTF-8') ?></span></td><td><a class="button-small" href="<?= BASE_URL ?>/<?= $baseFolder ?>/surat_masuk/detail.php?id=<?= (int)$row['id'] ?>">Detail</a></td></tr><?php endwhile; ?></tbody></table></div><?php else: ?><div class="empty-state">Belum ada surat masuk.</div><?php endif; ?></section>
<section class="panel"><div class="panel-heading"><div><h2>Surat Keluar Terbaru</h2><p>Data surat keluar yang baru dicatat.</p></div><a class="text-link" href="<?= BASE_URL ?>/<?= $baseFolder ?>/surat_keluar/index.php">Lihat semua surat keluar</a></div>
<?php if ($recentOut->num_rows > 0): ?><div class="table-wrap"><table><thead><tr><th>Nomor Surat</th><th>Tanggal Surat</th><th>Perihal</th><th>Tujuan</th><th>Status</th><th>Aksi</th></tr></thead><tbody><?php while ($row = $recentOut->fetch_assoc()): ?><tr><td class="cell-strong"><?= htmlspecialchars($row['nomor_surat'], ENT_QUOTES, 'UTF-8') ?></td><td><?= date('d-m-Y', strtotime($row['tanggal_surat'])) ?></td><td><?= htmlspecialchars($row['perihal'], ENT_QUOTES, 'UTF-8') ?></td><td><?= htmlspecialchars($row['tujuan'], ENT_QUOTES, 'UTF-8') ?></td><td><span class="status-badge <?= $row['status']==='Selesai'?'status-done':($row['status']==='Dalam Proses'?'status-progress':'status-waiting') ?>"><?= htmlspecialchars($row['status'], ENT_QUOTES, 'UTF-8') ?></span></td><td><a class="button-small" href="<?= BASE_URL ?>/<?= $baseFolder ?>/surat_keluar/detail.php?id=<?= (int)$row['id'] ?>">Detail</a></td></tr><?php endwhile; ?></tbody></table></div><?php else: ?><div class="empty-state">Belum ada surat keluar.</div><?php endif; ?></section>
<?php require __DIR__ . '/../include/footer.php'; ?>
