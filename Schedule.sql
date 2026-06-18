-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Июн 17 2026 г., 13:02
-- Версия сервера: 5.6.51
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
  `academic_group_id` int(255) NOT NULL,
  `name` varchar(255) NOT NULL COMMENT 'ИКБО-01-22',
  `direction` varchar(255) NOT NULL COMMENT 'Направление подготовки'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Структура таблицы `buildings`
--

CREATE TABLE `buildings` (
  `building_id` int(255) NOT NULL,
  `name` varchar(255) NOT NULL COMMENT 'Корпус А, Корпус Б',
  `address` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Структура таблицы `building_distances`
--

CREATE TABLE `building_distances` (
  `distance_id` int(255) NOT NULL,
  `building_from` int(255) NOT NULL,
  `building_to` int(255) NOT NULL,
  `travel_time_minutes` int(10) NOT NULL COMMENT 'Время перехода в минутах'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Структура таблицы `disciplines`
--

CREATE TABLE `disciplines` (
  `discipline_id` int(255) NOT NULL,
  `discipline_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `groups`
--

CREATE TABLE `groups` (
  `group_id` int(255) NOT NULL,
  `group_code` int(255) NOT NULL,
  `discipline_id` int(255) NOT NULL,
  `students_count` int(255) NOT NULL,
  `academic_group_id` int(255) DEFAULT NULL,
  `parent_group_id` int(255) DEFAULT NULL COMMENT 'Для подгрупп',
  `is_flow` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `lessons_periods`
--

CREATE TABLE `lessons_periods` (
  `period_id` int(255) NOT NULL,
  `time` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `lesson_card`
--

CREATE TABLE `lesson_card` (
  `card_id` int(255) NOT NULL,
  `discipline_id` int(255) NOT NULL,
  `lesson_type_id` int(255) NOT NULL,
  `group_id` int(255) NOT NULL,
  `teacher_id` int(255) NOT NULL,
  `room_id` int(255) NOT NULL,
  `period_id` int(10) NOT NULL,
  `day_of_week` int(1) NOT NULL COMMENT '1-6 (ПН-СБ)',
  `week_number` int(2) NOT NULL DEFAULT '1' COMMENT 'Номер недели в семестре',
  `semester_id` int(255) NOT NULL,
  `date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `lesson_periods`
--

CREATE TABLE `lesson_periods` (
  `period_id` int(255) NOT NULL,
  `period` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `lesson_types`
--

CREATE TABLE `lesson_types` (
  `lesson_type_id` int(255) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `rooms`
--

CREATE TABLE `rooms` (
  `room_id` int(255) NOT NULL,
  `room_number` int(255) NOT NULL,
  `room_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `free_space_count` int(255) NOT NULL,
  `building_id` int(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `semesters`
--

CREATE TABLE `semesters` (
  `semester_id` int(255) NOT NULL,
  `name` varchar(255) NOT NULL COMMENT 'Осень 2026, Весна 2027',
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `is_active` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Структура таблицы `subdivisions`
--

CREATE TABLE `subdivisions` (
  `subdivision_id` int(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `parent_id` int(255) DEFAULT NULL COMMENT 'NULL = Университет, >0 = Школа/Кафедра'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Структура таблицы `teachers`
--

CREATE TABLE `teachers` (
  `teacher_id` int(255) NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subdivision_id` int(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `academic_groups`
--
ALTER TABLE `academic_groups`
  ADD PRIMARY KEY (`academic_group_id`);

--
-- Индексы таблицы `buildings`
--
ALTER TABLE `buildings`
  ADD PRIMARY KEY (`building_id`);

--
-- Индексы таблицы `building_distances`
--
ALTER TABLE `building_distances`
  ADD PRIMARY KEY (`distance_id`),
  ADD KEY `building_from` (`building_from`),
  ADD KEY `building_to` (`building_to`);

--
-- Индексы таблицы `disciplines`
--
ALTER TABLE `disciplines`
  ADD PRIMARY KEY (`discipline_id`);

--
-- Индексы таблицы `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`group_id`),
  ADD KEY `discipline_id` (`discipline_id`),
  ADD KEY `academic_group_id` (`academic_group_id`);

--
-- Индексы таблицы `lessons_periods`
--
ALTER TABLE `lessons_periods`
  ADD PRIMARY KEY (`period_id`);

--
-- Индексы таблицы `lesson_card`
--
ALTER TABLE `lesson_card`
  ADD PRIMARY KEY (`card_id`),
  ADD KEY `lesson_type_id` (`lesson_type_id`),
  ADD KEY `group_id` (`group_id`),
  ADD KEY `teacher_id` (`teacher_id`),
  ADD KEY `room_id` (`room_id`),
  ADD KEY `lesson_name_id` (`discipline_id`),
  ADD KEY `period_id` (`period_id`),
  ADD KEY `semester_id` (`semester_id`),
  ADD KEY `day_of_week` (`day_of_week`),
  ADD KEY `week_number` (`week_number`);

--
-- Индексы таблицы `lesson_periods`
--
ALTER TABLE `lesson_periods`
  ADD PRIMARY KEY (`period_id`);

--
-- Индексы таблицы `lesson_types`
--
ALTER TABLE `lesson_types`
  ADD PRIMARY KEY (`lesson_type_id`);

--
-- Индексы таблицы `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`room_id`),
  ADD KEY `building_id` (`building_id`);

--
-- Индексы таблицы `semesters`
--
ALTER TABLE `semesters`
  ADD PRIMARY KEY (`semester_id`);

--
-- Индексы таблицы `subdivisions`
--
ALTER TABLE `subdivisions`
  ADD PRIMARY KEY (`subdivision_id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Индексы таблицы `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`teacher_id`),
  ADD KEY `subdivision_id` (`subdivision_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `academic_groups`
--
ALTER TABLE `academic_groups`
  MODIFY `academic_group_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `buildings`
--
ALTER TABLE `buildings`
  MODIFY `building_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `building_distances`
--
ALTER TABLE `building_distances`
  MODIFY `distance_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `groups`
--
ALTER TABLE `groups`
  MODIFY `group_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `lessons_periods`
--
ALTER TABLE `lessons_periods`
  MODIFY `period_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `lesson_card`
--
ALTER TABLE `lesson_card`
  MODIFY `card_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `lesson_periods`
--
ALTER TABLE `lesson_periods`
  MODIFY `period_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `lesson_types`
--
ALTER TABLE `lesson_types`
  MODIFY `lesson_type_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `rooms`
--
ALTER TABLE `rooms`
  MODIFY `room_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `semesters`
--
ALTER TABLE `semesters`
  MODIFY `semester_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `subdivisions`
--
ALTER TABLE `subdivisions`
  MODIFY `subdivision_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `teachers`
--
ALTER TABLE `teachers`
  MODIFY `teacher_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `groups`
--
ALTER TABLE `groups`
  ADD CONSTRAINT `fk_groups_academic` FOREIGN KEY (`academic_group_id`) REFERENCES `academic_groups` (`academic_group_id`);

--
-- Ограничения внешнего ключа таблицы `lesson_card`
--
ALTER TABLE `lesson_card`
  ADD CONSTRAINT `fk_lesson_card_semester` FOREIGN KEY (`semester_id`) REFERENCES `semesters` (`semester_id`);

--
-- Ограничения внешнего ключа таблицы `rooms`
--
ALTER TABLE `rooms`
  ADD CONSTRAINT `fk_rooms_building` FOREIGN KEY (`building_id`) REFERENCES `buildings` (`building_id`);

--
-- Ограничения внешнего ключа таблицы `teachers`
--
ALTER TABLE `teachers`
  ADD CONSTRAINT `fk_teachers_subdivision` FOREIGN KEY (`subdivision_id`) REFERENCES `subdivisions` (`subdivision_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
