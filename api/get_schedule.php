<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/db.php';

// Получаем параметры
$mondayStr = $_GET['monday'] ?? date('Y-m-d');
$teacherId = $_GET['teacher'] ?? null;
$groupId   = $_GET['group'] ?? null;
$classroom = $_GET['classroom'] ?? null;

// Определяем чётность недели
$monday = new DateTime($mondayStr);
$mondayTimestamp = $monday->getTimestamp();
$jan1 = new DateTime(date('Y-01-01'));
$weekNumber = (int)(($mondayTimestamp - $jan1->getTimestamp()) / 604800);
$isEven = ($weekNumber % 2 === 0);

// Строим запрос с фильтрами
$sql = "
    SELECT 
        lc.card_id,
        lc.semester_date,
        lc.week_type,
        g.name as group_name,
        g.students_count,
        d.discipline_name,
        lt.name as lesson_type,
        t.teacher_id,
        CONCAT(t.last_name, ' ', t.first_name, ' ', t.middle_name) as teacher_name,
        r.building,
        r.room_number,
        r.room_type,
        r.seats,
        tp.period_number
    FROM lesson_card lc
    JOIN `groups` g ON lc.group_id = g.group_id
    JOIN disciplines d ON lc.discipline_id = d.discipline_id
    JOIN lesson_types lt ON lc.lesson_type_id = lt.lesson_type_id
    JOIN teachers t ON lc.teacher_id = t.teacher_id
    JOIN rooms r ON lc.room_id = r.room_id
    JOIN time_periods tp ON lc.period_id = tp.period_id
    WHERE lc.semester_date >= ? AND lc.semester_date < ?
    AND (lc.week_type = 'all' OR (lc.week_type = 'even' AND ?) OR (lc.week_type = 'odd' AND ?))
";

$params = [
    $monday->format('Y-m-d'),
    (clone $monday)->modify('+7 days')->format('Y-m-d'),
    $isEven ? 1 : 0,
    $isEven ? 0 : 1
];

if ($teacherId) {
    $sql .= " AND lc.teacher_id = ?";
    $params[] = (int)$teacherId;
}
if ($groupId) {
    $sql .= " AND lc.group_id = ?";
    $params[] = (int)$groupId;
}
if ($classroom) {
    $parts = preg_split('/(\d+)/', $classroom, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
    if (!empty($parts)) {
        $bld = $parts[0];
        $num = isset($parts[1]) ? $parts[1] : '';
        if ($bld && $num) {
            $sql .= " AND r.building = ? AND r.room_number = ?";
            $params[] = $bld;
            $params[] = $num;
        }
    }
}

$sql .= " ORDER BY lc.semester_date, tp.period_number, g.name";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

$lessons = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($lessons);
?>
