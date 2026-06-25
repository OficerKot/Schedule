<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Подключение к БД
$host = '127.0.0.1';
$port = '3306';
$db   = 'Schedule';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO(
        "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4",
        $user,
        $pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Connection failed: " . $e->getMessage()]);
    exit;
}

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'teachers':
        echo json_encode(getTeachers($pdo));
        break;

    case 'groups':
        echo json_encode(getGroups($pdo));
        break;

    case 'classrooms':
        echo json_encode(getClassrooms($pdo));
        break;

    case 'schedule':
        echo json_encode(getSchedule($pdo, $_GET));
        break;

    default:
        http_response_code(400);
        echo json_encode(["error" => "Unknown action"]);
        break;
}

/**
 * Получить всех преподавателей для фильтра
 */
function getTeachers(PDO $pdo): array {
    $stmt = $pdo->query("
        SELECT teacher_id, first_name, middle_name, last_name,
               school, department, chair, degree, title, position, type
        FROM teachers
        ORDER BY last_name
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Получить все учебные группы для фильтра
 */
function getGroups(PDO $pdo): array {
    $stmt = $pdo->query("
        SELECT group_id, name, students_count
        FROM `groups`
        ORDER BY name
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Получить все аудитории для фильтра
 */
function getClassrooms(PDO $pdo): array {
    $stmt = $pdo->query("
        SELECT room_id, building, room_number, room_type, seats
        FROM rooms
        ORDER BY building, room_number
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Получить расписание на неделю с учётом фильтров и week_type
 */
function getSchedule(PDO $pdo, array $params): array {
    // Получаем понедельник недели
    $mondayStr = $params['monday'] ?? date('Y-m-d');
    $monday = new DateTime($mondayStr);

    // Фильтры
    $teacherId = $params['teacher'] ?? null;
    $groupId   = $params['group'] ?? null;
    $classroomKey = $params['classroom'] ?? null;

    // Определяем чётность текущей недели
    $mondayTimestamp = $monday->getTimestamp();
    $jan1 = new DateTime(date('Y-01-01'));
    $weekNumber = (int)(($mondayTimestamp - $jan1->getTimestamp()) / 604800);
    $isEven = ($weekNumber % 2 === 0);

    // Строим WHERE
    $where = ["lc.semester_date >= ? AND lc.semester_date < ?"];
    $values = [
        $monday->format('Y-m-d'),
        (clone $monday)->modify('+7 days')->format('Y-m-d')
    ];

    if ($teacherId) {
        $where[] = "lc.teacher_id = ?";
        $values[] = (int)$teacherId;
    }
    if ($groupId) {
        $where[] = "lc.group_id = ?";
        $values[] = (int)$groupId;
    }
    if ($classroomKey) {
        // Разбираем "Д303" → building="Д", room_number="303"
        $parts = preg_split('/(\d+)/', $classroomKey, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
        if (!empty($parts)) {
            $bld = $parts[0];
            $num = isset($parts[1]) ? $parts[1] : '';
            if ($bld && $num) {
                $where[] = "r.building = ? AND r.room_number = ?";
                $values[] = $bld;
                $values[] = $num;
            }
        }
    }

    // Фильтруем по чётности недели
    $where[] = "(lc.week_type = 'all' OR (lc.week_type = 'even' AND ?) OR (lc.week_type = 'odd' AND ?))";
    $values[] = $isEven ? '1' : '0';
    $values[] = $isEven ? '0' : '1';

    $sql = "
        SELECT
            lc.card_id,
            lc.semester_date,
            lc.period_id,
            lc.week_type,
            d.discipline_name,
            lt.name AS lesson_type_name,
            t.first_name,
            t.middle_name,
            t.last_name,
            r.building,
            r.room_number,
            r.room_type,
            r.seats,
            g.name AS group_name,
            g.students_count,
            tp.period_number,
            tp.time_range
        FROM lesson_card lc
        JOIN disciplines d ON d.discipline_id = lc.discipline_id
        JOIN lesson_types lt ON lt.lesson_type_id = lc.lesson_type_id
        JOIN teachers t ON t.teacher_id = lc.teacher_id
        JOIN rooms r ON r.room_id = lc.room_id
        JOIN `groups` g ON g.group_id = lc.group_id
        JOIN time_periods tp ON tp.period_id = lc.period_id
        WHERE " . implode(" AND ", $where) . "
        ORDER BY semester_date, tp.period_number, g.name
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($values);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Группируем по дате
    $days = [];
    foreach ($rows as $row) {
        $dateKey = $row['semester_date'];
        if (!isset($days[$dateKey])) {
            $days[$dateKey] = [
                'date' => $dateKey,
                'lessons' => []
            ];
        }

        $days[$dateKey]['lessons'][] = [
            'lesson_name' => $row['discipline_name'],
            'lesson_number' => (int)$row['period_number'],
            'lesson_type' => $row['lesson_type_name'],
            'teacher' => [
                'id' => (int)$row['card_id'], // card_id как уникальный ID занятия
                'first_name' => $row['first_name'],
                'middle_name' => $row['middle_name'],
                'last_name' => $row['last_name'],
            ],
            'classroom' => [
                'building' => $row['building'],
                'classroom_number' => $row['room_number'],
                'seats' => (int)$row['seats'],
                'type' => $row['room_type'],
            ],
            'group' => [
                'name' => $row['group_name'],
                'students_count' => (int)$row['students_count'],
            ],
            'time_range' => $row['time_range'],
        ];
    }

    return array_values($days);
}
