<?php
require_once __DIR__ . '/includes/bootstrap.php';
if (($_SESSION['isLogin'] ?? false) === true) {
    header('Location: dashboard.php'); exit;
}
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validasiCsrf();
    $user = cariUser($conn, input('email'), is_string($_POST['password'] ?? null) ? $_POST['password'] : '');
    if ($user) {
        session_regenerate_id(true);
        $_SESSION['isLogin'] = true;
        $_SESSION['nim'] = $user['nim'];
        $_SESSION['id'] = $user['id'];
        header('Location: dashboard.php'); exit;
    }
    $error = 'Email atau password salah.';
}
$title = 'Masuk ke Student Portal';
require __DIR__ . '/includes/header.php';
?>
<div class="panel narrow"><p>Akses data mahasiswa dan evaluasi akademik.</p>
<?php if ($error): ?><p class="error" role="alert"><?= e($error) ?></p><?php endif; ?>
<form method="post"><?php csrfField(); ?>
<label for="email">Email</label><input id="email" type="email" name="email" maxlength="100" autocomplete="username" required value="<?= e(input('email')) ?>">
<label for="password">Password</label><input id="password" type="password" name="password" autocomplete="current-password" required>
<button type="submit">Masuk</button></form>
<p class="hint">Demo lokal: andi@ukrida.ac.id / 123456</p></div>
<?php require __DIR__ . '/includes/footer.php'; ?>
