<?php
require_once __DIR__ . '/../../include/auth_user.php';
$role = 'User';
$area = 'user';
$type = 'Keluar';
$folder = $type === 'Masuk' ? 'surat_masuk' : 'surat_keluar';
$pageTitle = 'Detail ' . ($type === 'Masuk' ? 'Surat Masuk' : 'Surat Keluar');
$activeMenu = $type === 'Masuk' ? 'masuk' : 'keluar';
$id = max(0, (int)($_GET['id'] ?? 0));
$stmt = $conn->prepare('SELECT s.*, u.nama AS pembuat_nama, up.nama AS pengubah_nama FROM surat s JOIN users u ON u.id=s.created_by LEFT JOIN users up ON up.id=s.updated_by WHERE s.id=? AND s.jenis_surat=? LIMIT 1');
$stmt->bind_param('is', $id, $type);
$stmt->execute();
$letter = $stmt->get_result()->fetch_assoc();
$stmt->close();
if (!$letter) { $_SESSION['flash']=['type'=>'error','message'=>'Data surat tidak ditemukan.']; header('Location: ' . BASE_URL . '/' . $area . '/' . $folder . '/index.php'); exit; }
$logsStmt = $conn->prepare('SELECT l.*, u.nama AS pengubah_nama FROM surat_status_logs l JOIN users u ON u.id=l.changed_by WHERE l.surat_id=? ORDER BY l.changed_at DESC, l.id DESC');
$logsStmt->bind_param('i', $id);
$logsStmt->execute();
$logs = $logsStmt->get_result();
$canEdit = $role === 'Admin' || ((int)$letter['created_by'] === (int)$_SESSION['user']['id'] && $letter['status'] !== 'Selesai');
require __DIR__ . '/../../include/header.php';
?>
<div class="page-heading"><div><h1>Detail <?= $type === 'Masuk' ? 'Surat Masuk' : 'Surat Keluar' ?></h1><p>Informasi surat dan riwayat perubahan statusnya.</p><div class="breadcrumbs"><a href="<?= BASE_URL ?>/<?= $area ?>/dashboard.php">Dashboard</a><span>›</span><a href="<?= BASE_URL ?>/<?= $area ?>/<?= $folder ?>/index.php"><?= $type === 'Masuk' ? 'Surat Masuk' : 'Surat Keluar' ?></a><span>›</span>Detail</div></div><div class="heading-actions"><?php if ($canEdit): ?><a class="button button-light" href="<?= BASE_URL ?>/<?= $area ?>/<?= $folder ?>/form.php?id=<?= $id ?>">✎ Edit</a><?php endif; ?><a class="button button-dark" href="<?= BASE_URL ?>/<?= $area ?>/status_surat/detail.php?id=<?= $id ?>">Lihat Status</a></div></div>
<div class="detail-layout"><section class="panel"><div class="panel-heading"><div><h2>Informasi Surat</h2><p>Data pokok surat yang tercatat.</p></div><span class="status-badge <?= $letter['status']==='Selesai'?'status-done':($letter['status']==='Dalam Proses'?'status-progress':(in_array($letter['status'],['Ditolak','Dibatalkan'],true)?'status-rejected':'status-waiting')) ?>"><?= htmlspecialchars($letter['status'], ENT_QUOTES, 'UTF-8') ?></span></div>
<dl class="detail-list"><div><dt>Nomor Surat</dt><dd><?= htmlspecialchars($letter['nomor_surat'], ENT_QUOTES, 'UTF-8') ?></dd></div><div><dt>Jenis Surat</dt><dd><?= $type === 'Masuk' ? 'Surat Masuk' : 'Surat Keluar' ?></dd></div><div><dt>Tanggal Surat</dt><dd><?= date('d-m-Y', strtotime($letter['tanggal_surat'])) ?></dd></div><div><dt><?= $type === 'Masuk' ? 'Tanggal Diterima' : 'Tanggal Pengiriman / Surat' ?></dt><dd><?= !empty($letter['tanggal_diterima']) ? date('d-m-Y', strtotime($letter['tanggal_diterima'])) : date('d-m-Y', strtotime($letter['tanggal_surat'])) ?></dd></div><div><dt>Pengirim</dt><dd><?= htmlspecialchars($letter['pengirim'] ?: '-', ENT_QUOTES, 'UTF-8') ?></dd></div><div><dt>Tujuan</dt><dd><?= htmlspecialchars($letter['tujuan'] ?: '-', ENT_QUOTES, 'UTF-8') ?></dd></div><div class="full-span"><dt>Perihal</dt><dd><?= htmlspecialchars($letter['perihal'], ENT_QUOTES, 'UTF-8') ?></dd></div><div class="full-span"><dt>Keterangan</dt><dd><?= nl2br(htmlspecialchars($letter['deskripsi'] ?: '-', ENT_QUOTES, 'UTF-8')) ?></dd></div><div><dt>Dicatat oleh</dt><dd><?= htmlspecialchars($letter['pembuat_nama'], ENT_QUOTES, 'UTF-8') ?></dd></div><div><dt>Terakhir diperbarui</dt><dd><?= date('d-m-Y H:i', strtotime($letter['updated_at'])) ?><?= $letter['pengubah_nama'] ? ' · ' . htmlspecialchars($letter['pengubah_nama'], ENT_QUOTES, 'UTF-8') : '' ?></dd></div></dl>
<?php if (!empty($letter['uploaded_file'])): ?><div class="file-card"><span class="file-symbol">FILE</span><div><strong>Lampiran surat</strong><small>File PDF atau gambar</small></div><div class="file-actions"><a class="button button-light" href="<?= BASE_URL ?>/proses/file_surat.php?id=<?= $id ?>&mode=view">Lihat</a><a class="button button-dark" href="<?= BASE_URL ?>/proses/file_surat.php?id=<?= $id ?>&mode=download">Unduh</a></div></div><?php endif; ?>
</section><section class="panel"><div class="panel-heading"><div><h2>Riwayat Status</h2><p>Setiap perubahan menyimpan waktu, pengguna, dan catatan.</p></div><a class="text-link" href="<?= BASE_URL ?>/<?= $area ?>/status_surat/detail.php?id=<?= $id ?>">Rincian status</a></div>
<?php if ($logs->num_rows > 0): ?><div class="timeline"><?php while ($log=$logs->fetch_assoc()): ?><div class="timeline-item"><span class="timeline-dot"></span><div class="timeline-content"><div class="timeline-meta"><strong><?= htmlspecialchars($log['status_baru'], ENT_QUOTES, 'UTF-8') ?></strong><time><?= date('d-m-Y H:i', strtotime($log['changed_at'])) ?></time></div><p><?= $log['status_lama'] ? htmlspecialchars($log['status_lama'], ENT_QUOTES, 'UTF-8') . ' → ' : '' ?><?= htmlspecialchars($log['status_baru'], ENT_QUOTES, 'UTF-8') ?></p><?php if (!empty($log['catatan'])): ?><p class="timeline-note"><?= htmlspecialchars($log['catatan'], ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?><small>Oleh <?= htmlspecialchars($log['pengubah_nama'], ENT_QUOTES, 'UTF-8') ?></small></div></div><?php endwhile; ?></div><?php else: ?><div class="empty-state">Belum ada riwayat status.</div><?php endif; ?></section></div>
<?php require __DIR__ . '/../../include/footer.php'; ?>
