<!-- pages/report_classrooms.php -->
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/general.css">
    <link rel="stylesheet" href="../assets/css/reports.css">
    <title>Занятость помещений - Отчет</title>
</head>
<?php include "../includes/header.php"; ?>
<body>
    <div class="report-page">
        <div class="report-header">
            <h2>Занятость помещений</h2>
            <a href="reports.php" class="back-btn">← Назад к отчетам</a>
        </div>

        <div class="filters">
            <div class="filter-group">
                <label for="buildingSelect">Корпус</label>
                <select id="buildingSelect">
                    <option value="">Все корпуса</option>
                    <option value="А">Корпус А</option>
                    <option value="Б">Корпус Б</option>
                    <option value="Д">Корпус Д</option>
                </select>
            </div>
            <button id="loadReportBtn">Сформировать</button>
        </div>

        <div id="reportContent">
            <div class="info">Выберите корпус и нажмите «Сформировать»</div>
        </div>
    </div>

    <script src="../assets/js/reports.js"></script>
</body>
</html>