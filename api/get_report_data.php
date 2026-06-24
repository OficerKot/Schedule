<?php
// api/get_report_data.php

header('Content-Type: application/json');

// Подключение к БД
require_once '../includes/db_connection.php';

$reportType = $_GET['type'] ?? '';
$groupId = $_GET['group_id'] ?? 0;
$weekNumber = $_GET['week'] ?? 1;
$semesterId = $_GET['semester_id'] ?? 0;
$subdivisionId = $_GET['subdivision_id'] ?? 0;

$response = ['success' => false, 'data' => []];

// Определяем тип отчета
$reportType = $_GET['type'] ?? '';

switch ($reportType) {
    case 'group_schedule':
        $sql = "SELECT 
                    DATE_FORMAT(lc.semester_date, '%a') as day,
                    tp.period_number as period,
                    d.discipline_name,
                    lt.name as lesson_type,
                    CONCAT(t.last_name, ' ', t.first_name) as teacher,
                    r.room_number,
                    r.room_type
                FROM lesson_card lc
                JOIN disciplines d ON lc.discipline_id = d.discipline_id
                JOIN lesson_types lt ON lc.lesson_type_id = lt.lesson_type_id
                JOIN teachers t ON lc.teacher_id = t.teacher_id
                JOIN rooms r ON lc.room_id = r.room_id
                JOIN time_periods tp ON lc.period_id = tp.period_id
                WHERE lc.group_id = ?
                LIMIT 100";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $groupId);
        $stmt->execute();
        $result = $stmt->get_result();

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        $response = ['success' => true, 'data' => $data];
        break;

    case 'exams':
        $sql = "SELECT 
                    DATE_FORMAT(lc.semester_date, '%d.%m.%Y') as date,
                    DATE_FORMAT(lc.semester_date, '%a') as day,
                    d.discipline_name,
                    lt.name as type,
                    CONCAT(t.last_name, ' ', t.first_name) as teacher,
                    r.room_number
                FROM lesson_card lc
                JOIN disciplines d ON lc.discipline_id = d.discipline_id
                JOIN lesson_types lt ON lc.lesson_type_id = lt.lesson_type_id
                JOIN teachers t ON lc.teacher_id = t.teacher_id
                JOIN rooms r ON lc.room_id = r.room_id
                WHERE lc.group_id = ? 
                  AND lt.name IN ('Зачёт', 'Экзамен')
                ORDER BY lc.semester_date";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $groupId);
        $stmt->execute();
        $result = $stmt->get_result();

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        $response = ['success' => true, 'data' => $data];
        break;

    case 'matrix':
        // Заглушка для шахматки (сложный запрос)
        $response = ['success' => true, 'data' => ['message' => 'Шахматка в разработке']];
        break;

    case 'workload':
        // Заглушка для нагрузки
        $response = ['success' => true, 'data' => ['message' => 'Нагрузка в разработке']];
        break;

    case 'classrooms':
        // Заглушка для занятости помещений
        $response = ['success' => true, 'data' => ['message' => 'Занятость помещений в разработке']];
        break;

    default:
        $response = ['success' => false, 'message' => 'Неизвестный тип отчета'];
}

echo json_encode($response, JSON_UNESCAPED_UNICODE);