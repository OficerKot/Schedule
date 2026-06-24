<?php
header('Content-Type: text/html; charset=utf-8');
$pdo = new PDO("mysql:host=127.0.0.1;port=3306;dbname=Schedule;charset=utf8mb4", 'root', '');

echo "<h3>rooms table:</h3>";
$rows = $pdo->query("SELECT * FROM rooms ORDER BY building, room_number")->fetchAll(PDO::FETCH_ASSOC);
echo "<table border='1'><tr><th>room_id</th><th>building</th><th>room_number</th><th>room_type</th><th>seats</th></tr>";
foreach ($rows as $r) {
    echo "<tr><td>$r[room_id]</td><td>$r[building]</td><td>$r[room_number]</td><td>$r[room_type]</td><td>$r[seats]</td></tr>";
}
echo "</table>";

echo "<h3>building_distances table:</h3>";
$bd = $pdo->query("SELECT * FROM building_distances ORDER BY from_building, to_building")->fetchAll(PDO::FETCH_ASSOC);
echo "<table border='1'><tr><th>from</th><th>to</th><th>minutes</th></tr>";
foreach ($bd as $r) {
    echo "<tr><td>$r[from_building]</td><td>$r[to_building]</td><td>$r[walk_minutes]</td></tr>";
}
echo "</table>";
echo "Count: " . count($bd);
?>
