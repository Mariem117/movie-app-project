<?php
session_start();
require_once __DIR__ . '/database.php';  // Fixed path with slash

// Get the standardized PDO connection
$pdo = getPDO();

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php"); // Fixed redirect path
    exit();
}

// Handle user deletion
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $user_id = (int)$_GET['delete'];
    
    // Prevent admin from deleting themselves
    if ($user_id == $_SESSION['user_id']) {
        $_SESSION['error'] = "You cannot delete your own account!";
    } else {
        try {
            // Delete user with PDO prepared statement
            $delete_query = "DELETE FROM users WHERE id = ? AND role != 'admin'";
            $stmt = $pdo->prepare($delete_query);
            $result = $stmt->execute([$user_id]);
            
            if ($result && $stmt->rowCount() > 0) {
                $_SESSION['success'] = "User deleted successfully!";
            } else {
                $_SESSION['error'] = "Failed to delete user or user is an admin!";
            }
        } catch (PDOException $e) {
            $_SESSION['error'] = "Database error: " . $e->getMessage();
        }
    }
    
    header("Location: register.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link rel="stylesheet" href="main.css">
</head>
<body>
    <h1>User Management</h1>
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
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['id']); ?></td>
                    <td><?php echo htmlspecialchars($user['name']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td>
                        <form method="POST" action="">
                            <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['id']); ?>">
                            <button type="submit" name="delete_user" onclick="return confirm('Are you sure you want to delete this user?');">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>