<?php require_once __DIR__ . '/../config/security.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>

    <!-- External CSS -->
    <link rel="stylesheet" href="/e_commerce/assets/css/user.css">

</head>
<body>
<div class="menu">
    <div class="menu-left">
        <a href="home.php">Home</a>

        <a href="../index.php">login</a>
    </div>




    <div class="cart">
        <a href="cart.php">🛒 View Cart</a>
        <form action="../auth/logout.php" method="POST" style="display:inline"><input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>"><button type="submit">Logout</button></form>
    </div>
</div>

<hr>
</body>