<?php
session_start();
require_once __DIR__ . '/../config/security.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit;
}
?>

<h2>Admin Dashboard</h2>
<form action="../auth/logout.php" method="POST" style="display:inline"><input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>"><button type="submit">Logout</button></form>
