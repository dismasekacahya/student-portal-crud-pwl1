<?php
$editing = isset($id);
$errors = [];
$fields = ['nim', 'nama', 'email', 'id_prodi', 'nilai_tugas', 'nilai_uts', 'nilai_uas', 'kehadiran'];
$data = $editing ? mahasiswaAtau404($conn, $id) : array_fill_keys($fields, '');
$prodi = $conn->query('SELECT * FROM program_studi ORDER BY nama_prodi')->fetch_all(MYSQLI_ASSOC);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validasiCsrf();
    foreach ($fields as $field) { $data[$field] = input($field); }
    $password = is_string($_POST['password'] ?? null) ? $_POST['password'] : '';
    if (!preg_match('/^[0-9]{1,20}$/D', $data['nim'])) { $errors[] = 'NIM wajib berisi 1–20 digit.'; }
    if ($data['nama'] === '' || mb_strlen($data['nama']) > 100) { $errors[] = 'Nama wajib diisi, maksimal 100 karakter.'; }
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL) || strlen($data['email']) > 100) { $errors[] = 'Format email tidak valid (maksimal 100 karakter).'; }
    if (!in_array($data['id_prodi'], array_map('strval', array_column($prodi, 'id_prodi')), true)) { $errors[] = 'Pilih program studi yang tersedia.'; }
    foreach (['nilai_tugas', 'nilai_uts', 'nilai_uas', 'kehadiran'] as $field) {
        if (!is_numeric($data[$field]) || !is_finite((float) $data[$field]) || $data[$field] < 0 || $data[$field] > 100) {
            $errors[] = str_replace('_', ' ', ucfirst($field)) . ' harus angka 0–100.';
        }
    }
    if (is_numeric($data['kehadiran']) && (float) $data['kehadiran'] !== (float) (int) $data['kehadiran']) { $errors[] = 'Kehadiran harus bilangan bulat.'; }
    if ((!$editing || $password !== '') && (strlen($password) < 6 || strlen($password) > 72)) { $errors[] = 'Password harus 6–72 byte.'; }
    if (!$errors) {
        try {
            $conn->begin_transaction();
            if ($editing) {
                $stmt = $conn->prepare('UPDATE mahasiswa SET nim=?, nama=?, email=?, id_prodi=?, nilai_tugas=?, nilai_uts=?, nilai_uas=?, kehadiran=? WHERE id=?');
                $stmt->bind_param('sssidddii', $data['nim'], $data['nama'], $data['email'], $data['id_prodi'], $data['nilai_tugas'], $data['nilai_uts'], $data['nilai_uas'], $data['kehadiran'], $id);
                $stmt->execute();
                if ($password !== '') {
                    $hash = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $conn->prepare('UPDATE mahasiswa SET password=? WHERE id=?');
                    $stmt->bind_param('si', $hash, $id);
                    $stmt->execute();
                }
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare('INSERT INTO mahasiswa (nim,nama,email,id_prodi,nilai_tugas,nilai_uts,nilai_uas,kehadiran,password) VALUES (?,?,?,?,?,?,?,?,?)');
                $stmt->bind_param('sssidddis', $data['nim'], $data['nama'], $data['email'], $data['id_prodi'], $data['nilai_tugas'], $data['nilai_uts'], $data['nilai_uas'], $data['kehadiran'], $hash);
                $stmt->execute();
            }
            $conn->commit();
            if ($editing && (int) $_SESSION['id'] === $id) { $_SESSION['nim'] = $data['nim']; }
            flashRedirect($editing ? 'Data mahasiswa berhasil diperbarui.' : 'Data mahasiswa berhasil ditambahkan.');
        } catch (mysqli_sql_exception $e) {
            $conn->rollback();
            error_log($e->getMessage());
            $errors[] = $e->getCode() === 1062 ? 'NIM atau email sudah digunakan.' : 'Data gagal disimpan. Periksa koneksi database.';
        }
    }
}
$title = $editing ? 'Edit Mahasiswa' : 'Tambah Mahasiswa';
require __DIR__ . '/header.php';
?>
<a href="index.php">← Kembali ke daftar</a>
<div class="panel narrow">
<?php if ($errors): ?><div class="error" role="alert"><strong>Periksa data berikut:</strong><ul><?php foreach ($errors as $error): ?><li><?= e($error) ?></li><?php endforeach; ?></ul></div><?php endif; ?>
<form method="post"><?php csrfField(); ?>
<label for="nim">NIM</label><input id="nim" name="nim" inputmode="numeric" pattern="[0-9]{1,20}" maxlength="20" required value="<?= e($data['nim']) ?>">
<label for="nama">Nama</label><input id="nama" name="nama" maxlength="100" required value="<?= e($data['nama']) ?>">
<label for="email">Email</label><input id="email" name="email" type="email" maxlength="100" required value="<?= e($data['email']) ?>">
<label for="id_prodi">Program Studi</label><select id="id_prodi" name="id_prodi" required><option value="">Pilih program studi</option><?php foreach ($prodi as $p): ?><option value="<?= (int) $p['id_prodi'] ?>" <?= (string) $data['id_prodi'] === (string) $p['id_prodi'] ? 'selected' : '' ?>><?= e($p['nama_prodi']) ?></option><?php endforeach; ?></select>
<div class="grid"><?php foreach (['nilai_tugas' => 'Nilai Tugas', 'nilai_uts' => 'Nilai UTS', 'nilai_uas' => 'Nilai UAS', 'kehadiran' => 'Kehadiran (%)'] as $field => $label): ?><div><label for="<?= $field ?>"><?= $label ?></label><input id="<?= $field ?>" type="number" name="<?= $field ?>" min="0" max="100" step="<?= $field === 'kehadiran' ? '1' : '0.01' ?>" required value="<?= e($data[$field]) ?>"></div><?php endforeach; ?></div>
<label for="password">Password<?= $editing ? ' baru (kosongkan jika tetap)' : '' ?></label><input id="password" name="password" type="password" minlength="6" maxlength="72" autocomplete="new-password" <?= $editing ? '' : 'required' ?>>
<button type="submit"><?= $editing ? 'Simpan Perubahan' : 'Simpan Mahasiswa' ?></button>
</form></div>
<?php require __DIR__ . '/footer.php'; ?>
