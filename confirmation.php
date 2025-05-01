<?php
session_start();
if (!isset($_SESSION['booking_details'])) {
    header('Location: front_pg.php');
    exit();
}
$booking = $_SESSION['booking_details'];
unset($_SESSION['booking_details']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation</title>
    <link rel="stylesheet" href="moviepage.css">
</head>
<body>
    <div class="container">
        <h1>Booking Confirmed!</h1>
        <p><strong>Movie:</strong> <?= htmlspecialchars($booking['movie']); ?></p>
        <p><strong>Showtime:</strong> <?= htmlspecialchars($booking['showtime']); ?></p>
        <p><strong>Seats:</strong> <?= htmlspecialchars(implode(', ', $booking['seats'])); ?></p>
        <p><strong>Total Price:</strong> $<?= htmlspecialchars($booking['total_price']); ?></p>
        <a href="front_pg.php" class="book-ticket">Back to Home</a>
    </div>
</body>
</html>