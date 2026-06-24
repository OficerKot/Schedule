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
$group_id = $input['group_id'] ?? 0;

if ($group_id) {
    $stmt = $pdo->prepare("UPDATE `groups` SET `name` = :name, `students_count` = :students_count WHERE `group_id` = :id");
    $stmt->execute([
        'id' => $group_id,
        'name' => $input['name'],
        'students_count' => $input['students_count']
    ]);
    echo json_encode(['success' => true, 'message' => 'Группа обновлена']);
} else {
    $stmt = $pdo->prepare("INSERT INTO `groups` (`name`, `students_count`) VALUES (:name, :students_count)");
    $stmt->execute([
        'name' => $input['name'],
        'students_count' => $input['students_count']
    ]);
    echo json_encode(['success' => true, 'message' => 'Группа добавлена']);
}
?>
