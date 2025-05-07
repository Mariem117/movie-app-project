<?php
session_start();
require_once 'database.php';
$movie_title = 'The Lord of the Rings';
$pdo = getPDO();
$stmt = $pdo->prepare("SELECT id FROM movies WHERE title = ?");
$stmt->execute([$movie_title]);
$movie = $stmt->fetch(PDO::FETCH_ASSOC);
$movie_id = 5;
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
    <title>The Lord of the Rings</title>
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
        <a href="movie1 drama.html" class="arrow-nav arrow-prev">&#8249;</a>
        <a href="#" class="arrow-nav arrow-next">&#8250;</a>
        
        <div class="movie-card">
            <div class="movie-content">
                <div class="left-column">
                    <img src="https://th.bing.com/th/id/OIP.YH5ZMdyZn7f6CVOKDf-HRwHaLH?w=204&h=306&c=7&r=0&o=7&cb=iwp1&dpr=1.3&pid=1.7&rm=3" alt="The Lord of the Rings Poster" class="movie-poster">
                    
                    <div class="actors">
                        <h3>Actors</h3>
                        <div class="actor-images">
                            <div class="actor-item">
                                <img src="https://th.bing.com/th/id/OIP.IXJ81Oxk16XTD2RTxlVETAHaLH?w=186&h=279&c=7&r=0&o=5&dpr=1.3&pid=1.7" alt="Elijah Wood" class="actor">
                                <span class="actor-name">Elijah Wood</span>
                            </div>
                            <div class="actor-item">
                                <img src="https://th.bing.com/th/id/OIP.7_1sFO2Fr_KzSDtpTmCk8QHaLA?w=186&h=276&c=7&r=0&o=5&dpr=1.3&pid=1.7" alt="Viggo Mortensen" class="actor">
                                <span class="actor-name">Viggo Mortensen</span>
                            </div>
                            <div class="actor-item" alt="Ian McKellen" class="actor">
                                <img src="https://th.bing.com/th/id/OIP.C5qZnjhEFmkoIb3JdIR2rQHaJL?w=186&h=231&c=7&r=0&o=5&dpr=1.3&pid=1.7" alt="Orlando Bloom" class="actor">
                                <span class="actor-name">Ian McKellen</span>
                            </div>
                            <div class="actor-item">
                                <img src="https://th.bing.com/th/id/OIP.NGm3646AH0HPezx6UFw0xAHaKl?w=186&h=265&c=7&r=0&o=5&dpr=1.3&pid=1.7" alt="Orlando Bloom" class="actor">
                                <span class="actor-name">Orlando Bloom</span>
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
                        <p class="movie-info">2003 | 142 Mins | <span class="rating">★★★★☆</span></p>
                        <div class="tags">
                            <span class="tag">Drama</span>
                            <span class="tag">Adventure</span>
            
                        </div>
                        
                        <p class="description">The Lord of the Rings is an epic fantasy adventure where a young hobbit, Frodo Baggins, embarks on a perilous journey to destroy a powerful ring and save Middle-earth from the dark lord Sauron.<a href="#" class="read-more">Read More</a></p>
                        
                        <div class="trailer-container">
                            <h3>Movie Trailer</h3>
                            <iframe class="movie-trailer" src="https://www.youtube.com/embed/r5X-hFf6Bwo?si=Dm-6gpeJhOoeWqJh" frameborder="0" allowfullscreen></iframe>
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