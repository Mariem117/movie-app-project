<?php
session_start();
require_once 'database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$pdo = getPDO();

$movie_id = filter_input(INPUT_GET, 'movie_id', FILTER_VALIDATE_INT);
if (!$movie_id) {
    header('Location: index.php');
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM movies WHERE id = ?");
$stmt->execute([$movie_id]);
$movie = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$movie) {
    header('Location: index.php');
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM showtimes WHERE movie_id = ?");
$stmt->execute([$movie_id]);
$showtimes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MoodFlix – Réservation</title>
    <link rel="stylesheet" href="booking_page.css">
    <link rel="icon" href="../images/img.png" type="image/x-icon">
</head>
<body>
    <div class="container">
        <div class="movie-info">
            <img src="<?= htmlspecialchars($movie['poster'] ?? '../spiderman.jpg') ?>" alt="<?= htmlspecialchars($movie['title']) ?>" class="poster">
            <div class="selectors">
                <label>Cinéma :
                    <select name="cinema_id" id="cinema-select">
                        <option value="">Select a cinema</option>
                        <option value="1">Pathe Tunis City</option>
                        <option value="2">Pathe Azur City</option>
                        <option value="3">Pathe Mall of Sousse</option>
                    </select>
                </label>
                <label>Film :
                    <select>
                        <option><?= htmlspecialchars($movie['title']) ?></option>
                    </select>
                </label>
                <label>Date :
                    <select name="showtime_date" id="date-select">
                        <option value="">Choose a date</option>
                        <?php
                        $dates = array_unique(array_map(function($showtime) {
                            return date('d M Y', strtotime($showtime['showtime']));
                        }, $showtimes));
                        foreach ($dates as $date):
                        ?>
                            <option value="<?= $date ?>"><?= $date ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <label>Horaire :
                    <select name="showtime_id" id="showtime-select">
                        <option value="">Choose a time</option>
                        <?php foreach ($showtimes as $showtime): ?>
                            <option value="<?= $showtime['id'] ?>" data-date="<?= date('d M Y', strtotime($showtime['showtime'])) ?>">
                                <?= date('H:i', strtotime($showtime['showtime'])) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </label>
            </div>
        </div>
        <div class="seat-selection">
            <h2 class="animated-title">Select your seats</h2>
            <?php if (empty($showtimes)): ?>
                <p>No showtimes available for this movie.</p>
            <?php else: ?>
                <div class="seats"></div>
                <div class="indications">
                    <div class="legend-item">
                        <div class="seat available"></div>
                        <span>Available Seat</span>
                    </div>
                    <div class="legend-item">
                        <div class="seat occupied"></div>
                        <span>Reserved Seat</span>
                    </div>
                    <div class="legend-item">
                        <div class="seat selected"></div>
                        <span>Selected Seat</span>
                    </div>
                </div>
                <form method="POST" action="bookings.php">
                    <input type="hidden" name="user_id" value="<?= $_SESSION['user_id'] ?>">
                    <input type="hidden" name="movie_id" value="<?= $movie_id ?>">
                    <input type="hidden" name="showtime_id" id="selected-showtime-id">
                    <input type="hidden" name="cinema_id" id="selected-cinema-id">
                    <input type="hidden" name="seats" id="selected-seats">
                    <input type="hidden" name="total_price" id="calculated-price">
                    <button type="submit" class="booknow">Book Now</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            <?php if (!empty($showtimes)): ?>
                const seatsContainer = document.querySelector('.seats');
                const rows = 10;
                const cols = 10;
                const seatPrice = 10.8;
                const selectedSeatsInput = document.getElementById('selected-seats');
                const showtimeSelect = document.getElementById('showtime-select');
                const dateSelect = document.getElementById('date-select');
                const cinemaSelect = document.getElementById('cinema-select');
                const selectedShowtimeId = document.getElementById('selected-showtime-id');
                const selectedCinemaId = document.getElementById('selected-cinema-id');
                const totalPriceInput = document.getElementById('calculated-price');
                const getSeatImage = (status) => {
                    switch (status) {
                        case 'available': return "../chair_available.png";
                        case 'selected': return "../chair_selected.png";
                        case 'occupied': return "../chair_occupied.png";
                    }
                };
                for (let row = 1; row <= rows; row++) {
                    for (let col = 1; col <= cols; col++) {
                        const seat = document.createElement('div');
                        seat.classList.add('seat');
                        seat.dataset.status = Math.random() < 0.2 ? 'occupied' : 'available';
                        seat.dataset.seat = `${row}-${col}`;
                        const img = document.createElement('img');
                        img.src = getSeatImage(seat.dataset.status);
                        img.alt = `${seat.dataset.status} seat`;
                        img.style.width = '100%';
                        img.style.height = '100%';
                        img.style.objectFit = 'contain';
                        seat.appendChild(img);
                        seat.addEventListener('click', () => {
                            if (seat.dataset.status !== 'occupied') {
                                seat.dataset.status = seat.dataset.status === 'selected' ? 'available' : 'selected';
                                img.src = getSeatImage(seat.dataset.status);
                                updateSelectedSeats();
                            }
                        });
                        seatsContainer.appendChild(seat);
                    }
                }
                function updateSelectedSeats() {
                    const selected = [...document.querySelectorAll('.seat[data-status="selected"]')];
                    const seats = selected.map(seat => seat.dataset.seat);
                    selectedSeatsInput.value = seats.join(',');
                    totalPriceInput.value = (seats.length * seatPrice).toFixed(2);
                    selectedShowtimeId.value = showtimeSelect.value;
                    selectedCinemaId.value = cinemaSelect.value;
                }
                dateSelect.addEventListener('change', () => {
                    const selectedDate = dateSelect.value;
                    Array.from(showtimeSelect.options).forEach(option => {
                        if (option.value === '' || option.dataset.date === selectedDate) {
                            option.style.display = 'block';
                        } else {
                            option.style.display = 'none';
                        }
                    });
                    showtimeSelect.value = '';
                    selectedShowtimeId.value = '';
                });
                showtimeSelect.addEventListener('change', () => {
                    selectedShowtimeId.value = showtimeSelect.value;
                });
                cinemaSelect.addEventListener('change', () => {
                    selectedCinemaId.value = cinemaSelect.value;
                });
            <?php endif; ?>
        });
    </script>
</body>
</html>