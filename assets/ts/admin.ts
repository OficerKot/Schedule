document.addEventListener('DOMContentLoaded', () => {
const loginLink = document.getElementById('loginLink') as HTMLAnchorElement;
const loginModal = document.getElementById('loginModal') as HTMLElement;
const closeBtn = document.querySelector('.close-modal') as HTMLElement;
const loginForm = document.getElementById('loginForm') as HTMLFormElement;
const loginMessage = document.getElementById('loginMessage') as HTMLElement;
const adminPanelBtn = document.getElementById('adminPanelBtn') as HTMLElement;

// Проверяем сохранённое состояние при загрузке
function checkAuthState() {
    const isLoggedIn = localStorage.getItem('isAdminLoggedIn') === 'true';
    
    if (isLoggedIn && loginLink && adminPanelBtn) {
        loginLink.style.display = 'none';
        adminPanelBtn.style.display = 'inline-block';
    } else if (loginLink && adminPanelBtn) {
        loginLink.style.display = 'inline-block';
        adminPanelBtn.style.display = 'none';
    }
}

if (loginLink && loginModal && closeBtn && loginForm && loginMessage && adminPanelBtn) {
    // Проверяем состояние при загрузке
    checkAuthState();

    // Открыть модальное окно
    loginLink.addEventListener('click', (e) => {
        e.preventDefault();
        loginModal.style.display = 'block';
    });

    // Закрыть модальное окно
    closeBtn.addEventListener('click', () => {
        loginModal.style.display = 'none';
    });

    // Закрыть при клике вне окна
    window.addEventListener('click', (e) => {
        if (e.target === loginModal) {
            loginModal.style.display = 'none';
        }
    });

    // Обработка формы входа
    loginForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const login = (document.getElementById('loginUsername') as HTMLInputElement).value;
        const password = (document.getElementById('loginPassword') as HTMLInputElement).value;

        try {
            const response = await fetch('api/login.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ login, password })
            });
            const data = await response.json();

            if (data.success) {
                // Сохраняем состояние в localStorage
                localStorage.setItem('isAdminLoggedIn', 'true');
                
                loginModal.style.display = 'none';
                loginLink.style.display = 'none';
                adminPanelBtn.style.display = 'inline-block';
                loginMessage.textContent = '';
                alert('Добро пожаловать, администратор!');
            } else {
                loginMessage.textContent = data.message;
                loginMessage.className = 'message error';
            }
        } catch (error) {
            loginMessage.textContent = 'Ошибка соединения с сервером';
            loginMessage.className = 'message error';
        }
    });

    // Кнопка админ-панели
    adminPanelBtn.addEventListener('click', () => {
        window.location.href = 'pages/admin.php';
    });
}

// Добавляем кнопку "Выйти" если пользователь авторизован
function addLogoutButton() {
    const isLoggedIn = localStorage.getItem('isAdminLoggedIn') === 'true';
    const adminPanelBtn = document.getElementById('adminPanelBtn');
    
    if (isLoggedIn && adminPanelBtn) {
        const logoutBtn = document.createElement('button');
        logoutBtn.id = 'logoutBtn';
        logoutBtn.style.cssText = 'margin-left: 10px; padding: 8px 15px; background: #dc3545; color: #fff; border: none; border-radius: 4px; cursor: pointer;';
        logoutBtn.textContent = 'Выйти';
        logoutBtn.addEventListener('click', () => {
            localStorage.removeItem('isAdminLoggedIn');
            window.location.href = 'api/logout.php';
        });
        adminPanelBtn.parentNode?.insertBefore(logoutBtn, adminPanelBtn.nextSibling);
    }
}

// Вызываем функцию добавления кнопки выхода
addLogoutButton();
});
