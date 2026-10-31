<?php
require_once __DIR__ . '/../../include/auth_admin.php';
$role = 'Admin';
$area = 'admin';
$type = 'Masuk';
$pageTitle = $type === 'Masuk' ? 'Surat Masuk' : 'Surat Keluar';
$activeMenu = $type === 'Masuk' ? 'masuk' : 'keluar';
$q = trim((string)($_GET['q'] ?? ''));
$statusFilter = trim((string)($_GET['status'] ?? ''));
$from = trim((string)($_GET['from'] ?? ''));
$to = trim((string)($_GET['to'] ?? ''));
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 10;
$like = '%' . $q . '%';
$dateColumn = $type === 'Masuk' ? 'tanggal_diterima' : 'tanggal_surat';
$validStatuses = $type === 'Masuk' ? ['Diterima','Menunggu Disposisi','Dalam Proses','Selesai','Ditolak'] : ['Draft','Dalam Proses','Selesai','Dibatalkan'];
if (!in_array($statusFilter, $validStatuses, true)) $statusFilter = '';
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $from) || !checkdate((int)substr($from,5,2), (int)substr($from,8,2), (int)substr($from,0,4))) $from = '';
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $to) || !checkdate((int)substr($to,5,2), (int)substr($to,8,2), (int)substr($to,0,4))) $to = '';
$countSql = "SELECT COUNT(*) AS total FROM surat WHERE jenis_surat=? AND (?='' OR nomor_surat LIKE ? OR perihal LIKE ? OR pengirim LIKE ? OR tujuan LIKE ?) AND (?='' OR status=?) AND (?='' OR {$dateColumn}>=?) AND (?='' OR {$dateColumn}<=?)";
$countStmt = $conn->prepare($countSql);
$countStmt->bind_param('ssssssssssss', $type, $q, $like, $like, $like, $like, $statusFilter, $statusFilter, $from, $from, $to, $to);
$countStmt->execute();
$total = (int)$countStmt->get_result()->fetch_assoc()['total'];
$countStmt->close();
$totalPages = max(1, (int)ceil($total / $perPage));
$page = min($page, $totalPages);
$offset = ($page - 1) * $perPage;
$listSql = "SELECT s.*, u.nama AS pembuat FROM surat s JOIN users u ON u.id=s.created_by WHERE s.jenis_surat=? AND (?='' OR s.nomor_surat LIKE ? OR s.perihal LIKE ? OR s.pengirim LIKE ? OR s.tujuan LIKE ?) AND (?='' OR s.status=?) AND (?='' OR s.{$dateColumn}>=?) AND (?='' OR s.{$dateColumn}<=?) ORDER BY s.{$dateColumn} DESC, s.id DESC LIMIT ? OFFSET ?";
$listStmt = $conn->prepare($listSql);
$listStmt->bind_param('ssssssssssssii', $type, $q, $like, $like, $like, $like, $statusFilter, $statusFilter, $from, $from, $to, $to, $perPage, $offset);
$listStmt->execute();
$rows = $listStmt->get_result();
$statStmt = $conn->prepare('SELECT status, COUNT(*) AS jumlah FROM surat WHERE jenis_surat=? GROUP BY status');
$statStmt->bind_param('s', $type);
$statStmt->execute();
$statRows = $statStmt->get_result();
$stats = [];
while ($st = $statRows->fetch_assoc()) $stats[$st['status']] = (int)$st['jumlah'];
require __DIR__ . '/../../include/header.php';
$folder = $type === 'Masuk' ? 'surat_masuk' : 'surat_keluar';
?>
<div class="page-heading"><div><h1><?= $pageTitle ?></h1><p><?= $type === 'Masuk' ? 'Catat dan kelola surat yang diterima.' : 'Catat dan kelola surat yang dikirim.' ?></p><div class="breadcrumbs"><a href="<?= BASE_URL ?>/<?= $area ?>/dashboard.php">Dashboard</a><span>›</span><?= $pageTitle ?></div></div><a class="button button-dark" href="<?= BASE_URL ?>/<?= $area ?>/<?= $folder ?>/form.php">＋ Tambah <?= $pageTitle ?></a></div>
<div class="mini-stats"><div><span>Total <?= $pageTitle ?></span><strong><?= number_format($total) ?></strong></div><div><span><?= $type === 'Masuk' ? 'Menunggu Disposisi' : 'Draft' ?></span><strong><?= number_format($stats[$type === 'Masuk' ? 'Menunggu Disposisi' : 'Draft'] ?? 0) ?></strong></div><div><span>Dalam Proses</span><strong><?= number_format($stats['Dalam Proses'] ?? 0) ?></strong></div><div><span>Selesai</span><strong><?= number_format($stats['Selesai'] ?? 0) ?></strong></div></div>
<section class="panel filter-panel"><div class="panel-heading"><div><h2>Filter Data <?= $pageTitle ?></h2><p>Cari nomor surat, perihal, pengirim/tujuan, status, atau tanggal.</p></div></div><form class="filter-form" method="get"><label class="search-input"><span>⌕</span><input type="search" name="q" value="<?= htmlspecialchars($q, ENT_QUOTES, 'UTF-8') ?>" placeholder="Nomor, perihal, pengirim/tujuan..."></label><label><span class="sr-only">Tanggal dari</span><input type="date" name="from" value="<?= htmlspecialchars($from, ENT_QUOTES, 'UTF-8') ?>" aria-label="Tanggal dari"></label><label><span class="sr-only">Tanggal sampai</span><input type="date" name="to" value="<?= htmlspecialchars($to, ENT_QUOTES, 'UTF-8') ?>" aria-label="Tanggal sampai"></label><select name="status" aria-label="Filter status"><option value="">Semua Status</option><?php foreach ($validStatuses as $option): ?><option value="<?= htmlspecialchars($option, ENT_QUOTES, 'UTF-8') ?>" <?= $statusFilter === $option ? 'selected' : '' ?>><?= htmlspecialchars($option, ENT_QUOTES, 'UTF-8') ?></option><?php endforeach; ?></select><div class="filter-actions"><button class="button button-dark" type="submit">⌕ Cari</button><a class="button button-light" href="<?= BASE_URL ?>/<?= $area ?>/<?= $folder ?>/index.php">Reset</a></div></form></section>
<section class="panel"><div class="panel-heading"><div><h2>Daftar <?= $pageTitle ?></h2><p>Menampilkan <?= $total ? $offset + 1 : 0 ?>–<?= min($offset + $perPage, $total) ?> dari <?= number_format($total) ?> data</p></div><a class="button button-light" href="<?= BASE_URL ?>/proses/surat_export.php?jenis=<?= urlencode($type) ?>&q=<?= urlencode($q) ?>&status=<?= urlencode($statusFilter) ?>&from=<?= urlencode($from) ?>&to=<?= urlencode($to) ?>">⇩ Export CSV</a></div>
<?php if ($rows->num_rows > 0): ?><div class="table-wrap"><table><thead><tr><th>No.</th><th>Nomor Surat</th><th><?= $type === 'Masuk' ? 'Tanggal Diterima' : 'Tanggal Surat' ?></th><th>Perihal</th><th><?= $type === 'Masuk' ? 'Pengirim' : 'Tujuan' ?></th><th>Status</th><th>Aksi</th></tr></thead><tbody><?php $number=$offset+1; while ($row=$rows->fetch_assoc()): ?><tr><td><?= $number++ ?></td><td class="cell-strong"><?= htmlspecialchars($row['nomor_surat'], ENT_QUOTES, 'UTF-8') ?></td><td><?= !empty($row[$dateColumn]) ? date('d-m-Y', strtotime($row[$dateColumn])) : '-' ?></td><td><?= htmlspecialchars($row['perihal'], ENT_QUOTES, 'UTF-8') ?></td><td><?= htmlspecialchars($type === 'Masuk' ? $row['pengirim'] : $row['tujuan'], ENT_QUOTES, 'UTF-8') ?></td><td><span class="status-badge <?= $row['status']==='Selesai'?'status-done':($row['status']==='Dalam Proses'?'status-progress':(in_array($row['status'],['Ditolak','Dibatalkan'],true)?'status-rejected':'status-waiting')) ?>"><?= htmlspecialchars($row['status'], ENT_QUOTES, 'UTF-8') ?></span></td><td><div class="action-group"><a class="icon-button" title="Detail" href="<?= BASE_URL ?>/<?= $area ?>/<?= $folder ?>/detail.php?id=<?= (int)$row['id'] ?>">◉</a><?php if ($role === 'Admin' || ((int)$row['created_by'] === (int)$_SESSION['user']['id'] && $row['status'] !== 'Selesai')): ?><a class="icon-button" title="Edit" href="<?= BASE_URL ?>/<?= $area ?>/<?= $folder ?>/form.php?id=<?= (int)$row['id'] ?>">✎</a><?php endif; ?><?php if ($role === 'Admin'): ?><form method="post" action="<?= BASE_URL ?>/proses/surat_hapus.php" data-confirm="Hapus surat ini beserta riwayat statusnya?"><input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>"><input type="hidden" name="id" value="<?= (int)$row['id'] ?>"><button class="icon-button danger-button" title="Hapus" type="submit">×</button></form><?php endif; ?></div></td></tr><?php endwhile; ?></tbody></table></div><?php else: ?><div class="empty-state">Data <?= strtolower($pageTitle) ?> tidak ditemukan. Ubah filter atau tambahkan surat baru.</div><?php endif; ?>
<?php if ($totalPages > 1): ?><nav class="pagination" aria-label="Paginasi"><?php for ($p=1; $p<=$totalPages; $p++): $params=$_GET; $params['page']=$p; ?><a class="<?= $p===$page?'current':'' ?>" href="?<?= htmlspecialchars(http_build_query($params), ENT_QUOTES, 'UTF-8') ?>"><?= $p ?></a><?php endfor; ?></nav><?php endif; ?></section>
<?php require __DIR__ . '/../../include/footer.php'; ?>
