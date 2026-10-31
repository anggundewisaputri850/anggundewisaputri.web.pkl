# sistem_arsip_surat

Aplikasi pengarsipan surat dengan **PHP Native + MySQL** untuk XAMPP. Tidak menggunakan Laravel atau framework PHP. CSS dan JavaScript disimpan terpisah dari file PHP.

## Struktur folder

```text
sistem_arsip_surat/
├── index.php                  # halaman login sederhana
├── proses_login.php           # proses login
├── logout.php                 # proses logout
├── admin/                     # halaman untuk Admin
│   ├── dashboard.php
│   ├── surat_masuk/            # index.php, form.php, detail.php
│   ├── status_surat/           # index.php, detail.php
│   ├── surat_keluar/           # index.php, form.php, detail.php
│   └── arsip_surat/            # index.php, detail.php
├── user/                      # halaman untuk User
│   ├── dashboard.php
│   ├── surat_masuk/
│   ├── status_surat/
│   ├── surat_keluar/
│   └── arsip_surat/
├── include/
│   ├── disposisi_surat_panel.php # panel disposisi pada detail surat masuk/status
│   ├── config.php             # koneksi database
│   ├── session.php            # pengaturan sesi
│   ├── auth_admin.php         # pembatas akses Admin
│   ├── auth_user.php          # pembatas akses User
│   ├── header.php             # bagian awal layout
│   ├── sidebar.php            # sidebar
│   ├── footer.php             # bagian akhir layout
│   ├── css/style.css           # CSS halaman utama
│   ├── css/login.css           # CSS halaman login
│   └── js/script.js            # JavaScript kecil untuk UI
├── proses/                    # proses simpan surat, disposisi, status, file, dan ekspor
├── assets/img/logo.svg
├── uploads/surat/             # lampiran surat
└── database/pengarsipan_surat.sql
```

Tidak ada file `functions.php` atau `helpers.php`. Halaman PHP menggunakan `include/` untuk bagian layout, konfigurasi, dan pemeriksaan akses; proses surat dipisah ke file proses yang namanya menjelaskan tugasnya.

## Cara instalasi di XAMPP

1. Ekstrak folder ini ke `C:\xampp\htdocs\sistem_arsip_surat`.
2. Jalankan Apache dan MySQL melalui XAMPP Control Panel.
3. Buka `http://localhost/phpmyadmin`.
4. Pilih menu **Import** dan impor `database/pengarsipan_surat.sql`. File tersebut membuat lima tabel: `users`, `surat`, `surat_status_logs`, `arsip_surat`, dan `disposisi_surat`. **Impor ulang akan menghapus tabel dan data sebelumnya**, jadi jangan jalankan pada database aktif yang berisi data penting. Untuk database yang sudah berjalan, gunakan `database/migrasi_disposisi_surat.sql` hanya bila tabel `disposisi_surat` belum ada.
5. Buka `http://localhost/sistem_arsip_surat/`.

Pengaturan koneksi ada di `include/config.php`. Pengaturan standar XAMPP biasanya menggunakan host `localhost`, username `root`, dan password kosong.

## Akun uji

- Admin: username `admin`, password `Admin123!`
- User: username `user`, password `User123!`

Ubah password kedua akun setelah login pertama. SQL menyimpan password dalam bentuk hash, bukan teks biasa.

## Alur status

- Surat masuk: Diterima → Menunggu Disposisi → Dalam Proses → Selesai atau Ditolak.
- Surat keluar: Draft → Dalam Proses → Selesai atau Dibatalkan.
- Saat Admin mengubah status ke **Selesai**, surat masuk ke arsip otomatis dan perubahan dicatat dalam timeline.
- Jika Admin mengubah status Selesai kembali ke status lain, arsip aktifnya dikeluarkan.
- User dapat melihat riwayat status, tetapi hanya Admin yang dapat mengubah status.

## Catatan

- File `database/pengarsipan_surat.sql` membuat ulang lima tabel: `users`, `surat`, `surat_status_logs`, `arsip_surat`, dan `disposisi_surat`. Cadangkan database lebih dulu jika berisi data penting.
- Lampiran dibatasi PDF, JPG, dan PNG dengan ukuran maksimum 5 MB.
- Pastikan folder `uploads/surat` dapat ditulisi oleh PHP/Apache.
- Website ini ditujukan untuk penggunaan lokal/pembelajaran. Sebelum digunakan pada server publik, lakukan audit dan penyesuaian keamanan server.


## Disposisi surat

Pada detail Surat Masuk, Admin dapat menambah dan memperbarui disposisi melalui tabel `disposisi_surat`. Informasi yang dicatat meliputi penerima disposisi, instruksi, batas waktu, status tindak lanjut, catatan tindak lanjut, dan pengguna yang membuat atau memperbarui catatan. User dapat melihat informasi disposisi, tetapi tidak dapat mengubahnya. Fitur ini hanya digunakan untuk surat masuk; surat keluar tetap dikelola melalui alur status surat.

Lampiran surat tetap disimpan sebagai satu nama file pada kolom `surat.uploaded_file`; aplikasi tidak menggunakan tabel `surat_lampiran`.
