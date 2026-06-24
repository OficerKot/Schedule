<?php
// Прямой тест подключения к БД
echo "Тест подключения к БД<br><br>";

try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=Schedule;charset=utf8mb4', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<span style='color:green'>✓ Подключение к БД успешно!</span><br><br>";
    
    // Тест запроса
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM groups");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Количество групп в БД: " . $result['count'] . "<br><br>";
    
    // Тест JSON
    $stmt = $pdo->query("SELECT group_id, name, students_count FROM groups ORDER BY name LIMIT 3");
    $groups = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "JSON ответ:<br>";
    echo "<pre>" . json_encode($groups, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "</pre>";
    
} catch (PDOException $e) {
    echo "<span style='color:red'>✗ Ошибка БД: " . $e->getMessage() . "</span>";
}
?>
