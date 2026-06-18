<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = '127.0.0.1';
$port = '3307';
$db = 'Schedule';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO(
        "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4",
        $user, $pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    $mondayStr = '2026-09-01';
    $monday = new DateTime($mondayStr);
    
    $where = ["lc.semester_date >= ? AND lc.semester_date < ?"];
    $values = [
        $monday->format('Y-m-d'),
        (clone $monday)->modify('+7 days')->format('Y-m-d')
    ];
    
    $isEven = true;
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
        ORDER BY lc.semester_date, tp.period_number, g.name
    ";
    
    echo "SQL: " . $sql . "\n\n";
    echo "Values: " . json_encode($values) . "\n\n";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($values);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Rows: " . count($rows) . "\n";
    foreach ($rows as $row) {
        echo json_encode($row) . "\n";
    }
} catch (PDOException $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "Code: " . $e->getCode() . "\n";
}
?>
