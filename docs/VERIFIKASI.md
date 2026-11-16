# Hasil verifikasi

Tanggal: 16 November 2026 (Asia/Makassar).

Lingkungan aktual: macOS, PHP CLI 8.5.12, MariaDB 13.0.2, Chrome headless melalui Playwright. Database diimpor dari `database/student_portal.sql`. PHP server berjalan pada `127.0.0.1:8087`; MariaDB pengujian memakai port 3307/socket lokal. XAMPP/Laragon tidak dijalankan pada sesi pengujian ini.

## Hasil

- **19 file PHP lolos `php -l`**.
- **34 pemeriksaan integrasi HTTP lulus** melalui `tests/http_smoke.py`.
- Pengujian meliputi login wajib, CSRF, percobaan SQL injection pada login, data seed, nilai akhir Andi 83.40/B, ID invalid/404, validasi nilai/prodi, INSERT, XSS escaping, duplikasi, UPDATE, password hash dan password tetap saat edit kosong, perubahan NIM akun aktif, konfirmasi DELETE tanpa mutasi melalui GET, perlindungan akun aktif, DELETE, dan logout.
- Browser Chrome: login, dashboard, tabel daftar, form tambah berlabel, tampilan mobile 390px tanpa overflow halaman, serta tidak ada exception JavaScript.
- Mahasiswa sementara yang dibuat pengujian telah dihapus. Empat mahasiswa demo tetap tersedia.

Uji dijalankan terhadap database nyata, bukan mock. Screenshot aplikasi disiapkan dalam paket pengumpulan lokal. Repository ini menyediakan source untuk dijalankan lokal; tidak ada deployment PHP/MySQL publik.
