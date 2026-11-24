<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {
    $user = getenv('DB_USER');
    if ($user === false || $user === '') throw new RuntimeException('DB_USER is required');
    $conn = new mysqli(getenv('DB_HOST') ?: '127.0.0.1', $user, getenv('DB_PASSWORD') ?: '', getenv('DB_NAME') ?: 'ecommerce', (int)(getenv('DB_PORT') ?: 3307));
    $conn->set_charset('utf8mb4');
} catch (Throwable $e) {
    error_log('Database connection failed');
    http_response_code(503);
    exit('Database unavailable');
}
