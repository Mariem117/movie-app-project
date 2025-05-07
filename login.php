<?php
session_start();
require_once 'database.php';

if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] === 'admin') {
        header('Location: dashboard.php');
    } else {
        header('Location: index.php');
    }
    exit();
}

$error = '';
$debug_mode = false; // Set to true for debugging purposes

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        $error = 'Please enter both email and password.';
    } else {
        try {
            $pdo = getPDO();
            $stmt = $pdo->prepare("SELECT id, email, password, role FROM users WHERE email = :email");
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            error_log("Login attempt for email: $email, User found: " . ($user ? 'Yes' : 'No'));

            if (!$user) {
                $error = 'No user found with email: ' . htmlspecialchars($email);
            } else {
                // For debugging - check password values
                if ($debug_mode) {
                    error_log("Input password: $password");
                    error_log("Stored hash: " . $user['password']);
                }
                
                // Try with and without trim
                $trimmed_verify = password_verify(trim($password), $user['password']);
                $normal_verify = password_verify($password, $user['password']);
                
                if ($debug_mode) {
                    error_log("Trimmed verify result: " . ($trimmed_verify ? 'true' : 'false'));
                    error_log("Normal verify result: " . ($normal_verify ? 'true' : 'false'));
                }
                
                if (!$trimmed_verify && !$normal_verify) {
                    // Check if password was hashed with MD5 or another algorithm
                    if (strlen($user['password']) === 32 && ctype_xdigit($user['password'])) {
                        // Likely MD5 hash
                        $md5_match = (md5($password) === $user['password']);
                        if ($debug_mode) {
                            error_log("MD5 match: " . ($md5_match ? 'true' : 'false'));
                        }
                        
                        if ($md5_match) {
                            // Upgrade to bcrypt hash for future logins
                            $new_hash = password_hash($password, PASSWORD_DEFAULT);
                            $update_stmt = $pdo->prepare("UPDATE users SET password = :password WHERE id = :id");
                            $update_stmt->execute(['password' => $new_hash, 'id' => $user['id']]);
                            error_log("Upgraded password hash for user ID: " . $user['id']);
                            
                            // Continue with login
                            $_SESSION['user_id'] = $user['id'];
                            $_SESSION['role'] = $user['role'];
                            error_log("Login successful after MD5 upgrade for email: $email, Role: {$user['role']}");
                            if ($user['role'] === 'admin') {
                                header('Location: dashboard.php');
                            } else {
                                header('Location: index.php');
                            }
                            exit();
                        }
                    }
                    
                    $error = 'Password does not match for email: ' . htmlspecialchars($email);
                    error_log("Password verification failed for email: $email");
                } else {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['role'] = $user['role'];
                    error_log("Login successful for email: $email, Role: {$user['role']}");
                    if ($user['role'] === 'admin') {
                        header('Location: dashboard.php');
                    } else {
                        header('Location: index.php');
                    }
                    exit();
                }
            }
        } catch (PDOException $e) {
            $error = 'Database error: ' . $e->getMessage();
            error_log("Database error during login: " . $e->getMessage());
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Ticket - Log In</title>
    <link rel="stylesheet" href="login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="form-container">
        <button onclick="location.href='index.php'" class="home-button">⬅ HOME</button>
        <h2>Log In</h2>
        <?php if ($error): ?>
            <p class="error"><?= htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <form method="POST" action="">
            <input type="email" name="email" placeholder="Email *" required>
            <input type="password" name="password" placeholder="Password *" required>
            <div class="text-right">
                <a href="#">Forgot password?</a>
            </div>
            <div class="container-login-form-btn">
                <div class="wrap-login-form-btn">
                    <button class="login-form-btn">Login</button>
                </div>
            </div>
            <div class="flex-col-c">
                <span class="txt1">Or Sign Up Using</span>
                <a href="sign_up.php" class="txt2">Sign Up</a>
            </div>
            <div class="flex-c-m">
                <a href="#" class="login-social-item bg3">
                    <i class="fab fa-google"></i>
                </a>
            </div>
        </form>
    </div>
</body>
</html>