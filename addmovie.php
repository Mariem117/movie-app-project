<?php
require_once __DIR__ . '/database.php';
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit();
}
if (strlen($title) > 255) {
    die("Title is too long.");
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $title = $conn->real_escape_string($_POST['title']);
    if (strlen($title) > 255) {
        die("Title is too long.");
    }
    $year = intval($_POST['year']);
    $duration = $conn->real_escape_string($_POST['duration']);
    $rating = floatval($_POST['rating']);
    $description = $conn->real_escape_string($_POST['description']);
    $director = $conn->real_escape_string($_POST['director']);
    $trailer_url = $conn->real_escape_string($_POST['trailer_url']);
    $mood = $conn->real_escape_string($_POST['mood']);
    
    // Process tags
    $tags = isset($_POST['tags']) ? implode(',', $_POST['tags']) : '';
    
    // Process poster upload
    $poster = '';
    if (isset($_FILES['poster']) && $_FILES['poster']['error'] === 0) {
        $upload_dir = '../uploads/posters/';
        
        // Create directory if it doesn't exist
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $file_name = time() . '_' . $_FILES['poster']['name'];
        $target_file = $upload_dir . $file_name;
        
        // Move uploaded file
        if (move_uploaded_file($_FILES['poster']['tmp_name'], $target_file)) {
            $poster = 'uploads/posters/' . $file_name;
        } else {
            $_SESSION['error'] = "Failed to upload poster!";
            header("Location: add_movie.php");
            exit();
        }
    }
    
    // Insert movie data
    $insert_query = "INSERT INTO movies (title, year, duration, rating, description, director, trailer_url, mood, tags, poster) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("sisdsssss", $title, $year, $duration, $rating, $description, $director, $trailer_url, $mood, $tags, $poster);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Movie added successfully!";
        header("Location: movies.php");
        exit();
    } else {
        $_SESSION['error'] = "Failed to add movie: " . $conn->error;
    }
    
    $stmt->close();
}

// Get all moods for the dropdown
$moods = [
    'Exciting', 'Thrilling', 'Playful', 'Funny', 'Suspense', 
    'Insight', 'Drama', 'Heartwarming', 'Imagination', 'Reflection', 
    'Scary', 'Melodic', 'Intense', 'Romantic', 'Sci-Fi', 'Classic'
];

// Get all tags for the checkboxes
$all_tags = [
    'Action', 'Adventure', 'Comedy', 'Crime', 'Drama', 'Fantasy', 
    'Horror', 'Romance', 'Science Fiction', 'Thriller', 'Animation', 'Documentary'
];

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Movie - MoodFlix Admin</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <div class="admin-container">
        <aside class="sidebar">
            <div class="logo">
                <h2>MoodFlix Admin</h2>
            </div>
            <nav>
                <ul>
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li class="active"><a href="movies.php">Movies</a></li>
                    <li><a href="users.php">Users</a></li>
                    <li><a href="bookings.php">Bookings</a></li>
                    <li><a href="showtimes.php">Showtimes</a></li>
                    <li><a href="../logout.php">Logout</a></li>
                </ul>
            </nav>
        </aside>
        
        <main class="content">
            <header>
                <h1>Add New Movie</h1>
                <a href="movies.php" class="btn back-btn">Back to Movies</a>
            </header>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert error">
                    <?php 
                    echo $_SESSION['error']; 
                    unset($_SESSION['error']);
                    ?>
                </div>
            <?php endif; ?>
            
            <div class="form-container">
                <form action="add_movie.php" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="title">Title *</label>
                        <input type="text" id="title" name="title" required>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="year">Year *</label>
                            <input type="number" id="year" name="year" min="1900" max="2099" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="duration">Duration (e.g., 2H 30Mins) *</label>
                            <input type="text" id="duration" name="duration" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="rating">Rating (0-5) *</label>
                            <input type="number" id="rating" name="rating" min="0" max="5" step="0.1" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Description *</label>
                        <textarea id="description" name="description" rows="4" required></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="director">Director(s) *</label>
                        <input type="text" id="director" name="director" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="poster">Poster Image</label>
                        <input type="file" id="poster" name="poster" accept="image/*">
                    </div>
                    
                    <div class="form-group">
                        <label for="trailer_url">YouTube Trailer URL</label>
                        <input type="text" id="trailer_url" name="trailer_url" placeholder="e.g., https://www.youtube.com/embed/TcMBFSGVi1c">
                    </div>
                    
                    <div class="form-group">
                        <label for="mood">Mood *</label>
                        <select id="mood" name="mood" required>
                            <option value="">Select Mood</option>
                            <?php foreach ($moods as $mood): ?>
                                <option value="<?php echo $mood; ?>"><?php echo $mood; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Tags</label>
                        <div class="tags-container">
                            <?php foreach ($all_tags as $tag): ?>
                                <div class="tag-checkbox">
                                    <input type="checkbox" id="tag_<?php echo $tag; ?>" name="tags[]" value="<?php echo $tag; ?>">
                                    <label for="tag_<?php echo $tag; ?>"><?php echo $tag; ?></label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn submit-btn">Add Movie</button>
                        <a href="movies.php" class="btn cancel-btn">Cancel</a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>