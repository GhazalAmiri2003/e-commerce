<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}
?>

<h2>User Dashboard</h2>
<a href="../auth/logout.php">Logout</a>
