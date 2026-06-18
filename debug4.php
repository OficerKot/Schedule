<?php
header('Content-Type: application/json');
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
    
    // Тест classroom фильтра
    $classroomKey = 'Д303';
    $building = $classroomKey[0];
    $roomNum = substr($classroomKey, 1);
    echo "building='$building' roomNum='$roomNum'\n";
    
    $sql = "SELECT lc.*, r.building, r.room_number FROM lesson_card lc JOIN rooms r ON r.room_id = lc.room_id WHERE (r.building = ? AND r.room_number = ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$building, $roomNum]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "Found " . count($rows) . " rows\n";
    foreach ($rows as $r) {
        echo json_encode($r) . "\n";
    }
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>
