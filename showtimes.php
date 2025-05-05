<?php
session_start();
require_once 'database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

$pdo = getPDO();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        if (isset($_POST['add_showtime'])) {
            $movie_id = $_POST['movie_id'];
            $showtime = $_POST['showtime'];
            if (!strtotime($showtime)) {
                $_SESSION['error_message'] = "Invalid showtime format.";
            } else {
                $stmt = $pdo->prepare("INSERT INTO showtimes (movie_id, showtime) VALUES (?, ?)");
                $stmt->execute([$movie_id, $showtime]);
                $_SESSION['success_message'] = "Showtime added successfully.";
            }
        }

        if (isset($_POST['delete_showtime'])) {
            $showtime_id = $_POST['showtime_id'];
            $stmt = $pdo->prepare("DELETE FROM showtimes WHERE id = ?");
            $stmt->execute([$showtime_id]);
            $_SESSION['success_message'] = "Showtime deleted successfully.";
        }
    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Database error: " . $e->getMessage();
    }
}

$showtimes = $pdo->query("SELECT s.*, m.title FROM showtimes s JOIN movies m ON s.movie_id = m.id")->fetchAll(PDO::FETCH_ASSOC);
$movies = $pdo->query("SELECT id, title FROM movies")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Showtimes</title>
    <link rel="stylesheet" href="styles/main.css">
</head>
<body>
    <h1>Manage Showtimes</h1>
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="success"><?= htmlspecialchars($_SESSION['success_message']); unset($_SESSION['success_message']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="error"><?= htmlspecialchars($_SESSION['error_message']); unset($_SESSION['error_message']); ?></div>
    <?php endif; ?>
    <form method="POST">
        <select name="movie_id" required>
            <option value="">Select Movie</option>
            <?php foreach ($movies as $movie): ?>
                <option value="<?= $movie['id']; ?>"><?= htmlspecialchars($movie['title']); ?></option>
            <?php endforeach; ?>
        </select>
        <input type="datetime-local" name="showtime" required>
        <button type="submit" name="add_showtime">Add Showtime</button>
    </form>
    <h2>Existing Showtimes</h2>
    <table>
        <tr>
            <th>Movie</th>
            <th>Showtime</th>
            <th>Action</th>
        </tr>
        <?php foreach ($showtimes as $showtime): ?>
            <tr>
                <td><?= htmlspecialchars($showtime['title']); ?></td>
                <td><?= htmlspecialchars($showtime['showtime']); ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="showtime_id" value="<?= $showtime['id']; ?>">
                        <button type="submit" name="delete_showtime">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>