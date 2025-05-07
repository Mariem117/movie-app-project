<?php
session_start();
require_once 'database.php';
$movie_title = 'Eternal Sunshine of the Spotless Mind';
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
    <title>Eternal Sunshine of the Spotless Mind</title>
    <link rel="icon" href="img.png" type="image/x-icon">
    <link rel="stylesheet" href="moviepage.css">
</head>
<body>
    <div class="header">
        <button class="change-mood" onclick="window.location.href='index.php'">&#x2B05; Change Mood</button>
        <span class="mood-title">&#x1F929; Romantic Mood</span>
        <a href="wishlist.html" class="wish"><button>🎬 Wishlist</button></a>
    </div>
    <div class="container">
        <a href="index.php" class="arrow-nav arrow-prev">&#8249;</a>
        <a href="movie 2 romance.html" class="arrow-nav arrow-next">&#8250;</a>
        <div class="movie-card">
            <div class="movie-content">
                <div class="left-column">
                    <img src="https://th.bing.com/th/id/OIP.FDYkuQxrHeTLdxACZrFxJgHaLH?w=204&h=306&c=7&r=0&o=5&dpr=1.3&pid=1.7" alt="Eternal Sunshine of the Spotless Mind Poster" class="movie-poster">
                    <div class="actors">
                        <h3>Actors</h3>
                        <div class="actor-images">
                            <div class="actor-item">
                                <img src="https://th.bing.com/th/id/OIP.4T9mmNq1UCxkFzHRrU3KCAHaFR?w=269&h=190&c=7&r=0&o=5&dpr=1.3&pid=1.7" alt="Michel Gondry" class="actor">
                                <span class="actor-name">Michel Gondry</span>
                            </div>
                            <div class="actor-item">
                                <img src="https://th.bing.com/th/id/OIP.JdUdM2EPtjsaoI7660aCuQHaE7?w=265&h=180&c=7&r=0&o=5&dpr=1.3&pid=1.7" alt="Jim Carrey" class="actor">
                                <span class="actor-name">Jim Carrey</span>
                            </div>
                            <div class="actor-item">
                                <img src="https://th.bing.com/th/id/OIP.Ye4_KOhl-qLHzOgadR74lAHaEK?w=276&h=180&c=7&r=0&o=5&dpr=1.3&pid=1.7" alt="Kate Winslet" class="actor">
                                <span class="actor-name">Kate Winslet</span>
                            </div>
                            <div class="actor-item">
                                <img src="https://th.bing.com/th/id/OIP.KcyIkh1mFG_DskK8mHYkYQHaFj?w=208&h=156&c=7&r=0&o=5&dpr=1.3&pid=1.7" alt="Tom Wilkinson" class="actor">
                                <span class="actor-name">Tom Wilkinson</span>
                            </div>
                            <div class="actor-item">
                                <img src="https://th.bing.com/th/id/OIP.CxpIgZNsSHN7deC5s0zsqAAAAA?w=141&h=213&c=7&r=0&o=5&dpr=1.3&pid=1.7" alt="Alfred Molina" class="actor">
                                <span class="actor-name">Gerry Robert Byrne</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="director">
                        <h3>Director</h3>
                        <p>Michel Gondry</p>
                    </div>
                </div>
                
                <div class="right-column">
                    <div class="movie-details">
                        <h2 class="movie-title">Eternal Sunshine of the Spotless Mind</h2>
                        <p class="movie-info">2004 | 108 Mins | <span class="rating">★★★★☆</span></p>
                        <div class="tags">
                            <span class="tag">Romance</span>
                            <span class="tag">Drama</span>
                            <span class="tag">Science Fiction</span>
                        </div>
                        
                        <p class="description"> Eternal Sunshine of the Spotless Mind is a sci-fi romance about a man who tries to erase memories of his ex, only to realize he still loves her. It’s a heartfelt, surreal journey through love and loss. <a href="#" class="read-more">Read More</a></p>
                        
                        <div class="trailer-container">
                            <h3>Movie Trailer</h3>
                            <iframe class="movie-trailer" src="https://www.youtube.com/embed/07-QBnEkgXU" frameborder="0" allowfullscreen></iframe>
                        </div>
                        
                        <div class="action-buttons">
                            <button class="wishlist-btn" onclick="toggleWishlist()">Add to Wishlist</button>
                            <a href="book_ticket.php?movie_id=2" class="book-ticket">Book Ticket</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function toggleWishlist() {
            let movie = {
                title: "Eternal Sunshine of the Spotless Mind",
                poster: "Eternal Sunshine of the Spotless Mind.jpg"
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

            if (wishlist.some(movie => movie.title === "Eternal Sunshine of the Spotless Mind")) {
                wishlistBtn.innerText = "In Wishlist";
                wishlistBtn.classList.add("in-wishlist");
            }
            
            document.querySelector(".arrow-next").addEventListener("click", function(e) {
                e.preventDefault();
            });
        };
    </script>
</body>
</html>