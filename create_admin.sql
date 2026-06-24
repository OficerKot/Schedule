-- ============================================
-- Создание первого администратора
-- ============================================
-- Выполните этот SQL-запрос в phpMyAdmin:
-- 1. Откройте phpMyAdmin
-- 2. Выберите базу данных Schedule
-- 3. Перейдите во вкладку "SQL"
-- 4. Вставьте и выполните запрос ниже
-- ============================================

-- Логин: admin
-- Пароль: admin123
-- (Обязательно смените пароль после первого входа!)

INSERT INTO users (login, password_hash, role) 
VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Проверка создания:
-- SELECT user_id, login, role FROM users WHERE login = 'admin';
