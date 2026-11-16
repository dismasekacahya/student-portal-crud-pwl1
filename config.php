<?php
// Konfigurasi lokal opsional tidak masuk Git.
$settings = is_file(__DIR__ . '/config.local.php') ? require __DIR__ . '/config.local.php' : [];
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {
    $conn = new mysqli(
        $settings['host'] ?? getenv('DB_HOST') ?: '127.0.0.1',
        $settings['user'] ?? getenv('DB_USER') ?: 'root',
        $settings['password'] ?? getenv('DB_PASSWORD') ?: '',
        $settings['database'] ?? getenv('DB_NAME') ?: 'student_portal',
        (int) ($settings['port'] ?? getenv('DB_PORT') ?: 3306)
    );
    $conn->set_charset('utf8mb4');
} catch (mysqli_sql_exception $e) {
    error_log($e->getMessage());
    http_response_code(503);
    exit('Database belum terhubung. Ikuti langkah instalasi pada README.md.');
}
