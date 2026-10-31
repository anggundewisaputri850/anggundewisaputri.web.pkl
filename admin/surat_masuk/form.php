<?php
require_once __DIR__ . '/../../include/auth_admin.php';
$role = 'Admin';
$area = 'admin';
$type = 'Masuk';
$folder = $type === 'Masuk' ? 'surat_masuk' : 'surat_keluar';
$pageTitle = ($type === 'Masuk' ? 'Surat Masuk' : 'Surat Keluar');
$activeMenu = $type === 'Masuk' ? 'masuk' : 'keluar';
$id = max(0, (int)($_GET['id'] ?? 0));
$letter = null;
if ($id > 0) {
    $stmt = $conn->prepare('SELECT * FROM surat WHERE id=? AND jenis_surat=? LIMIT 1');
    $stmt->bind_param('is', $id, $type);
    $stmt->execute();
    $letter = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    if (!$letter) { $_SESSION['flash']=['type'=>'error','message'=>'Data surat tidak ditemukan.']; header('Location: ' . BASE_URL . '/' . $area . '/' . $folder . '/index.php'); exit; }
    if ($role !== 'Admin' && ((int)$letter['created_by'] !== (int)$_SESSION['user']['id'] || $letter['status'] === 'Selesai')) { $_SESSION['flash']=['type'=>'error','message'=>'Surat ini tidak dapat diedit oleh akun Anda.']; header('Location: ' . BASE_URL . '/' . $area . '/' . $folder . '/detail.php?id=' . $id); exit; }
}
require __DIR__ . '/../../include/header.php';
$csrf = htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8');
?>
<div class="page-heading"><div><h1><?= $id ? 'Edit ' : 'Tambah ' ?><?= $pageTitle ?></h1><p>Isi informasi surat dengan benar. Kolom bertanda wajib tidak boleh kosong.</p><div class="breadcrumbs"><a href="<?= BASE_URL ?>/<?= $area ?>/dashboard.php">Dashboard</a><span>›</span><a href="<?= BASE_URL ?>/<?= $area ?>/<?= $folder ?>/index.php"><?= $pageTitle ?></a><span>›</span>Formulir</div></div></div>
<section class="panel form-panel"><div class="panel-heading"><div><h2>Informasi Surat</h2><p>Format tanggal menggunakan tahun-bulan-hari pada formulir.</p></div></div>
<form method="post" action="<?= BASE_URL ?>/proses/surat_simpan.php" enctype="multipart/form-data" class="form-grid">
<input type="hidden" name="csrf_token" value="<?= $csrf ?>"><input type="hidden" name="id" value="<?= $id ?>"><input type="hidden" name="jenis_surat" value="<?= $type ?>">
<div class="form-section-title"><h2>Data Utama</h2><p>Nomor surat harus unik dalam setiap jenis surat.</p></div>
<div class="form-field"><label for="nomor_surat">Nomor Surat *</label><input id="nomor_surat" name="nomor_surat" maxlength="100" required value="<?= htmlspecialchars($letter['nomor_surat'] ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="Contoh: 001/SM/X/2026"></div>
<div class="form-field"><label for="tanggal_surat">Tanggal Surat *</label><input id="tanggal_surat" name="tanggal_surat" type="date" required value="<?= htmlspecialchars($letter['tanggal_surat'] ?? date('Y-m-d'), ENT_QUOTES, 'UTF-8') ?>"></div>
<?php if ($type === 'Masuk'): ?>
<div class="form-field"><label for="tanggal_diterima">Tanggal Diterima *</label><input id="tanggal_diterima" name="tanggal_diterima" type="date" required value="<?= htmlspecialchars($letter['tanggal_diterima'] ?? date('Y-m-d'), ENT_QUOTES, 'UTF-8') ?>"></div>
<div class="form-field"><label for="pengirim">Pengirim *</label><input id="pengirim" name="pengirim" maxlength="200" required value="<?= htmlspecialchars($letter['pengirim'] ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="Nama instansi/pengirim"></div>
<div class="form-field"><label for="tujuan">Tujuan / Bagian Penerima</label><input id="tujuan" name="tujuan" maxlength="200" value="<?= htmlspecialchars($letter['tujuan'] ?? 'Bagian Umum', ENT_QUOTES, 'UTF-8') ?>" placeholder="Bagian penerima surat"></div>
<?php else: ?>
<div class="form-field"><label for="pengirim">Pengirim / Bagian</label><input id="pengirim" name="pengirim" maxlength="200" value="<?= htmlspecialchars($letter['pengirim'] ?? 'Bagian Umum', ENT_QUOTES, 'UTF-8') ?>" placeholder="Contoh: Bagian Umum"></div>
<div class="form-field"><label for="tujuan">Tujuan *</label><input id="tujuan" name="tujuan" maxlength="200" required value="<?= htmlspecialchars($letter['tujuan'] ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="Instansi tujuan surat"></div>
<?php endif; ?>
<div class="form-field full-span"><label for="perihal">Perihal *</label><input id="perihal" name="perihal" maxlength="250" required value="<?= htmlspecialchars($letter['perihal'] ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="Ringkasan isi surat"></div>
<div class="form-field full-span"><label for="deskripsi">Keterangan</label><textarea id="deskripsi" name="deskripsi" rows="4" maxlength="5000" placeholder="Keterangan tambahan (opsional)"><?= htmlspecialchars($letter['deskripsi'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea></div>
<div class="form-field full-span"><label for="lampiran">Lampiran (PDF, JPG, PNG; maksimal 5 MB)</label><input id="lampiran" name="lampiran" type="file" accept=".pdf,.jpg,.jpeg,.png,application/pdf,image/jpeg,image/png"><?php if (!empty($letter['uploaded_file'])): ?><span class="field-help">Lampiran saat ini tersedia. <a class="text-link" href="<?= BASE_URL ?>/proses/file_surat.php?id=<?= $id ?>">Lihat lampiran</a>. Unggah file baru hanya jika ingin menggantinya.</span><?php endif; ?></div>
<div class="form-actions full-span"><a class="button button-light" href="<?= $id ? BASE_URL . '/' . $area . '/' . $folder . '/detail.php?id=' . $id : BASE_URL . '/' . $area . '/' . $folder . '/index.php' ?>">Batal</a><button class="button button-dark" type="submit">Simpan Surat</button></div>
</form></section>
<?php require __DIR__ . '/../../include/footer.php'; ?>
