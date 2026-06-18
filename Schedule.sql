-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3307
-- Время создания: Июн 18 2026 г., 07:24
-- Версия сервера: 8.0.30
-- Версия PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `Schedule`
--

-- --------------------------------------------------------

--
-- Структура таблицы `academic_groups`
--

CREATE TABLE `academic_groups` (
  `academic_group_id` int NOT NULL,
  `code` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `direction` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Направление подготовки',
  `course` tinyint DEFAULT NULL COMMENT 'Курс (1-6)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `building_distances`
--

CREATE TABLE `building_distances` (
  `from_building` varchar(50) NOT NULL,
  `to_building` varchar(50) NOT NULL,
  `walk_minutes` tinyint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `calendar_days`
--

CREATE TABLE `calendar_days` (
  `day_id` int NOT NULL,
  `day_date` date NOT NULL,
  `is_working` tinyint(1) DEFAULT '1',
  `is_holiday` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `departments`
--

CREATE TABLE `departments` (
  `department_id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_id` int DEFAULT NULL,
  `level` enum('university','school','chair') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'chair'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `disciplines`
--

CREATE TABLE `disciplines` (
  `discipline_id` int NOT NULL,
  `discipline_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `discipline_hours`
--

CREATE TABLE `discipline_hours` (
  `discipline_id` int NOT NULL,
  `lecture_hours` int DEFAULT '0',
  `practice_hours` int DEFAULT '0',
  `lab_hours` int DEFAULT '0',
  `assessment_type` enum('credit','exam','none') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'none'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `groups`
--

CREATE TABLE `groups` (
  `group_id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Уникальное название (напр. Б9124-09.03.03-Пикд)',
  `students_count` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `group_academic_link`
--

CREATE TABLE `group_academic_link` (
  `group_id` int NOT NULL,
  `academic_group_id` int NOT NULL,
  `is_main` tinyint(1) DEFAULT '0' COMMENT 'Основная академическая группа'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `lesson_card`
--

CREATE TABLE `lesson_card` (
  `card_id` int NOT NULL,
  `semester_date` date NOT NULL COMMENT 'Дата начала семестра',
  `week_type` enum('even','odd','all') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'all' COMMENT 'Чётная/нечётная/любая неделя',
  `discipline_id` int NOT NULL,
  `lesson_type_id` int NOT NULL,
  `group_id` int NOT NULL,
  `teacher_id` int NOT NULL,
  `room_id` int NOT NULL,
  `period_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `lesson_types`
--

CREATE TABLE `lesson_types` (
  `lesson_type_id` int NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `duration_minutes` int DEFAULT NULL COMMENT 'Длительность в минутах'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `lesson_types`
--

INSERT INTO `lesson_types` (`lesson_type_id`, `name`, `duration_minutes`) VALUES
(1, 'Лекция', 90),
(2, 'Практическое занятие', 90),
(3, 'Лабораторная работа', 90),
(4, 'Зачёт', 10),
(5, 'Экзамен', 20),
(6, 'Консультация', 120);

-- --------------------------------------------------------

--
-- Структура таблицы `rooms`
--

CREATE TABLE `rooms` (
  `room_id` int NOT NULL,
  `building` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Корпус',
  `room_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Номер аудитории',
  `room_type` enum('lecture','practical','lab','computer') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `seats` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `schedule_changes`
--

CREATE TABLE `schedule_changes` (
  `change_id` int NOT NULL,
  `card_id` int DEFAULT NULL,
  `changed_by` int NOT NULL,
  `changed_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `action` enum('create','move','delete') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `old_data` json DEFAULT NULL,
  `new_data` json DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `teachers`
--

CREATE TABLE `teachers` (
  `teacher_id` int NOT NULL,
  `first_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `middle_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `school` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `department` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `chair` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `degree` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Учёная степень',
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Учёное звание',
  `position` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Должность',
  `type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `teacher_preferences`
--

CREATE TABLE `teacher_preferences` (
  `preference_id` int NOT NULL,
  `teacher_id` int NOT NULL,
  `day_of_week` tinyint DEFAULT NULL COMMENT '1=пн, ..., 6=сб',
  `period` tinyint DEFAULT NULL COMMENT '1-8',
  `preference_type` enum('prefer','avoid') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'prefer'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `time_periods`
--

CREATE TABLE `time_periods` (
  `period_id` int NOT NULL,
  `period_number` tinyint NOT NULL COMMENT 'Номер пары (1-8)',
  `time_range` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Время (напр. 8:30-10:00)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `user_id` int NOT NULL,
  `login` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','dispatcher','teacher','student') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `teacher_id` int DEFAULT NULL,
  `group_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `academic_groups`
--
ALTER TABLE `academic_groups`
  ADD PRIMARY KEY (`academic_group_id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Индексы таблицы `building_distances`
--
ALTER TABLE `building_distances`
  ADD PRIMARY KEY (`from_building`,`to_building`);

--
-- Индексы таблицы `calendar_days`
--
ALTER TABLE `calendar_days`
  ADD PRIMARY KEY (`day_id`),
  ADD UNIQUE KEY `day_date` (`day_date`);

--
-- Индексы таблицы `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`department_id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Индексы таблицы `disciplines`
--
ALTER TABLE `disciplines`
  ADD PRIMARY KEY (`discipline_id`);

--
-- Индексы таблицы `discipline_hours`
--
ALTER TABLE `discipline_hours`
  ADD PRIMARY KEY (`discipline_id`);

--
-- Индексы таблицы `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`group_id`);

--
-- Индексы таблицы `group_academic_link`
--
ALTER TABLE `group_academic_link`
  ADD PRIMARY KEY (`group_id`,`academic_group_id`),
  ADD KEY `academic_group_id` (`academic_group_id`);

--
-- Индексы таблицы `lesson_card`
--
ALTER TABLE `lesson_card`
  ADD PRIMARY KEY (`card_id`),
  ADD KEY `semester_date` (`semester_date`),
  ADD KEY `week_type` (`week_type`),
  ADD KEY `group_id` (`group_id`),
  ADD KEY `teacher_id` (`teacher_id`),
  ADD KEY `room_id` (`room_id`),
  ADD KEY `period_id` (`period_id`),
  ADD KEY `discipline_id` (`discipline_id`),
  ADD KEY `lesson_card_ibfk_2` (`lesson_type_id`);

--
-- Индексы таблицы `lesson_types`
--
ALTER TABLE `lesson_types`
  ADD PRIMARY KEY (`lesson_type_id`);

--
-- Индексы таблицы `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`room_id`);

--
-- Индексы таблицы `schedule_changes`
--
ALTER TABLE `schedule_changes`
  ADD PRIMARY KEY (`change_id`),
  ADD KEY `card_id` (`card_id`);

--
-- Индексы таблицы `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`teacher_id`);

--
-- Индексы таблицы `teacher_preferences`
--
ALTER TABLE `teacher_preferences`
  ADD PRIMARY KEY (`preference_id`),
  ADD KEY `teacher_id` (`teacher_id`);

--
-- Индексы таблицы `time_periods`
--
ALTER TABLE `time_periods`
  ADD PRIMARY KEY (`period_id`),
  ADD UNIQUE KEY `period_number` (`period_number`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `login` (`login`),
  ADD KEY `teacher_id` (`teacher_id`),
  ADD KEY `group_id` (`group_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `academic_groups`
--
ALTER TABLE `academic_groups`
  MODIFY `academic_group_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `calendar_days`
--
ALTER TABLE `calendar_days`
  MODIFY `day_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `departments`
--
ALTER TABLE `departments`
  MODIFY `department_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `disciplines`
--
ALTER TABLE `disciplines`
  MODIFY `discipline_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `groups`
--
ALTER TABLE `groups`
  MODIFY `group_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `lesson_card`
--
ALTER TABLE `lesson_card`
  MODIFY `card_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `lesson_types`
--
ALTER TABLE `lesson_types`
  MODIFY `lesson_type_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `rooms`
--
ALTER TABLE `rooms`
  MODIFY `room_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `schedule_changes`
--
ALTER TABLE `schedule_changes`
  MODIFY `change_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `teachers`
--
ALTER TABLE `teachers`
  MODIFY `teacher_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `teacher_preferences`
--
ALTER TABLE `teacher_preferences`
  MODIFY `preference_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `time_periods`
--
ALTER TABLE `time_periods`
  MODIFY `period_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `departments`
--
ALTER TABLE `departments`
  ADD CONSTRAINT `departments_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `departments` (`department_id`);

--
-- Ограничения внешнего ключа таблицы `discipline_hours`
--
ALTER TABLE `discipline_hours`
  ADD CONSTRAINT `discipline_hours_ibfk_1` FOREIGN KEY (`discipline_id`) REFERENCES `disciplines` (`discipline_id`);

--
-- Ограничения внешнего ключа таблицы `group_academic_link`
--
ALTER TABLE `group_academic_link`
  ADD CONSTRAINT `group_academic_link_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`group_id`),
  ADD CONSTRAINT `group_academic_link_ibfk_2` FOREIGN KEY (`academic_group_id`) REFERENCES `academic_groups` (`academic_group_id`);

--
-- Ограничения внешнего ключа таблицы `lesson_card`
--
ALTER TABLE `lesson_card`
  ADD CONSTRAINT `lesson_card_ibfk_1` FOREIGN KEY (`discipline_id`) REFERENCES `disciplines` (`discipline_id`),
  ADD CONSTRAINT `lesson_card_ibfk_2` FOREIGN KEY (`lesson_type_id`) REFERENCES `lesson_types` (`lesson_type_id`),
  ADD CONSTRAINT `lesson_card_ibfk_3` FOREIGN KEY (`group_id`) REFERENCES `groups` (`group_id`),
  ADD CONSTRAINT `lesson_card_ibfk_4` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`teacher_id`),
  ADD CONSTRAINT `lesson_card_ibfk_5` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`room_id`),
  ADD CONSTRAINT `lesson_card_ibfk_6` FOREIGN KEY (`period_id`) REFERENCES `time_periods` (`period_id`);

--
-- Ограничения внешнего ключа таблицы `schedule_changes`
--
ALTER TABLE `schedule_changes`
  ADD CONSTRAINT `schedule_changes_ibfk_1` FOREIGN KEY (`card_id`) REFERENCES `lesson_card` (`card_id`);

--
-- Ограничения внешнего ключа таблицы `teacher_preferences`
--
ALTER TABLE `teacher_preferences`
  ADD CONSTRAINT `teacher_preferences_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`teacher_id`);

--
-- Ограничения внешнего ключа таблицы `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`teacher_id`),
  ADD CONSTRAINT `users_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `groups` (`group_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
