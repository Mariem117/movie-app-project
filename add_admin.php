<?php
session_start();
require_once 'database.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}
$pdo = getPDO();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, email, phone, password, role) VALUES (?, ?, ?, ?, ?, 'admin')");
    $stmt->execute(['New', 'Admin', $email, '+21600000000', $password]);
    $_SESSION['success_message'] = 'Admin created successfully.';
    header('Location: user.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MoodFlix - Add Admin</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <main>
        <div class="container">
            <h2>Add New Admin</h2>
            <form action="add_admin.php" method="POST">
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit">Create Admin</button>
            </form>
        </div>
    </main>
    <?php include '../includes/footer.php'; ?>
</body>
</html>