<!DOCTYPE html>
<html lang="ru">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="../assets/css/general.css">
	<link rel="stylesheet" href="../assets/css/reports.css">
	<title>Отчеты</title>
</head>

<?php include "../includes/header.php"; ?>

<body>
	<div class="reports-page">
		<h1>Отчеты</h1>
		<p class="subtitle">Выберите тип отчета для формирования:</p>

		<div class="reports-grid">
			<!-- Отчет 1 -->
			<div class="report-card" onclick="location.href='report_group_schedule.php'">

				<h3>Расписание группы</h3>
				<p>Печатная форма расписания для одной группы</p>
				<button class="btn-open">Открыть</button>
			</div>

			<!-- Отчет 2 -->
			<div class="report-card" onclick="location.href='report_matrix.php'">

				<h3>Шахматная ведомость</h3>
				<p>Расписание нескольких групп в виде шахматки</p>
				<button class="btn-open">Открыть</button>
			</div>

			<!-- Отчет 3 -->
			<div class="report-card" onclick="location.href='report_exams.php'">

				<h3>Зачеты и экзамены</h3>
				<p>Список зачетов и экзаменов для группы</p>
				<button class="btn-open">Открыть</button>
			</div>

			<!-- Отчет 4 -->
			<div class="report-card" onclick="location.href='report_workload.php'">

				<h3>Нагрузка преподавателей</h3>
				<p>План занятости преподавателей подразделения</p>
				<button class="btn-open">Открыть</button>
			</div>

			<!-- Отчет 5 -->
			<div class="report-card" onclick="location.href='report_classrooms.php'">

				<h3>Занятость помещений</h3>
				<p>Сводная занятость по типам, времени, корпусам</p>
				<button class="btn-open">Открыть</button>
			</div>
		</div>
	</div>
</body>

</html>