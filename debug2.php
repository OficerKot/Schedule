<?php
header('Content-Type: text/html; charset=utf-8');
$pdo = new PDO("mysql:host=127.0.0.1;port=3307;dbname=Schedule;charset=utf8mb4", 'root', '');
$rows = $pdo->query("SELECT semester_date, week_type, group_id, period_id, discipline_id FROM lesson_card ORDER BY semester_date, group_id, period_id")->fetchAll(PDO::FETCH_ASSOC);
echo "<table border='1'><tr><th>semester_date</th><th>week_type</th><th>group_id</th><th>period_id</th><th>discipline_id</th></tr>";
foreach ($rows as $r) {
    echo "<tr><td>$r[semester_date]</td><td>$r[week_type]</td><td>$r[group_id]</td><td>$r[period_id]</td><td>$r[discipline_id]</td></tr>";
}
echo "</table>";
?>
