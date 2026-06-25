<?php
error_reporting(0);
header('Content-Type: application/json');

require_once __DIR__ . '/db.php';

$stmt = $pdo->query("SELECT room_id, building, room_number, seats, room_type FROM rooms ORDER BY building, room_number");
$rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($rooms);
?>
