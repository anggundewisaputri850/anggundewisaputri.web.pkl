<?php
require_once __DIR__ . '/../include/session.php';
require_once __DIR__ . '/../include/config.php';

if (empty($_SESSION['user'])) {
    header('Location: ' . BASE_URL . '/index.php');
    exit;
}
if (($_SESSION['user']['role'] ?? '') !== 'Admin') {
    http_response_code(403);
    exit('Hanya Admin yang dapat mengelola disposisi surat.');
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/admin/surat_masuk/index.php');
    exit;
}
$token = $_POST['csrf_token'] ?? '';
if (!is_string($token) || empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
    http_response_code(419);
    exit('Formulir kedaluwarsa. Silakan kembali dan coba lagi.');
}

$suratId = max(0, (int)($_POST['surat_id'] ?? 0));
$disposisiId = max(0, (int)($_POST['disposisi_id'] ?? 0));
$tujuan = trim((string)($_POST['ditujukan_kepada'] ?? ''));
$instruksi = trim((string)($_POST['instruksi'] ?? ''));
$batas = trim((string)($_POST['batas_waktu'] ?? ''));
$status = trim((string)($_POST['status_tindak_lanjut'] ?? 'Belum Ditindaklanjuti'));
$catatan = trim((string)($_POST['catatan_tindak_lanjut'] ?? ''));
$userId = (int)$_SESSION['user']['id'];
$redirect = BASE_URL . '/admin/surat_masuk/detail.php?id=' . $suratId;

$back = static function (string $type, string $message) use ($redirect): void {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
    header('Location: ' . $redirect);
    exit;
};

if ($suratId < 1 || $tujuan === '' || $instruksi === '' || strlen($tujuan) > 150 || strlen($catatan) > 10000 || strlen($instruksi) > 20000) {
    $back('error', 'Periksa kembali penerima disposisi dan instruksi.');
}
if (!in_array($status, ['Belum Ditindaklanjuti', 'Dalam Proses', 'Selesai'], true)) {
    $back('error', 'Status tindak lanjut tidak valid.');
}
if ($batas !== '') {
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $batas) || !checkdate((int)substr($batas, 5, 2), (int)substr($batas, 8, 2), (int)substr($batas, 0, 4))) {
        $back('error', 'Tanggal batas waktu tidak valid.');
    }
} else {
    $batas = null;
}

$stmt = $conn->prepare("SELECT id FROM surat WHERE id=? AND jenis_surat='Masuk' LIMIT 1");
$stmt->bind_param('i', $suratId);
$stmt->execute();
$letterExists = (bool)$stmt->get_result()->fetch_assoc();
$stmt->close();
if (!$letterExists) {
    $back('error', 'Surat masuk tidak ditemukan.');
}

try {
    if ($disposisiId > 0) {
        $check = $conn->prepare('SELECT id FROM disposisi_surat WHERE id=? AND surat_id=? LIMIT 1');
        $check->bind_param('ii', $disposisiId, $suratId);
        $check->execute();
        $exists = (bool)$check->get_result()->fetch_assoc();
        $check->close();
        if (!$exists) {
            $back('error', 'Data disposisi tidak ditemukan untuk surat ini.');
        }
        $stmt = $conn->prepare('UPDATE disposisi_surat SET ditujukan_kepada=?, instruksi=?, batas_waktu=?, status_tindak_lanjut=?, catatan_tindak_lanjut=?, updated_by=? WHERE id=? AND surat_id=?');
        $stmt->bind_param('sssssiii', $tujuan, $instruksi, $batas, $status, $catatan, $userId, $disposisiId, $suratId);
        $stmt->execute();
        $stmt->close();
        $back('success', 'Data disposisi berhasil diperbarui.');
    }

    $stmt = $conn->prepare('INSERT INTO disposisi_surat (surat_id, ditujukan_kepada, instruksi, batas_waktu, status_tindak_lanjut, catatan_tindak_lanjut, created_by) VALUES (?, ?, ?, ?, ?, ?, ?)');
    $stmt->bind_param('isssssi', $suratId, $tujuan, $instruksi, $batas, $status, $catatan, $userId);
    $stmt->execute();
    $stmt->close();
    $back('success', 'Disposisi surat berhasil ditambahkan.');
} catch (Throwable $error) {
    $back('error', 'Disposisi gagal disimpan. Pastikan tabel disposisi_surat sudah tersedia pada database.');
}
