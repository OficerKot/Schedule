<?php
error_reporting(0);
header('Content-Type: application/json');

try {
    $pdo = new PDO('mysql:host=localhost;dbname=Schedule;charset=utf8mb4', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $pdo->query("SELECT `group_id`, `name`, `students_count` FROM `groups` ORDER BY `name`");
    $groups = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($groups);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Connection failed']);
}
?>
