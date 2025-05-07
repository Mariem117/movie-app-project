<?php
session_start();
require_once 'database.php';
$movie_title = 'The Matrix';
$pdo = getPDO();
$stmt = $pdo->prepare("SELECT id FROM movies WHERE title = ?");
$stmt->execute([$movie_title]);
$movie = $stmt->fetch(PDO::FETCH_ASSOC);
$movie_id = 6;
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
    <title>The Matrix</title>
    <link rel="icon" href="img.png" type="image/x-icon">
    <link rel="stylesheet" href="moviepage.css">
</head>
<body>
    <div class="header">
        <button class="change-mood" onclick="window.location.href='index.php'">&#x2B05; Change Mood</button>
        <span class="mood-title">&#x1F929; Imagination Mood</span>
        <a href="wishlist.html" class="wish"><button>🎬 Wishlist</button></a>
          
    </div>
    
    <div class="container">
        <a href="a.html" class="arrow-nav arrow-prev">&#8249;</a>
        <a href="movie2 imagination.html" class="arrow-nav arrow-next">&#8250;</a>
        
        <div class="movie-card">
            <div class="movie-content">
                <div class="left-column">
                    <img src="https://th.bing.com/th/id/OIP.nq66xqwF5qVWxt11yjmU6gHaLH?w=204&h=306&c=7&r=0&o=5&dpr=1.3&pid=1.7" alt=" The Matrix Poster" class="movie-poster">
                    
                    <div class="actors">
                        <h3>Actors</h3>
                        <div class="actor-images">
                            <div class="actor-item">
                                <img src="https://th.bing.com/th/id/OIP.ToQXbrVtCA0K-DpQ_WMHQAHaE7?w=280&h=186&c=7&r=0&o=5&dpr=1.3&pid=1.7" alt="Keanu Reeves" class="actor">
                                <span class="actor-name">Keanu Reeves</span>
                            </div>
                            <div class="actor-item">
                                <img src="https://th.bing.com/th/id/OIP.dbEPLVazrBbYeDFh9Obi6QHaE8?w=286&h=191&c=7&r=0&o=5&dpr=1.3&pid=1.7" alt="Morgan Freeman" class="actor">
                                <span class="actor-name">Morgan Freeman</span>
                            </div>
                            <div class="actor-item">
                                <img src="https://th.bing.com/th/id/OIP.foVoPTYilBbGJsSQGVE4HAHaJz?w=208&h=275&c=7&r=0&o=5&dpr=1.3&pid=1.7" alt="Carrie‑Anne Moss" class="actor">
                                <span class="actor-name">Carrie‑Anne Moss</span>
                            </div>
                            <div class="actor-item">
                                <img src="https://th.bing.com/th/id/OIP.uVxMpBZFP8E-ynyAoQmrRgHaJw?w=208&h=274&c=7&r=0&o=5&dpr=1.3&pid=1.7" alt="Hugo Weaving" class="actor">
                                <span class="actor-name">Hugo Weaving</span>
                            </div>
                        
                        </div>
                    </div>
                    
                    <div class="director">
                        <h3>Directors</h3>
                        <p>Lana & Lilly Wachowski</p>
                    </div>
                </div>
                
                <div class="right-column">
                    <div class="movie-details">
                        <h2 class="movie-title">The Matrix</h2>
                        <p class="movie-info">1999 | 136 Mins | <span class="rating">★★★★☆</span></p>
                        <div class="tags">
                            <span class="tag">Imagination</span>
            
                        </div>
                        
                        <p class="description">The Matrix (1999) is a groundbreaking science fiction film directed by the Wachowskis. It follows Neo, a computer hacker who discovers that reality as he knows it is a simulated world created by machines to control humanity. Joining a group of rebels, Neo fights to free mankind and fulfill his role as "The One" who can bend the rules of the Matrix and challenge the system. The film is known for its innovative visual effects, philosophical themes, and action sequences.<a href="#" class="read-more">Read More</a></p>
                        
                        <div class="trailer-container">
                            <h3>Movie Trailer</h3>
                            <iframe class="movie-trailer" src="https://www.youtube.com/embed/vKQi3bBA1y8?si=WoVi8FCkshfEwvDp" frameborder="0" allowfullscreen></iframe>
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
            });
        };
    </script>
</body>
</html>