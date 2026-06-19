<?php
session_start();
error_reporting(0);
header('Content-Type: application/json');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Нет прав доступа']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$discipline_id = $input['discipline_id'] ?? 0;

try {
    $pdo = new PDO('mysql:host=localhost;dbname=Schedule;charset=utf8mb4', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    if ($discipline_id) {
        // Обновляем дисциплину
        $stmt = $pdo->prepare("UPDATE disciplines SET discipline_name = :name WHERE discipline_id = :id");
        $stmt->execute(['id' => $discipline_id, 'name' => $input['discipline_name']]);
        
        // Обновляем часы
        $stmt = $pdo->prepare("
            INSERT INTO discipline_hours (discipline_id, lecture_hours, practice_hours, lab_hours, assessment_type)
            VALUES (:id, :lectures, :practice, :labs, :type)
            ON DUPLICATE KEY UPDATE 
                lecture_hours = :lectures2, practice_hours = :practice2, lab_hours = :labs2, assessment_type = :type2
        ");
        $stmt->execute([
            'id' => $discipline_id,
            'lectures' => $input['lecture_hours'],
            'practice' => $input['practice_hours'],
            'labs' => $input['lab_hours'],
            'type' => $input['assessment_type'],
            'lectures2' => $input['lecture_hours'],
            'practice2' => $input['practice_hours'],
            'labs2' => $input['lab_hours'],
            'type2' => $input['assessment_type']
        ]);
        
        echo json_encode(['success' => true, 'message' => 'Дисциплина обновлена']);
    } else {
        // Создание
        $stmt = $pdo->prepare("INSERT INTO disciplines (discipline_name) VALUES (:name)");
        $stmt->execute(['name' => $input['discipline_name']]);
        $discipline_id = $pdo->lastInsertId();
        
        $stmt = $pdo->prepare("
            INSERT INTO discipline_hours (discipline_id, lecture_hours, practice_hours, lab_hours, assessment_type)
            VALUES (:id, :lectures, :practice, :labs, :type)
        ");
        $stmt->execute([
            'id' => $discipline_id,
            'lectures' => $input['lecture_hours'],
            'practice' => $input['practice_hours'],
            'labs' => $input['lab_hours'],
            'type' => $input['assessment_type']
        ]);
        
        echo json_encode(['success' => true, 'message' => 'Дисциплина добавлена']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Ошибка БД']);
}
?>
