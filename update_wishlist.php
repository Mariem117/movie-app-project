<?php
session_start();
require_once 'database.php';
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    exit();
}
$pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$data = json_decode(file_get_contents('php://input'), true);
$movie_id = filter_var($data['movie_id'], FILTER_VALIDATE_INT);
$action = $data['action'];
if (!$movie_id || !in_array($action, ['add', 'remove'])) {
    http_response_code(400);
    exit();
}
if ($action === 'add') {
    $stmt = $pdo->prepare("INSERT INTO wishlist (user_id, movie_id) VALUES (?, ?)");
    $stmt->execute([$_SESSION['user_id'], $movie_id]);
} else {
    $stmt = $pdo->prepare("DELETE FROM wishlist WHERE user_id = ? AND movie_id = ?");
    $stmt->execute([$_SESSION['user_id'], $movie_id]);
}
http_response_code(200);
?>