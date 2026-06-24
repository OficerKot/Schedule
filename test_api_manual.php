<?php
// Прямой тест API
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Тест get_groups.php</h2>";

try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=Schedule;charset=utf8mb4', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✓ Подключение к БД<br>";
    
    $stmt = $pdo->query("SELECT group_id, name, students_count FROM groups ORDER BY name");
    $groups = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Количество групп: " . count($groups) . "<br>";
    echo "JSON:<br>";
    header('Content-Type: application/json');
    echo json_encode($groups, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    
} catch (PDOException $e) {
    echo "<br>✗ Ошибка БД: " . $e->getMessage() . "<br>";
    echo "Code: " . $e->getCode();
} catch (Exception $e) {
    echo "<br>✗ Ошибка: " . $e->getMessage();
}
?>
