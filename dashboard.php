<?php
require_once __DIR__ . '/includes/bootstrap.php';
cekLogin();
$user = cariUserByNim($conn, $_SESSION['nim']);
if (!$user) { $_SESSION = []; header('Location: login.php'); exit; }
$title = 'Dashboard Mahasiswa';
require __DIR__ . '/includes/header.php';
?>
<p>Selamat datang, <?= e($user['nama']) ?>.</p>
<?php
require __DIR__ . '/includes/student-detail.php';
require __DIR__ . '/includes/footer.php';
