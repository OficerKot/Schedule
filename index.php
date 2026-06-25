<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="assets/css/general.css">
	<link rel="stylesheet" href="assets/css/schedule.css">
	<title>Расписание занятий</title>
</head>

<?php include "includes/header.php";?>

<body>
	<h1> Расписание занятий</h1>

	<!-- Контейнер с кнопками управления -->
	<div class="navButtonsContainer">
		<?php include "includes/filterButtons.php"; ?>
		<div class="nav-buttons">
			<?php include "includes/weekNavButtons.php"; ?>
		</div>
	</div>

	<!-- Контейнер с учебными днями (пн-сб) -->
	<div id="scheduleContainer" class="scheduleContainer">

	</div>
</body>

<script type="module" src="assets/js/schedule.js"></script>

</html>