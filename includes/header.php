<header>
    <?php
    // Определяем базовый путь: если мы в папке pages/, то добавляем ../, иначе пусто
    $basePath = (strpos($_SERVER['SCRIPT_NAME'], '/pages/') !== false) ? '../' : '';
    ?>
    <div class="headerBtnsContainer">
        <a href="<?= $basePath ?>index.php" style="text-decoration: none;"> Расписание</a>
        <a href="<?= $basePath ?>pages/teachersInfo.php" style="text-decoration: none;"> Преподаватели</a>
        <a href="<?= $basePath ?>pages/reports.php" style="text-decoration: none;"> Отчеты</a>
        <a href="#" id="loginLink" style="text-decoration: none; color: #007bff;"> Вход</a>
        <button id="adminPanelBtn" style="display: none; margin-left: 10px;"> Админ</button>
    </div>
    <script type="module" src="<?= $basePath ?>assets/js/admin.js"></script>
</header>

<!-- Login Modal -->
<div id="loginModal" class="modal">
    <div class="modal-content">
        <span class="close-modal">&times;</span>
        <h2> Вход в систему</h2>
        <form id="loginForm" class="authForm">
            <div class="form-group">
                <label for="loginUsername">Логин</label>
                <input type="text" id="loginUsername" placeholder="Введите логин" required>
            </div>
            <div class="form-group">
                <label for="loginPassword">Пароль</label>
                <input type="password" id="loginPassword" placeholder="Введите пароль" required>
            </div>
            <button type="submit">Войти</button>
        </form>
        <p id="loginMessage" class="message"></p>
    </div>
</div>