<!-- pages/report_workload.php -->
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/general.css">
    <link rel="stylesheet" href="../assets/css/reports.css">
    <title>Нагрузка преподавателей - Отчет</title>
</head>
<?php include "../includes/header.php"; ?>
<body>
    <div class="report-page">
        <div class="report-header">
            <h2>Нагрузка преподавателей</h2>
            <a href="reports.php" class="back-btn">← Назад к отчетам</a>
        </div>
        <div class="filters">
            <div class="filter-group">
                <label>Подразделение:</label>
                <select id="subdivisionSelect">
                    <option value="1">Кафедра математики</option>
                    <option value="2">Кафедра ИТ</option>
                    <option value="3">Кафедра экономики</option>
                </select>
            </div>
            <button id="loadReportBtn">Сформировать</button>
        </div>
        <div id="reportContent">
            <div class="info">Выберите подразделение и нажмите "Сформировать"</div>
        </div>
    </div>
    <script src="../assets/js/reports.js"></script>
</body>
</html>