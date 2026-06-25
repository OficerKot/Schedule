<?php
error_reporting(0);
header('Content-Type: application/json');

require_once __DIR__ . '/db.php';
    
$stmt = $pdo->query("SELECT teacher_id, first_name, middle_name, last_name, position, chair, degree, title FROM teachers ORDER BY last_name");
$teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($teachers);
?>
