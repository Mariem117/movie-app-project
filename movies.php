<?php
require_once 'database.php';

session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
}

$pdo = new PDO($dsn, $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_movie'])) {
        $title = $_POST['title'];
        $description = $_POST['description'];
        $release_date = $_POST['release_date'];
        $sql = "INSERT INTO movies (title, description, release_date) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$title, $description, $release_date]);
        $_SESSION['success_message'] = "Movie added successfully.";
    }

    if (isset($_POST['edit_movie'])) {
        $id = $_POST['id'];
        $title = $_POST['title'];
        $description = $_POST['description'];
        $release_date = $_POST['release_date'];
        $sql = "UPDATE movies SET title = ?, description = ?, release_date = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$title, $description, $release_date, $id]);
        $_SESSION['success_message'] = "Movie updated successfully.";
    }

    if (isset($_POST['delete_movie'])) {
        $id = $_POST['id'];
        $sql = "DELETE FROM movies WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        $_SESSION['success_message'] = "Movie deleted successfully.";
    }
}

$sql = "SELECT * FROM movies";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$movies = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Movies</title>
    <link rel="stylesheet" href="main.css">
</head>
<body>
    <h1>Manage Movies</h1>

    <?php
    if (isset($_SESSION['success_message'])) {
        echo "<div class='success'>{$_SESSION['success_message']}</div>";
        unset($_SESSION['success_message']);
    }

    if (isset($_SESSION['error_message'])) {
        echo "<div class='error'>{$_SESSION['error_message']}</div>";
        unset($_SESSION['error_message']);
    }
    ?>

    <form method="POST">
        <h2>Add Movie</h2>
        <input type="text" name="title" placeholder="Movie Title" required>
        <textarea name="description" placeholder="Movie Description" required></textarea>
        <input type="date" name="release_date" required>
        <button type="submit" name="add_movie">Add Movie</button>
    </form>

    <h2>Existing Movies</h2>
    <table>
        <tr>
            <th>Title</th>
            <th>Description</th>
            <th>Release Date</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($movies as $movie): ?>
        <tr>
            <td><?php echo htmlspecialchars($movie['title']); ?></td>
            <td><?php echo htmlspecialchars($movie['description']); ?></td>
            <td><?php echo htmlspecialchars($movie['release_date']); ?></td>
            <td>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="id" value="<?php echo $movie['id']; ?>">
                    <button type="submit" name="delete_movie" onclick="return confirm('Are you sure you want to delete this?');">Delete</button>
                </form>
                <button onclick="editMovie(<?php echo $movie['id']; ?>, '<?php echo htmlspecialchars($movie['title']); ?>', '<?php echo htmlspecialchars($movie['description']); ?>', '<?php echo htmlspecialchars($movie['release_date']); ?>')">Edit</button>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <script>
        function editMovie(id, title, description, release_date) {
            document.querySelector('input[name="id"]').value = id;
            document.querySelector('input[name="title"]').value = title;
            document.querySelector('textarea[name="description"]').value = description;
            document.querySelector('input[name="release_date"]').value = release_date;
            document.querySelector('button[name="edit_movie"]').style.display = 'block';
        }
    </script>
</body>
</html>