<?php
session_start();
require_once 'database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'User not logged in']);
    exit();
}

// Get JSON data from request
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['movie_id']) || !isset($data['action'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required parameters']);
    exit();
}

$movie_id = $data['movie_id'];
$action = $data['action'];
$user_id = $_SESSION['user_id'];

// Connect to database
$pdo = getPDO();

try {
    if ($action === 'add') {
        // Check if entry already exists
        $check_stmt = $pdo->prepare("SELECT 1 FROM wishlist WHERE user_id = ? AND movie_id = ?");
        $check_stmt->execute([$user_id, $movie_id]);
        
        if (!$check_stmt->fetchColumn()) {
            // Insert new wishlist item
            $stmt = $pdo->prepare("INSERT INTO wishlist (user_id, movie_id) VALUES (?, ?)");
            $stmt->execute([$user_id, $movie_id]);
        }
        
        echo json_encode(['status' => 'success', 'message' => 'Movie added to wishlist']);
    } elseif ($action === 'remove') {
        // Remove wishlist item
        $stmt = $pdo->prepare("DELETE FROM wishlist WHERE user_id = ? AND movie_id = ?");
        $stmt->execute([$user_id, $movie_id]);
        
        echo json_encode(['status' => 'success', 'message' => 'Movie removed from wishlist']);
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid action']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>