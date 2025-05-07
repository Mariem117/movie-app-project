<?php
session_start();
require_once 'database.php';
$movie_title = 'Flight 404';
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
    <title>Movie Mood Ticket Booking - Flight 404</title>
    <link rel="icon" href="image(2).png" type="image/x-icon">
    <link rel="stylesheet" href="moviepage.css">
</head>
<body>
    <div class="header">
        <button class="change-mood" onclick="location.href='index.php'">⬅ Change Mood</button>
        <span class="mood-title">🔍 Suspense Mood</span>
        <a href="wishlist.php" class="wish"><button>🎬 Wishlist</button></a>
    </div>
    
    <div class="container">
        <a href="movie1_suspense.php" class="arrow-nav arrow-prev">‹</a>
        <a href="#" class="arrow-nav arrow-next">›</a>
        
        <div class="movie-card">
            <div class="movie-content">
                <div class="left-column">
                    <img src="https://th.bing.com/th/id/OIP.uqC-KXQE8mcjb2IG35FSQAHaLG?rs=1&pid=ImgDetMain" alt="Flight 404 Poster" class="movie-poster">
                    
                    <div class="actors">
                        <h3>Actors</h3>
                        <div class="actor-images">
                            <div class="actor-item">
                                <img src="https://th.bing.com/th/id/R.e7fa45fc0b35f585484a0de6dcaeb37c?rik=NMU3Vz0TDqcFnQ&pid=ImgRaw&r=0" alt="Mona Zaki" class="actor">
                                <span class="actor-name">Mona Zaki</span>
                            </div>
                            <div class="actor-item">
                                <img src="https://th.bing.com/th/id/OIP.NVn3bQSui6COy5eH1wZ-UAHaEd?w=267&h=180&c=7&r=0&o=5&dpr=1.3&pid=1.7" alt="Khaled El Nabawy" class="actor">
                                <span class="actor-name">Khaled El Nabawy</span>
                            </div>
                            <div class="actor-item">
                                <img src="https://th.bing.com/th/id/OIP.VTzSyr4njCkFA7530eoDkgAAAA?w=117&h=180&c=7&r=0&o=5&dpr=1.3&pid=1.7" alt="Mohamed Farrag" class="actor">
                                <span class="actor-name">Mohamed Farrag</span>
                            </div>
                            <div class="actor-item">
                                <img src="https://th.bing.com/th/id/OIP.REPFBKYfsiUf_h01Y-ddmwHaE3?rs=1&pid=ImgDetMain" alt="Mohamed Mamdouh" class="actor">
                                <span class="actor-name">Mohamed Mamdouh</span>
                            </div>
                            <div class="actor-item">
                                <img src="https://th.bing.com/th/id/OIP.Av_0JlMHryOlgnHtOqJajQHaHa?w=186&h=186&c=7&r=0&o=5&dpr=1.3&pid=1.7" alt="Shereen Reda" class="actor">
                                <span class="actor-name">Shereen Reda</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="director">
                        <h3>Director</h3>
                        <p>Hani Khalifa</p>
                    </div>
                </div>
                
                <div class="right-column">
                    <div class="movie-details">
                        <h2 class="movie-title">Flight 404</h2>
                        <p class="movie-info">2024 | 1H 45Mins | <span class="rating">★★★★☆</span></p>
                        <div class="tags">
                            <span class="tag">Drama</span>
                            <span class="tag">Thriller</span>
                            <span class="tag">Suspense</span>
                        </div>
                        
                        <p class="description">Ghada’s shady dealings to fund a pilgrimage unravel during a plane emergency, blending survival suspense with moral tension as her past decisions haunt her. <a href="#" class="read-more">Read More</a></p>
                        
                        <div class="trailer-container">
                            <h3>Movie Trailer</h3>
                            <iframe class="movie-trailer" src="https://www.youtube.com/embed/5MtmzfD9jaE?si=ZkjO_V8c4Tdt98t6 frameborder="0" allowfullscreen></iframe>
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
                title: "Flight 404",
                poster: "flight404_poster.jpg"
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

            if (wishlist.some(movie => movie.title === "Flight 404")) {
                wishlistBtn.innerText = "In Wishlist";
                wishlistBtn.classList.add("in-wishlist");
            }
            
            document.querySelector(".arrow-next").addEventListener("click", function(e) {
            });
        };
    </script>
</body>
</html>