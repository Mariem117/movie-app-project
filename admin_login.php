<?php
session_start();
require_once 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    $pdo = getPDO();
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND role = 'admin'");
    $stmt->execute([$email]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    error_log("Admin query for email=$email, found=" . ($admin ? 'yes' : 'no'));
if ($admin && password_verify($password, $admin['password'])) {
    error_log("Admin login successful: user_id=" . $admin['id']);
    $_SESSION['user_id'] = $admin['id'];
    $_SESSION['role'] = $admin['role'];
    $_SESSION['admin_logged_in'] = true;
    header('Location: dashboard.php');
    exit();
} else {
    error_log("Admin login failed: email=$email");
    $_SESSION['error_message'] = "Invalid email or password.";
    header('Location: admin_login.php');
    exit();
}
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <link rel="stylesheet" href="sign_up.css">
</head>
<body>
<div class="form-container">
    <h1>Admin Log In</h1>
    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="error"><?= htmlspecialchars($_SESSION['error_message']) ?></div>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>
    <form action="admin_login.php" method="POST">
        <input type="email" name="email" placeholder="Admin Email *" required>
        <input type="password" name="password" placeholder="Password *" required>
        <button type="submit">Log In</button>
    </form>
</div>
</body>
</html>