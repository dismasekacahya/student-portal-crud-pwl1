<?php
require_once __DIR__ . '/includes/bootstrap.php';
cekLogin();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validasiCsrf();
    $_SESSION = [];
    $params = session_get_cookie_params();
    setcookie(session_name(), '', ['expires' => time() - 3600, 'path' => $params['path'], 'httponly' => true, 'samesite' => 'Lax']);
    session_destroy();
    header('Location: login.php'); exit;
}
$title = 'Keluar';
require __DIR__ . '/includes/header.php';
?>
<div class="panel narrow"><p>Akhiri sesi Student Portal?</p><form method="post"><?php csrfField(); ?><button>Ya, Keluar</button> <a href="index.php">Batal</a></form></div>
<?php require __DIR__ . '/includes/footer.php'; ?>
