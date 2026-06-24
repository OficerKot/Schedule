<?php
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Метод не разрешен']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$login = $data['login'] ?? '';
$password = $data['password'] ?? '';
$confirmPassword = $data['confirmPassword'] ?? '';

if (empty($login) || empty($password) || empty($confirmPassword)) {
    echo json_encode(['success' => false, 'message' => 'Все поля обязательны']);
    exit;
}

if ($password !== $confirmPassword) {
    echo json_encode(['success' => false, 'message' => 'Пароли не совпадают']);
    exit;
}

try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=Schedule;charset=utf8mb4', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Проверка уникальности логина
    $stmt = $pdo->prepare("SELECT user_id FROM users WHERE login = :login");
    $stmt->execute(['login' => $login]);
    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Пользователь с таким логином уже существует']);
        exit;
    }

    // Хэширование пароля
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // Вставка нового пользователя (по умолчанию роль student)
    $stmt = $pdo->prepare("INSERT INTO users (login, password_hash, role) VALUES (:login, :password_hash, 'student')");
    $stmt->execute([
        'login' => $login,
        'password_hash' => $passwordHash
    ]);

    echo json_encode(['success' => true, 'message' => 'Регистрация успешна']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Ошибка сервера']);
}
?>
