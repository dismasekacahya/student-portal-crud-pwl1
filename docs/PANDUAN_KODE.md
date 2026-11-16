# Panduan memahami project

Baca sambil menjalankan aplikasi dan mencoba perubahan kecil sendiri.

1. **Koneksi:** `config.php` membuat objek `mysqli`. `set_charset('utf8mb4')` menentukan encoding koneksi. Konfigurasi lokal tidak masuk Git.
2. **Session:** `includes/bootstrap.php` menjalankan `session_start()`. `cekLogin()` mengarahkan pengguna yang belum login ke `login.php`. Setelah login benar, ID session diperbarui untuk mencegah penggunaan ID session lama.
3. **Password:** `password_hash()` membuat hash saat tambah/ganti password. `password_verify()` memeriksa password saat login. Hash tidak dapat dipakai langsung sebagai password login.
4. **Read:** `queryMahasiswa()` mengambil data dari tabel mahasiswa dan JOIN program studi. Primary key `id` menentukan record untuk detail/edit/hapus; NIM dapat berubah.
5. **Create:** `tambah.php` memuat form bersama. GET menampilkan form; POST memeriksa token, input wajib, email, prodi, rentang nilai, lalu INSERT.
6. **Update:** `edit.php?id=...` mengambil data lama. POST menjalankan UPDATE dengan `WHERE id = ?`. Tanpa WHERE semua mahasiswa bisa terubah. Password kosong sengaja tidak diperbarui.
7. **Delete:** GET `hapus.php?id=...` hanya menampilkan konfirmasi. POST bertoken menjalankan DELETE satu ID. Foreign key menjaga relasi prodi. Akun aktif tidak boleh menghapus dirinya.
8. **Prepared statement:** tanda `?` adalah placeholder nilai. `bind_param()` memasangkan nilai dengan tipe (`s` string, `i` integer, `d` desimal), sehingga input bukan bagian sintaks SQL.
9. **Escaping:** `e()` membungkus `htmlspecialchars()` agar nama/email yang mengandung HTML ditampilkan sebagai teks. Ini berbeda dari prepared statement yang melindungi query SQL.
10. **POST/Redirect/GET:** `flashRedirect()` menyimpan pesan session dan mengalihkan ke daftar setelah sukses. Refresh halaman daftar tidak mengulangi INSERT.
11. **Perhitungan:** `hitungNilaiAkhir(80,78,90)` menghasilkan `83.40`; grade B. Lulus memerlukan nilai minimal 60 dan kehadiran minimal 75. Rumus dan batas grade mengikuti functions.php pertemuan 7.
12. **CSRF:** token acak disimpan di session dan dikirim melalui hidden input. `hash_equals()` membandingkan token sebelum aksi POST, sehingga situs lain tidak dapat mengirim perubahan dengan menebak token.

Latihan: tambahkan mahasiswa fiktif, ubah nilai UAS, periksa perubahan nilai akhir; coba NIM duplikat, nilai 101, dan ID tidak ada. Jelaskan mengapa masing-masing ditolak sebelum mengumpulkan.
