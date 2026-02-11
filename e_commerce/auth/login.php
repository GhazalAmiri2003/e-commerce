<?php
session_start();
require "C:/xampp/htdocs/e_commerce/config/db.php";

if (isset($_POST['login'])) {

    $email = $_POST['email'];
    $pass  = $_POST['password'];

    $result = mysqli_query($conn,
        "SELECT * FROM users WHERE email='$email' AND password='$pass'"
    );

    if (mysqli_num_rows($result) == 1) {

        $user = mysqli_fetch_assoc($result);

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role']    = $user['role'];

        if ($user['role'] === 'admin') {
            header("Location: ../admin/products.php");
        } else {
            header("Location: ../user/home.php");
        }
        exit;

    } else {
        $error = "Invalid email or password";
    }
}
if (isset($_POST['skip'])) {
    header("Location: ../user/home.php");

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>

    <!-- External CSS -->
    <link rel="stylesheet" href="/e_commerce/assets/css/base.css">
    <link rel="stylesheet" href="/e_commerce/assets/css/auth.css">
</head>
<body>

<div class="auth-container">
    <form method="POST" class="auth-form">
        <h2>Login</h2>

        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>

        <button type="submit" name="login">Login</button>


        <?php if (isset($error)) echo "<p style='color:red'>$error</p>"; ?>
    </form>
</div>

</body>
</html>
