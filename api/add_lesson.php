<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
error_reporting(0);
header('Content-Type: application/json');

require_once __DIR__ . '/db.php';
    
// Проверка прав администратора
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Нет прав доступа']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Метод не разрешен']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$date = $input['date'] ?? '';
if ($date) {
    $dayOfWeek = date('N', strtotime($date)); // 1 = понедельник, 7 = воскресенье
    if ($dayOfWeek == 7) {
        echo json_encode(['success' => false, 'message' => 'Нельзя добавлять пары в воскресенье']);
        exit;
    }
}
$group_id = $input['group_id'] ?? 0;
$teacher_id = $input['teacher_id'] ?? 0;
$room_id = $input['room_id'] ?? 0;
$discipline_id = $input['discipline_id'] ?? 0;
$lesson_type_id = $input['lesson_type_id'] ?? 0;
$period_id = $input['period_id'] ?? 0;
$week_type = $input['week_type'] ?? 'all';

if (empty($date) || !$group_id || !$teacher_id || !$room_id || !$discipline_id || !$lesson_type_id || !$period_id) {
    echo json_encode(['success' => false, 'message' => 'Заполните все поля']);
    exit;
}

try {
    // Проверка конфликтов
    $stmt = $pdo->prepare("
        SELECT lc.card_id 
        FROM lesson_card lc
        WHERE lc.semester_date = :date 
        AND lc.period_id = :period_id
        AND (
            lc.group_id = :group_id 
            OR lc.teacher_id = :teacher_id 
            OR lc.room_id = :room_id
        )
    ");
    $stmt->execute([
        'date' => $date,
        'period_id' => $period_id,
        'group_id' => $group_id,
        'teacher_id' => $teacher_id,
        'room_id' => $room_id
    ]);
    
    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Обнаружен конфликт! Проверьте расписание.']);
        exit;
    }
    
    // Добавление занятия
    $stmt = $pdo->prepare("
        INSERT INTO lesson_card (semester_date, week_type, discipline_id, lesson_type_id, group_id, teacher_id, room_id, period_id)
        VALUES (:date, :week_type, :discipline_id, :lesson_type_id, :group_id, :teacher_id, :room_id, :period_id)
    ");
    $stmt->execute([
        'date' => $date,
        'week_type' => $week_type,
        'discipline_id' => $discipline_id,
        'lesson_type_id' => $lesson_type_id,
        'group_id' => $group_id,
        'teacher_id' => $teacher_id,
        'room_id' => $room_id,
        'period_id' => $period_id
    ]);
    
    echo json_encode(['success' => true, 'message' => 'Занятие добавлено']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Ошибка БД: ' . $e->getMessage()]);
}
?>
