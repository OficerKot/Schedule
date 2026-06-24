<?php
session_start();
error_reporting(0);
header('Content-Type: application/json');

require_once __DIR__ . '/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Нет прав доступа']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$room_id = $input['room_id'] ?? 0;

if ($room_id) {
    $stmt = $pdo->prepare("UPDATE rooms SET building = :building, room_number = :room_number, room_type = :room_type, seats = :seats WHERE room_id = :id");
    $stmt->execute([
        'id' => $room_id,
        'building' => $input['building'],
        'room_number' => $input['room_number'],
        'room_type' => $input['room_type'],
        'seats' => $input['seats']
    ]);
    echo json_encode(['success' => true, 'message' => 'Аудитория обновлена']);
} else {
    $stmt = $pdo->prepare("INSERT INTO rooms (building, room_number, room_type, seats) VALUES (:building, :room_number, :room_type, :seats)");
    $stmt->execute([
        'building' => $input['building'],
        'room_number' => $input['room_number'],
        'room_type' => $input['room_type'],
        'seats' => $input['seats']
    ]);
    echo json_encode(['success' => true, 'message' => 'Аудитория добавлена']);
}
?>
