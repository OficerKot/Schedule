<header>
	<div class="headerBtnsContainer">
		<a href="../index.php" style="text-decoration: none;">Расписание</a>
		<a href="../pages/teachersInfo.php" style="text-decoration: none;">Преподаватели</a>
		<a href="../pages/reports.php" style="text-decoration: none;" class="active">Отчеты</a>

		<a href="#" id="loginLink" style="text-decoration: none; color: #007bff;">Вход</a>
		<button id="adminPanelBtn" style="display: none; margin-left: 10px;">Админ-панель</button>
	</div>
</header>

<!-- Login Modal -->
<div id="loginModal" class="modal">
	<div class="modal-content">
		<span class="close-modal">&times;</span>
		<h2>Вход</h2>
		<form id="loginForm">
			<div class="form-group">
				<label for="loginUsername">Логин</label>
				<input type="text" id="loginUsername" required>
			</div>
			<div class="form-group">
				<label for="loginPassword">Пароль</label>
				<input type="password" id="loginPassword" required>
			</div>
			<button type="submit">Войти</button>
		</form>
		<p id="loginMessage" class="message"></p>
	</div>
</div>
