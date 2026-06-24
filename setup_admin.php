<?php
// Скрипт для создания первого администратора
// Запустить один раз, затем удалить этот файл

require_once __DIR__ . '/api/db.php';

$login = 'admin';
$password = 'admin123'; // Поменяйте на свой пароль!

try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=Schedule;charset=utf8mb4', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Проверка, существует ли уже админ
    $stmt = $pdo->prepare("SELECT user_id FROM users WHERE login = :login");
    $stmt->execute(['login' => $login]);
    
    if ($stmt->fetch()) {
        echo "Пользователь '$login' уже существует.\n";
    } else {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (login, password_hash, role) VALUES (:login, :password_hash, 'admin')");
        $stmt->execute([
            'login' => $login,
            'password_hash' => $passwordHash
        ]);
        echo "Администратор создан!\n";
        echo "Логин: $login\n";
        echo "Пароль: $password\n";
    }
} catch (PDOException $e) {
    echo "Ошибка: " . $e->getMessage() . "\n";
}
?>
