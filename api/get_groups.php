<?php
error_reporting(0);
header('Content-Type: application/json');

require_once __DIR__ . '/db.php';

$stmt = $pdo->query("SELECT `group_id`, `name`, `students_count` FROM `groups` ORDER BY `name`");
$groups = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($groups);
?>
