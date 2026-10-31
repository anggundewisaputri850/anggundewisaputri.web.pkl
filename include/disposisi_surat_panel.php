<?php
// Bagian ini ditampilkan hanya pada detail surat masuk.
if (empty($letter) || ($letter['jenis_surat'] ?? '') !== 'Masuk') {
    return;
}
$dispStmt = $conn->prepare('SELECT d.*, uc.nama AS pembuat_nama, uu.nama AS pengubah_nama FROM disposisi_surat d JOIN users uc ON uc.id=d.created_by LEFT JOIN users uu ON uu.id=d.updated_by WHERE d.surat_id=? ORDER BY d.tanggal_disposisi DESC, d.id DESC');
$dispStmt->bind_param('i', $id);
$dispStmt->execute();
$disposisiRows = $dispStmt->get_result();
$canManageDisposisi = (($role ?? '') === 'Admin');
$csrfDisposition = htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8');
?>
<section class="panel disposition-panel">
    <div class="panel-heading">
        <div>
            <h2>Disposisi dan Tindak Lanjut</h2>
            <p>Arahan untuk pihak terkait beserta perkembangan tindak lanjutnya.</p>
        </div>
        <?php if ($disposisiRows->num_rows > 0): ?><span class="disposition-count"><?= (int)$disposisiRows->num_rows ?> catatan</span><?php endif; ?>
    </div>

    <?php if ($canManageDisposisi): ?>
    <form class="disposition-form" method="post" action="<?= BASE_URL ?>/proses/disposisi_simpan.php">
        <input type="hidden" name="csrf_token" value="<?= $csrfDisposition ?>">
        <input type="hidden" name="surat_id" value="<?= (int)$id ?>">
        <div class="disposition-form-heading"><strong>Tambah Disposisi</strong><span>Isi penerima dan arahan yang perlu dilakukan.</span></div>
        <div class="disposition-fields">
            <div class="disposition-field"><label for="disp_tujuan_baru">Ditujukan Kepada *</label><input id="disp_tujuan_baru" name="ditujukan_kepada" maxlength="150" required placeholder="Contoh: Kepala Bagian Umum"></div>
            <div class="disposition-field"><label for="disp_batas_baru">Batas Waktu</label><input id="disp_batas_baru" name="batas_waktu" type="date"></div>
            <div class="disposition-field disposition-wide"><label for="disp_instruksi_baru">Instruksi Disposisi *</label><textarea id="disp_instruksi_baru" name="instruksi" rows="3" maxlength="20000" required placeholder="Arahan atau tindak lanjut yang harus dilakukan"></textarea></div>
            <div class="disposition-field"><label for="disp_status_baru">Status Tindak Lanjut</label><select id="disp_status_baru" name="status_tindak_lanjut"><option>Belum Ditindaklanjuti</option><option>Dalam Proses</option><option>Selesai</option></select></div>
            <div class="disposition-field disposition-wide"><label for="disp_catatan_baru">Catatan Tindak Lanjut</label><textarea id="disp_catatan_baru" name="catatan_tindak_lanjut" rows="2" maxlength="10000" placeholder="Catatan tambahan (opsional)"></textarea></div>
        </div>
        <div class="disposition-actions"><button class="button button-dark" type="submit">Simpan Disposisi</button></div>
    </form>
    <?php endif; ?>

    <?php if ($disposisiRows->num_rows > 0): ?>
        <div class="disposition-list">
        <?php while ($disp = $disposisiRows->fetch_assoc()): ?>
            <?php $dispStatusClass = $disp['status_tindak_lanjut'] === 'Selesai' ? 'disposition-done' : ($disp['status_tindak_lanjut'] === 'Dalam Proses' ? 'disposition-progress' : 'disposition-waiting'); ?>
            <article class="disposition-item">
                <div class="disposition-item-top">
                    <div><h3><?= htmlspecialchars($disp['ditujukan_kepada'], ENT_QUOTES, 'UTF-8') ?></h3><p><?= date('d-m-Y H:i', strtotime($disp['tanggal_disposisi'])) ?> · Dicatat oleh <?= htmlspecialchars($disp['pembuat_nama'], ENT_QUOTES, 'UTF-8') ?></p></div>
                    <span class="disposition-status <?= $dispStatusClass ?>"><?= htmlspecialchars($disp['status_tindak_lanjut'], ENT_QUOTES, 'UTF-8') ?></span>
                </div>
                <div class="disposition-body"><strong>Instruksi</strong><p><?= nl2br(htmlspecialchars($disp['instruksi'], ENT_QUOTES, 'UTF-8')) ?></p></div>
                <div class="disposition-meta">
                    <div><span>Batas waktu</span><strong><?= !empty($disp['batas_waktu']) ? date('d-m-Y', strtotime($disp['batas_waktu'])) : 'Tidak ditentukan' ?></strong></div>
                    <div><span>Catatan tindak lanjut</span><strong><?= $disp['catatan_tindak_lanjut'] !== null && $disp['catatan_tindak_lanjut'] !== '' ? nl2br(htmlspecialchars($disp['catatan_tindak_lanjut'], ENT_QUOTES, 'UTF-8')) : '-' ?></strong></div>
                    <?php if (!empty($disp['pengubah_nama'])): ?><div><span>Terakhir diperbarui</span><strong><?= date('d-m-Y H:i', strtotime($disp['updated_at'])) ?> · <?= htmlspecialchars($disp['pengubah_nama'], ENT_QUOTES, 'UTF-8') ?></strong></div><?php endif; ?>
                </div>
                <?php if ($canManageDisposisi): ?>
                    <details class="disposition-edit"><summary>Edit disposisi / tindak lanjut</summary>
                        <form class="disposition-form disposition-edit-form" method="post" action="<?= BASE_URL ?>/proses/disposisi_simpan.php">
                            <input type="hidden" name="csrf_token" value="<?= $csrfDisposition ?>">
                            <input type="hidden" name="surat_id" value="<?= (int)$id ?>">
                            <input type="hidden" name="disposisi_id" value="<?= (int)$disp['id'] ?>">
                            <div class="disposition-fields">
                                <div class="disposition-field"><label>Ditujukan Kepada *</label><input name="ditujukan_kepada" maxlength="150" required value="<?= htmlspecialchars($disp['ditujukan_kepada'], ENT_QUOTES, 'UTF-8') ?>"></div>
                                <div class="disposition-field"><label>Batas Waktu</label><input name="batas_waktu" type="date" value="<?= htmlspecialchars($disp['batas_waktu'] ?? '', ENT_QUOTES, 'UTF-8') ?>"></div>
                                <div class="disposition-field disposition-wide"><label>Instruksi Disposisi *</label><textarea name="instruksi" rows="3" maxlength="20000" required><?= htmlspecialchars($disp['instruksi'], ENT_QUOTES, 'UTF-8') ?></textarea></div>
                                <div class="disposition-field"><label>Status Tindak Lanjut</label><select name="status_tindak_lanjut"><?php foreach (['Belum Ditindaklanjuti','Dalam Proses','Selesai'] as $dispStatus): ?><option value="<?= htmlspecialchars($dispStatus, ENT_QUOTES, 'UTF-8') ?>" <?= $disp['status_tindak_lanjut'] === $dispStatus ? 'selected' : '' ?>><?= htmlspecialchars($dispStatus, ENT_QUOTES, 'UTF-8') ?></option><?php endforeach; ?></select></div>
                                <div class="disposition-field disposition-wide"><label>Catatan Tindak Lanjut</label><textarea name="catatan_tindak_lanjut" rows="2" maxlength="10000"><?= htmlspecialchars($disp['catatan_tindak_lanjut'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea></div>
                            </div>
                            <div class="disposition-actions"><button class="button button-dark" type="submit">Perbarui Disposisi</button></div>
                        </form>
                    </details>
                <?php endif; ?>
            </article>
        <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="empty-state"><?= $canManageDisposisi ? 'Belum ada disposisi untuk surat ini. Gunakan formulir di atas untuk menambahkan arahan.' : 'Belum ada disposisi yang dicatat untuk surat ini.' ?></div>
    <?php endif; ?>
</section>
<?php $dispStmt->close(); ?>
