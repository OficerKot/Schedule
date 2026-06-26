<?php
session_start();
$isAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/general.css">
    <link rel="stylesheet" href="../assets/css/schedule.css">
    <title>Справочники</title>
    <style>

    .adminMenu {
        display: flex;
        gap: 8px;
        margin: 20px 0 16px;
        flex-wrap: wrap;
    }
    .adminMenu button {
        padding: 8px 20px;
        background: var(--surface-2);
        border: 1px solid var(--border-mid);
        border-radius: var(--r-sm);
        color: var(--t-secondary);
        font-family: var(--ff);
        font-size: 0.85rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.15s;
    }
    .adminMenu button:hover {
        background: var(--surface-3);
        color: var(--t-primary);
    }
    .adminMenu button.active {
        background: var(--accent-dim);
        color: var(--t-accent);
        border-color: var(--accent);
        font-weight: 600;
        box-shadow: 0 0 0 1px var(--accent);
    }

    .adminSection h2 {
        font-size: 1.2rem;
        margin-bottom: 12px;
        color: var(--t-primary);
    }

    /* Кнопка "Добавить" над таблицами */
    .adminSection > button {
        padding: 6px 18px;
        background: var(--surface-2);
        border: 1px solid var(--border-mid);
        border-radius: var(--r-sm);
        color: var(--t-secondary);
        font-family: var(--ff);
        font-size: 0.82rem;
        font-weight: 500;
        cursor: pointer;
        transition: 0.15s;
        margin-bottom: 12px;
    }
    .adminSection > button:hover {
        background: var(--surface-3);
        color: var(--t-primary);
    }

    /* Кнопка "Обновить таблицу" */
    #scheduleSection > div > button {
        padding: 6px 18px;
        background: var(--surface-2);
        border: 1px solid var(--border-mid);
        border-radius: var(--r-sm);
        color: var(--t-secondary);
        font-family: var(--ff);
        font-size: 0.82rem;
        cursor: pointer;
        transition: 0.15s;
        margin-bottom: 8px;
    }
    #scheduleSection > div > button:hover {
        background: var(--surface-3);
        color: var(--t-primary);
    }

    /* Кнопки в модалках (дублируем из general.css на случай, если не подхватится) */
    .modal-actions .btn-save {
        background: var(--accent);
        color: #fff;
    }
    .modal-actions .btn-save:hover {
        background: #6a91ff;
    }
    .modal-actions .btn-cancel {
        background: var(--surface-3);
        color: var(--t-secondary);
        border: 1px solid var(--border-mid) !important;
    }
    .modal-actions .btn-cancel:hover {
        color: var(--t-primary);
    }

    /* Убираем лишние отступы внутри модалок */
    .modal-box .adminForm {
        padding: 8px 0;
        background: transparent;
        border: none;
        margin-bottom: 0;
    }

    /* Таблицы в админке — наследуются от general.css, но добавим компактность */
    #scheduleTable th,
    #teachersTable th,
    #groupsTable th,
    #roomsTable th,
    #disciplinesTable th {
        font-size: 0.7rem;
        padding: 6px 10px;
    }
    #scheduleTable td,
    #teachersTable td,
    #groupsTable td,
    #roomsTable td,
    #disciplinesTable td {
        padding: 6px 10px;
        font-size: 0.8rem;
    }

    /* Кнопки действий в таблицах (уже есть в general.css, но если нет — продублируем) */
    .btn-small {
        padding: 3px 8px;
        margin: 0 2px;
        font-size: 0.7rem;
        font-weight: 600;
        border: none;
        border-radius: var(--r-sm);
        cursor: pointer;
        transition: 0.1s;
    }
    .btn-small:hover {
        filter: brightness(1.2);
    }
    .btn-edit {
        background: rgba(240, 168, 54, 0.2);
        color: #f0a836;
        border: 1px solid rgba(240, 168, 54, 0.3);
    }
    .btn-delete {
        background: rgba(224, 95, 95, 0.2);
        color: #e05f5f;
        border: 1px solid rgba(224, 95, 95, 0.3);
    }

    /* Сообщение об ошибке/успехе в админке */
    #adminMessage {
        margin: 10px 0;
    }

    /* Адаптив для модалок */
    @media (max-width: 600px) {
        .modal-box {
            padding: 16px;
        }
        .adminForm {
            grid-template-columns: 1fr !important;
        }
    }
</style>
</head>

<body>
    <?php include "../includes/header.php"; ?>

    <div class="container" style="max-width: 1400px; margin: 20px auto;">
        <h1>Справочники</h1>
        
        <div class="adminMenu">
            <button class="active" onclick="showSection('schedule', this)">Расписание</button>
            <button onclick="showSection('teachers', this)">Преподаватели</button>
            <button onclick="showSection('groups', this)">Группы</button>
            <button onclick="showSection('rooms', this)">Аудитории</button>
            <button onclick="showSection('disciplines', this)">Дисциплины</button>
        </div>

        <!-- РАСПИСАНИЕ -->
        <div id="scheduleSection" class="adminSection">
            <h2>Расписание</h2>
            <?php if ($isAdmin): ?>
            <div class="adminForm">
                <div class="form-group">
                    <label>Дата</label>
                    <input type="date" id="adminDate">
                </div>
                <div class="form-group">
                    <label>Группа</label>
                    <select id="adminGroup"></select>
                </div>
                <div class="form-group">
                    <label>Преподаватель</label>
                    <select id="adminTeacher"></select>
                </div>
                <div class="form-group">
                    <label>Аудитория</label>
                    <select id="adminRoom"></select>
                </div>
                <div class="form-group">
                    <label>Дисциплина</label>
                    <select id="adminDiscipline"></select>
                </div>
                <div class="form-group">
                    <label>Тип занятия</label>
                    <select id="adminLessonType">
                        <option value="1">Лекция</option>
                        <option value="2">Практика</option>
                        <option value="3">Лабораторная работа</option>
                        <option value="4">Зачёт</option>
                        <option value="5">Экзамен</option>
                        <option value="6">Консультация</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Пара</label>
                    <select id="adminPeriod">
                        <option value="1">1 (8:30-10:00)</option>
                        <option value="2">2 (10:10-11:40)</option>
                        <option value="3">3 (11:50-13:20)</option>
                        <option value="4">4 (13:30-15:00)</option>
                        <option value="5">5 (15:10-16:40)</option>
                        <option value="6">6 (16:50-18:20)</option>
                        <option value="7">7 (18:30-19:00)</option>
                        <option value="8">8 (19:10-20:40)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Неделя</label>
                    <select id="adminWeekType">
                        <option value="all">Любая</option>
                        <option value="even">Чётная</option>
                        <option value="odd">Нечётная</option>
                    </select>
                </div>
                <button onclick="addLesson()">Добавить занятие</button>
            </div>
            <?php endif; ?>
            <div id="adminMessage" class="message"></div>
            <div style="margin-top: 20px;">
                <button onclick="loadScheduleTable()">Обновить таблицу</button>
                <table id="scheduleTable">
                    <thead>
                        <tr>
                            <th>Дата</th>
                            <th>Группа</th>
                            <th>Дисциплина</th>
                            <th>Тип</th>
                            <th>Преподаватель</th>
                            <th>Аудитория</th>
                            <th>Пара</th>
                            <th>Неделя</th>
                            <?php if ($isAdmin): ?><th>Действия</th><?php endif; ?>
                        </tr>
                    </thead>
                    <tbody id="scheduleTableBody"></tbody>
                </table>
            </div>
        </div>

        <!-- ПРЕПОДАВАТЕЛИ -->
        <div id="teachersSection" class="adminSection" style="display: none;">
            <h2>Преподаватели</h2>
            <?php if ($isAdmin): ?>
            <button onclick="openTeacherModal()" style="margin-bottom: 10px;">Добавить преподавателя</button>
            <?php endif; ?>
            <table id="teachersTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>ФИО</th>
                        <th>Должность</th>
                        <th>Кафедра</th>
                        <?php if ($isAdmin): ?><th>Действия</th><?php endif; ?>
                    </tr>
                </thead>
                <tbody id="teachersTableBody"></tbody>
            </table>
        </div>

        <!-- ГРУППЫ -->
        <div id="groupsSection" class="adminSection" style="display: none;">
            <h2>Группы</h2>
            <?php if ($isAdmin): ?>
            <button onclick="openGroupModal()" style="margin-bottom: 10px;">Добавить группу</button>
            <?php endif; ?>
            <table id="groupsTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Название</th>
                        <th>Кол-во студентов</th>
                        <?php if ($isAdmin): ?><th>Действия</th><?php endif; ?>
                    </tr>
                </thead>
                <tbody id="groupsTableBody"></tbody>
            </table>
        </div>

        <!-- АУДИТОРИИ -->
        <div id="roomsSection" class="adminSection" style="display: none;">
            <h2>Аудитории</h2>
            <?php if ($isAdmin): ?>
            <button onclick="openRoomModal()" style="margin-bottom: 10px;">Добавить аудиторию</button>
            <?php endif; ?>
            <table id="roomsTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Корпус</th>
                        <th>Номер</th>
                        <th>Тип</th>
                        <th>Вместимость</th>
                        <?php if ($isAdmin): ?><th>Действия</th><?php endif; ?>
                    </tr>
                </thead>
                <tbody id="roomsTableBody"></tbody>
            </table>
        </div>

        <!-- ДИСЦИПЛИНЫ -->
        <div id="disciplinesSection" class="adminSection" style="display: none;">
            <h2>Дисциплины</h2>
            <?php if ($isAdmin): ?>
            <button onclick="openDisciplineModal()" style="margin-bottom: 10px;">Добавить дисциплину</button>
            <?php endif; ?>
            <table id="disciplinesTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Название</th>
                        <th>Часы лекций</th>
                        <th>Часы практики</th>
                        <th>Часы лабораторных</th>
                        <th>Форма отчёта</th>
                        <?php if ($isAdmin): ?><th>Действия</th><?php endif; ?>
                    </tr>
                </thead>
                <tbody id="disciplinesTableBody"></tbody>
            </table>
        </div>
    </div>

    <!-- МОДАЛЬНОЕ ОКНО ДЛЯ ПРЕПОДАВАТЕЛЕЙ -->
    <div id="teacherModal" class="modal-overlay">
        <div class="modal-box">
            <h3 id="teacherModalTitle">Добавить преподавателя</h3>
            <input type="hidden" id="teacherEditId">
            <div class="adminForm" style="grid-template-columns: 1fr;">
                <div class="form-group">
                    <label>Фамилия</label>
                    <input type="text" id="teacherLastName">
                </div>
                <div class="form-group">
                    <label>Имя</label>
                    <input type="text" id="teacherFirstName">
                </div>
                <div class="form-group">
                    <label>Отчество</label>
                    <input type="text" id="teacherMiddleName">
                </div>
                <div class="form-group">
                    <label>Должность</label>
                    <input type="text" id="teacherPosition">
                </div>
                <div class="form-group">
                    <label>Кафедра</label>
                    <input type="text" id="teacherChair">
                </div>
                <div class="form-group">
                    <label>Учёная степень</label>
                    <input type="text" id="teacherDegree">
                </div>
                <div class="form-group">
                    <label>Учёное звание</label>
                    <input type="text" id="titleLabel">
                </div>
            </div>
            <div class="modal-actions">
                <button class="btn-cancel" onclick="closeTeacherModal()">Отмена</button>
                <button class="btn-save" onclick="saveTeacher()">Сохранить</button>
            </div>
        </div>
    </div>

    <!-- МОДАЛЬНОЕ ОКНО ДЛЯ ГРУПП -->
    <div id="groupModal" class="modal-overlay">
        <div class="modal-box">
            <h3 id="groupModalTitle">Добавить группу</h3>
            <input type="hidden" id="groupEditId">
            <div class="adminForm" style="grid-template-columns: 1fr;">
                <div class="form-group">
                    <label>Название группы</label>
                    <input type="text" id="groupName">
                </div>
                <div class="form-group">
                    <label>Количество студентов</label>
                    <input type="number" id="groupStudents">
                </div>
            </div>
            <div class="modal-actions">
                <button class="btn-cancel" onclick="closeGroupModal()">Отмена</button>
                <button class="btn-save" onclick="saveGroup()">Сохранить</button>
            </div>
        </div>
    </div>

    <!-- МОДАЛЬНОЕ ОКНО ДЛЯ АУДИТОРИЙ -->
    <div id="roomModal" class="modal-overlay">
        <div class="modal-box">
            <h3 id="roomModalTitle">Добавить аудиторию</h3>
            <input type="hidden" id="roomEditId">
            <div class="adminForm" style="grid-template-columns: 1fr;">
                <div class="form-group">
                    <label>Корпус</label>
                    <input type="text" id="roomBuilding">
                </div>
                <div class="form-group">
                    <label>Номер аудитории</label>
                    <input type="text" id="roomNumber">
                </div>
                <div class="form-group">
                    <label>Тип</label>
                    <select id="roomType">
                        <option value="lecture">Лекционная</option>
                        <option value="practical">Практическая</option>
                        <option value="lab">Лаборатория</option>
                        <option value="computer">Компьютерный класс</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Вместимость</label>
                    <input type="number" id="roomSeats">
                </div>
            </div>
            <div class="modal-actions">
                <button class="btn-cancel" onclick="closeRoomModal()">Отмена</button>
                <button class="btn-save" onclick="saveRoom()">Сохранить</button>
            </div>
        </div>
    </div>

    <!-- МОДАЛЬНОЕ ОКНО ДЛЯ ДИСЦИПЛИН -->
    <div id="disciplineModal" class="modal-overlay">
        <div class="modal-box">
            <h3 id="disciplineModalTitle">Добавить дисциплину</h3>
            <input type="hidden" id="disciplineEditId">
            <div class="adminForm" style="grid-template-columns: 1fr;">
                <div class="form-group">
                    <label>Название дисциплины</label>
                    <input type="text" id="disciplineName">
                </div>
                <div class="form-group">
                    <label>Часы лекций</label>
                    <input type="number" id="disciplineLectures">
                </div>
                <div class="form-group">
                    <label>Часы практики</label>
                    <input type="number" id="disciplinePractice">
                </div>
                <div class="form-group">
                    <label>Часы лабораторных</label>
                    <input type="number" id="disciplineLabs">
                </div>
                <div class="form-group">
                    <label>Форма отчёта</label>
                    <select id="disciplineType">
                        <option value="none">Без отчёта</option>
                        <option value="credit">Зачёт</option>
                        <option value="exam">Экзамен</option>
                    </select>
                </div>
            </div>
            <div class="modal-actions">
                <button class="btn-cancel" onclick="closeDisciplineModal()">Отмена</button>
                <button class="btn-save" onclick="saveDiscipline()">Сохранить</button>
            </div>
        </div>
    </div>

    <!-- МОДАЛЬНОЕ ОКНО ДЛЯ РЕДАКТИРОВАНИЯ ЗАНЯТИЯ -->
    <div id="lessonModal" class="modal-overlay">
        <div class="modal-box">
            <h3 id="lessonModalTitle">Редактировать занятие</h3>
            <input type="hidden" id="lessonEditId">
            <div class="adminForm" style="grid-template-columns: 1fr;">
                <div class="form-group">
                    <label>Дата</label>
                    <input type="date" id="lessonEditDate">
                </div>
                <div class="form-group">
                    <label>Группа</label>
                    <select id="lessonEditGroup"></select>
                </div>
                <div class="form-group">
                    <label>Преподаватель</label>
                    <select id="lessonEditTeacher"></select>
                </div>
                <div class="form-group">
                    <label>Аудитория</label>
                    <select id="lessonEditRoom"></select>
                </div>
                <div class="form-group">
                    <label>Дисциплина</label>
                    <select id="lessonEditDiscipline"></select>
                </div>
                <div class="form-group">
                    <label>Тип занятия</label>
                    <select id="lessonEditLessonType">
                        <option value="1">Лекция</option>
                        <option value="2">Практика</option>
                        <option value="3">Лабораторная работа</option>
                        <option value="4">Зачёт</option>
                        <option value="5">Экзамен</option>
                        <option value="6">Консультация</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Пара</label>
                    <select id="lessonEditPeriod">
                        <option value="1">1 (8:30-10:00)</option>
                        <option value="2">2 (10:10-11:40)</option>
                        <option value="3">3 (11:50-13:20)</option>
                        <option value="4">4 (13:30-15:00)</option>
                        <option value="5">5 (15:10-16:40)</option>
                        <option value="6">6 (16:50-18:20)</option>
                        <option value="7">7 (18:30-19:00)</option>
                        <option value="8">8 (19:10-20:40)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Неделя</label>
                    <select id="lessonEditWeekType">
                        <option value="all">Любая</option>
                        <option value="even">Чётная</option>
                        <option value="odd">Нечётная</option>
                    </select>
                </div>
            </div>
            <div class="modal-actions">
                <button class="btn-cancel" onclick="closeLessonModal()">Отмена</button>
                <button class="btn-save" onclick="saveLesson()">Сохранить</button>
            </div>
        </div>
    </div>

    <script>
        const API_BASE = '../api/';
        const isAdmin = <?= json_encode($isAdmin) ?>;
        const scheduleColspan = isAdmin ? 9 : 8;

        function showSection(section, btn) {
            document.querySelectorAll('.adminSection').forEach(el => el.style.display = 'none');
            document.getElementById(section + 'Section').style.display = 'block';
            
            document.querySelectorAll('.adminMenu button').forEach(b => b.classList.remove('active'));
            if (btn) btn.classList.add('active');

            if (section === 'schedule') {
                if (isAdmin) loadDropdowns();
                loadScheduleTable();
            }
            if (section === 'teachers') loadTeachersTable();
            if (section === 'groups') loadGroupsTable();
            if (section === 'rooms') loadRoomsTable();
            if (section === 'disciplines') loadDisciplinesTable();
        }

        function showMessage(text, success) {
            const msg = document.getElementById('adminMessage');
            if (msg) {
                msg.textContent = text;
                msg.className = 'message ' + (success ? 'success' : 'error');
                setTimeout(() => msg.textContent = '', 3000);
            }
        }

        async function loadDropdowns() {
            if (!isAdmin) return;
            try {
                console.log('Загрузка справочников...');
                const urls = [
                    API_BASE + 'get_groups.php',
                    API_BASE + 'get_teachers.php',
                    API_BASE + 'get_rooms.php',
                    API_BASE + 'get_disciplines.php'
                ];
                
                console.log('URLs:', urls);
                
                const responses = await Promise.all(urls.map(url => fetch(url)));
                const groups = await responses[0].json();
                const teachers = await responses[1].json();
                const rooms = await responses[2].json();
                const disciplines = await responses[3].json();

                console.log('Данные загружены:', groups, teachers);

                if (!Array.isArray(groups)) {
                    console.error('groups не является массивом:', groups);
                    showMessage('Ошибка загрузки групп', false);
                    return;
                }
                
                const groupSelect = document.getElementById('adminGroup');
                if (groupSelect) {
                    groupSelect.innerHTML = '<option value="">-- Выберите группу --</option>';
                    groups.forEach(g => {
                        if (g && g.group_id) {
                            const opt = document.createElement('option');
                            opt.value = g.group_id;
                            opt.textContent = g.name;
                            groupSelect.appendChild(opt);
                        }
                    });
                    console.log('Выбран групп загружен:', groupSelect.options.length, 'опций');
                }

                if (!Array.isArray(teachers)) {
                    console.error('teachers не является массивом:', teachers);
                }

                const teacherSelect = document.getElementById('adminTeacher');
                if (teacherSelect) {
                    teacherSelect.innerHTML = '<option value="">-- Выберите преподавателя --</option>';
                    (Array.isArray(teachers) ? teachers : []).forEach(t => {
                        if (t && t.teacher_id) {
                            const opt = document.createElement('option');
                            opt.value = t.teacher_id;
                            opt.textContent = `${t.last_name} ${t.first_name} ${t.middle_name || ''}`;
                            teacherSelect.appendChild(opt);
                        }
                    });
                }

                if (!Array.isArray(rooms)) {
                    console.error('rooms не является массивом:', rooms);
                }

                const roomSelect = document.getElementById('adminRoom');
                if (roomSelect) {
                    roomSelect.innerHTML = '<option value="">-- Выберите аудиторию --</option>';
                    (Array.isArray(rooms) ? rooms : []).forEach(r => {
                        if (r && r.room_id) {
                            const opt = document.createElement('option');
                            opt.value = r.room_id;
                            opt.textContent = `${r.building}-${r.room_number} (${r.seats} мест)`;
                            roomSelect.appendChild(opt);
                        }
                    });
                }

                if (!Array.isArray(disciplines)) {
                    console.error('disciplines не является массивом:', disciplines);
                }

                const discSelect = document.getElementById('adminDiscipline');
                if (discSelect) {
                    discSelect.innerHTML = '<option value="">-- Выберите дисциплину --</option>';
                    (Array.isArray(disciplines) ? disciplines : []).forEach(d => {
                        if (d && d.discipline_id) {
                            const opt = document.createElement('option');
                            opt.value = d.discipline_id;
                            opt.textContent = d.discipline_name;
                            discSelect.appendChild(opt);
                        }
                    });
                }
            } catch (err) {
                console.error('Ошибка загрузки данных:', err);
                showMessage('Ошибка загрузки справочников: ' + err.message, false);
            }
        }

        async function addLesson() {
            const data = {
                date: document.getElementById('adminDate').value,
                group_id: document.getElementById('adminGroup').value,
                teacher_id: document.getElementById('adminTeacher').value,
                room_id: document.getElementById('adminRoom').value,
                discipline_id: document.getElementById('adminDiscipline').value,
                lesson_type_id: document.getElementById('adminLessonType').value,
                period_id: document.getElementById('adminPeriod').value,
                week_type: document.getElementById('adminWeekType').value
            };

            if (!data.date || !data.group_id || !data.teacher_id || !data.room_id) {
                showMessage('Заполните все обязательные поля', false);
                return;
            }

            try {
                const response = await fetch(API_BASE + 'add_lesson.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });
                const result = await response.json();
                showMessage(result.message, result.success);
                if (result.success) {
                    loadDropdowns();
                    loadScheduleTable();
                }
            } catch (err) {
                showMessage('Ошибка сети', false);
            }
        }

        async function loadScheduleTable() {
            try {
                console.log('Загрузка расписания...');
                const response = await fetch(API_BASE + 'get_all_lessons.php');
                
                if (!response.ok) {
                    console.error('HTTP ошибка:', response.status, response.statusText);
                    throw new Error('HTTP error: ' + response.status);
                }
                
                const lessons = await response.json();
                
                console.log('Загружено занятий:', lessons.length);
                console.log('Первые занятия:', lessons.slice(0, 3));
                
                const tbody = document.getElementById('scheduleTableBody');
                if (!tbody) {
                    console.error('Таблица scheduleTableBody не найдена');
                    return;
                }
                
                tbody.innerHTML = '';
                
                if (!Array.isArray(lessons) || lessons.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="' + scheduleColspan + '">Нет занятий</td></tr>';
                    return;
                }
                
                lessons.forEach(l => {
                    if (!l || !l.card_id) return;
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td>${l.semester_date || '-'}</td>
                        <td>${l.group_name || '-'}</td>
                        <td>${l.discipline_name || '-'}</td>
                        <td>${l.lesson_type || '-'}</td>
                        <td>${l.teacher_name || '-'}</td>
                        <td>${l.building || ''}-${l.room_number || '-'}</td>
                        <td>${l.period_number || '-'}</td>
                        <td>${l.week_type || '-'}</td>
                        ${
                            isAdmin
                            ? `
                            <td>
                                <button class="btn-small btn-edit" onclick="editLesson(${l.card_id})">Ред.</button>
                                <button class="btn-small btn-delete" onclick="deleteLesson(${l.card_id})">Удалить</button>
                            </td>
                            `
                            : ''
                        }
                    `;
                    tbody.appendChild(tr);
                });
            } catch (err) {
                console.error('Ошибка загрузки расписания:', err);
                showMessage('Ошибка загрузки расписания: ' + err.message, false);
            }
        }

        async function deleteLesson(id) {
            if (!confirm('Удалить это занятие?')) return;
            
            try {
                const response = await fetch(API_BASE + 'delete_lesson.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ card_id: id })
                });
                const result = await response.json();
                showMessage(result.message, result.success);
                if (result.success) loadScheduleTable();
            } catch (err) {
                showMessage('Ошибка сети', false);
            }
        }

        // === РЕДАКТИРОВАНИЕ ЗАНЯТИЯ ===
        async function editLesson(id) {
            try {
                const response = await fetch(API_BASE + 'get_lesson.php?id=' + id);
                const lesson = await response.json();
                
                document.getElementById('lessonEditId').value = lesson.card_id;
                document.getElementById('lessonEditDate').value = lesson.semester_date;
                document.getElementById('lessonEditLessonType').value = lesson.lesson_type_id;
                document.getElementById('lessonEditPeriod').value = lesson.period_id;
                document.getElementById('lessonEditWeekType').value = lesson.week_type;
                
                // Загружаем справочники для выпадающих списков
                const [groups, teachers, rooms, disciplines] = await Promise.all([
                    fetch(API_BASE + 'get_groups.php').then(r => r.json()),
                    fetch(API_BASE + 'get_teachers.php').then(r => r.json()),
                    fetch(API_BASE + 'get_rooms.php').then(r => r.json()),
                    fetch(API_BASE + 'get_disciplines.php').then(r => r.json())
                ]);

                const groupSelect = document.getElementById('lessonEditGroup');
                groupSelect.innerHTML = '';
                groups.forEach(g => {
                    const opt = document.createElement('option');
                    opt.value = g.group_id;
                    opt.textContent = g.name;
                    if (g.group_id == lesson.group_id) opt.selected = true;
                    groupSelect.appendChild(opt);
                });

                const teacherSelect = document.getElementById('lessonEditTeacher');
                teacherSelect.innerHTML = '';
                teachers.forEach(t => {
                    const opt = document.createElement('option');
                    opt.value = t.teacher_id;
                    opt.textContent = `${t.last_name} ${t.first_name} ${t.middle_name || ''}`;
                    if (t.teacher_id == lesson.teacher_id) opt.selected = true;
                    teacherSelect.appendChild(opt);
                });

                const roomSelect = document.getElementById('lessonEditRoom');
                roomSelect.innerHTML = '';
                rooms.forEach(r => {
                    const opt = document.createElement('option');
                    opt.value = r.room_id;
                    opt.textContent = `${r.building}-${r.room_number} (${r.seats} мест)`;
                    if (r.room_id == lesson.room_id) opt.selected = true;
                    roomSelect.appendChild(opt);
                });

                const discSelect = document.getElementById('lessonEditDiscipline');
                discSelect.innerHTML = '';
                disciplines.forEach(d => {
                    const opt = document.createElement('option');
                    opt.value = d.discipline_id;
                    opt.textContent = d.discipline_name;
                    if (d.discipline_id == lesson.discipline_id) opt.selected = true;
                    discSelect.appendChild(opt);
                });
                
                document.getElementById('lessonModalTitle').textContent = 'Редактировать занятие';
                document.getElementById('lessonModal').classList.add('active');
            } catch (err) {
                console.error('Ошибка:', err);
                showMessage('Ошибка загрузки данных занятия', false);
            }
        }

        function closeLessonModal() {
            document.getElementById('lessonModal').classList.remove('active');
        }

        async function saveLesson() {
            const data = {
                card_id: document.getElementById('lessonEditId').value,
                date: document.getElementById('lessonEditDate').value,
                group_id: document.getElementById('lessonEditGroup').value,
                teacher_id: document.getElementById('lessonEditTeacher').value,
                room_id: document.getElementById('lessonEditRoom').value,
                discipline_id: document.getElementById('lessonEditDiscipline').value,
                lesson_type_id: document.getElementById('lessonEditLessonType').value,
                period_id: document.getElementById('lessonEditPeriod').value,
                week_type: document.getElementById('lessonEditWeekType').value
            };

            try {
                const response = await fetch(API_BASE + 'save_lesson.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });
                const result = await response.json();
                showMessage(result.message, result.success);
                if (result.success) {
                    closeLessonModal();
                    loadScheduleTable();
                }
            } catch (err) {
                showMessage('Ошибка сети', false);
            }
        }

        // === ПРЕПОДАВАТЕЛИ ===
        async function loadTeachersTable() {
            try {
                const response = await fetch(API_BASE + 'get_teachers.php');
                const teachers = await response.json();
                
                const tbody = document.getElementById('teachersTableBody');
                tbody.innerHTML = '';
                
                if (Array.isArray(teachers)) {
                    teachers.forEach(t => {
                        if (!t || !t.teacher_id) return;
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td>${t.teacher_id}</td>
                            <td>${t.last_name} ${t.first_name} ${t.middle_name || ''}</td>
                            <td>${t.position || '-'}</td>
                            <td>${t.chair || '-'}</td>
                            ${
                                isAdmin
                                ? `
                                <td>
                                    <button class="btn-small btn-edit" onclick="editTeacher(${t.teacher_id})">Ред.</button>
                                    <button class="btn-small btn-delete" onclick="deleteTeacher(${t.teacher_id})">Удл.</button>
                                </td>
                                `
                                : ''
                            }
                        `;
                        tbody.appendChild(tr);
                    });
                }
            } catch (err) {
                console.error('Ошибка загрузки преподавателей:', err);
            }
        }

        function openTeacherModal() {
            document.getElementById('teacherEditId').value = '';
            document.getElementById('teacherLastName').value = '';
            document.getElementById('teacherFirstName').value = '';
            document.getElementById('teacherMiddleName').value = '';
            document.getElementById('teacherPosition').value = '';
            document.getElementById('teacherChair').value = '';
            document.getElementById('teacherDegree').value = '';
            document.getElementById('titleLabel').value = '';
            document.getElementById('teacherModalTitle').textContent = 'Добавить преподавателя';
            document.getElementById('teacherModal').classList.add('active');
        }

        function closeTeacherModal() {
            document.getElementById('teacherModal').classList.remove('active');
        }

        async function editTeacher(id) {
            try {
                const response = await fetch(API_BASE + 'get_teacher.php?id=' + id);
                const teacher = await response.json();
                
                document.getElementById('teacherEditId').value = teacher.teacher_id;
                document.getElementById('teacherLastName').value = teacher.last_name;
                document.getElementById('teacherFirstName').value = teacher.first_name;
                document.getElementById('teacherMiddleName').value = teacher.middle_name || '';
                document.getElementById('teacherPosition').value = teacher.position || '';
                document.getElementById('teacherChair').value = teacher.chair || '';
                document.getElementById('teacherDegree').value = teacher.degree || '';
                document.getElementById('titleLabel').value = teacher.title || '';
                document.getElementById('teacherModalTitle').textContent = 'Редактировать преподавателя';
                document.getElementById('teacherModal').classList.add('active');
            } catch (err) {
                console.error('Ошибка:', err);
            }
        }

        async function saveTeacher() {
            const data = {
                teacher_id: document.getElementById('teacherEditId').value,
                last_name: document.getElementById('teacherLastName').value,
                first_name: document.getElementById('teacherFirstName').value,
                middle_name: document.getElementById('teacherMiddleName').value,
                position: document.getElementById('teacherPosition').value,
                chair: document.getElementById('teacherChair').value,
                degree: document.getElementById('teacherDegree').value,
                title: document.getElementById('titleLabel').value
            };

            try {
                const response = await fetch(API_BASE + 'save_teacher.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });
                const result = await response.json();
                showMessage(result.message, result.success);
                if (result.success) {
                    closeTeacherModal();
                    loadTeachersTable();
                }
            } catch (err) {
                showMessage('Ошибка сети', false);
            }
        }

        async function deleteTeacher(id) {
            if (!confirm('Удалить преподавателя?')) return;
            
            try {
                const response = await fetch(API_BASE + 'delete_teacher.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ teacher_id: id })
                });
                const result = await response.json();
                showMessage(result.message, result.success);
                if (result.success) loadTeachersTable();
            } catch (err) {
                showMessage('Ошибка сети', false);
            }
        }

        // === ГРУППЫ ===
        async function loadGroupsTable() {
            try {
                const response = await fetch(API_BASE + 'get_groups.php');
                const groups = await response.json();
                
                const tbody = document.getElementById('groupsTableBody');
                tbody.innerHTML = '';
                
                if (Array.isArray(groups)) {
                    groups.forEach(g => {
                        if (!g || !g.group_id) return;
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td>${g.group_id}</td>
                            <td>${g.name}</td>
                            <td>${g.students_count}</td>
                            ${
                                isAdmin
                                ? `
                            <td>
                                <button class="btn-small btn-edit" onclick="editGroup(${g.group_id})">Ред.</button>
                                <button class="btn-small btn-delete" onclick="deleteGroup(${g.group_id})">Удл.</button>
                            </td>
                                `
                                : ''
                            }
                        `;
                        tbody.appendChild(tr);
                    });
                }
            } catch (err) {
                console.error('Ошибка загрузки групп:', err);
            }
        }

        function openGroupModal() {
            document.getElementById('groupEditId').value = '';
            document.getElementById('groupName').value = '';
            document.getElementById('groupStudents').value = '';
            document.getElementById('groupModalTitle').textContent = 'Добавить группу';
            document.getElementById('groupModal').classList.add('active');
        }

        function closeGroupModal() {
            document.getElementById('groupModal').classList.remove('active');
        }

        async function editGroup(id) {
            try {
                const response = await fetch(API_BASE + 'get_group.php?id=' + id);
                const group = await response.json();
                
                document.getElementById('groupEditId').value = group.group_id;
                document.getElementById('groupName').value = group.name;
                document.getElementById('groupStudents').value = group.students_count;
                document.getElementById('groupModalTitle').textContent = 'Редактировать группу';
                document.getElementById('groupModal').classList.add('active');
            } catch (err) {
                console.error('Ошибка:', err);
            }
        }

        async function saveGroup() {
            const data = {
                group_id: document.getElementById('groupEditId').value,
                name: document.getElementById('groupName').value,
                students_count: parseInt(document.getElementById('groupStudents').value)
            };

            try {
                const response = await fetch(API_BASE + 'save_group.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });
                const result = await response.json();
                showMessage(result.message, result.success);
                if (result.success) {
                    closeGroupModal();
                    loadGroupsTable();
                }
            } catch (err) {
                showMessage('Ошибка сети', false);
            }
        }

        async function deleteGroup(id) {
            if (!confirm('Удалить группу?')) return;
            
            try {
                const response = await fetch(API_BASE + 'delete_group.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ group_id: id })
                });
                const result = await response.json();
                showMessage(result.message, result.success);
                if (result.success) loadGroupsTable();
            } catch (err) {
                showMessage('Ошибка сети', false);
            }
        }

        // === АУДИТОРИИ ===
        async function loadRoomsTable() {
            try {
                const response = await fetch(API_BASE + 'get_rooms.php');
                const rooms = await response.json();
                
                const tbody = document.getElementById('roomsTableBody');
                tbody.innerHTML = '';
                
                if (Array.isArray(rooms)) {
                    rooms.forEach(r => {
                        if (!r || !r.room_id) return;
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td>${r.room_id}</td>
                            <td>${r.building}</td>
                            <td>${r.room_number}</td>
                            <td>${r.room_type}</td>
                            <td>${r.seats}</td>
                            ${
                                isAdmin
                                ? `
                            <td>
                                <button class="btn-small btn-edit" onclick="editRoom(${r.room_id})">Ред.</button>
                                <button class="btn-small btn-delete" onclick="deleteRoom(${r.room_id})">Удл.</button>
                            </td>
                                `
                                : ''
                            }
                        `;
                        tbody.appendChild(tr);
                    });
                }
            } catch (err) {
                console.error('Ошибка загрузки аудиторий:', err);
            }
        }

        function openRoomModal() {
            document.getElementById('roomEditId').value = '';
            document.getElementById('roomBuilding').value = '';
            document.getElementById('roomNumber').value = '';
            document.getElementById('roomType').value = 'lecture';
            document.getElementById('roomSeats').value = '';
            document.getElementById('roomModalTitle').textContent = 'Добавить аудиторию';
            document.getElementById('roomModal').classList.add('active');
        }

        function closeRoomModal() {
            document.getElementById('roomModal').classList.remove('active');
        }

        async function editRoom(id) {
            try {
                const response = await fetch(API_BASE + 'get_room.php?id=' + id);
                const room = await response.json();
                
                document.getElementById('roomEditId').value = room.room_id;
                document.getElementById('roomBuilding').value = room.building;
                document.getElementById('roomNumber').value = room.room_number;
                document.getElementById('roomType').value = room.room_type;
                document.getElementById('roomSeats').value = room.seats;
                document.getElementById('roomModalTitle').textContent = 'Редактировать аудиторию';
                document.getElementById('roomModal').classList.add('active');
            } catch (err) {
                console.error('Ошибка:', err);
            }
        }

        async function saveRoom() {
            const data = {
                room_id: document.getElementById('roomEditId').value,
                building: document.getElementById('roomBuilding').value,
                room_number: document.getElementById('roomNumber').value,
                room_type: document.getElementById('roomType').value,
                seats: parseInt(document.getElementById('roomSeats').value)
            };

            try {
                const response = await fetch(API_BASE + 'save_room.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });
                const result = await response.json();
                showMessage(result.message, result.success);
                if (result.success) {
                    closeRoomModal();
                    loadRoomsTable();
                }
            } catch (err) {
                showMessage('Ошибка сети', false);
            }
        }

        async function deleteRoom(id) {
            if (!confirm('Удалить аудиторию?')) return;
            
            try {
                const response = await fetch(API_BASE + 'delete_room.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ room_id: id })
                });
                const result = await response.json();
                showMessage(result.message, result.success);
                if (result.success) loadRoomsTable();
            } catch (err) {
                showMessage('Ошибка сети', false);
            }
        }

        // === ДИСЦИПЛИНЫ ===
        async function loadDisciplinesTable() {
            try {
                const response = await fetch(API_BASE + 'get_disciplines.php');
                const disciplines = await response.json();
                
                const tbody = document.getElementById('disciplinesTableBody');
                tbody.innerHTML = '';
                
                if (Array.isArray(disciplines)) {
                    disciplines.forEach(d => {
                        if (!d || !d.discipline_id) return;
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td>${d.discipline_id}</td>
                            <td>${d.discipline_name}</td>
                            <td>${d.lecture_hours || 0}</td>
                            <td>${d.practice_hours || 0}</td>
                            <td>${d.lab_hours || 0}</td>
                            <td>${d.assessment_type || '-'}</td>
                            ${
                                isAdmin
                                ? `
                            <td>
                                <button class="btn-small btn-edit" onclick="editDiscipline(${d.discipline_id})">Ред.</button>
                                <button class="btn-small btn-delete" onclick="deleteDiscipline(${d.discipline_id})">Удл.</button>
                            </td>
                                `
                                : ''
                            }
                        `;
                        tbody.appendChild(tr);
                    });
                }
            } catch (err) {
                console.error('Ошибка загрузки дисциплин:', err);
            }
        }

        function openDisciplineModal() {
            document.getElementById('disciplineEditId').value = '';
            document.getElementById('disciplineName').value = '';
            document.getElementById('disciplineLectures').value = '';
            document.getElementById('disciplinePractice').value = '';
            document.getElementById('disciplineLabs').value = '';
            document.getElementById('disciplineType').value = 'none';
            document.getElementById('disciplineModalTitle').textContent = 'Добавить дисциплину';
            document.getElementById('disciplineModal').classList.add('active');
        }

        function closeDisciplineModal() {
            document.getElementById('disciplineModal').classList.remove('active');
        }

        async function editDiscipline(id) {
            try {
                const response = await fetch(API_BASE + 'get_discipline.php?id=' + id);
                const disc = await response.json();
                
                document.getElementById('disciplineEditId').value = disc.discipline_id;
                document.getElementById('disciplineName').value = disc.discipline_name;
                document.getElementById('disciplineLectures').value = disc.lecture_hours || 0;
                document.getElementById('disciplinePractice').value = disc.practice_hours || 0;
                document.getElementById('disciplineLabs').value = disc.lab_hours || 0;
                document.getElementById('disciplineType').value = disc.assessment_type || 'none';
                document.getElementById('disciplineModalTitle').textContent = 'Редактировать дисциплину';
                document.getElementById('disciplineModal').classList.add('active');
            } catch (err) {
                console.error('Ошибка:', err);
            }
        }

        async function saveDiscipline() {
            const data = {
                discipline_id: document.getElementById('disciplineEditId').value,
                discipline_name: document.getElementById('disciplineName').value,
                lecture_hours: parseInt(document.getElementById('disciplineLectures').value) || 0,
                practice_hours: parseInt(document.getElementById('disciplinePractice').value) || 0,
                lab_hours: parseInt(document.getElementById('disciplineLabs').value) || 0,
                assessment_type: document.getElementById('disciplineType').value
            };

            try {
                const response = await fetch(API_BASE + 'save_discipline.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });
                const result = await response.json();
                showMessage(result.message, result.success);
                if (result.success) {
                    closeDisciplineModal();
                    loadDisciplinesTable();
                }
            } catch (err) {
                showMessage('Ошибка сети', false);
            }
        }

        async function deleteDiscipline(id) {
            if (!confirm('Удалить дисциплину?')) return;
            
            try {
                const response = await fetch(API_BASE + 'delete_discipline.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ discipline_id: id })
                });
                const result = await response.json();
                showMessage(result.message, result.success);
                if (result.success) loadDisciplinesTable();
            } catch (err) {
                showMessage('Ошибка сети', false);
            }
        }

        // Инициализация при загрузке страницы
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Admin page initialized');
            if (isAdmin) loadDropdowns();
            loadScheduleTable();
        });
    </script>
    <script type="module" src="../assets/js/admin.js"></script>
</body>

</html>
