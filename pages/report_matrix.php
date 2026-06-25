<!-- pages/report_matrix.php -->
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/general.css">
    <link rel="stylesheet" href="../assets/css/reports.css">
    <title>Шахматная ведомость - Отчет</title>
</head>
<?php include "../includes/header.php"; ?>
<body>
    <div class="report-page">
        <div class="report-header">
            <h2>Шахматная ведомость</h2>
            <a href="reports.php" class="back-btn">← Назад к отчетам</a>
        </div>
        <div class="filters">
            <div class="filter-group">
                <label>Группы (выберите несколько):</label>
                <select id="groupSelect" multiple size="4">
                    <option value="1">Б9124-09.03.03-Пикд</option>
                    <option value="2">Б9124-09.03.03-Пикр</option>
                    <option value="3">Б9125-09.03.03-Пикд</option>
                    <option value="4">Б9125-09.03.03-Пикр</option>
                    <option value="5">Б9224-09.03.03-Пикд</option>
                    <option value="6">Б9324-09.03.03-Пикд</option>
                    <option value="7">Б9130-01.03.02-Кмд</option>
                    <option value="8">Б9140-38.03.01-Эк</option>
                </select>
            </div>
            <div class="filter-group">
                <label>Неделя:</label>
                <select id="weekSelect">
                    <option value="1">Неделя 1</option>
                    <option value="2">Неделя 2</option>
                    <option value="3">Неделя 3</option>
                </select>
            </div>
            <button id="loadReportBtn">Сформировать</button>
        </div>
        <div id="reportContent">
            <div class="info">Выберите группы и нажмите "Сформировать"</div>
        </div>
    </div>
    <script src="../assets/js/reports.js"></script>
</body>
</html>