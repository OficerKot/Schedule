<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

require_once __DIR__ . '/db.php';

$card_id = $_GET['id'] ?? null;

if (!$card_id) {
    echo json_encode(['error' => 'ID не указан']);
    exit;
}

$stmt = $pdo->prepare("
    SELECT 
        lc.card_id,
        lc.semester_date,
        lc.week_type,
        lc.group_id,
        lc.teacher_id,
        lc.room_id,
        lc.discipline_id,
        lc.lesson_type_id,
        lc.period_id
    FROM lesson_card lc
    WHERE lc.card_id = :id
");

$stmt->execute(['id' => $card_id]);
$lesson = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$lesson) {
    echo json_encode(['error' => 'Занятие не найдено']);
    exit;
}

echo json_encode($lesson);
?>
