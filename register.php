<?php
session_start();
require_once 'database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get and sanitize form data
    $first_name = filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_STRING);
    $last_name = filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $phone_code = $_POST['phone_code'];
    $phone_number = $_POST['phone_number'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validate inputs
    $errors = [];
    
    if (empty($first_name)) {
        $errors[] = "First name is required";
    }
    
    if (empty($last_name)) {
        $errors[] = "Last name is required";
    }
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid email is required";
    }
    
    if (empty($phone_number)) {
        $errors[] = "Phone number is required";
    }
    
    if (empty($password)) {
        $errors[] = "Password is required";
    }
    
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match";
    }
    
    // Check if email already exists
    $pdo = getPDO();
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $email_exists = $stmt->fetchColumn() > 0;
    
    if ($email_exists) {
        $errors[] = "Email already in use";
    }
    
    // If no errors, create the user
    if (empty($errors)) {
        $phone = $phone_code . $phone_number;
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, email, phone, password) VALUES (?, ?, ?, ?, ?)");
        
        if ($stmt->execute([$first_name, $last_name, $email, $phone, $hashed_password])) {
            $_SESSION['success_message'] = "Registration successful!Now you can choose your movie";
            header('Location: index.php');
            exit();
        } else {
            $_SESSION['error_message'] = "Registration failed. Please try again.";
            header('Location: sign_up.php');
            exit();
        }
    } else {
        $_SESSION['error_message'] = implode("<br>", $errors);
        header('Location: sign_up.php');
        exit();
    }
}
else {
    // If not POST request, redirect to sign up page
    header('Location: sign_up.php');
    exit();
}
?>