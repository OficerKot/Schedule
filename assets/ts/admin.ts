export {};

document.addEventListener('DOMContentLoaded', () => {
    const loginLink = document.getElementById('loginLink') as HTMLAnchorElement;
    const loginModal = document.getElementById('loginModal') as HTMLElement;
    const closeBtn = document.querySelector('.close-modal') as HTMLElement;
    const loginForm = document.getElementById('loginForm') as HTMLFormElement;
    const loginMessage = document.getElementById('loginMessage') as HTMLElement;
    const adminPanelBtn = document.getElementById('adminPanelBtn') as HTMLElement;

    const isLoggedIn = localStorage.getItem('isAdminLoggedIn') === 'true';
    const isAdminPage = window.location.pathname.includes('admin.php');
    const headerContainer = document.querySelector('.headerBtnsContainer');
    
    console.log('isLoggedIn:', isLoggedIn, 'isAdminPage:', isAdminPage);
    
    if (isLoggedIn) {
        // Авторизованный пользователь
        if (loginLink) loginLink.style.display = 'none';
        
        // adminPanelBtn видна только если мы не на странице админ-панели
        if (adminPanelBtn) {
            adminPanelBtn.style.display = isAdminPage ? 'none' : 'inline-block';
        }
        
        // Создаём кнопку "Выйти"
        const logoutBtn = document.createElement('button');
        logoutBtn.id = 'logoutBtn';
        logoutBtn.textContent = 'Выйти';
        logoutBtn.style.cssText = 'margin-left: 10px; padding: 8px 15px; background: #dc3545; color: #fff; border: none; border-radius: 4px; cursor: pointer;';
        logoutBtn.addEventListener('click', () => {
            localStorage.removeItem('isAdminLoggedIn');
            window.location.href = '/api/logout.php';
        });
        
        // Всегда добавляем кнопку в header
        if (headerContainer) {
            headerContainer.appendChild(logoutBtn);
            console.log('Добавлена кнопка Выйти');
        }
    } else {
        // Неавторизованный пользователь
        if (loginLink) loginLink.style.display = 'inline-block';
        if (adminPanelBtn) adminPanelBtn.style.display = 'none';
    }

    // Обработчики модального окна входа (только если элементы существуют)
    if (loginLink && loginModal && closeBtn && loginForm && loginMessage) {
        loginLink.addEventListener('click', (e) => {
            e.preventDefault();
            loginModal.style.display = 'block';
        });

        closeBtn.addEventListener('click', () => {
            loginModal.style.display = 'none';
        });

        window.addEventListener('click', (e) => {
            if (e.target === loginModal) {
                loginModal.style.display = 'none';
            }
        });

        loginForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const login = (document.getElementById('loginUsername') as HTMLInputElement).value;
            const password = (document.getElementById('loginPassword') as HTMLInputElement).value;

            try {
                const response = await fetch('/api/login.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ login, password })
                });
                const data = await response.json();

                if (data.success) {
                    localStorage.setItem('isAdminLoggedIn', 'true');
                    loginModal.style.display = 'none';
                    loginLink.style.display = 'none';
                    adminPanelBtn.style.display = 'inline-block';
                    loginMessage.textContent = '';
                    alert('Добро пожаловать, администратор!');
                    window.location.reload();
                } else {
                    loginMessage.textContent = data.message;
                    loginMessage.className = 'message error';
                }
            } catch (error) {
                loginMessage.textContent = 'Ошибка соединения с сервером';
                loginMessage.className = 'message error';
            }
        });

        adminPanelBtn.addEventListener('click', () => {
            window.location.href = '/pages/admin.php';
        });
    }
});
