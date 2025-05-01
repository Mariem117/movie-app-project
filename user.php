<?php
session_start();
require_once __DIR__ . '/database.php';  // Fixed path with slash

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../log_in.html");
    exit();
}

// Handle user deletion
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $user_id = (int)$_GET['delete'];
    
    // Prevent admin from deleting themselves
    if ($user_id == $_SESSION['user_id']) {
        $_SESSION['error'] = "You cannot delete your own account!";
    } else {
        // Delete user
        $delete_query = "DELETE FROM users WHERE id = ? AND role != 'admin'";
        $stmt = $conn->prepare($delete_query);
        $stmt->bind_param("i", $user_id);
        
        if ($stmt->execute() && $stmt->affected_rows > 0) {
            $_SESSION['success'] = "User deleted successfully!";
        } else {
            $_SESSION['error'] = "Failed to delete user or user is an admin!";
        }
        
        $stmt->close();
    }
    
    header("Location: register.php");
    exit();
}

// Rest of your code remains the same
// ...
?>