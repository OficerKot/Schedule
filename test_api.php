<?php
// Тест API endpoints
echo "<h2>Тест API endpoints</h2>";

$endpoints = [
    'get_groups.php' => 'GET /api/get_groups.php',
    'get_teachers.php' => 'GET /api/get_teachers.php',
    'get_schedule.php' => 'GET /api/get_schedule.php'
];

foreach ($endpoints as $file => $name) {
    echo "<h3>$name</h3>";
    
    $url = 'http://localhost' . dirname($_SERVER['PHP_SELF']) . '/api/' . $file;
    $url = str_replace('/pages/test_api.php', '', $url);
    $url .= '/api/' . $file;
    
    echo "URL: $url<br>";
    
    $response = @file_get_contents($url);
    if ($response !== false) {
        echo "<pre>" . htmlspecialchars($response) . "</pre>";
    } else {
        echo "<p style='color:red'>Ошибка загрузки</p>";
    }
    echo "<hr>";
}
?>
