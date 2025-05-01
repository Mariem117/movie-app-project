<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}
require_once 'database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    
    // Use the connection from database.php
    $pdo = getPDO();
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['admin_logged_in'] = $user['role'] === 'admin';
        header('Location: front_pg.php');
    } else {
        $_SESSION['error_message'] = "Invalid email or password.";
        header('Location: login.php');
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Ticket - Log In</title>
    <link rel="stylesheet" href="sign_up.css">
</head>
<body>
    <div class="form-container">
        <img src="vector-3d-movie-glasses.jpg" alt="Left Image" class="side-image left">
        <div class="title-box">
            <h1>Log In</h1>
        </div>
        <img src="vector-3d-movie-glasses.jpg" alt="Right Image" class="side-image right">
        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="error"><?= htmlspecialchars($_SESSION['error_message']); ?></div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="success"><?= htmlspecialchars($_SESSION['success_message']); ?></div>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>
        <form action="login.php" method="POST">
            <input type="email" name="email" placeholder="Email *" required>
            <input type="password" name="password" placeholder="Password *" required>
            <button type="submit">Log In</button>
        </form>
        <p>Don't have an account? <a href="sign_up.php">Sign Up</a></p>
    </div>
</body>
</html>