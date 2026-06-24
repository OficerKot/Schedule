<?php
error_reporting(0);
header('Content-Type: application/json');

require_once __DIR__ . '/db.php';

$id = $_GET['id'] ?? 0;

$stmt = $pdo->prepare("SELECT * FROM rooms WHERE room_id = :id");
$stmt->execute(['id' => $id]);
$room = $stmt->fetch(PDO::FETCH_ASSOC);

echo json_encode($room);
?>
