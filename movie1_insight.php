<?php
session_start();
require_once 'database.php';
$movie_title = 'The Truman Show';
$pdo = getPDO();
$stmt = $pdo->prepare("SELECT id FROM movies WHERE title = ?");
$stmt->execute([$movie_title]);
$movie = $stmt->fetch(PDO::FETCH_ASSOC);
$movie_id = $movie ? $movie['id'] : null;
if (!$movie_id) {
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Mood Ticket Booking - The Truman Show</title>
    <link rel="icon" href="image(2).png" type="image/x-icon">
    <link rel="stylesheet" href="moviepage.css">
</head>
<body>
    <div class="header">
        <button class="change-mood" onclick="location.href='index.php'">⬅ Change Mood</button>        <span class="mood-title">🔍 Insight Mood</span>
        <a href="wishlist.php" class="wish"><button>🎬 Wishlist</button></a>
    </div>
    
    <div class="container">
        <a href="#" class="arrow-nav arrow-prev">‹</a>
        <a href="movie2_insight.php" class="arrow-nav arrow-next">›</a>
        
        <div class="movie-card">
            <div class="movie-content">
                <div class="left-column">
                    <img src="https://th.bing.com/th/id/OIP.95wfah9Ooa7RD1lSIzZFzQAAAA?w=186&h=279&c=7&r=0&o=5&dpr=1.3&pid=1.7" alt="The Truman Show Poster" class="movie-poster">
                    
                    <div class="actors">
                        <h3>Actors</h3>
                        <div class="actor-images">
                            <div class="actor-item">
                                <img src="https://th.bing.com/th/id/OIP.xupo35HRQ85pa9eHO8RLCQHaLH?w=115&h=180&c=7&r=0&o=5&dpr=1.3&pid=1.7" alt="Jim Carrey" class="actor">
                                <span class="actor-name">Jim Carrey</span>
                            </div>
                            <div class="actor-item">
                                <img src="https://th.bing.com/th/id/OIP.lSQ2Ucucq-HsiSKntsVligHaKQ?w=186&h=258&c=7&r=0&o=5&dpr=1.3&pid=1.7" alt="Laura Linney" class="actor">
                                <span class="actor-name">Laura Linney</span>
                            </div>
                            <div class="actor-item">
                                <img src="https://th.bing.com/th/id/OIP.5MoRHVAHZgvmNM8lD8kLfQHaJ3?w=186&h=248&c=7&r=0&o=5&dpr=1.3&pid=1.7" alt="Ed Harris" class="actor">
                                <span class="actor-name">Ed Harris</span>
                            </div>
                            <div class="actor-item">
                                <img src="https://image.tmdb.org/t/p/w185/7k0oMcd5oavV2V9jA9LGz3ZQm0c.jpg" alt="Noah Emmerich" class="actor">
                                <span class="actor-name">Noah Emmerich</span>
                            </div>
                            <div class="actor-item">
                                <img src="https://th.bing.com/th/id/OIP.vAFhAVTJEFLaHnjqiYbtwAHaKG?w=186&h=253&c=7&r=0&o=5&dpr=1.3&pid=1.7" alt="Natascha McElhone" class="actor">
                                <span class="actor-name">Natascha McElhone</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="director">
                        <h3>Director</h3>
                        <p>Peter Weir</p>
                    </div>
                </div>
                
                <div class="right-column">
                    <div class="movie-details">
                        <h2 class="movie-title">The Truman Show</h2>
                        <p class="movie-info">1998 | 1H 43Mins | <span class="rating">★★★★★</span></p>
                        <div class="tags">
                            <span class="tag">Drama</span>
                            <span class="tag">Comedy</span>
                            <span class="tag">Sci-Fi</span>
                        </div>
                        
                        <p class="description">Truman Burbank lives a seemingly perfect life, unaware that his world is a fabricated TV set watched by millions. As he begins to suspect the truth, he embarks on a journey to uncover reality and reclaim his freedom. <a href="#" class="read-more">Read More</a></p>
                        
                        <div class="trailer-container">
                            <h3>Movie Trailer</h3>
                            <iframe class="movie-trailer" src="https://www.youtube.com/embed/dlnmQbPGuls?si=5z8y3q7l5i8q9y9l" frameborder="0" allowfullscreen></iframe>
                        </div>
                        
                        <div class="action-buttons">
                            <button class="wishlist-btn" onclick="toggleWishlist()">Add to Wishlist</button>
                            <a href="book_ticket.php?movie_id=<?= $movie_id ?>" class="book-ticket">Book Ticket</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function toggleWishlist() {
            let movie = {
                title: "The Truman Show",
                poster: "truman_show_poster.jpg"
            };
            let wishlist = JSON.parse(localStorage.getItem("wishlist")) || [];
            let index = wishlist.findIndex(item => item.title === movie.title);
            let wishlistBtn = document.querySelector(".wishlist-btn");

            if (index === -1) {
                wishlist.push(movie);
                wishlistBtn.innerText = "In Wishlist";
                wishlistBtn.classList.add("in-wishlist");
            } else {
                wishlist.splice(index, 1);
                wishlistBtn.innerText = "Add to Wishlist";
                wishlistBtn.classList.remove("in-wishlist");
            }

            localStorage.setItem("wishlist", JSON.stringify(wishlist));
        }

        window.onload = function () {
            let wishlist = JSON.parse(localStorage.getItem("wishlist")) || [];
            let wishlistBtn = document.querySelector(".wishlist-btn");

            if (wishlist.some(movie => movie.title === "The Truman Show")) {
                wishlistBtn.innerText = "In Wishlist";
                wishlistBtn.classList.add("in-wishlist");
            }
            
            document.querySelector(".arrow-next").addEventListener("click", function(e) {
            });
        };
    </script>
</body>
</html>