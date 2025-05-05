<?php
session_start();
require_once 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    // Basic validation
    if (!$email || !$password) {
        $_SESSION['error_message'] = "Email and password are required.";
        header('Location: add_admin.php');
        exit();
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error_message'] = "Invalid email format.";
        header('Location: add_admin.php');
        exit();
    }

    // Check if email already exists
    $pdo = getPDO();
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetchColumn() > 0) {
        $_SESSION['error_message'] = "Email already exists.";
        header('Location: add_admin.php');
        exit();
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Insert new admin
    $stmt = $pdo->prepare("INSERT INTO users (email, password, role) VALUES (?, ?, 'admin')");
    try {
        $stmt->execute([$email, $hashed_password]);
        $_SESSION['success_message'] = "Admin account created successfully.";
        header('Location: dashboard.php');
        exit();
    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Error creating admin account: " . $e->getMessage();
        header('Location: add_admin.php');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Admin</title>
    <link rel="stylesheet" href="sign_up.css">
</head>
<body>
<div class="form-container">
    <h1>Add New Admin</h1>
    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="error"><?= htmlspecialchars($_SESSION['error_message']) ?></div>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>
    <form action="add_admin.php" method="POST">
        <input type="email" name="email" placeholder="Email *" required>
        <input type="password" name="password" placeholder="Password *" required>
        <button type="submit">Add Admin</button>
    </form>
</div>
</body>
</html>