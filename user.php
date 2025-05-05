<?php
session_start();
require_once 'database.php';

$pdo = getPDO();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Fetch all users
try {
    $stmt = $pdo->query("SELECT id, first_name, last_name, email, role FROM users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $_SESSION['error_message'] = "Database error: " . $e->getMessage();
    $users = [];
}

// Handle user deletion (POST or GET)
if (($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user']) && is_numeric($_POST['user_id'])) || 
    (isset($_GET['delete']) && is_numeric($_GET['delete']))) {
    $user_id = isset($_POST['user_id']) ? (int)$_POST['user_id'] : (int)$_GET['delete'];
    
    if ($user_id == $_SESSION['user_id']) {
        $_SESSION['error_message'] = "You cannot delete your own account!";
    } else {
        try {
            $delete_query = "DELETE FROM users WHERE id = ? AND role != 'admin'";
            $stmt = $pdo->prepare($delete_query);
            $result = $stmt->execute([$user_id]);
            
            if ($result && $stmt->rowCount() > 0) {
                $_SESSION['success_message'] = "User deleted successfully!";
            } else {
                $_SESSION['error_message'] = "Failed to delete user or user is an admin!";
            }
        } catch (PDOException $e) {
            $_SESSION['error_message'] = "Database error: " . $e->getMessage();
        }
    }
    
    header("Location: user.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link rel="stylesheet" href="styles/main.css">
</head>
<body>
    <h1>User Management</h1>
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class='success'><?= htmlspecialchars($_SESSION['success_message']); ?></div>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>
    <?php if (isset($_SESSION['error_message'])): ?>
        <div class='error'><?= htmlspecialchars($_SESSION['error_message']); ?></div>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($users)): ?>
                <tr>
                    <td colspan="5">No users found.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id']); ?></td>
                        <td><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></td>
                        <td><?= htmlspecialchars($user['email']); ?></td>
                        <td><?= htmlspecialchars($user['role']); ?></td>
                        <td>
                            <form method="POST" action="">
                                <input type="hidden" name="user_id" value="<?= htmlspecialchars($user['id']); ?>">
                                <button type="submit" name="delete_user" onclick="return confirm('Are you sure you want to delete this user?');">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>