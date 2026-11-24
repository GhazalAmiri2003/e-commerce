<?php
session_start();
require __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/security.php';
if (isset($_POST['login'])) {
    require_csrf();
    $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
    $pass = $_POST['password'] ?? '';
    if ($email && is_string($pass)) {
        $stmt = $conn->prepare('SELECT id, role, password FROM users WHERE email = ? LIMIT 1');
        $stmt->bind_param('s', $email); $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        if ($user && password_verify($pass, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = (int)$user['id'];
            $_SESSION['role'] = $user['role'];
            unset($_SESSION['csrf_token']);
            header('Location: ' . ($user['role'] === 'admin' ? '../admin/products.php' : '../user/home.php'));
            exit;
        }
    }
    $error = 'Invalid email or password. Legacy accounts require a password reset.';
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
<input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
        <h2>Login</h2>

        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>

        <button type="submit" name="login">Login</button>


        <?php if (isset($error)) echo "<p style='color:red'>$error</p>"; ?>
    </form>
</div>

</body>
</html>
