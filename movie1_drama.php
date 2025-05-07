<?php
session_start();
require_once 'database.php';
$movie_title = 'The Shawshank Redemption';
$pdo = getPDO();
$stmt = $pdo->prepare("SELECT id FROM movies WHERE title = ?");
$stmt->execute([$movie_title]);
$movie = $stmt->fetch(PDO::FETCH_ASSOC);
$movie_id = 4;
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
    <title>The Shawshank Redemption</title>
    <link rel="icon" href="img.png" type="image/x-icon">
    <link rel="stylesheet" href="moviepage.css">
</head>
<body>
    <div class="header">
        <button class="change-mood" onclick="window.location.href='index.php'">&#x2B05; Change Mood</button>
        <span class="mood-title">&#x1F929; Dramatic Mood</span>
        <a href="wishlist.html" class="wish"><button>🎬 Wishlist</button></a>
          
    </div>
    
    <div class="container">
        <a href="a.html" class="arrow-nav arrow-prev">&#8249;</a>
        <a href="movie2 drama.html" class="arrow-nav arrow-next">&#8250;</a>
        
        <div class="movie-card">
            <div class="movie-content">
                <div class="left-column">
                    <img src="https://th.bing.com/th/id/OIP.on0nifskEruGYiwLZLbFJQHaK8?w=205&h=303&c=7&r=0&o=5&dpr=1.3&pid=1.7" alt="The Shawshank Redemption Poster" class="movie-poster">
                    
                    <div class="actors">
                        <h3>Actors</h3>
                        <div class="actor-images">
                            <div class="actor-item">
                                <img src="https://th.bing.com/th/id/OIP.j9lnNaL-Wk_130h4vEIzrAHaLH?w=186&h=279&c=7&r=0&o=5&dpr=1.3&pid=1.7" alt="Tim Robbins" class="actor">
                                <span class="actor-name">Tim Robbins</span>
                            </div>
                            <div class="actor-item">
                                <img src="https://th.bing.com/th/id/OIP.dbEPLVazrBbYeDFh9Obi6QHaE8?w=252&h=180&c=7&r=0&o=5&dpr=1.3&pid=1.7" alt="Morgan Freeman" class="actor">
                                <span class="actor-name">Morgan Freeman</span>
                            </div>
                            <div class="actor-item" alt="Bob Gunton" class="actor">
                                <img src="https://th.bing.com/th/id/OIP.0r1g2j4vXk3a5q6x8b7m9AHaLH?w=205&h=306&c=7&r=0&o=5&dpr=1.3&pid=1.7" alt="Bob Gunton" class="actor">
                                <span class="actor-name">Bob Gunton</span>
                            </div>
                            <div class="actor-item">
                                <img src="https://th.bing.com/th/id/OIP.3qq5hI0XYIrhfeZym2eG9AHaLH?w=204&h=306&c=7&r=0&o=5&dpr=1.3&pid=1.7" alt="William Sadler" class="actor">
                                <span class="actor-name">William Sadler</span>
                            </div>
                        
                        </div>
                    </div>
                    
                    <div class="director">
                        <h3>Director</h3>
                        <p>Frank Darabont</p>
                    </div>
                </div>
                
                <div class="right-column">
                    <div class="movie-details">
                        <h2 class="movie-title">The Shawshank Redemption</h2>
                        <p class="movie-info">1994 | 142 Mins | <span class="rating">★★★★☆</span></p>
                        <div class="tags">
                            <span class="tag">Drama</span>
            
                        </div>
                        
                        <p class="description">The Shawshank Redemption is a 1994 drama about Andy Dufresne, a banker wrongly imprisoned for murder, who forms a deep friendship with fellow inmate Red. Through hope and resilience, Andy quietly plots his escape from Shawshank Prison, inspiring everyone around him. <a href="#" class="read-more">Read More</a></p>
                        
                        <div class="trailer-container">
                            <h3>Movie Trailer</h3>
                            <iframe class="movie-trailer" src="https://www.youtube.com/embed/PLl99DlL6b4?si=XLUOHtWuM3GMC-Gb" frameborder="0" allowfullscreen></iframe>
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