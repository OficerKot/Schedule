<?php
error_reporting(0);
header('Content-Type: application/json');

try {
    $pdo = new PDO('mysql:host=localhost;dbname=Schedule;charset=utf8mb4', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $pdo->query("SELECT teacher_id, first_name, middle_name, last_name, position, chair, degree, title FROM teachers ORDER BY last_name");
    $teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($teachers);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Connection failed']);
}
?>
