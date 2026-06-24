<?php
error_reporting(0);
header('Content-Type: application/json');

$id = $_GET['id'] ?? 0;

try {
    $pdo = new PDO('mysql:host=localhost;dbname=Schedule;charset=utf8mb4', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $pdo->prepare("
        SELECT d.*, dh.lecture_hours, dh.practice_hours, dh.lab_hours, dh.assessment_type 
        FROM disciplines d 
        LEFT JOIN discipline_hours dh ON d.discipline_id = dh.discipline_id 
        WHERE d.discipline_id = :id
    ");
    $stmt->execute(['id' => $id]);
    $disc = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo json_encode($disc);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Connection failed']);
}
?>
