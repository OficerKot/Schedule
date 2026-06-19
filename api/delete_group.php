<?php
session_start();
error_reporting(0);
header('Content-Type: application/json');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Нет прав доступа']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$group_id = $input['group_id'] ?? 0;

if (!$group_id) {
    echo json_encode(['success' => false, 'message' => 'ID не указан']);
    exit;
}

try {
    $pdo = new PDO('mysql:host=localhost;dbname=Schedule;charset=utf8mb4', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $pdo->prepare("DELETE FROM `groups` WHERE `group_id` = :id");
    $stmt->execute(['id' => $group_id]);
    
    echo json_encode(['success' => true, 'message' => 'Группа удалена']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Ошибка БД']);
}
?>
