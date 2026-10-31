<?php
// Koneksi database MySQL. Ubah nilai berikut jika konfigurasi XAMPP berbeda.
$scriptPath = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
$projectMarker = '/sistem_arsip_surat';
$markerPosition = strpos($scriptPath, $projectMarker);
if ($markerPosition !== false) {
    $baseUrl = substr($scriptPath, 0, $markerPosition + strlen($projectMarker));
} else {
    $baseUrl = '';
}
if (!defined('BASE_URL')) {
    define('BASE_URL', $baseUrl);
}

date_default_timezone_set('Asia/Jakarta');
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = '';
$dbName = 'pengarsipan_surat';

try {
    $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
    $conn->set_charset('utf8mb4');
} catch (mysqli_sql_exception $error) {
    http_response_code(500);
    echo '<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Koneksi Database</title></head><body><h2>Koneksi database belum berhasil</h2><p>Aktifkan MySQL di XAMPP, kemudian impor file <code>database/pengarsipan_surat.sql</code> melalui phpMyAdmin.</p><p>Jika nama pengguna atau kata sandi MySQL berbeda, ubah pengaturan pada <code>include/config.php</code>.</p></body></html>';
    exit;
}
