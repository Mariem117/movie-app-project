<?php
session_start();
require_once 'database.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
$pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$movie_id = filter_input(INPUT_GET, 'movie_id', FILTER_VALIDATE_INT);
if (!$movie_id) {
    header('Location: front_pg.php');
    exit();
}
$stmt = $pdo->prepare("SELECT * FROM movies WHERE id = ?");
$stmt->execute([$movie_id]);
$movie = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$movie) {
    header('Location: front_pg.php');
    exit();
}
$stmt = $pdo->prepare("SELECT * FROM showtimes WHERE movie_id = ?");
$stmt->execute([$movie_id]);
$showtimes = $stmt->fetchAll(PDO::FETCH_ASSOC);
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $showtime_id = filter_input(INPUT_POST, 'showtime_id', FILTER_VALIDATE_INT);
    $seats = filter_input(INPUT_POST, 'seats', FILTER_SANITIZE_STRING);
    $total_price = filter_input(INPUT_POST, 'total_price', FILTER_VALIDATE_FLOAT);
    if ($showtime_id && $seats && $total_price) {
        $stmt = $pdo->prepare("INSERT INTO bookings (user_id, movie_id, showtime, seats, total_price, booking_date) VALUES (?, ?, ?, ?, ?, CURDATE())");
        $stmt->execute([$_SESSION['user_id'], $movie_id, $showtimes[array_search($showtime_id, array_column($showtimes, 'id'))]['showtime'], $seats, $total_price]);
        $_SESSION['booking_details'] = [
            'movie' => $movie['title'],
            'showtime' => $showtimes[array_search($showtime_id, array_column($showtimes, 'id'))]['showtime'],
            'seats' => explode(',', $seats),
            'total_price' => $total_price
        ];
        header('Location: confirmation.php');
        exit();
    } else {
        $_SESSION['error_message'] = "Invalid booking details.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Ticket - <?= htmlspecialchars($movie['title']); ?></title>
    <link rel="stylesheet" href="styles/moviepage.css">
</head>
<body>
    <div class="header">
        <button class="change-mood" onclick="location.href='movies.php?mood=<?= strtolower($movie['mood']); ?>'">⬅ Back</button>
        <span class="mood-title">Book Ticket</span>
        <div class="spacer"></div>
    </div>
    <div class="container">
        <h1>Book Ticket for <?= htmlspecialchars($movie['title']); ?></h1>
        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="error"><?= htmlspecialchars($_SESSION['error_message']); ?></div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>
        <form method="POST">
            <label for="showtime">Select Showtime:</label>
            <select name="showtime_id" id="showtime" required>
                <?php foreach ($showtimes as $showtime): ?>
                    <option value="<?= $showtime['id']; ?>"><?= htmlspecialchars($showtime['showtime']); ?></option>
                <?php endforeach; ?>
            </select>
            <label for="seats">Seats (comma-separated, e.g., A1,A2):</label>
            <input type="text" name="seats" id="seats" required>
            <label for="total_price">Total Price ($):</label>
            <input type="number" name="total_price" id="total_price" step="0.01" required>
            <button type="submit" class="book-ticket">Confirm Booking</button>
        </form>
    </div>
</body>
</html>