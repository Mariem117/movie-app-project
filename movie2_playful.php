<?php
session_start();
require_once 'database.php';
$movie_title = 'The Garfield';
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
    <title>Movie Mood Ticket Booking - The Garfield</title>
    <link rel="icon" href="image(2).png" type="image/x-icon">
    <link rel="stylesheet" href="moviepage.css">
</head>
<body>
    <div class="header">
        <button class="change-mood" onclick="location.href='index.php'">⬅ Change Mood</button>        <span class="mood-title">🎈 Playful mood</span>
        <a href="wishlist.php" class="wish" style="color: inherit; text-decoration:none"><button>🎬 Wishlist</button></a>
          
    </div>
    
    <div class="container">
        <a href="movie1_playful.php" class="arrow-nav arrow-prev">&#8249;</a>
        <a href="#" class="arrow-nav arrow-next">&#8250;</a>
        
        <div class="movie-card">
            <div class="movie-content">
                <div class="left-column">
                    <img src="https://th.bing.com/th/id/OIP.un-Uc-roqopTR3uc1kQJmgHaK-?rs=1&pid=ImgDetMain" alt="The Garfield Poster" class="movie-poster">
                    
                    <div class="actors">
                        <h3>Actors(voice)</h3>
                        <div class="actor-images">
                            <div class="actor-item">
                                <img src="https://th.bing.com/th/id/OIP.U8_Qny8p_bL_QKsKlsa5jwHaHa?rs=1&pid=ImgDetMain" alt="Chris Pratt" class="actor">
                                <span class="actor-name">Chris Pratt</span>
                            </div>
                            <div class="actor-item">
                                <img src="https://th.bing.com/th/id/OIP.lKKfG5aQwjyHTM7kH77R8AHaHa?rs=1&pid=ImgDetMain" alt="Samuel L. Jackson" class="actor">
                                <span class="actor-name">Samuel L. Jackson</span>
                            </div>
                            <div class="actor-item">
                                <img src="https://th.bing.com/th/id/OIP.R1kTUT0g5pqvb-JQNBES6wHaEs?rs=1&pid=ImgDetMain" alt="Hannah Waddingham" class="actor">
                                <span class="actor-name">Hannah Waddingham</span>
                            </div>
                            <div class="actor-item">
                                <img src="https://th.bing.com/th/id/OIP.Awr2-yRx5kjbfNN2oqYH1QHaJ4?rs=1&pid=ImgDetMain" alt="Ving Rhames" class="actor">
                                <span class="actor-name">Ving Rhames</span>
                            </div>
                            <div class="actor-item">
                                <img src="https://th.bing.com/th/id/OIP.aLCjCqb3i0EtQUWN7bGmBQHaJi?rs=1&pid=ImgDetMain" alt="Alfred Molina" class="actor">
                                <span class="actor-name">Alfred Molina</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="director">
                        <h3>Director</h3>
                        <p>Mark Dindal</p>
                    </div>
                </div>
                
                <div class="right-column">
                    <div class="movie-details">
                        <h2 class="movie-title">The Garfield</h2>
                        <p class="movie-info">2024 | 1H 40Mins | <span class="rating">★★★★☆</span></p>
                        <div class="tags">
                            <span class="tag">Animation</span>
                            <span class="tag">Comedy</span>
                            <span class="tag">Family</span>
                            <span class="tag">Adventure</span>
                        </div>
                        
                        <p class="description">Garfield, the lasagna-loving, Monday-hating cat, is thrust into a wild outdoor adventure after reuniting with his scruffy father, Vic. Joined by his canine friend Odie, Garfield embarks on a hilarious, high-stakes heist filled with comedic mishaps. <a href="#" class="read-more">Read More</a></p>
                        
                        <div class="trailer-container">
                            <h3>Movie Trailer</h3>
                            <iframe class="movie-trailer" src="https://www.youtube.com/embed/IeFWNtMo1Fs?si=cuo57dv6n2DzmsEb" frameborder="0" allowfullscreen></iframe>
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
                title: "Spider-Man: No Way Home",
                poster: "spiderman poster.jpg"
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

            if (wishlist.some(movie => movie.title === "Spider-Man: No Way Home")) {
                wishlistBtn.innerText = "In Wishlist";
                wishlistBtn.classList.add("in-wishlist");
            }
            
            document.querySelector(".arrow-next").addEventListener("click", function(e) {
            });
        };
    </script>
</body>
</html>