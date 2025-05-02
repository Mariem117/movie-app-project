<?php
session_start();
require_once '../database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Use the existing PDO connection from database.php
$pdo = getPDO();

$stmt = $pdo->prepare("SELECT m.* FROM wishlist w JOIN movies m ON w.movie_id = m.id WHERE w.user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$wishlist = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Wishlist</title>
    <link rel="icon" href="image(2).png" type="x-icon">
    <link rel="stylesheet" href="wishlist.css">
</head>
<body>
    <div class="header">
        <a href="index.php" class="back-btn">⬅ Back</a>
        <span class="mood-title">My Wishlist (<span id="wishlist-count"><?= count($wishlist); ?></span>)</span>
        <div class="spacer"></div>
    </div>
    <div class="container">
        <h2 class="section-title">Movies You've Saved</h2>
        <div id="wishlist-container" class="wishlist-grid">
            <?php if (empty($wishlist)): ?>
                <p class='empty-wishlist'>Your wishlist is empty. Add some movies to watch later!</p>
            <?php else: ?>
                <?php foreach ($wishlist as $movie): ?>
                    <div class="movie-card" data-id="<?= $movie['id']; ?>">
                        <div class="movie-content">
                            <div class="poster-container">
                                <img src="<?= htmlspecialchars($movie['poster']); ?>" alt="<?= htmlspecialchars($movie['title']); ?> Poster" class="movie-poster">
                            </div>
                            <h3 class="movie-title"><?= htmlspecialchars($movie['title']); ?></h3>
                            <button class="remove-btn" onclick="removeFromWishlist(<?= $movie['id']; ?>, '<?= addslashes($movie['title']); ?>')">Remove</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    <script>
        function removeFromWishlist(movieId, title) {
            let wishlist = JSON.parse(localStorage.getItem("wishlist")) || [];
            wishlist = wishlist.filter(movie => movie.title !== title);
            localStorage.setItem("wishlist", JSON.stringify(wishlist));
            fetch('update_wishlist.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ movie_id: movieId, action: 'remove' })
            }).then(() => {
                document.querySelector(`[data-id="${movieId}"]`).remove();
                let countElement = document.getElementById("wishlist-count");
                countElement.innerText = wishlist.length;
                if (wishlist.length === 0) {
                    document.getElementById("wishlist-container").innerHTML = "<p class='empty-wishlist'>Your wishlist is empty. Add some movies to watch later!</p>";
                }
            }).catch(error => alert(error.message));
        }
        window.onload = function() {
            let wishlist = JSON.parse(localStorage.getItem("wishlist")) || [];
            let countElement = document.getElementById("wishlist-count");
            countElement.innerText = wishlist.length;
            if (wishlist.length === 0) {
                countElement.parentElement.style.display = 'none';
            }
        };
    </script>
</body>
</html>