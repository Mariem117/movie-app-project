<?php
session_start();
require_once 'database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$pdo = getPDO();

// Process form submission with payment details
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cardnumber'])) {
    $movie_id = filter_input(INPUT_POST, 'movie_id', FILTER_VALIDATE_INT);
    $showtime_id = filter_input(INPUT_POST, 'showtime_id', FILTER_VALIDATE_INT);
    $seats = filter_input(INPUT_POST, 'seats', FILTER_SANITIZE_STRING);
    $total_price = filter_input(INPUT_POST, 'total_price', FILTER_VALIDATE_FLOAT);
    $cinema_id = filter_input(INPUT_POST, 'cinema_id', FILTER_VALIDATE_INT);

    if ($movie_id && $showtime_id && $seats && $total_price) {
        try {
            // Get cinema name from cinema_id
            $stmt = $pdo->prepare("SELECT name FROM cinemas WHERE id = ?");
            $stmt->execute([$cinema_id]);
            $cinema_row = $stmt->fetch(PDO::FETCH_ASSOC);
            $cinema_name = $cinema_row ? $cinema_row['name'] : 'Unknown Cinema';

            $stmt = $pdo->prepare("SELECT showtime FROM showtimes WHERE id = ?");
            $stmt->execute([$showtime_id]);
            $showtime_row = $stmt->fetch(PDO::FETCH_ASSOC);
            $showtime_time = $showtime_row['showtime']; // Store the showtime value

            $stmt = $pdo->prepare("SELECT title FROM movies WHERE id = ?");
            $stmt->execute([$movie_id]);
            $movie_row = $stmt->fetch(PDO::FETCH_ASSOC);

            $stmt = $pdo->prepare("INSERT INTO bookings (user_id, movie_id, booking_date, showtime, seats, total_price) VALUES (?, ?, CURDATE(), ?, ?, ?)");
            $stmt->execute([$_SESSION['user_id'], $movie_id, $showtime_time, $seats, $total_price]);
            $_SESSION['booking_details'] = [
                'movie' => $movie_row['title'],
                'showtime' => $showtime_row['showtime'],
                'seats' => explode(',', $seats),
                'total_price' => $total_price,
                'cinema' => $cinema_name // Add cinema name to session data
            ];
            header('Location: confirmation.php');
            exit();
        } catch (PDOException $e) {
            $_SESSION['error_message'] = "Database error: " . $e->getMessage();
        }
    } else {
        $_SESSION['error_message'] = "Invalid booking data.";
    }
}

// Get the total price from POST data if available
$displayed_price = isset($_POST['total_price']) ? filter_var($_POST['total_price'], FILTER_VALIDATE_FLOAT) : 10.8;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Page</title>
    <link rel="stylesheet" href="bookings.css">
    <link rel="icon" href="img.png" type="x-icon">
</head>
<body>
    <div class="payment-container">
        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="error"><?= htmlspecialchars($_SESSION['error_message']); unset($_SESSION['error_message']); ?></div>
        <?php endif; ?>
        <button class="prev-button" onclick="window.location.href='book_ticket.php?movie_id=<?= htmlspecialchars($_POST['movie_id'] ?? 1); ?>'">Previous</button>
        <h2>Payment Method</h2>
        <div class="card-container">
            <img src="cartenoir.png" alt="Carte Noir" class="payment-card">
            <img src="cartebleu.png" alt="Carte Bleu" class="payment-card">
        </div>
        <h2 class="payment-title">Payment Details</h2>
        <form class="payment-form" action="bookings.php" method="POST">
            <!-- Hidden fields to pass booking data to confirmation page -->
            <input type="hidden" name="movie_id" value="<?= htmlspecialchars($_POST['movie_id'] ?? ''); ?>">
            <input type="hidden" name="showtime_id" value="<?= htmlspecialchars($_POST['showtime_id'] ?? ''); ?>">
            <input type="hidden" name="seats" value="<?= htmlspecialchars($_POST['seats'] ?? ''); ?>">
            <input type="hidden" name="total_price" value="<?= htmlspecialchars($_POST['total_price'] ?? $displayed_price); ?>">
            <input type="hidden" name="cinema_id" value="<?= htmlspecialchars($_POST['cinema_id'] ?? ''); ?>">
            
            <label>Your Email</label>
            <input type="email" name="email" placeholder="Enter your email" required>
            <label>Cardholder Name</label>
            <input type="text" name="cardname" placeholder="Enter cardholder name" required>
            <label>Card Number</label>
            <input type="text" name="cardnumber" placeholder="Enter card number" maxlength="16" required>
            <div class="row">
                <div class="column1">
                    <label>Date</label>
                    <input type="month" name="expiry" required min="2025-04" max="2030-12">
                </div>
                <div class="column2">
                    <label>CVV</label>
                    <input type="password" name="cvv" placeholder="CVV" maxlength="3" required>
                </div>
            </div>
            <div class="terms">
                <input type="checkbox" id="agree" required>
                <label for="agree">I agree on the terms and conditions.</label>
            </div>
            <div class="pay-button">
                <button type="submit" class="pay-now">Pay Now</button>
                <span class="price">$<?= htmlspecialchars(number_format($displayed_price, 2)); ?></span>
            </div>
        </form>
    </div>
</body>
</html>