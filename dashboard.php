<?php
session_start();
require_once 'database.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

$pdo = getPDO();

try {
    $totalMovies = $pdo->query("SELECT COUNT(*) FROM movies")->fetchColumn();
    $totalShowtimes = $pdo->query("SELECT COUNT(*) FROM showtimes")->fetchColumn();
    $totalUsers = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
} catch (PDOException $e) {
    $_SESSION['error_message'] = "Database error: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <?php
    if (file_exists('includes/header.php')) {
        include 'includes/header.php';
    } else {
        echo '<nav><a href="index.php">Home</a> <a href="logout.php">Log Out</a></nav>';
    }
    ?>
    <div class="dashboard">
        <h1>Admin Dashboard</h1>
        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="error"><?= htmlspecialchars($_SESSION['error_message']); ?></div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>
        <div class="stats-container">
            <div class="stat-card">
                <h3>Total Movies</h3>
                <p class="stat-number"><?= $totalMovies; ?></p>
            </div>
            <div class="stat-card">
                <h3>Total Showtimes</h3>
                <p class="stat-number"><?= $totalShowtimes; ?></p>
            </div>
            <div class="stat-card">
                <h3>Total Users</h3>
                <p class="stat-number"><?= $totalUsers; ?></p>
            </div>
        </div>
        <div class="quick-links">
            <h2>Quick Links</h2>
            <ul>
                <li><a href="movies.php">Manage Movies</a></li>
                <li><a href="showtimes.php">Manage Showtimes</a></li>
                <li><a href="user.php">Manage Users</a></li>
                <li><a href="bookings.php">Manage Bookings</a></li>
            </ul>
        </div>
    </div>
    <?php
    if (file_exists('includes/footer.php')) {
        include 'includes/footer.php';
    } else {
        echo '<footer><p>&copy; ' . date("Y") . ' Movie Ticket Booking. All rights reserved.</p></footer>';
    }
    ?>
</body>
</html>