<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Ticket - Sign Up</title>
    <link rel="stylesheet" href="sign_up.css">
</head>
<body>
    <div class="form-container">
        <img src="vector-3d-movie-glasses.jpg" alt="Left Image" class="side-image left">
        <div class="title-box">
            <h1>Sign Up</h1>
        </div>
        <img src="vector-3d-movie-glasses.jpg" alt="Right Image" class="side-image right">
        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="error"><?= htmlspecialchars($_SESSION['error_message']); ?></div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>
        <form action="register.php" method="POST">
            <input type="text" name="first_name" placeholder="First Name *" required>
            <input type="text" name="last_name" placeholder="Last Name *" required>
            <input type="email" name="email" placeholder="Email *" required>
            <div class="phone-container">
                <select id="phone-number" name="phone_code" required>
                    <option value="+216">🇹🇳 +216</option>
                    <option value="+1">🇺🇸 +1</option>
                    <option value="+44">🇬🇧 +44</option>
                    <option value="+91">🇮🇳 +91</option>
                </select>
                <input type="tel" name="phone_number" placeholder="Phone Number *" required>
            </div>
            <input type="password" name="password" placeholder="Password *" required>
            <input type="password" name="confirm_password" placeholder="Password Confirmation *" required>
            <button type="submit">Sign Up</button>
        </form>
        <p>Already have an account? <a href="login.php">Log In</a></p>
    </div>
</body>
</html>