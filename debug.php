<?php
header('Content-Type: text/html; charset=utf-8');

$host = '127.0.0.1';
$port = '3306';
$db = 'Schedule';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO(
        "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4",
        $user, $pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    echo "<h2 style='color:green'>✓ Подключение к БД OK</h2>";
} catch (PDOException $e) {
    echo "<h2 style='color:red'>✗ Connection failed: " . htmlspecialchars($e->getMessage()) . "</h2>";
    exit;
}

// Счётчики
$tables = ['teachers', 'groups', 'rooms', 'lesson_card', 'lesson_types', 'time_periods', 'disciplines'];
echo "<h3>Счётчики:</h3><table border='1'><tr><th>Таблица</th><th>Записей</th></tr>";
foreach ($tables as $t) {
    $c = $pdo->query("SELECT COUNT(*) FROM `$t`")->fetchColumn();
    echo "<tr><td>$t</td><td>$c</td></tr>";
}
echo "</table>";

// Тест запроса расписания
echo "<h3>Тест запроса расписания (2026-09-01 → 2026-09-08):</h3>";
$monday = '2026-09-01';
$sql = "
    SELECT lc.semester_date, d.discipline_name, lt.name as type_name, t.last_name,
           r.building, r.room_number, g.name as group_name, lc.week_type, tp.period_number
    FROM lesson_card lc
    JOIN disciplines d ON d.discipline_id = lc.discipline_id
    JOIN lesson_types lt ON lt.lesson_type_id = lc.lesson_type_id
    JOIN teachers t ON t.teacher_id = lc.teacher_id
    JOIN rooms r ON r.room_id = lc.room_id
    JOIN `groups` g ON g.group_id = lc.group_id
    JOIN time_periods tp ON tp.period_id = lc.period_id
    WHERE lc.semester_date >= ? AND lc.semester_date < ?
    ORDER BY lc.semester_date, tp.period_number
";
$stmt = $pdo->prepare($sql);
$stmt->execute([$monday, '2026-09-08']);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo "<p>Строк: " . count($rows) . "</p>";

if (count($rows) > 0) {
    echo "<table border='1' cellpadding='4'><tr>
        <th>Дата</th><th>Пара</th><th>Дисциплина</th><th>Тип</th>
        <th>Преподаватель</th><th>Аудитория</th><th>Группа</th><th>Неделя</th>
    </tr>";
    foreach ($rows as $row) {
        echo "<tr>";
        echo "<td>{$row['semester_date']}</td>";
        echo "<td>{$row['period_number']}</td>";
        echo "<td>" . htmlspecialchars($row['discipline_name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['type_name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['last_name']) . "</td>";
        echo "<td>{$row['building']}-{$row['room_number']}</td>";
        echo "<td>" . htmlspecialchars($row['group_name']) . "</td>";
        echo "<td>{$row['week_type']}</td>";
        echo "</tr>";
    }
    echo "</table>";
}

// Тест реального запроса из api.php (с фильтрами)
echo "<h3>Тест реального запроса (с week_type фильтром):</h3>";
$isEven = true;
$where = ["lc.semester_date >= ? AND lc.semester_date < ?"];
$values = [$monday, '2026-09-08'];
$where[] = "(lc.week_type = 'all' OR (lc.week_type = 'even' AND ?) OR (lc.week_type = 'odd' AND ?))";
$values[] = $isEven ? '1' : '0';
$values[] = $isEven ? '0' : '1';

$sql2 = "
    SELECT lc.semester_date, d.discipline_name, lt.name, t.last_name,
           r.building, r.room_number, g.name, lc.week_type, tp.period_number
    FROM lesson_card lc
    JOIN disciplines d ON d.discipline_id = lc.discipline_id
    JOIN lesson_types lt ON lt.lesson_type_id = lc.lesson_type_id
    JOIN teachers t ON t.teacher_id = lc.teacher_id
    JOIN rooms r ON r.room_id = lc.room_id
    JOIN groups g ON g.group_id = lc.group_id
    JOIN time_periods tp ON tp.period_id = lc.period_id
    WHERE " . implode(" AND ", $where) . "
    ORDER BY lc.semester_date, tp.period_number
";
echo "<p>SQL: " . htmlspecialchars($sql2) . "</p>";
echo "<p>Values: " . json_encode($values) . "</p>";

$stmt2 = $pdo->prepare($sql2);
try {
    $stmt2->execute($values);
    $rows2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
    echo "<p style='color:" . (count($rows2) > 0 ? 'green' : 'red') . "'>Строк: " . count($rows2) . "</p>";
} catch (PDOException $e) {
    echo "<p style='color:red'>ERROR: " . htmlspecialchars($e->getMessage()) . "</p>";
}

// Проверка week_type распределения
echo "<h3>Распределение week_type:</h3>";
$wc = $pdo->query("SELECT week_type, COUNT(*) as cnt FROM lesson_card GROUP BY week_type")->fetchAll(PDO::FETCH_ASSOC);
echo "<table border='1'><tr><th>week_type</th><th>Count</th></tr>";
foreach ($wc as $w) {
    echo "<tr><td>{$w['week_type']}</td><td>{$w['cnt']}</td></tr>";
}
echo "</table>";

// Проверка текущего дня и чётности
echo "<h3>Текущее состояние:</h3>";
echo "<p>Сегодня: " . date('Y-m-d') . "</p>";
$today = new DateTime();
$jan1 = new DateTime(date('Y-01-01'));
$weekNum = (int)(($today->getTimestamp() - $jan1->getTimestamp()) / 604800);
echo "<p>Номер недели от января: $weekNum</p>";
echo "<p>Чётность: " . ($weekNum % 2 === 0 ? 'чётная' : 'нечётная') . "</p>";

?>
