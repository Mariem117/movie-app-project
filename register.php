<?php
session_start();
require_once 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $phone_code = trim($_POST['phone_code']);
    $phone_number = trim($_POST['phone_number']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Combine phone_code and phone_number
    $phone = $phone_code . $phone_number;

    // Validation
    if (empty($first_name) || empty($last_name) || empty($email) || empty($phone_code) || empty($phone_number) || empty($password) || empty($confirm_password)) {
        $_SESSION['error_message'] = 'All fields are required.';
        header('Location: sign_up.php');
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error_message'] = 'Invalid email format.';
        header('Location: sign_up.php');
        exit();
    }

    if ($password !== $confirm_password) {
        $_SESSION['error_message'] = 'Passwords do not match.';
        header('Location: sign_up.php');
        exit();
    }

    try {
        $pdo = getPDO();

        // Check if email already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $_SESSION['error_message'] = 'Email already registered.';
            header('Location: sign_up.php');
            exit();
        }

        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Fixed SQL statement - matching the number of parameters with placeholders
        $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, email, phone_code, phone, password, role) VALUES (?, ?, ?, ?, ?, ?, 'user')");
        $stmt->execute([$first_name, $last_name, $email, $phone_code, $phone, $hashed_password]);

        // Log in the new user
        $user_id = $pdo->lastInsertId();
        $_SESSION['user_id'] = $user_id;
        $_SESSION['role'] = 'user';
        header('Location: index.php');
        exit();
    } catch (PDOException $e) {
        $_SESSION['error_message'] = 'Database error: ' . $e->getMessage();
        header('Location: sign_up.php');
        exit();
    }
} else {
    header('Location: sign_up.php');
    exit();
}
?>