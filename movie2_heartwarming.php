<?php
session_start();
require_once 'database.php';
$movie_title = 'Sen to Chihiro no kamikakushi';
$pdo = getPDO();
$stmt = $pdo->prepare("SELECT id FROM movies WHERE title = ?");
$stmt->execute([$movie_title]);
$movie = $stmt->fetch(PDO::FETCH_ASSOC);
$movie_id = 9;
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
    <title>Sen to Chihiro no kamikakushi</title>
    <link rel="icon" href="img.png" type="image/x-icon">
    <link rel="stylesheet" href="moviepage.css">
</head>
<body>
    <div class="header">
        <button class="change-mood" onclick="window.location.href='index.php'">&#x2B05; Change Mood</button>
        <span class="mood-title">&#x1F929; Heartwarming Mood</span>
        <a href="wishlist.html" class="wish"><button>🎬 Wishlist</button></a>
          
    </div>
    
    <div class="container">
        <a href="movie1 heartwarming.html" class="arrow-nav arrow-prev">&#8249;</a>
        <a href="#" class="arrow-nav arrow-next">&#8250;</a>
        
        <div class="movie-card">
            <div class="movie-content">
                <div class="left-column">
                    <img src="https://th.bing.com/th/id/OIP.7GGKW65Y_3DfbAIrfzn13QHaLG?w=186&h=279&c=7&r=0&o=5&dpr=1.3&pid=1.7" alt="Sen to Chihiro no kamikakushi Poster" class="movie-poster">
                    
                    <div class="actors">
                        <h3>Actors</h3>
                        <div class="actor-images">
                            <div class="actor-item">
                                <img src="https://th.bing.com/th/id/OIP.gPFDBdbkxeN6DIimRr81EAHaJT?w=186&h=235&c=7&r=0&o=5&dpr=1.3&pid=1.7" alt="Daveigh Chase" class="actor">
                                <span class="actor-name">Daveigh Chase</span>
                            </div>
                            <div class="actor-item">
                                <img src="data:image/jpeg;base64,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" alt="Anthony Gonzalez" class="actor">
                                <span class="actor-name">Suzanne Pleshette</span>
                            </div>
                            <div class="actor-item" alt="Ian McKellen" class="actor">
                                <img src="https://th.bing.com/th/id/OIP.hgtkfZwj_hbnO-KQpdw9ZQHaJi?w=186&h=239&c=7&r=0&o=5&dpr=1.3&pid=1.7" alt="Miyu Irino" class="actor">
                                <span class="actor-name">Miyu Irino</span>
                            </div>
                            <div class="actor-item">
                                <img src="https://th.bing.com/th/id/OIP.Fhjy0-PTW-96COjMBZmIYQHaJt?w=138&h=180&c=7&r=0&o=5&dpr=1.3&pid=1.7=" alt="Rumi Hiiragi" class="actor">
                                <span class="actor-name">Rumi Hiiragi</span>
                            </div>
                        
                        </div>
                    </div>
                    
                    <div class="director">
                        <h3>Director</h3>
                        <p>Lee Unkrich</p>
                    </div>
                </div>
                
                <div class="right-column">
                    <div class="movie-details">
                        <h2 class="movie-title">Sen to Chihiro no kamikakushi</h2>
                        <p class="movie-info">2001 | 125 Mins | <span class="rating">★★★★☆</span></p>
                        <div class="tags">
                            <span class="tag">Animation</span>
                            <span class="tag">Adventure</span>
            
                        </div>
                        
                        <p class="description">Sen to Chihiro no Kamikakushi (Spirited Away) is a magical animated film about Chihiro, a young girl who becomes trapped in a mysterious spirit world and must find the courage to save her parents and return to the human world.<a href="#" class="read-more">Read More</a></p>
                        
                        <div class="trailer-container">
                            <h3>Movie Trailer</h3>
                            <iframe class="movie-trailer" src="https://www.youtube.com/embed/ByXuk9QqQkk?si=DJfGaMNVdUsK7MMN" frameborder="0" allowfullscreen></iframe>
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