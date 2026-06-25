<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

require_once __DIR__ . '/db.php';

$stmt = $pdo->query("
    SELECT 
        lc.card_id,
        lc.semester_date,
        lc.week_type,
        g.name as group_name,
        d.discipline_name,
        lt.name as lesson_type,
        CONCAT(t.last_name, ' ', t.first_name) as teacher_name,
        r.building,
        r.room_number,
        tp.period_number
    FROM lesson_card lc
    JOIN `groups` g ON lc.group_id = g.group_id
    JOIN disciplines d ON lc.discipline_id = d.discipline_id
    JOIN lesson_types lt ON lc.lesson_type_id = lt.lesson_type_id
    JOIN teachers t ON lc.teacher_id = t.teacher_id
    JOIN rooms r ON lc.room_id = r.room_id
    JOIN time_periods tp ON lc.period_id = tp.period_id
    ORDER BY lc.semester_date, tp.period_number
");

$lessons = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($lessons);
?>
