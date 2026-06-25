<?php
if (session_status() === PHP_SESSION_NONE) {
    $cookieParams = [
        'lifetime' => 30 * 24 * 60 * 60,
        'path' => '/',
        'domain' => '',
        'secure' => false,
        'httponly' => true
    ];
    session_set_cookie_params($cookieParams);
    session_start();
}

header('Content-Type: application/json');

echo json_encode([
    'logged_in' => isset($_SESSION['user_id']),
    'role' => $_SESSION['role'] ?? null
]);
