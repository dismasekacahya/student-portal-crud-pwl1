<?php
require_once __DIR__ . '/includes/bootstrap.php';
cekLogin();
$result = queryMahasiswa($conn);
$title = 'Data Mahasiswa';
require __DIR__ . '/includes/header.php';
?>
<div class="toolbar"><p>Kelola identitas, program studi, dan nilai mahasiswa.</p><a class="button" href="tambah.php">+ Tambah Mahasiswa</a></div>
<div class="panel table-scroll">
<table><caption><?= $result->num_rows ?> mahasiswa terdaftar</caption><thead><tr><th scope="col">NIM</th><th scope="col">Nama</th><th scope="col">Email</th><th scope="col">Program Studi</th><th scope="col">Aksi</th></tr></thead><tbody>
<?php while ($row = $result->fetch_assoc()): ?>
<tr><td><?= e($row['nim']) ?></td><td><?= e($row['nama']) ?></td><td><?= e($row['email']) ?></td><td><?= e($row['nama_prodi']) ?></td><td class="actions"><a href="detail.php?id=<?= (int) $row['id'] ?>">Detail</a><a href="edit.php?id=<?= (int) $row['id'] ?>">Edit</a><a class="danger-link" href="hapus.php?id=<?= (int) $row['id'] ?>">Hapus</a></td></tr>
<?php endwhile; ?>
<?php if ($result->num_rows === 0): ?><tr><td colspan="5">Belum ada data mahasiswa.</td></tr><?php endif; ?>
</tbody></table></div>
<?php require __DIR__ . '/includes/footer.php'; ?>
