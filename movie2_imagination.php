<?php
session_start();
require_once 'database.php';
$movie_title = 'Interstellar';
$pdo = getPDO();
$stmt = $pdo->prepare("SELECT id FROM movies WHERE title = ?");
$stmt->execute([$movie_title]);
$movie = $stmt->fetch(PDO::FETCH_ASSOC);
$movie_id = 7;
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
    <title>Interstellar</title>
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
        <a href="movie1 imagination.html" class="arrow-nav arrow-prev">&#8249;</a>
        <a href="#" class="arrow-nav arrow-next">&#8250;</a>
        
        <div class="movie-card">
            <div class="movie-content">
                <div class="left-column">
                    <img src="https://th.bing.com/th/id/OIP.jVVUF1D1uEuSPvQtvM5uXgHaLH?w=204&h=306&c=7&r=0&o=5&dpr=1.3&pid=1.7" alt=" Interstellar Poster" class="movie-poster">
                    
                    <div class="actors">
                        <h3>Actors</h3>
                        <div class="actor-images">
                            <div class="actor-item">
                                <img src="https://th.bing.com/th/id/OIP.sP9aKuyWGXzAx0_gvAn-kwHaLH?w=204&h=306&c=7&r=0&o=5&dpr=1.3&pid=1.7" alt="Matthew McConaughey" class="actor">
                                <span class="actor-name">Matthew McConaughey</span>
                            </div>
                            <div class="actor-item">
                                <img src="https://th.bing.com/th/id/OIP.gyi5PFoajM0r4BdIljbRhwHaLH?w=204&h=306&c=7&r=0&o=5&dpr=1.3&pid=1.7" alt="Anne Hathaway" class="actor">
                                <span class="actor-name">Anne Hathaway</span>
                            </div>
                            <div class="actor-item">
                                <img src="https://th.bing.com/th/id/OIP.IgghqFSYIHnvtvlopQL6YwHaLJ?w=203&h=306&c=7&r=0&o=5&dpr=1.3&pid=1.7" alt="Jessica Chastain" class="actor">
                                <span class="actor-name">Jessica Chastain</span>
                            </div>
                            <div class="actor-item">
                                <img src="https://th.bing.com/th/id/OIP.OfnmsiWvE8cAPzOcxVtjGwHaKX?w=208&h=291&c=7&r=0&o=5&dpr=1.3&pid=1.7" alt="Mackenzie Foy" class="actor">
                                <span class="actor-name">Mackenzie Foy</span>
                            </div>
                        
                        </div>
                    </div>
                    
                    <div class="director">
                        <h3>Director</h3>
                        <p>Christopher Nolan</p>
                    </div>
                </div>
                
                <div class="right-column">
                    <div class="movie-details">
                        <h2 class="movie-title">Interstellar</h2>
                        <p class="movie-info">2014 | 169 Mins | <span class="rating">★★★★☆</span></p>
                        <div class="tags">
                            <span class="tag">Imagination</span>
            
                        </div>
                        
                        <p class="description">Interstellar (2014), directed by Christopher Nolan, is a science fiction epic about a team of astronauts who travel through a wormhole near Saturn in search of a new habitable planet for humanity. Set in a future where Earth is facing environmental collapse, the story centers on Cooper, a former NASA pilot, who must leave his family behind to embark on the dangerous mission. The film explores themes of love, time, and survival, combining emotional storytelling with scientifically grounded concepts like relativity and black holes.<a href="#" class="read-more">Read More</a></p>
                        
                        <div class="trailer-container">
                            <h3>Movie Trailer</h3>
                            <iframe class="movie-trailer" src="https://www.youtube.com/embed/zSWdZVtXT7E?si=v85Tu0hi9yBF3wMI" frameborder="0" allowfullscreen></iframe>
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