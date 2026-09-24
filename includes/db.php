<?php
require_once __DIR__ . '/config.php';

try {
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];
    if (DB_SSL === '1') {
        $options[PDO::MYSQL_ATTR_SSL_CA] = '/etc/ssl/certs/ca-certificates.crt';
    }
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        $options
    );
} catch (PDOException $ex) {
    http_response_code(500);
    die('<h3 style="font-family:sans-serif">Database connection failed.</h3>'
      . '<p style="font-family:sans-serif">Check that MySQL is running in XAMPP and that you imported <code>database.sql</code>.<br>'
      . 'Details: ' . htmlspecialchars($ex->getMessage()) . '</p>');
}
