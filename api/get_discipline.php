<?php
error_reporting(0);
header('Content-Type: application/json');

require_once __DIR__ . '/db.php';

$id = $_GET['id'] ?? 0;

$stmt = $pdo->prepare("
    SELECT d.*, dh.lecture_hours, dh.practice_hours, dh.lab_hours, dh.assessment_type 
    FROM disciplines d 
    LEFT JOIN discipline_hours dh ON d.discipline_id = dh.discipline_id 
    WHERE d.discipline_id = :id
");
$stmt->execute(['id' => $id]);
$disc = $stmt->fetch(PDO::FETCH_ASSOC);

echo json_encode($disc);
?>
