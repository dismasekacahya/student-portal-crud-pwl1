<?php
require_once __DIR__ . '/includes/bootstrap.php';
cekLogin();
// Tautan ?nim= dari project lama tetap dapat digunakan.
if (isset($_GET['nim']) && is_string($_GET['nim'])) {
    $user = cariUserByNim($conn, $_GET['nim']);
    if (!$user) { http_response_code(404); exit('Data mahasiswa tidak ditemukan.'); }
} else {
    $user = mahasiswaAtau404($conn, idDariUrl());
}
$title = 'Detail Mahasiswa';
require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/student-detail.php';
require __DIR__ . '/includes/footer.php';
