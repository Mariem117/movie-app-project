<?php
session_start();
require_once 'database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not authenticated']);
    exit();
}

$pdo = getPDO();

// Check if this is an AJAX request
$isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
          strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

// Get input data - support both form data and JSON
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle form-encoded data
    if (isset($_POST['movie_id']) && isset($_POST['action'])) {
        $movie_id = filter_input(INPUT_POST, 'movie_id', FILTER_VALIDATE_INT);
        $action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);
    } 
    // Handle JSON data
    else {
        $input = json_decode(file_get_contents('php://input'), true);
        $movie_id = filter_var($input['movie_id'] ?? null, FILTER_VALIDATE_INT);
        $action = filter_var($input['action'] ?? null, FILTER_SANITIZE_STRING);
    }
    
    if (!$movie_id) {
        echo json_encode(['success' => false, 'message' => 'Invalid movie ID']);
        exit();
    }
    
    // Check if movie exists
    $stmt = $pdo->prepare("SELECT id FROM movies WHERE id = ?");
    $stmt->execute([$movie_id]);
    if (!$stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Movie not found']);
        exit();
    }
    
    try {
        if ($action === 'add') {
            // Check if already in wishlist
            $stmt = $pdo->prepare("SELECT id FROM wishlist WHERE user_id = ? AND movie_id = ?");
            $stmt->execute([$_SESSION['user_id'], $movie_id]);
            if (!$stmt->fetch()) {
                // Add to wishlist
                $stmt = $pdo->prepare("INSERT INTO wishlist (user_id, movie_id) VALUES (?, ?)");
                $stmt->execute([$_SESSION['user_id'], $movie_id]);
            }
            
            $response = ['success' => true, 'message' => 'Movie added to wishlist'];
        } 
        elseif ($action === 'remove') {
            // Remove from wishlist
            $stmt = $pdo->prepare("DELETE FROM wishlist WHERE user_id = ? AND movie_id = ?");
            $stmt->execute([$_SESSION['user_id'], $movie_id]);
            
            $response = ['success' => true, 'message' => 'Movie removed from wishlist'];
        } 
        else {
            $response = ['success' => false, 'message' => 'Invalid action'];
        }
    } 
    catch (PDOException $e) {
        $response = ['success' => false, 'message' => 'Database error: ' . $e->getMessage()];
    }
    
    // Return response
    echo json_encode($response);
    
    // If not AJAX, redirect back
    if (!$isAjax && isset($_SERVER['HTTP_REFERER'])) {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }
}
else {
    // Not a POST request
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>