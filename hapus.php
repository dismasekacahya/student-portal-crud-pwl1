<?php
require_once __DIR__ . '/includes/bootstrap.php';
cekLogin();
$id = idDariUrl();
$mahasiswa = mahasiswaAtau404($conn, $id);
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validasiCsrf();
    if ((int) $_SESSION['id'] === $id) {
        http_response_code(422);
        $error = 'Akun yang sedang digunakan tidak dapat dihapus. Gunakan akun lain.';
    } else {
        try {
            $stmt = $conn->prepare('DELETE FROM mahasiswa WHERE id = ?');
            $stmt->bind_param('i', $id);
            $stmt->execute();
            flashRedirect('Data mahasiswa berhasil dihapus.');
        } catch (mysqli_sql_exception $e) {
            error_log($e->getMessage());
            $error = 'Data tidak dapat dihapus. Periksa relasi database.';
        }
    }
}
$title = 'Hapus Mahasiswa';
require __DIR__ . '/includes/header.php';
?>
<div class="panel narrow"><p>Apakah Anda yakin ingin menghapus mahasiswa berikut? Data yang dihapus tidak dapat dikembalikan.</p>
<dl><?php foreach (['nim'=>'NIM','nama'=>'Nama','email'=>'Email','nama_prodi'=>'Program Studi'] as $field=>$label): ?><dt><?= $label ?></dt><dd><?= e($mahasiswa[$field]) ?></dd><?php endforeach; ?></dl>
<?php if ($error): ?><p class="error" role="alert"><?= e($error) ?></p><?php endif; ?>
<form method="post"><?php csrfField(); ?><button class="danger" type="submit">Ya, Hapus</button> <a href="index.php">Batal</a></form></div>
<?php require __DIR__ . '/includes/footer.php'; ?>
