<?php
session_start();
require_once 'database.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}
$pdo = getPDO();

// Get movie_id and mood from URL
$movie_id = filter_input(INPUT_GET, 'movie_id', FILTER_VALIDATE_INT);
$mood = filter_input(INPUT_GET, 'mood', FILTER_SANITIZE_STRING);

if (!$movie_id || !$mood) {
    $_SESSION['error_message'] = "Invalid movie or mood.";
    header('Location: index.php');
    exit();
}

// Fetch movie details
$stmt = $pdo->prepare("SELECT * FROM movies WHERE id = ? AND mood = ?");
$stmt->execute([$movie_id, $mood]);
$movie = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$movie) {
    $_SESSION['error_message'] = "Movie not found.";
    header('Location: index.php');
    exit();
}

// Check if movie is in user's wishlist
$is_in_wishlist = false;
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT id FROM wishlist WHERE user_id = ? AND movie_id = ?");
    $stmt->execute([$_SESSION['user_id'], $movie_id]);
    $is_in_wishlist = $stmt->fetch() !== false;
}

// Handle admin-specific actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_movie']) && isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    try {
        $stmt = $pdo->prepare("DELETE FROM movies WHERE id = ?");
        $stmt->execute([$movie_id]);
        $_SESSION['success_message'] = "Movie deleted successfully.";
        header('Location: movies.php');
        exit();
    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Failed to delete movie: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MoodFlix - <?= htmlspecialchars($movie['title']); ?></title>
    <link rel="icon" href="images/image(2).png" type="image/x-icon">
    <link rel="stylesheet" href="styles/moviepage.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <div class="header">
        <button class="change-mood" onclick="location.href='index.php'">⬅ Change Mood</button>
        <span class="mood-title">🎬 <?= htmlspecialchars($mood); ?> Mood</span>
        <a href="wishlist.php" class="wish"><button>🎬 Wishlist</button></a>
    </div>
    
    <div class="container">
        <?php
        $stmt = $pdo->prepare("SELECT id, title FROM movies WHERE mood = ? AND id != ? LIMIT 2");
        $stmt->execute([$mood, $movie_id]);
        $other_movies = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $prev_movie = $other_movies[0] ?? null;
        $next_movie = $other_movies[1] ?? null;
        ?>
        <a href="<?= $prev_movie ? 'movie.php?movie_id=' . $prev_movie['id'] . '&mood=' . urlencode($mood) : '#' ?>" class="arrow-nav arrow-prev">‹</a>
        <a href="<?= $next_movie ? 'movie.php?movie_id=' . $next_movie['id'] . '&mood=' . urlencode($mood) : '#' ?>" class="arrow-nav arrow-next">›</a>
        
        <div class="movie-card">
            <div class="movie-content">
                <div class="left-column">
                    <img src="<?= htmlspecialchars($movie['poster'] ?? '../default_poster.jpg'); ?>" alt="<?= htmlspecialchars($movie['title']); ?> Poster" class="movie-poster">
                    
                    <div class="actors">
                        <h3>Actors</h3>
                        <p><?= htmlspecialchars($movie['actors'] ?? 'Cast not specified'); ?></p>
                    </div>
                    
                    <div class="director">
                        <h3>Director</h3>
                        <p><?= htmlspecialchars($movie['director'] ?? 'Unknown'); ?></p>
                    </div>
                </div>
                
                <div class="right-column">
                    <div class="movie-details">
                        <h2 class="movie-title"><?= htmlspecialchars($movie['title']); ?></h2>
                        <p class="movie-info"><?= htmlspecialchars($movie['year'] ?? 'N/A'); ?> | <?= htmlspecialchars($movie['duration'] ?? 'N/A'); ?> | <span class="rating"><?= str_repeat('★', floor($movie['rating'] ?? 0)) . str_repeat('☆', 5 - floor($movie['rating'] ?? 0)); ?></span></p>
                        <div class="tags">
                            <?php foreach (explode(',', $movie['tags'] ?? '') as $tag): ?>
                                <span class="tag"><?= htmlspecialchars(trim($tag)); ?></span>
                            <?php endforeach; ?>
                        </div>
                        
                        <p class="description"><?= htmlspecialchars($movie['description'] ?? 'No description available.'); ?> <a href="#" class="read-more">Read More</a></p>
                        
                        <div class="trailer-container">
                            <h3>Movie Trailer</h3>
                            <iframe class="movie-trailer" src="<?= htmlspecialchars($movie['trailer_url'] ?? ''); ?>" frameborder="0" allowfullscreen></iframe>
                        </div>
                        
                        <div class="action-buttons">
                            <a href="book_ticket.php?movie_id=<?= $movie_id; ?>" class="book-ticket"><button>Book Ticket</button></a>
                            <?php if (isset($_SESSION['user_id'])): ?>
                                <form action="update_wishlist.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="movie_id" value="<?= $movie_id; ?>">
                                    <input type="hidden" name="action" value="<?= $is_in_wishlist ? 'remove' : 'add'; ?>">
                                    <button type="submit" class="wishlist-btn <?= $is_in_wishlist ? 'in-wishlist' : ''; ?>">
                                        <?= $is_in_wishlist ? 'In Wishlist' : 'Add to Wishlist'; ?>
                                    </button>
                                </form>
                            <?php else: ?>
                                <a href="login.php"><button class="wishlist-btn">Add to Wishlist</button></a>
                            <?php endif; ?>
                            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                                <a href="addmovie.php?edit_id=<?= $movie_id; ?>" class="admin-btn"><button>Edit Movie</button></a>
                                <form action="" method="POST" style="display:inline;">
                                    <button type="submit" name="delete_movie" class="admin-btn" onclick="return confirm('Are you sure you want to delete this movie?');">Delete Movie</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>