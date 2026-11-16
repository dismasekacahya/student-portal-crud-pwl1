<?php

const BOBOT_TUGAS = 0.30;
const BOBOT_UTS = 0.30;
const BOBOT_UAS = 0.40;

function hitungNilaiAkhir(
    $tugas,
    $uts,
    $uas
) {
    return
        ($tugas * BOBOT_TUGAS) +
        ($uts * BOBOT_UTS) +
        ($uas * BOBOT_UAS);
}

function tentukanGrade($nilai) {

    if ($nilai >= 85) {
        return "A";
    } elseif ($nilai >= 70) {
        return "B";
    } elseif ($nilai >= 60) {
        return "C";
    } elseif ($nilai >= 50) {
        return "D";
    }

    return "E";
}

function tentukanStatusKelulusan(
    $nilai,
    $kehadiran
) {

    if (
        $nilai >= 60 &&
        $kehadiran >= 75
    ) {
        return "LULUS";
    }

    return "TIDAK LULUS";
}

function tentukanPredikat(
    $nilai,
    $kehadiran
) {

    if (
        $nilai >= 85 &&
        $kehadiran >= 90
    ) {
        return "SANGAT BAIK";

    } elseif (
        $nilai >= 70 &&
        $kehadiran >= 75
    ) {
        return "BAIK";

    } elseif (
        $nilai >= 60 &&
        $kehadiran >= 75
    ) {
        return "CUKUP";
    }

    return "PERLU PERBAIKAN";
}

function buatPesan($grade) {

    switch ($grade) {

        case "A":
            return
                "Excellent! Pertahankan performa Anda.";

        case "B":
            return
                "Good job! Tingkatkan sedikit lagi.";

        case "C":
            return
                "Cukup baik, tetapi masih perlu latihan.";

        case "D":
            return
                "Perlu meningkatkan pemahaman materi.";

        default:
            return
                "Perlu evaluasi dan latihan lebih lanjut.";
    }
}

function perluRemedial($nilai) {
    return $nilai < 60;
}


// Seluruh query dengan input pengguna memakai prepared statement.
function queryMahasiswa($conn, $condition = '', $value = null) {
    $sql = 'SELECT m.*, p.nama_prodi FROM mahasiswa m JOIN program_studi p ON m.id_prodi = p.id_prodi';
    if ($condition !== '') {
        $stmt = $conn->prepare($sql . ' WHERE ' . $condition);
        $stmt->bind_param('s', $value);
        $stmt->execute();
        return $stmt->get_result();
    }
    return $conn->query($sql . ' ORDER BY m.nama ASC');
}
function cariUserByNim($conn, $nim) {
    return queryMahasiswa($conn, 'm.nim = ?', $nim)->fetch_assoc();
}
function ambilMahasiswaById($conn, $id) {
    return queryMahasiswa($conn, 'm.id = ?', $id)->fetch_assoc();
}
function cariUser($conn, $email, $password) {
    $user = queryMahasiswa($conn, 'm.email = ?', $email)->fetch_assoc();
    return $user && password_verify($password, $user['password']) ? $user : null;
}
function cekLogin() {
    if (($_SESSION['isLogin'] ?? false) !== true) {
        header('Location: login.php');
        exit;
    }
}
function e($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
function input($key) {
    return is_string($_POST[$key] ?? null) ? trim($_POST[$key]) : '';
}
function csrfToken() {
    return $_SESSION['csrf'] ??= bin2hex(random_bytes(32));
}
function csrfField() {
    echo '<input type="hidden" name="csrf" value="' . e(csrfToken()) . '">';
}
function validasiCsrf() {
    if (!is_string($_POST['csrf'] ?? null) || !hash_equals(csrfToken(), $_POST['csrf'])) {
        http_response_code(403);
        exit('Permintaan tidak valid. Muat ulang halaman dan coba lagi.');
    }
}
function idDariUrl() {
    $id = filter_var($_GET['id'] ?? '', FILTER_VALIDATE_INT);
    if ($id === false || $id <= 0) {
        http_response_code(400);
        exit('ID tidak valid.');
    }
    return $id;
}
function mahasiswaAtau404($conn, $id) {
    $row = ambilMahasiswaById($conn, $id);
    if (!$row) {
        http_response_code(404);
        exit('Data mahasiswa tidak ditemukan.');
    }
    return $row;
}
function flashRedirect($message) {
    $_SESSION['flash'] = $message;
    header('Location: index.php');
    exit;
}
