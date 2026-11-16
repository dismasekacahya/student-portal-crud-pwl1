<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($title ?? 'Student Portal') ?> · Student Portal</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body>
<header><div class="wrap nav"><a class="brand" href="index.php">SP <span>Student Portal</span></a>
<?php if (($_SESSION['isLogin'] ?? false) === true): ?>
<nav aria-label="Navigasi utama"><a href="index.php">Mahasiswa</a><a href="dashboard.php">Dashboard</a><a href="profile.php">Profil</a><a href="logout.php">Keluar</a></nav>
<?php endif; ?></div></header>
<main class="wrap"><p class="eyebrow">PEMROGRAMAN WEB LANJUTAN I</p>
<h1><?= e($title ?? 'Student Portal') ?></h1>
<?php if (isset($_SESSION['flash'])): ?><p class="success" role="status"><?= e($_SESSION['flash']) ?></p><?php unset($_SESSION['flash']); endif; ?>
