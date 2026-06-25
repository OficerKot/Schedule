<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/general.css">
    <link rel="stylesheet" href="../assets/css/schedule.css">
    <title>Преподаватели</title>
    <style>
        /* ===== Стили для страницы преподавателей ===== */
        .teachers-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            padding: 20px 24px;
            animation: fadeIn 0.4s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .teacher-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--r-lg);
            padding: 18px 20px;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: default;
            color: var(--t-primary);
            position: relative;
            overflow: hidden;
        }

        /* Декоративная линия сверху */
        .teacher-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--accent);
            opacity: 0;
            transition: opacity 0.3s;
        }

        .teacher-card:hover::before {
            opacity: 1;
        }

        .teacher-card:hover {
            border-color: rgba(79, 127, 255, 0.3);
            box-shadow: 0 8px 30px rgba(79, 127, 255, 0.08);
            transform: translateY(-4px);
        }

        .teacher-card h3 {
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--t-primary);
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .teacher-card h3::before {;
            font-size: 1.2rem;
            opacity: 0.7;
        }

        .teacher-card .detail {
            font-size: 0.82rem;
            color: var(--t-secondary);
            margin: 4px 0;
            display: flex;
            align-items: baseline;
            gap: 8px;
            padding: 2px 0;
            border-bottom: 1px solid var(--border);
            transition: border-color 0.2s;
        }

        .teacher-card .detail:last-of-type {
            border-bottom: none;
        }

        .teacher-card .detail strong {
            color: var(--t-muted);
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            white-space: nowrap;
            min-width: 100px;
            font-weight: 600;
        }

        .teacher-card .detail .value {
            color: var(--t-primary);
            font-weight: 400;
        }

        /* Если поле отсутствует – скрываем */
        .teacher-card .detail:empty {
            display: none;
        }

        /* Адаптив */
        @media (max-width: 600px) {
            .teachers-grid {
                grid-template-columns: 1fr;
                padding: 12px;
                gap: 14px;
            }
            .teacher-card h3 {
                font-size: 0.95rem;
            }
            .teacher-card .detail strong {
                min-width: 70px;
            }
        }
    </style>
</head>

<?php include "../includes/header.php"; ?>

<body>
    <h1> Преподавательский состав</h1>

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
        echo "<p style='color: #e05f5f; padding: 20px;'> Ошибка подключения к БД: " . htmlspecialchars($e->getMessage()) . "</p>";
        $teachers = [];
    }
    ?>

    <div class="teachers-grid">
        <?php foreach ($teachers as $t): ?>
        <div class="teacher-card">
            <h3><?= htmlspecialchars($t['last_name'] . ' ' . $t['first_name'] . ' ' . $t['middle_name']) ?></h3>

            <?php if ($t['degree']): ?>
                <div class="detail"><strong> Степень</strong> <span class="value"><?= htmlspecialchars($t['degree']) ?></span></div>
            <?php endif; ?>

            <?php if ($t['title']): ?>
                <div class="detail"><strong> Звание</strong> <span class="value"><?= htmlspecialchars($t['title']) ?></span></div>
            <?php endif; ?>

            <?php if ($t['position']): ?>
                <div class="detail"><strong> Должность</strong> <span class="value"><?= htmlspecialchars($t['position']) ?></span></div>
            <?php endif; ?>

            <?php if ($t['school']): ?>
                <div class="detail"><strong> Школа</strong> <span class="value"><?= htmlspecialchars($t['school']) ?></span></div>
            <?php endif; ?>

            <?php if ($t['department']): ?>
                <div class="detail"><strong> Департамент</strong> <span class="value"><?= htmlspecialchars($t['department']) ?></span></div>
            <?php endif; ?>

            <?php if ($t['chair']): ?>
                <div class="detail"><strong> Кафедра</strong> <span class="value"><?= htmlspecialchars($t['chair']) ?></span></div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
</body>

<script type="module" src="../assets/js/admin.js"></script>

</html>