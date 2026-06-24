<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/general.css">
    <title>Регистрация</title>
</head>

<body>
    <?php include "../includes/header.php"; ?>

    <div class="container">
        <h1>Регистрация</h1>
        <form id="registerForm" class="authForm">
            <div class="form-group">
                <label for="login">Логин</label>
                <input type="text" id="login" name="login" required>
            </div>
            <div class="form-group">
                <label for="password">Пароль</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="confirmPassword">Подтверждение пароля</label>
                <input type="password" id="confirmPassword" name="confirmPassword" required>
            </div>
            <button type="submit">Зарегистрироваться</button>
        </form>
        <div id="message" class="message"></div>
    </div>

    <script>
        document.getElementById('registerForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const messageDiv = document.getElementById('message');
            const login = document.getElementById('login').value;
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirmPassword').value;

            try {
                const response = await fetch('../api/register.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ login, password, confirmPassword })
                });
                const data = await response.json();
                messageDiv.textContent = data.message;
                messageDiv.className = data.success ? 'message success' : 'message error';
                if (data.success) {
                    setTimeout(() => window.location.href = '../index.php', 2000);
                }
            } catch (err) {
                messageDiv.textContent = 'Ошибка сети';
                messageDiv.className = 'message error';
            }
        });
    </script>
</body>

</html>
