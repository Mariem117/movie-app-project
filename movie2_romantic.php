<?php
session_start();
require_once 'database.php';
$movie_title = 'Forrest Gump';
$pdo = getPDO();
$stmt = $pdo->prepare("SELECT id FROM movies WHERE title = ?");
$stmt->execute([$movie_title]);
$movie = $stmt->fetch(PDO::FETCH_ASSOC);
$movie_id = 3;
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
    <title>Forrest Gump</title>
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
        <a href="movie 1 romance.html" class="arrow-nav arrow-prev">&#8249;</a>
        <a href="#" class="arrow-nav arrow-next">&#8250;</a>
        
        <div class="movie-card">
            <div class="movie-content">
                <div class="left-column">
                    <img src="https://th.bing.com/th/id/OIP.4BQ14VKbOgT7NdoJ50ttUwHaK9?w=205&h=304&c=7&r=0&o=5&dpr=1.3&pid=1.7" alt="Forrest Gump Poster" class="movie-poster">
                    
                    <div class="actors">
                        <h3>Actors</h3>
                        <div class="actor-images">
                            <div class="actor-item">
                                <img src="https://th.bing.com/th/id/OIP.WqhN75bph1iiVECg4xU87AHaEs?w=313&h=199&c=7&r=0&o=5&dpr=1.3&pid=1.7" alt="Tom Hanks" class="actor">
                                <span class="actor-name">Tom Hanks</span>
                            </div>
                            <div class="actor-item">
                                <img src="https://th.bing.com/th/id/OIP.iwrdJs2TenZFS9ZBwhcqhwHaLi?w=200&h=312&c=7&r=0&o=5&dpr=1.3&pid=1.7" alt="Robin Wright" class="actor">
                                <span class="actor-name">Robin Wright</span>
                            </div>
                            <div class="actor-item">
                                <img src="https://th.bing.com/th/id/OIP.9qDzymYHRxvSXq4C6cRceQHaKK?w=208&h=285&c=7&r=0&o=5&dpr=1.3&pid=1.7" alt="Sally Field" class="actor">
                                <span class="actor-name">Sally Field</span>
                            </div>
                            <div class="actor-item">
                                <img src="https://th.bing.com/th/id/OIP.ddJkdjNTPNK9N2YU5qxj9wHaJQ?w=208&h=260&c=7&r=0&o=5&dpr=1.3&pid=1.7" alt="Gary Sinise" class="actor">
                                <span class="actor-name">Gary Sinise</span>
                            </div>
                        
                        </div>
                    </div>
                    
                    <div class="director">
                        <h3>Director</h3>
                        <p>Robert Zemeckis</p>
                    </div>
                </div>
                
                <div class="right-column">
                    <div class="movie-details">
                        <h2 class="movie-title">Forrest Gump</h2>
                        <p class="movie-info">1994 | 142 Mins | <span class="rating">★★★★☆</span></p>
                        <div class="tags">
                            <span class="tag">Romance</span>
                            <span class="tag">Drama</span>
            
                        </div>
                        
                        <p class="description">  Forrest Gump (1994) is a heartwarming and emotional drama film directed by Robert Zemeckis and based on the novel by Winston Groom. It stars Tom Hanks in the iconic role of Forrest Gump, a kind-hearted man with a low IQ but an incredible story to tell. <a href="#" class="read-more">Read More</a></p>
                        
                        <div class="trailer-container">
                            <h3>Movie Trailer</h3>
                            <iframe class="movie-trailer" src="https://www.youtube.com/embed/bLvqoHBptjg?si=GVsAY-uWioRjRykb" frameborder="0" allowfullscreen></iframe>
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