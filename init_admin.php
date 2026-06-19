<?php
// Запустите этот файл ОДИН РАЗ через браузер (например, http://localhost/Schedule/init_admin.php)
// Он создаст администратора с логином admin и паролем admin123

// Подключение к БД
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=Schedule;charset=utf8mb4', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Ошибка подключения к БД: " . $e->getMessage() . "\n");
}

$login = 'admin';
$password = 'admin123';

// Проверка, существует ли уже админ
$stmt = $pdo->prepare("SELECT user_id FROM users WHERE login = :login");
$stmt->execute(['login' => $login]);

if ($stmt->fetch()) {
    echo "<h2>Администратор '$login' уже существует!</h2>";
    echo "<p>Логин: <b>$login</b></p>";
    echo "<p>Пароль: <b>$password</b></p>";
    echo "<p>Удалите этого пользователя в phpMyAdmin, если хотите создать заново.</p>";
} else {
    // Создаём хэш пароля
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    
    // Вставляем нового админа
    $stmt = $pdo->prepare("INSERT INTO users (login, password_hash, role) VALUES (:login, :password_hash, 'admin')");
    $stmt->execute([
        'login' => $login,
        'password_hash' => $passwordHash
    ]);
    
    echo "<h2 style='color: green;'>✓ Администратор создан успешно!</h2>";
    echo "<p>Логин: <b>$login</b></p>";
    echo "<p>Пароль: <b>$password</b></p>";
    echo "<p style='color: red;'><b>Важно:</b> Удалите этот файл после использования!</p>";
    echo "<hr>";
    echo "<p>Теперь зайдите на <a href='index.php'>главную страницу</a> и попробуйте войти.</p>";
}
?>
