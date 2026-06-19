<?php
error_reporting(0);
header('Content-Type: application/json');

try {
    $pdo = new PDO('mysql:host=localhost;dbname=Schedule;charset=utf8mb4', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $pdo->query("
        SELECT d.discipline_id, d.discipline_name, dh.lecture_hours, dh.practice_hours, dh.lab_hours, dh.assessment_type 
        FROM disciplines d 
        LEFT JOIN discipline_hours dh ON d.discipline_id = dh.discipline_id 
        ORDER BY d.discipline_name
    ");
    $disciplines = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($disciplines);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Connection failed']);
}
?>
