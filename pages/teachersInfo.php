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
			grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
			gap: 16px;
			padding: 16px;
		}
		.teacher-card {
			border: 1px solid #b4b4b4;
			border-radius: 8px;
			padding: 16px;
			background: #f9f9f9;
		}
		.teacher-card h3 {
			margin: 0 0 8px 0;
			font-size: 16px;
		}
		.teacher-card .detail {
			margin: 4px 0;
			font-size: 14px;
			color: #555;
		}
		.teacher-card .detail strong {
			color: #333;
		}
	</style>
</head>

<?php include "../includes/header.php";?>

<body>
	<h1>Преподавательский состав</h1>

	<?php
	$host = '127.0.0.1';
	$port = '3307';
	$db = 'Schedule';
	$user = 'root';
	$pass = '';

	try {
		$pdo = new PDO(
			"mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4",
			$user, $pass,
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
		echo "<p style='color:red'>Ошибка подключения к БД: " . htmlspecialchars($e->getMessage()) . "</p>";
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

</html>