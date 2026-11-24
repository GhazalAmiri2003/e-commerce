<?php
session_start();
require __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/security.php';
require_admin(); require_csrf();
$id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT, ['options'=>['min_range'=>1]]);
if (!$id) { http_response_code(400); exit('Invalid product'); }
$stmt = $conn->prepare('DELETE FROM products WHERE id = ?');
$stmt->bind_param('i', $id); $stmt->execute();
header('Location: products.php'); exit;
