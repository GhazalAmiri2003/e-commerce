<?php
session_start();
require "../config/db.php";

if (!isset($_SESSION['user_id'])) {
    echo "login";
    exit;
}

$product_id = (int)$_POST['product_id'];

// گرفتن stock
$res = mysqli_query($conn,
    "SELECT stock FROM products WHERE id = $product_id"
);
$product = mysqli_fetch_assoc($res);

if ($product['stock'] <= 0) {
    echo "out_of_stock";
    exit;
}

// ساخت cart
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$_SESSION['cart'][$product_id] =
    (isset($_SESSION['cart'][$product_id]) ? $_SESSION['cart'][$product_id] : 0) + 1;

// کم کردن stock
mysqli_query($conn,
    "UPDATE products SET stock = stock - 1 WHERE id = $product_id"
);

// ثبت زمان
$_SESSION['cart_time'] = time();

echo "added";
