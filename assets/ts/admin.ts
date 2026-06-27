export {};

document.addEventListener('DOMContentLoaded', async () => {
    const loginLink = document.getElementById('loginLink') as HTMLAnchorElement;
    const loginModal = document.getElementById('loginModal') as HTMLElement;
    const closeBtn = document.querySelector('.close-modal') as HTMLElement;
    const loginForm = document.getElementById('loginForm') as HTMLFormElement;
    const loginMessage = document.getElementById('loginMessage') as HTMLElement;
    const headerContainer = document.querySelector('.headerBtnsContainer');

    const apiBase = window.location.pathname.includes('/pages/')
        ? '../api/'
        : 'api/';

    let isLoggedIn = false;
    let userRole = '';

    try {
        const sessionResponse = await fetch(apiBase + 'get_session.php');
        const session = await sessionResponse.json();
        isLoggedIn = session.logged_in === true;
        userRole = session.role || '';
        if (isLoggedIn && session.role) {
            localStorage.setItem('userRole', session.role);
        } else {
            localStorage.removeItem('userRole');
            localStorage.removeItem('isAdminLoggedIn');
        }
    } catch {
        isLoggedIn = localStorage.getItem('userRole') !== null;
        userRole = localStorage.getItem('userRole') || '';
    }

    const adminBtn = document.getElementById('adminPanelBtn');

    if (adminBtn) {
        adminBtn.addEventListener('click', () => {
            const basePath = window.location.pathname.includes('/pages/') ? '' : 'pages/';
            window.location.href = basePath + 'admin.php';
        });
    }

    if (isLoggedIn) {
        if (loginLink) loginLink.style.display = 'none';
        if (adminBtn) adminBtn.style.display = 'inline-block';

        const logoutBtn = document.createElement('button');
        logoutBtn.id = 'logoutBtn';
        logoutBtn.textContent = 'Выйти';
        logoutBtn.style.cssText = 'margin-left: 10px; padding: 8px 15px; background: #dc3545; color: #fff; border: none; border-radius: 4px; cursor: pointer;';
        logoutBtn.addEventListener('click', () => {
            localStorage.removeItem('userRole');
            localStorage.removeItem('isAdminLoggedIn');
            if (adminBtn) adminBtn.style.display = 'none';
            window.location.href = apiBase + 'logout.php';
        });

        if (headerContainer) {
            headerContainer.appendChild(logoutBtn);
        }
    } else {
        if (loginLink) loginLink.style.display = 'inline-block';
        if (adminBtn) adminBtn.style.display = 'none';
    }

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
                const response = await fetch(apiBase + 'login.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ login, password })
                });
                const data = await response.json();

                if (data.success) {
                    localStorage.setItem('userRole', data.role);
                    localStorage.removeItem('isAdminLoggedIn');
                    loginModal.style.display = 'none';
                    loginLink.style.display = 'none';
                    if (adminBtn) adminBtn.style.display = 'inline-block';
                    loginMessage.textContent = '';
                    window.location.reload();
                } else {
                    loginMessage.textContent = data.message;
                    loginMessage.className = 'message error';
                }
            } catch {
                loginMessage.textContent = 'Ошибка соединения с сервером';
                loginMessage.className = 'message error';
            }
        });
    }
});
