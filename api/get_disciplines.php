<?php
error_reporting(0);
header('Content-Type: application/json');

require_once __DIR__ . '/db.php';

$stmt = $pdo->query("
    SELECT d.discipline_id, d.discipline_name, dh.lecture_hours, dh.practice_hours, dh.lab_hours, dh.assessment_type 
    FROM disciplines d 
    LEFT JOIN discipline_hours dh ON d.discipline_id = dh.discipline_id 
    ORDER BY d.discipline_name
");
$disciplines = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($disciplines);
?>
