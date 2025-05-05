<?php
require_once 'database.php';

// Check if user exists and get current password hash
try {
    $pdo = getPDO();
    $stmt = $pdo->prepare("SELECT id, email, password, role FROM users WHERE email = :email");
    $stmt->execute(['email' => 'admin@gmail.com']);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo "User admin@gmail.com does not exist in the database!";
    } else {
        echo "User found:<br>";
        echo "ID: " . $user['id'] . "<br>";
        echo "Email: " . $user['email'] . "<br>";
        echo "Current Password Hash: " . $user['password'] . "<br>";
        echo "Role: " . $user['role'] . "<br><br>";
        
        // Try to verify the test password
        $test_password = 'admin123';
        $verification = password_verify($test_password, $user['password']);
        echo "Password verification test:<br>";
        echo "Testing password 'admin123': " . ($verification ? "MATCH" : "NO MATCH") . "<br><br>";
        
        // Force update password with new hash
        $new_hash = password_hash($test_password, PASSWORD_DEFAULT);
        echo "New hash generated: " . $new_hash . "<br>";
        
        $update = $pdo->prepare("UPDATE users SET password = :password WHERE email = :email");
        $result = $update->execute([
            'password' => $new_hash,
            'email' => 'admin@gmail.com'
        ]);
        
        echo "Password update: " . ($result ? "SUCCESS" : "FAILED") . "<br>";
        echo "Your password has been reset to 'admin123'. Please try logging in again.";
    }
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}
?>