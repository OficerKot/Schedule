<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/general.css">
    <link rel="stylesheet" href="../assets/css/schedule.css">
    <title>Преподаватели</title>
    <style>
        .teachers-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 16px;
            padding: 16px 20px;
        }
        .teacher-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--r-lg);
            padding: 16px 18px;
            transition: border-color 0.14s, box-shadow 0.14s, transform 0.14s;
            cursor: default;
            color: var(--t-primary);
        }
        .teacher-card:hover {
            border-color: rgba(79, 127, 255, 0.3);
            box-shadow: 0 4px 20px rgba(79, 127, 255, 0.08);
            transform: translateY(-2px);
        }
        .teacher-card h3 {
            font-size: 0.92rem;
            font-weight: 700;
            color: var(--t-primary);
            margin-bottom: 10px;
        }
        .teacher-card .detail {
            font-size: 0.78rem;
            color: var(--t-secondary);
            margin: 3px 0;
            display: flex;
            gap: 5px;
        }
        .teacher-card .detail strong {
            color: var(--t-muted);
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            white-space: nowrap;
        }
    </style>
</head>

<?php include "../includes/header.php"; ?>

<body>
    <h1>Преподавательский состав</h1>

    <?php
    // Прямое подключение к БД (как в вашем старом рабочем коде)
    $host = '127.0.0.1';
    $port = '3306';
    $db   = 'Schedule';
    $user = 'root';
    $pass = '';

    try {
        $pdo = new PDO(
            "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4",
            $user,
            $pass,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
        $stmt = $pdo->query("
            SELECT teacher_id, first_name, middle_name, last_name,
                   school, department, chair, degree, title, position
            FROM teachers
            ORDER BY last_name
        ");
        $teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "<p style='color: #e05f5f;'>Ошибка подключения к БД: " . htmlspecialchars($e->getMessage()) . "</p>";
        $teachers = [];
    }
    ?>

    <div class="teachers-grid">
        <?php foreach ($teachers as $t): ?>
        <div class="teacher-card">
            <h3><?= htmlspecialchars($t['last_name'] . ' ' . $t['first_name'] . ' ' . $t['middle_name']) ?></h3>
            <?php if ($t['degree']): ?><div class="detail"><strong>Степень:</strong> <?= htmlspecialchars($t['degree']) ?></div><?php endif; ?>
            <?php if ($t['title']): ?><div class="detail"><strong>Звание:</strong> <?= htmlspecialchars($t['title']) ?></div><?php endif; ?>
            <?php if ($t['position']): ?><div class="detail"><strong>Должность:</strong> <?= htmlspecialchars($t['position']) ?></div><?php endif; ?>
            <?php if ($t['school']): ?><div class="detail"><strong>Школа:</strong> <?= htmlspecialchars($t['school']) ?></div><?php endif; ?>
            <?php if ($t['department']): ?><div class="detail"><strong>Департамент:</strong> <?= htmlspecialchars($t['department']) ?></div><?php endif; ?>
            <?php if ($t['chair']): ?><div class="detail"><strong>Кафедра:</strong> <?= htmlspecialchars($t['chair']) ?></div><?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
</body>

<script type="module" src="../assets/js/admin.js"></script>

</html>