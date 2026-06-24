<?php
error_reporting(0);
header('Content-Type: application/json');

$id = $_GET['id'] ?? 0;

try {
    $pdo = new PDO('mysql:host=localhost;dbname=Schedule;charset=utf8mb4', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $pdo->prepare("SELECT * FROM rooms WHERE room_id = :id");
    $stmt->execute(['id' => $id]);
    $room = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo json_encode($room);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Connection failed']);
}
?>
