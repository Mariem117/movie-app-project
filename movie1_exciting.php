<?php
session_start();
require_once 'database.php';
$movie_title = 'Mad Max: Fury Road';
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
    <title>Movie Mood Ticket Booking - Mad Max: Fury Road</title>
    <link rel="icon" href="image(2).png" type="image/x-icon">
    <link rel="stylesheet" href="moviepage.css">
</head>
<body>
    <div class="header">
        <button class="change-mood" onclick="location.href='index.php'">⬅ Change Mood</button>        <span class="mood-title">&#x1F929; exciting Mood</span>
        <a href="wishlist.php" class="wish"><button>🎬 Wishlist</button></a>
    </div>
    
    <div class="container">
        <a href="#" class="arrow-nav arrow-prev">‹</a>
        <a href="movie2_exciting.php" class="arrow-nav arrow-next">›</a>
        
        <div class="movie-card">
            <div class="movie-content">
                <div class="left-column">
                    <img src="https://th.bing.com/th/id/R.a0753430500b50dd19efb368c2dd936e?rik=ophNTb4x4SPbTQ&riu=http%3a%2f%2fwww.thegoodthebadandtheodd.com%2fwp-content%2fuploads%2f2015%2f07%2fMadMax.jpg&ehk=Soaw5incFRJe0jUuIDXHMezxIiFgN%2f3ieGUEfqRVd9M%3d&risl=&pid=ImgRaw&r=0" alt="Mad Max: Fury Road Poster" class="movie-poster">
                    
                    <div class="actors">
                        <h3>Actors</h3>
                        <div class="actor-images">
                            <div class="actor-item">
                                <img src="https://th.bing.com/th/id/OIP.ORAYgnhmz9Be3GgsOQkp2wHaLH?w=186&h=279&c=7&r=0&o=5&dpr=1.3&pid=1.7" alt="Tom Hardy" class="actor">
                                <span class="actor-name">Tom Hardy</span>
                            </div>
                            <div class="actor-item">
                                <img src="https://th.bing.com/th/id/OIP.D5M64xhq9S_-59Kdb8FWyAHaKi?w=186&h=265&c=7&r=0&o=5&dpr=1.3&pid=1.7" alt="Charlize Theron" class="actor">
                                <span class="actor-name">Charlize Theron</span>
                            </div>
                            <div class="actor-item">
                                <img src="https://th.bing.com/th/id/OIP.qW0ehJhPJv-mfYmIEcQF8AHaKK?w=186&h=255&c=7&r=0&o=5&dpr=1.3&pid=1.7" alt="Nicholas Hoult" class="actor">
                                <span class="actor-name">Nicholas Hoult</span>
                            </div>
                            <div class="actor-item">
                                <img src="https://th.bing.com/th/id/OIP.TAYhbR0vwKiKd49bVRKsjQHaLH?w=186&h=279&c=7&r=0&o=5&dpr=1.3&pid=1.7" alt="Hugh Keays-Byrne" class="actor">
                                <span class="actor-name">Hugh Keays-Byrne</span>
                            </div>
                            <div class="actor-item">
                                <img src="https://th.bing.com/th/id/OIP.3h0SNokbALHxQxaHOoN2rQHaLE?w=186&h=278&c=7&r=0&o=5&dpr=1.3&pid=1.7" alt="Rosie Huntington-Whiteley" class="actor">
                                <span class="actor-name">Rosie Huntington-Whiteley</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="director">
                        <h3>Director</h3>
                        <p>George Miller</p>
                    </div>
                </div>
                
                <div class="right-column">
                    <div class="movie-details">
                        <h2 class="movie-title">Mad Max: Fury Road</h2>
                        <p class="movie-info">2015 | 2H 0Mins | <span class="rating">★★★★★</span></p>
                        <div class="tags">
                            <span class="tag">Action</span>
                            <span class="tag">Adventure</span>
                            <span class="tag">Sci-Fi</span>
                        </div>
                        
                        <p class="description">In a desolate wasteland, Max teams up with Furiosa to flee a ruthless warlord and his fanatical army. Their high-speed rebellion sparks a chaotic road war, pushing survival and freedom to the edge. <a href="#" class="read-more">Read More</a></p>
                        
                        <div class="trailer-container">
                            <h3>Movie Trailer</h3>
                            <iframe class="movie-trailer" src="https://www.youtube.com/embed/hEJnMQG9ev8?si=5z8y3q7l5i8q9y9l" frameborder="0" allowfullscreen></iframe>
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
        function loadWishlist() {
            let wishlist = JSON.parse(localStorage.getItem("wishlist")) || [];
            let container = document.getElementById("wishlist-container");
            container.innerHTML = "";

            if (wishlist.length === 0) {
                container.innerHTML = "<p class='empty-wishlist'>Your wishlist is empty. Add some movies to watch later!</p>";
                document.getElementById("wishlist-count").innerText = 0;
                return;
            }

            wishlist.forEach(movie => {
                let movieCard = document.createElement("div");
                movieCard.classList.add("movie-card");
                movieCard.setAttribute("data-id", movie.id);
                movieCard.innerHTML = `
                    <div class="movie-content">
                        <div class="poster-container">
                            <img src="${movie.poster}" alt="${movie.title} Poster" class="movie-poster">
                        </div>
                        <h3 class="movie-title">${movie.title}</h3>
                        <button class="remove-btn" onclick="removeFromWishlist(${movie.id}, '${movie.title}')">Remove</button>
                    </div>
                `;
                container.appendChild(movieCard);
            });

            document.getElementById("wishlist-count").innerText = wishlist.length;
        }

        function removeFromWishlist(movieId, title) {
            let wishlist = JSON.parse(localStorage.getItem("wishlist")) || [];
            wishlist = wishlist.filter(movie => movie.title !== title);
            localStorage.setItem("wishlist", JSON.stringify(wishlist));

            // Remove from database
            fetch('update_wishlist.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ movie_id: movieId, action: 'remove' })
            }).catch(error => alert(error.message));

            loadWishlist();
        }

        window.onload = loadWishlist;
    </script>
</body>
</html>