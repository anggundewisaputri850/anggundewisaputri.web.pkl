<?php
// Pengaturan sesi sederhana untuk PHP Native.
if (session_status() === PHP_SESSION_NONE) {
    // Nama sesi unik agar tidak berbenturan dengan aplikasi PHP lain di localhost.
    session_name('SISTEM_ARSIP_SURAT_SESSID');
    ini_set('session.use_strict_mode', '1');
    ini_set('session.cookie_httponly', '1');
    ini_set('session.cookie_samesite', 'Lax');
    if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
        ini_set('session.cookie_secure', '1');
    }
    session_start();
}
date_default_timezone_set('Asia/Jakarta');
