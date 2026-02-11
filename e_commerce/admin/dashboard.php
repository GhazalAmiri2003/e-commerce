<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit;
}
?>

<h2>Admin Dashboard</h2>
<a href="../auth/logout.php">Logout</a>
