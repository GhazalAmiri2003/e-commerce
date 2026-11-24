<?php
session_start();
require __DIR__ . '/config/db.php';
require_once __DIR__ . '/config/security.php';
if (isset($_POST['register'])) {
    require_csrf();
    $name = trim((string)($_POST['name'] ?? ''));
    $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
    $pass = $_POST['password'] ?? '';
    if (!$email || $name === '' || !is_string($pass) || strlen($pass) < 8 || strlen($pass) > 72) {
        $error = 'Enter a valid name, email and password (8–72 bytes).';
    } else {
        $stmt = $conn->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
        $stmt->bind_param('s', $email); $stmt->execute();
        if ($stmt->get_result()->fetch_assoc()) $error = 'Registration failed.';
        else {
            $hash = password_hash($pass, PASSWORD_DEFAULT);
            try {
                $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'user')");
                $stmt->bind_param('sss', $name, $email, $hash); $stmt->execute();
                header('Location: auth/login.php'); exit;
            } catch (mysqli_sql_exception $e) { $error = 'Registration failed.'; }
        }
    }
}
?>
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
<input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
        <h2>sign in</h2>

        <input type="text" name="name" placeholder="Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>

        <button type="submit" name="register">Register</button>
    <?php if (isset($error)) echo '<p>' . h($error) . '</p>'; ?>
</form>

</div>
</body>

