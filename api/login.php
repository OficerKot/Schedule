<?php
// Установка параметров сессии ДО начала сессии
$cookieParams = [
    'lifetime' => 30 * 24 * 60 * 60, // 30 дней
    'path' => '/',
    'domain' => '',
    'secure' => false,
    'httponly' => true
];

if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params($cookieParams);
    session_start();
}

header('Content-Type: application/json');

require_once __DIR__ . '/db.php';

$input = json_decode(file_get_contents('php://input'), true);
$login = $input['login'] ?? '';
$password = $input['password'] ?? '';

if (empty($login) || empty($password)) {
    echo json_encode(['success' => false, 'message' => 'Заполните все поля']);
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE login = :login");
$stmt->execute(['login' => $login]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user['password_hash'])) {
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['role'] = $user['role'];
    $_SESSION['login_time'] = time();
    
    if ($user['role'] === 'admin') {
        echo json_encode(['success' => true, 'role' => 'admin']);
    } else {
        echo json_encode(['success' => false, 'message' => 'У вас нет прав администратора']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Неверный логин или пароль']);
}
?>
