<?php
session_start();
require_once 'database.php';
$pdo = getPDO();
// Fetch movies from the database
$query = "SELECT * FROM movies";

$stmt = $pdo->prepare($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Mood Ticket Booking</title>
    <link rel="stylesheet" href="front_pg.css">
    <link rel="icon" href="image(2).png" type="x-icon">
</head>
<body>
    <nav>
        <div class="nav-left">
            <a href="wishlist.php">My Wishlist</a>
            <a href="#about-us">About Us</a>
            <a href="#contact-us">Contact</a>
        </div>
        <div class="nav-right">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="logout.php">Log Out</a>
            <?php else: ?>
                <a href="sign_up.php">Sign Up</a>
                <a href="login.php">Log In</a>
            <?php endif; ?>
        </div>
    </nav>
    <header>
        <div class="title-box-container">
            <img src="image(2).png" alt="Left Image" class="side-image left">
            <div class="title-box">
                <h1>MoodFlix</h1>
            </div>
            <img src="image(2).png" alt="Right Image" class="side-image right">
        </div>
        <div>
            <p>Choose your mood and discover the perfect movie playing in cinemas now</p>
            <h2>Select Your State of Mind. We'll Handle the Rest</h2>
        </div>
    </header>
    <main>
        <section id="moods">
            <div class="mood-buttons">
                <button onclick="location.href='movie1_exciting.html'">🎢 Exciting</button>
                <button onclick="location.href='movie1_thrilling.html'">🎬 Thrilling</button>
                <button onclick="location.href='movie1_playful.html'">🎈 Playful</button>
                <button onclick="location.href='movie1_funny.html'">😂 Funny</button>
                <button onclick="location.href='movie1_suspense.html'">🔍 Suspense</button>
                <button onclick="location.href='movie1_insight.html'">💡 Insight</button>
                <button onclick="location.href='movie1_drama.html'">🎭 Drama</button>
                <button onclick="location.href='movie1_heartwarming.html'">❤️ Heartwarming</button>
                <button onclick="location.href='movie1_imagination.html'">🌈 Imagination</button>
                <button onclick="location.href='movie1_reflection.html'">🪞 Reflection</button>
                <button onclick="location.href='movie1_scary.html'">👻 Scary</button>
                <button onclick="location.href='movie1_melodic.html'">🎶 Melodic</button>
                <button onclick="location.href='movie1_intense.html'">🔥 Intense</button>
                <button onclick="location.href='movie1_romantic.html'">💖 Romantic</button>
                <button onclick="location.href='movie1_scifi.html'">🚀 Sci-Fi</button>
                <button onclick="location.href='movie1_classic.html'">🎥 Classic</button>
            </div>
        </section>
    </main>
    <footer>
        <div class="footer-content">
            <div class="movie-listings">
                <div class="new-releases">
                    <h5>NEW RELEASES</h5>
                    <ul>
                        <li><a href="movie2_thrilling.php">Black Bag</a></li>
                        <li><a href="#">صاحبك راجل / Sabbek Rajel</a></li>
                        <li><a href="#">Aicha / عايشة</a></li>
                        <li><a href="#">Captain America: Brave New World</a></li>
                        <li><a href="#">BOL</a></li>
                        <li><a href="#">ICE</a></li>
                    </ul>
                </div>
                <div class="cinemas">
                    <h5>CINEMAS IN YOUR CITIES</h5>
                    <ul>
                        <li>Pathé Tunis</li>
                        <li>Pathé Azur</li>
                        <li>Pathé Mall Of Sousse</li>
                        <li>Sousse</li>
                    </ul>
                </div>
                <div class="about" id="about-us">
                    <h5>ABOUT US</h5>
                    <p>Created by three engineering students, we're revolutionizing movie selection. Discover films that match your mood. Get to know more about us by visiting our LinkedIn accounts.</p>
                    <div class="creators">
                        <a href="https://www.linkedin.com/in/mariem-wesleti-86481324a/" target="_blank">
                            <img src="mariem.jpg" alt="Mariem Oueslati">
                        </a>
                        <a href="https://www.linkedin.com/in/assyl-benkhdija-191075283/" target="_blank">
                            <img src="assyl.jpg" alt="Assil Ben Khedija">
                        </a>
                        <a href="https://www.linkedin.com/in/seifeddine-hamdi-64bb05349" target="_blank">
                            <img src="seif.jpg" alt="Seifeddine Hamdi">
                        </a>
                    </div>
                </div>
                <div class="contact" id="contact-us">
                    <h5>Contact Us</h5>
                    <ul>
                        <li><a href="mailto:mariem.wesleti@esen.tn">mariem.wesleti@esen.tn</a></li>
                        <li><a href="mailto:assil.benkhdija@esen.tn">assil.benkhdija@esen.tn</a></li>
                        <li><a href="mailto:seifeddine.hamdi@esen.tn">seifeddine.hamdi@esen.tn</a></li>
                    </ul>
                </div>
            </div>
            <p class="copyright">Tunisia Cinemas © 2025 - Copyrights are reserved</p>
        </div>
    </footer>
</body>
</html>