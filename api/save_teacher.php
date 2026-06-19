<?php
session_start();
error_reporting(0);
header('Content-Type: application/json');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Нет прав доступа']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$teacher_id = $input['teacher_id'] ?? 0;

try {
    $pdo = new PDO('mysql:host=localhost;dbname=Schedule;charset=utf8mb4', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    if ($teacher_id) {
        // Обновление
        $stmt = $pdo->prepare("
            UPDATE teachers 
            SET last_name = :last_name, first_name = :first_name, middle_name = :middle_name,
                position = :position, chair = :chair, degree = :degree, title = :title
            WHERE teacher_id = :id
        ");
        $stmt->execute([
            'id' => $teacher_id,
            'last_name' => $input['last_name'],
            'first_name' => $input['first_name'],
            'middle_name' => $input['middle_name'],
            'position' => $input['position'],
            'chair' => $input['chair'],
            'degree' => $input['degree'],
            'title' => $input['title']
        ]);
        echo json_encode(['success' => true, 'message' => 'Преподаватель обновлён']);
    } else {
        // Создание
        $stmt = $pdo->prepare("
            INSERT INTO teachers (last_name, first_name, middle_name, position, chair, degree, title)
            VALUES (:last_name, :first_name, :middle_name, :position, :chair, :degree, :title)
        ");
        $stmt->execute([
            'last_name' => $input['last_name'],
            'first_name' => $input['first_name'],
            'middle_name' => $input['middle_name'],
            'position' => $input['position'],
            'chair' => $input['chair'],
            'degree' => $input['degree'],
            'title' => $input['title']
        ]);
        echo json_encode(['success' => true, 'message' => 'Преподаватель добавлен']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Ошибка БД']);
}
?>
