<?php
// Тестовый скрипт для проверки подключения к БД
echo "<h2>Тест подключения к БД</h2>";

try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=Schedule;charset=utf8mb4', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<p style='color: green;'>✓ Подключение к БД успешно!</p>";
    
    // Проверка пользователей
    $stmt = $pdo->query("SELECT user_id, login, role FROM users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h3>Пользователи в БД:</h3>";
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>ID</th><th>Логин</th><th>Роль</th></tr>";
    foreach ($users as $user) {
        echo "<tr><td>{$user['user_id']}</td><td>{$user['login']}</td><td>{$user['role']}</td></tr>";
    }
    echo "</table>";
    
    // Проверка хэша для admin
    $stmt = $pdo->prepare("SELECT password_hash FROM users WHERE login = 'admin'");
    $stmt->execute();
    $hash = $stmt->fetchColumn();
    
    if ($hash) {
        echo "<h3>Проверка пароля:</h3>";
        echo "<p>Хэш: " . substr($hash, 0, 50) . "...</p>";
        
        if (password_verify('admin123', $hash)) {
            echo "<p style='color: green;'>✓ Пароль 'admin123' верен!</p>";
        } else {
            echo "<p style='color: red;'>✗ Пароль 'admin123' НЕ верен!</p>";
            echo "<p>Создайте нового админа через <a href='init_admin.php'>init_admin.php</a></p>";
        }
    } else {
        echo "<p style='color: red;'>Пользователь 'admin' не найден!</p>";
        echo "<p>Создайте его через <a href='init_admin.php'>init_admin.php</a></p>";
    }
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>✗ Ошибка подключения: " . $e->getMessage() . "</p>";
}
?>
