<?php
session_start();
require "C:/xampp/htdocs/e_commerce/config/db.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

$id = $_GET['id'];

mysqli_query($conn, "DELETE FROM products WHERE id=$id");

header("Location: products.php");
exit;
