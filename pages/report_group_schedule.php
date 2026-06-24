<!-- pages/report_group_schedule.php -->

<!DOCTYPE html>
<html lang="ru">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="../assets/css/general.css">
	<link rel="stylesheet" href="../assets/css/reports.css">
	<title>Расписание группы - Отчет</title>
</head>

<?php include "../includes/header.php"; ?>

<body>
	<div class="report-page">
		<div class="report-header">
			<h2>📋 Расписание группы</h2>
			<a href="reports.php" class="back-btn">← Назад к отчетам</a>
		</div>

		<div class="report-placeholder">
			<div class="placeholder-icon">🚧</div>
			<h3>Раздел в разработке</h3>
			<p>Здесь будет реализован отчет <strong>"Расписание группы"</strong></p>
			<p class="hint">Пока что это просто заглушка для навигации</p>

			<!-- Заглушка фильтров -->
			<div class="mock-filters">
				<div class="filter-group">
					<label>Группа:</label>
					<select disabled>
						<option>— выбрать группу —</option>
					</select>
				</div>
				<div class="filter-group">
					<label>Неделя:</label>
					<select disabled>
						<option>— выбрать неделю —</option>
					</select>
				</div>
				<button class="btn-disabled" disabled>Сформировать</button>
			</div>

			<div class="mock-table">
				<div class="mock-row">
					<span>День</span><span>Пара</span><span>Дисциплина</span><span>Тип</span><span>Преподаватель</span><span>Аудитория</span>
				</div>
				<div class="mock-row">
					<span>ПН</span><span>1</span><span>Математический анализ</span><span>Лекция</span><span>Курочкина И.А.</span><span>101</span>
				</div>
				<div class="mock-row">
					<span>ПН</span><span>2</span><span>Программирование на C++</span><span>Лабораторная</span><span>Смирнов А.П.</span><span>303</span>
				</div>
			</div>
		</div>
	</div>
</body>

</html>