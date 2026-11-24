<?php
session_start();
require __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/security.php';
if (empty($_SESSION['user_id'])) { exit('login'); }
require_csrf();
$id = filter_var($_POST['product_id'] ?? null, FILTER_VALIDATE_INT, ['options'=>['min_range'=>1]]);
if (!$id) { http_response_code(400); exit('invalid'); }
if (isset($_SESSION['cart_time']) && time() - $_SESSION['cart_time'] >= 1800) {
    unset($_SESSION['cart'], $_SESSION['cart_time']);
}
$qty = (int)($_SESSION['cart'][$id] ?? 0);
$stmt = $conn->prepare('SELECT stock FROM products WHERE id = ?');
$stmt->bind_param('i', $id); $stmt->execute();
$product = $stmt->get_result()->fetch_assoc();
if (!$product || (int)$product['stock'] <= $qty) exit('out_of_stock');
$_SESSION['cart'][$id] = $qty + 1;
$_SESSION['cart_time'] = time();
echo 'added';
