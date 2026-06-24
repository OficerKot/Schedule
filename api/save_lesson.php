<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

try {
    $pdo = new PDO('mysql:host=localhost;dbname=Schedule;charset=utf8mb4', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input) {
        echo json_encode(['success' => false, 'message' => 'Нет данных']);
        exit;
    }
    
    $card_id = $input['card_id'] ?? null;
    $date = $input['date'] ?? null;
    $group_id = $input['group_id'] ?? null;
    $teacher_id = $input['teacher_id'] ?? null;
    $room_id = $input['room_id'] ?? null;
    $discipline_id = $input['discipline_id'] ?? null;
    $lesson_type_id = $input['lesson_type_id'] ?? null;
    $period_id = $input['period_id'] ?? null;
    $week_type = $input['week_type'] ?? 'all';
    
    if (!$date || !$group_id || !$teacher_id || !$room_id) {
        echo json_encode(['success' => false, 'message' => 'Заполните все обязательные поля']);
        exit;
    }
    
    if ($card_id) {
        // Обновление
        $stmt = $pdo->prepare("
            UPDATE lesson_card 
            SET semester_date = :date, 
                group_id = :group_id, 
                teacher_id = :teacher_id, 
                room_id = :room_id, 
                discipline_id = :discipline_id, 
                lesson_type_id = :lesson_type_id, 
                period_id = :period_id,
                week_type = :week_type
            WHERE card_id = :id
        ");
        
        $stmt->execute([
            'id' => $card_id,
            'date' => $date,
            'group_id' => $group_id,
            'teacher_id' => $teacher_id,
            'room_id' => $room_id,
            'discipline_id' => $discipline_id,
            'lesson_type_id' => $lesson_type_id,
            'period_id' => $period_id,
            'week_type' => $week_type
        ]);
        
        echo json_encode(['success' => true, 'message' => 'Занятие обновлено']);
    } else {
        // Добавление
        $stmt = $pdo->prepare("
            INSERT INTO lesson_card 
            (semester_date, group_id, teacher_id, room_id, discipline_id, lesson_type_id, period_id, week_type) 
            VALUES (:date, :group_id, :teacher_id, :room_id, :discipline_id, :lesson_type_id, :period_id, :week_type)
        ");
        
        $stmt->execute([
            'date' => $date,
            'group_id' => $group_id,
            'teacher_id' => $teacher_id,
            'room_id' => $room_id,
            'discipline_id' => $discipline_id,
            'lesson_type_id' => $lesson_type_id,
            'period_id' => $period_id,
            'week_type' => $week_type
        ]);
        
        echo json_encode(['success' => true, 'message' => 'Занятие добавлено']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Ошибка БД: ' . $e->getMessage()]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Ошибка сервера: ' . $e->getMessage()]);
}
?>
