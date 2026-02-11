<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>

    <!-- External CSS -->
    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/auth.css">
</head>
<body>
<div class="auth-container">

    <form method="POST" class="auth-form">
        <h2>sign in</h2>

        <input type="text" name="name" placeholder="Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>

        <button type="submit" name="register">Register</button>
    </form>

</div>
</body>

<?php
require "C:/xampp/htdocs/e_commerce/config/db.php";

if (isset($_POST['register'])) {

    $name  = $_POST['name'];
    $email = $_POST['email'];
    $pass  = $_POST['password'];

    $check = mysqli_query($conn,
        "SELECT * FROM users WHERE email='$email'"
    );

    if (mysqli_num_rows($check) > 0) {
        echo "Email already exists";
    } else {

        mysqli_query($conn,
            "INSERT INTO users (name, email, password)
             VALUES ('$name', '$email', '$pass')"
        );

        header("Location: auth/login.php");
        exit;
    }
}
?>


