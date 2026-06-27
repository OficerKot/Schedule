<?php
// api/get_report_data.php

// Запрещаем вывод ошибок в ответ (чтобы не ломать JSON)
ini_set('display_errors', 0);
error_reporting(E_ALL);

header('Content-Type: application/json');

require_once __DIR__ . '/db.php';

try {
    $reportType = $_GET['type'] ?? '';
    $groupId = (int)($_GET['group_id'] ?? 0);
    $weekNumber = (int)($_GET['week'] ?? 1);
    $semesterId = (int)($_GET['semester_id'] ?? 0);

    $response = ['success' => false, 'data' => []];

switch ($reportType) {
    case 'group_schedule':
        $sql = "SELECT 
                    DATE_FORMAT(lc.semester_date, '%a') as day,
                    MAX(tp.period_number) as period,
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
                GROUP BY lc.card_id
                ORDER BY lc.semester_date, period
                LIMIT 100";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$groupId]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($data)) {
            $data = [
                [
                    'day' => 'ПН',
                    'period' => '1',
                    'discipline_name' => 'Нет данных',
                    'lesson_type' => '-',
                    'teacher' => '-',
                    'room_number' => '-',
                    'room_type' => '-'
                ]
            ];
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

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$groupId]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $response = ['success' => true, 'data' => $data];
        break;

    case 'matrix':
        $groupIds = isset($_GET['group_ids']) ? explode(',', $_GET['group_ids']) : [];
        if (empty($groupIds)) {
            $response = ['success' => false, 'message' => 'Не выбраны группы'];
            break;
        }
        $groupIds = array_map('intval', $groupIds);
        $placeholders = implode(',', array_fill(0, count($groupIds), '?'));

        // Вычисляем даты для выбранной недели
        $baseDate = new DateTime('now');
        $monday = clone $baseDate;
        $monday->modify('monday this week');
        $monday->modify('+' . ($weekNumber - 1) . ' weeks');
        $sunday = clone $monday;
        $sunday->modify('+6 days');

        $sql = "SELECT 
                    g.name as group_name,
                    DATE_FORMAT(lc.semester_date, '%a') as day,
                    MAX(tp.period_number) as period,
                    d.discipline_name
                FROM lesson_card lc
                JOIN `groups` g ON lc.group_id = g.group_id
                JOIN disciplines d ON lc.discipline_id = d.discipline_id
                JOIN time_periods tp ON lc.period_id = tp.period_id
                WHERE lc.group_id IN ($placeholders)
                  AND lc.semester_date BETWEEN ? AND ?
                GROUP BY lc.card_id
                ORDER BY g.group_id, lc.semester_date, period";

        $params = array_merge($groupIds, [$monday->format('Y-m-d'), $sunday->format('Y-m-d')]);
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $matrix = [];
        $days = ['ПН','ВТ','СР','ЧТ','ПТ','СБ'];
        $periods = [1,2,3,4,5,6,7,8];
        foreach ($rows as $row) {
            $group = $row['group_name'];
            $day = $row['day'];
            $period = $row['period'];
            $discipline = $row['discipline_name'];
            if (!isset($matrix[$group])) {
                $matrix[$group] = [];
            }
            $key = $day . '_' . $period;
            $matrix[$group][$key] = $discipline;
        }

        $result = [
            'groups' => array_keys($matrix),
            'days' => $days,
            'periods' => $periods,
            'matrix' => $matrix
        ];
        $response = ['success' => true, 'data' => $result];
        break;

    case 'workload':
        $subdivisionId = (int)($_GET['subdivision_id'] ?? 0);
        if (!$subdivisionId) {
            $response = ['success' => false, 'message' => 'Не выбрано подразделение'];
            break;
        }

        $stmt = $pdo->prepare("SELECT name FROM departments WHERE department_id = ? AND level = 'chair'");
        $stmt->execute([$subdivisionId]);
        $chairName = $stmt->fetchColumn();

        if (!$chairName) {
            $response = ['success' => false, 'message' => 'Кафедра с таким ID не найдена'];
            break;
        }

        $sql = "SELECT 
                    t.teacher_id,
                    CONCAT(t.last_name, ' ', t.first_name, ' ', t.middle_name) as teacher_name,
                    COUNT(lc.card_id) as total_lessons,
                    SUM(CASE WHEN lt.name = 'Лекция' THEN 1 ELSE 0 END) as lectures,
                    SUM(CASE WHEN lt.name = 'Практика' THEN 1 ELSE 0 END) as practices,
                    SUM(CASE WHEN lt.name = 'Лабораторная работа' THEN 1 ELSE 0 END) as labs
                FROM teachers t
                LEFT JOIN lesson_card lc ON t.teacher_id = lc.teacher_id
                LEFT JOIN lesson_types lt ON lc.lesson_type_id = lt.lesson_type_id
                WHERE t.chair = :chair
                GROUP BY t.teacher_id
                ORDER BY teacher_name";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['chair' => $chairName]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $data = [];
        foreach ($rows as $row) {
            $data[] = [
                'teacher' => $row['teacher_name'],
                'totalHours' => ($row['total_lessons'] ?? 0) * 2,
                'lectureHours' => ($row['lectures'] ?? 0) * 2,
                'practiceHours' => ($row['practices'] ?? 0) * 2,
                'labHours' => ($row['labs'] ?? 0) * 2,
            ];
        }
        $response = ['success' => true, 'data' => $data];
        break;

    case 'classrooms':
        $building = $_GET['building'] ?? '';
        $sqlTotal = "SELECT COUNT(*) as total FROM rooms";
        $params = [];
        if ($building) {
            $sqlTotal .= " WHERE building = ?";
            $params[] = $building;
        }
        $stmt = $pdo->prepare($sqlTotal);
        $stmt->execute($params);
        $totalRooms = $stmt->fetchColumn();

        $sqlUsed = "SELECT COUNT(DISTINCT lc.room_id) as used FROM lesson_card lc";
        if ($building) {
            $sqlUsed .= " JOIN rooms r ON lc.room_id = r.room_id WHERE r.building = ?";
            $stmt = $pdo->prepare($sqlUsed);
            $stmt->execute([$building]);
        } else {
            $stmt = $pdo->query($sqlUsed);
        }
        $usedRooms = $stmt->fetchColumn();

        $byType = [];
        $types = ['lecture', 'practical', 'lab', 'computer'];
        foreach ($types as $type) {
            $sqlType = "SELECT COUNT(DISTINCT lc.room_id) as cnt FROM lesson_card lc 
                        JOIN rooms r ON lc.room_id = r.room_id 
                        WHERE r.room_type = ?";
            $params = [$type];
            if ($building) {
                $sqlType .= " AND r.building = ?";
                $params[] = $building;
            }
            $stmt = $pdo->prepare($sqlType);
            $stmt->execute($params);
            $used = $stmt->fetchColumn();

            $sqlTotalType = "SELECT COUNT(*) FROM rooms WHERE room_type = ?";
            $paramsTotal = [$type];
            if ($building) {
                $sqlTotalType .= " AND building = ?";
                $paramsTotal[] = $building;
            }
            $stmtTotal = $pdo->prepare($sqlTotalType);
            $stmtTotal->execute($paramsTotal);
            $totalType = $stmtTotal->fetchColumn();
            $byType[$type] = $totalType ? round(($used / $totalType) * 100) : 0;
        }

        $byDay = [];
        $days = ['ПН','ВТ','СР','ЧТ','ПТ','СБ'];
        foreach ($days as $index => $dayName) {
            // Для демо используем случайное распределение или просто считаем занятость по карточкам
            $sqlDay = "SELECT COUNT(DISTINCT lc.room_id) FROM lesson_card lc 
                       JOIN rooms r ON lc.room_id = r.room_id";
            $params = [];
            if ($building) {
                $sqlDay .= " WHERE r.building = ?";
                $params[] = $building;
            }
            $stmt = $pdo->prepare($sqlDay);
            $stmt->execute($params);
            $byDay[$dayName] = (int)$stmt->fetchColumn();
        }

        $byPeriod = [];
        $periods = [1,2,3,4,5,6,7,8];
        foreach ($periods as $p) {
            $sqlPeriod = "SELECT COUNT(DISTINCT lc.room_id) FROM lesson_card lc 
                          JOIN rooms r ON lc.room_id = r.room_id 
                          WHERE lc.period_id = ?";
            $params = [$p];
            if ($building) {
                $sqlPeriod .= " AND r.building = ?";
                $params[] = $building;
            }
            $stmt = $pdo->prepare($sqlPeriod);
            $stmt->execute($params);
            $byPeriod[$p] = (int)$stmt->fetchColumn();
        }

        $response = [
            'success' => true,
            'data' => [
                'totalRooms' => (int)$totalRooms,
                'usedRooms' => (int)$usedRooms,
                'usagePercent' => $totalRooms ? round(($usedRooms / $totalRooms) * 100, 1) : 0,
                'byType' => $byType,
                'byDay' => $byDay,
                'byPeriod' => $byPeriod,
            ]
        ];
        break;

    default:
        $response = ['success' => false, 'message' => 'Неизвестный тип отчета'];
        break;
}

echo json_encode($response, JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Ошибка сервера: ' . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}