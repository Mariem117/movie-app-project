<?php
session_start();
if (!isset($_SESSION['booking_details'])) {
    header('Location: index.php');
    exit();
}
$booking = $_SESSION['booking_details'];
unset($_SESSION['booking_details']);  // Clear the booking details from session after use
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation</title>
    <link rel="icon" href="images/img.png" type="image/x-icon">
    <style>
        .container {
            background: rgba(30, 30, 30, 0.9);
            padding: 30px;
            border-radius: 10px;
            max-width: 600px;
            margin: 50px auto;
            text-align: center;
            color: white;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
        }
        
        h1 {
            color: #00c321;
            margin-bottom: 30px;
        }
        
        p {
            font-size: 18px;
            margin: 15px 0;
        }
        
        .booking-details {
            background: rgba(0, 0, 0, 0.3);
            padding: 20px;
            border-radius: 8px;
            margin: 25px 0;
        }
        
        .book-ticket {
            display: inline-block;
            background: #00c321;
            color: black;
            padding: 12px 25px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            margin-top: 20px;
            transition: all 0.3s ease;
        }
        
        .book-ticket:hover {
            background: #009e1a;
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Booking Confirmed!</h1>
        
        <div class="booking-details">
            <p><strong>Movie:</strong> <?= htmlspecialchars($booking['movie'] ?? 'Unknown'); ?></p>
            <p><strong>Cinema:</strong> <?= htmlspecialchars($booking['cinema'] ?? 'Unknown'); ?></p>
            <p><strong>Showtime:</strong> <?= htmlspecialchars(date('d M Y, H:i', strtotime($booking['showtime'] ?? 'now'))); ?></p>
            <p><strong>Seats:</strong> <?= htmlspecialchars(implode(', ', $booking['seats'] ?? ['None selected'])); ?></p>
            <p><strong>Total Price:</strong> $<?= htmlspecialchars(number_format($booking['total_price'] ?? 0, 2)); ?></p>
        </div>
        
        <p>Your booking has been confirmed! An email with your ticket details has been sent to your registered email address.</p>
        
        <a href="index.php" class="book-ticket">Back to Home</a>
    </div>
</body>
</html>