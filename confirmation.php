<?php
session_start();
require_once 'database.php';

// Process POST data if coming directly from payment form
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_SESSION['booking_details'])) {
    $movie_id = filter_input(INPUT_POST, 'movie_id', FILTER_VALIDATE_INT);
    $showtime_id = filter_input(INPUT_POST, 'showtime_id', FILTER_VALIDATE_INT);
    $seats = filter_input(INPUT_POST, 'seats', FILTER_SANITIZE_STRING);
    $total_price = filter_input(INPUT_POST, 'total_price', FILTER_VALIDATE_FLOAT);
    $cinema_id = filter_input(INPUT_POST, 'cinema_id', FILTER_VALIDATE_INT);
    
    if ($movie_id && $showtime_id && $seats && $total_price) {
        try {
            $pdo = getPDO();
            
            // Get cinema name
            $stmt = $pdo->prepare("SELECT name FROM cinemas WHERE id = ?");
            $stmt->execute([$cinema_id]);
            $cinema_row = $stmt->fetch(PDO::FETCH_ASSOC);
            $cinema_name = $cinema_row ? $cinema_row['name'] : 'Unknown Cinema';
            
            // Get movie details
            $stmt = $pdo->prepare("SELECT title FROM movies WHERE id = ?");
            $stmt->execute([$movie_id]);
            $movie_row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Get showtime details
            $stmt = $pdo->prepare("SELECT showtime FROM showtimes WHERE id = ?");
            $stmt->execute([$showtime_id]);
            $showtime_row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Create booking in database
            $stmt = $pdo->prepare("INSERT INTO bookings (user_id, movie_id, booking_date, showtime, seats, total_price) VALUES (?, ?, CURDATE(), ?, ?, ?)");
            $stmt->execute([$_SESSION['user_id'], $movie_id, $showtime_row['showtime'], $seats, $total_price]);
            
            // Set booking details for the page
            $_SESSION['booking_details'] = [
                'movie' => $movie_row['title'],
                'showtime' => $showtime_row['showtime'],
                'seats' => explode(',', $seats),
                'total_price' => $total_price,
                'cinema' => $cinema_name
            ];
        } catch (PDOException $e) {
            // If there's an error, redirect back to booking page
            $_SESSION['error_message'] = "Database error: " . $e->getMessage();
            header('Location: bookings.php');
            exit();
        }
    }
}

// Check if booking details exist in the session
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
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 20px;
            color: #fff;
            font-weight: bold;
            background: url('/movie-app-project/images/image (1).png');
            animation: animatedBackround 120s linear infinite;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animated-title {
            animation: fadeIn 3s ease-in-out;
        }
        @keyframes animatedBackround{
            0% {background-position: center;}
            50%{background-position: right;}
            100%{background-position: 0% 50%;}
        }
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        .container {
            background: rgba(46, 204, 113, 0.4);
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