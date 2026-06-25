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
$discipline_id = $input['discipline_id'] ?? 0;

if (!$discipline_id) {
    echo json_encode(['success' => false, 'message' => 'ID не указан']);
    exit;
}

// Удаляем часы дисциплины
$stmt = $pdo->prepare("DELETE FROM discipline_hours WHERE discipline_id = :id");
$stmt->execute(['id' => $discipline_id]);

// Удаляем дисциплину
$stmt = $pdo->prepare("DELETE FROM disciplines WHERE discipline_id = :id");
$stmt->execute(['id' => $discipline_id]);

echo json_encode(['success' => true, 'message' => 'Дисциплина удалена']);
?>
