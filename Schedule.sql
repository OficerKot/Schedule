-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Июн 19 2026 г., 12:40
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

--
-- Дамп данных таблицы `academic_groups`
--

INSERT INTO `academic_groups` (`academic_group_id`, `code`, `direction`, `course`) VALUES
(1, 'Б9124', 'Программная инженерия', 1),
(2, 'Б9125', 'Программная инженерия', 1),
(3, 'Б9224', 'Программная инженерия', 2),
(4, 'Б9324', 'Программная инженерия', 3),
(5, 'Б9130', 'Прикладная математика', 1),
(6, 'Б9140', 'Экономика', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `building_distances`
--

CREATE TABLE `building_distances` (
  `from_building` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `to_building` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `walk_minutes` tinyint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `building_distances`
--

INSERT INTO `building_distances` (`from_building`, `to_building`, `walk_minutes`) VALUES
('A', 'A', 0),
('A', 'B', 5),
('A', 'C', 8),
('A', 'D', 12),
('A', 'E', 10),
('A', 'F', 15),
('A', 'G', 20),
('B', 'A', 5),
('B', 'B', 0),
('B', 'C', 5),
('B', 'D', 10),
('B', 'E', 8),
('B', 'F', 12),
('B', 'G', 18),
('C', 'A', 8),
('C', 'B', 5),
('C', 'C', 0),
('C', 'D', 7),
('C', 'E', 6),
('C', 'F', 10),
('C', 'G', 15),
('D', 'A', 12),
('D', 'B', 10),
('D', 'C', 7),
('D', 'D', 0),
('D', 'E', 12),
('D', 'F', 8),
('D', 'G', 10),
('E', 'A', 10),
('E', 'B', 8),
('E', 'C', 6),
('E', 'D', 12),
('E', 'E', 0),
('E', 'F', 15),
('E', 'G', 18),
('F', 'A', 15),
('F', 'B', 12),
('F', 'C', 10),
('F', 'D', 8),
('F', 'E', 15),
('F', 'F', 0),
('F', 'G', 8),
('G', 'A', 20),
('G', 'B', 18),
('G', 'C', 15),
('G', 'D', 10),
('G', 'E', 18),
('G', 'F', 8),
('G', 'G', 0);

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

--
-- Дамп данных таблицы `calendar_days`
--

INSERT INTO `calendar_days` (`day_id`, `day_date`, `is_working`, `is_holiday`) VALUES
(154, '2026-11-04', 0, 1),
(155, '2027-01-01', 0, 1),
(156, '2027-01-02', 0, 1),
(157, '2027-01-03', 0, 1),
(158, '2027-01-04', 0, 1),
(159, '2027-01-05', 0, 1),
(160, '2027-01-06', 0, 1),
(161, '2027-01-07', 0, 1),
(162, '2027-01-08', 0, 1),
(163, '2027-01-11', 0, 1),
(164, '2026-09-01', 1, 0),
(165, '2026-09-02', 1, 0),
(166, '2026-09-03', 1, 0),
(167, '2026-09-04', 1, 0),
(168, '2026-09-05', 1, 0),
(169, '2026-09-06', 1, 0),
(170, '2026-09-07', 0, 0),
(171, '2026-09-08', 1, 0),
(172, '2026-09-09', 1, 0),
(173, '2026-09-10', 1, 0),
(174, '2026-09-11', 1, 0),
(175, '2026-09-12', 1, 0),
(176, '2026-09-13', 1, 0),
(177, '2026-09-14', 1, 1),
(178, '2026-09-15', 0, 0),
(179, '2026-09-16', 1, 0),
(180, '2026-09-17', 1, 0),
(181, '2026-09-18', 1, 0),
(182, '2026-09-19', 1, 0),
(183, '2026-09-20', 1, 0),
(184, '2026-09-21', 1, 0),
(185, '2026-09-22', 0, 0),
(186, '2026-09-23', 1, 0),
(187, '2026-09-24', 1, 0),
(188, '2026-09-25', 1, 0),
(189, '2026-09-26', 1, 0),
(190, '2026-09-27', 1, 0),
(191, '2026-09-28', 1, 0),
(192, '2026-09-29', 0, 0),
(193, '2026-09-30', 1, 0),
(194, '2026-10-01', 1, 0),
(195, '2026-10-02', 1, 0),
(196, '2026-10-03', 1, 0),
(197, '2026-10-04', 1, 0),
(198, '2026-10-05', 1, 0),
(199, '2026-10-06', 0, 0),
(200, '2026-10-07', 1, 0),
(201, '2026-10-08', 1, 0),
(202, '2026-10-09', 1, 0),
(203, '2026-10-10', 1, 0),
(204, '2026-10-11', 1, 0),
(205, '2026-10-12', 1, 0),
(206, '2026-10-13', 0, 0),
(207, '2026-10-14', 1, 0),
(208, '2026-10-15', 1, 0),
(209, '2026-10-16', 1, 0),
(210, '2026-10-17', 1, 0),
(211, '2026-10-18', 1, 0),
(212, '2026-10-19', 1, 0),
(213, '2026-10-20', 0, 0),
(214, '2026-10-21', 1, 0),
(215, '2026-10-22', 1, 0),
(216, '2026-10-23', 1, 0),
(217, '2026-10-24', 1, 0),
(218, '2026-10-25', 1, 0),
(219, '2026-10-26', 1, 0),
(220, '2026-10-27', 0, 0),
(221, '2026-10-28', 1, 0),
(222, '2026-10-29', 1, 0),
(223, '2026-10-30', 1, 0),
(224, '2026-10-31', 1, 0),
(225, '2026-11-01', 1, 0),
(226, '2026-11-02', 1, 0),
(227, '2026-11-03', 0, 0),
(228, '2026-11-05', 1, 0),
(229, '2026-11-06', 1, 0),
(230, '2026-11-07', 1, 0),
(231, '2026-11-08', 1, 0),
(232, '2026-11-09', 1, 0),
(233, '2026-11-10', 1, 0),
(234, '2026-11-11', 0, 0),
(235, '2026-11-12', 1, 0),
(236, '2026-11-13', 1, 0),
(237, '2026-11-14', 1, 0),
(238, '2026-11-15', 1, 0),
(239, '2026-11-16', 1, 0),
(240, '2026-11-17', 1, 0),
(241, '2026-11-18', 0, 0),
(242, '2026-11-19', 1, 0),
(243, '2026-11-20', 1, 0),
(244, '2026-11-21', 1, 0),
(245, '2026-11-22', 1, 0),
(246, '2026-11-23', 1, 0),
(247, '2026-11-24', 1, 0),
(248, '2026-11-25', 0, 0),
(249, '2026-11-26', 1, 0),
(250, '2026-11-27', 1, 0),
(251, '2026-11-28', 1, 0),
(252, '2026-11-29', 1, 0),
(253, '2026-11-30', 1, 0),
(254, '2026-12-01', 1, 0),
(255, '2026-12-02', 0, 0),
(256, '2026-12-03', 1, 0),
(257, '2026-12-04', 1, 0),
(258, '2026-12-05', 1, 0),
(259, '2026-12-06', 1, 0),
(260, '2026-12-07', 1, 0),
(261, '2026-12-08', 1, 0),
(262, '2026-12-09', 0, 0),
(263, '2026-12-10', 1, 0),
(264, '2026-12-11', 1, 0),
(265, '2026-12-12', 1, 0),
(266, '2026-12-13', 1, 0),
(267, '2026-12-14', 1, 0),
(268, '2026-12-15', 1, 0),
(269, '2026-12-16', 0, 0),
(270, '2026-12-17', 1, 0),
(271, '2026-12-18', 1, 0),
(272, '2026-12-19', 1, 0),
(273, '2026-12-20', 1, 0),
(274, '2026-12-21', 1, 0),
(275, '2026-12-22', 1, 0),
(276, '2026-12-23', 0, 0),
(277, '2026-12-24', 1, 0),
(278, '2026-12-25', 1, 0),
(279, '2026-12-26', 1, 0),
(280, '2026-12-27', 1, 0),
(281, '2026-12-28', 1, 0),
(282, '2026-12-29', 1, 0),
(283, '2026-12-30', 0, 0),
(284, '2026-12-31', 1, 0),
(285, '2027-01-09', 1, 0),
(286, '2027-01-10', 1, 0),
(287, '2027-01-12', 1, 0),
(288, '2027-01-13', 1, 0),
(289, '2027-01-14', 1, 0),
(290, '2027-01-15', 1, 0),
(291, '2027-01-16', 1, 0),
(292, '2027-01-17', 0, 0),
(293, '2027-01-18', 1, 0),
(294, '2027-01-19', 1, 0),
(295, '2027-01-20', 1, 0),
(296, '2027-01-21', 1, 0),
(297, '2027-01-22', 1, 0),
(298, '2027-01-23', 1, 0),
(299, '2027-01-24', 0, 0),
(300, '2027-01-25', 1, 0),
(301, '2027-01-26', 1, 0),
(302, '2027-01-27', 1, 0),
(303, '2027-01-28', 1, 0),
(304, '2027-01-29', 1, 0),
(305, '2027-01-30', 1, 0),
(306, '2027-01-31', 0, 0);

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

--
-- Дамп данных таблицы `departments`
--

INSERT INTO `departments` (`department_id`, `name`, `parent_id`, `level`) VALUES
(1, 'Университет', NULL, 'university'),
(2, 'Школа ИМКТ', 1, 'school'),
(3, 'Школа Экономики', 1, 'school'),
(4, 'Кафедра математики', 2, 'chair'),
(5, 'Кафедра ИТ', 2, 'chair'),
(6, 'Кафедра экономики', 3, 'chair');

-- --------------------------------------------------------

--
-- Структура таблицы `disciplines`
--

CREATE TABLE `disciplines` (
  `discipline_id` int NOT NULL,
  `discipline_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `disciplines`
--

INSERT INTO `disciplines` (`discipline_id`, `discipline_name`) VALUES
(1, 'Математический анализ'),
(2, 'Аналитическая геометрия и компьютерная графика'),
(3, 'Программирование на C++'),
(4, 'Базы данных'),
(5, 'Физика'),
(6, 'История России'),
(7, 'Дискретная математика'),
(8, 'Операционные системы'),
(9, 'Компьютерные сети'),
(10, 'Экономическая теория');

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

--
-- Дамп данных таблицы `discipline_hours`
--

INSERT INTO `discipline_hours` (`discipline_id`, `lecture_hours`, `practice_hours`, `lab_hours`, `assessment_type`) VALUES
(1, 36, 18, 0, 'exam'),
(2, 18, 18, 18, 'exam'),
(3, 18, 0, 36, 'exam'),
(4, 18, 0, 36, 'credit'),
(5, 18, 18, 0, 'exam'),
(6, 18, 0, 0, 'credit'),
(7, 18, 18, 0, 'exam'),
(8, 18, 0, 36, 'credit'),
(9, 18, 18, 0, 'credit'),
(10, 36, 0, 0, 'exam');

-- --------------------------------------------------------

--
-- Структура таблицы `groups`
--

CREATE TABLE `groups` (
  `group_id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Уникальное название (напр. Б9124-09.03.03-Пикд)',
  `students_count` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `groups`
--

INSERT INTO `groups` (`group_id`, `name`, `students_count`) VALUES
(1, 'Б9124-09.03.03-Пикд', 28),
(2, 'Б9124-09.03.03-Пикр', 25),
(3, 'Б9125-09.03.03-Пикд', 30),
(4, 'Б9125-09.03.03-Пикр', 22),
(5, 'Б9224-09.03.03-Пикд', 26),
(6, 'Б9324-09.03.03-Пикд', 24),
(7, 'Б9130-01.03.02-Кмд', 20),
(8, 'Б9140-38.03.01-Эк', 30);

-- --------------------------------------------------------

--
-- Структура таблицы `group_academic_link`
--

CREATE TABLE `group_academic_link` (
  `group_id` int NOT NULL,
  `academic_group_id` int NOT NULL,
  `is_main` tinyint(1) DEFAULT '0' COMMENT 'Основная академическая группа'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `group_academic_link`
--

INSERT INTO `group_academic_link` (`group_id`, `academic_group_id`, `is_main`) VALUES
(1, 1, 1),
(2, 1, 0),
(3, 2, 1),
(4, 2, 0),
(5, 3, 1),
(6, 4, 1),
(7, 5, 1),
(8, 6, 1);

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

--
-- Дамп данных таблицы `lesson_card`
--

INSERT INTO `lesson_card` (`card_id`, `semester_date`, `week_type`, `discipline_id`, `lesson_type_id`, `group_id`, `teacher_id`, `room_id`, `period_id`) VALUES
(19, '2026-09-01', 'even', 1, 1, 1, 1, 3, 1),
(20, '2026-09-01', 'even', 3, 3, 1, 3, 5, 2),
(21, '2026-09-02', 'even', 2, 1, 1, 2, 1, 1),
(22, '2026-09-02', 'even', 2, 3, 1, 1, 3, 3),
(23, '2026-09-03', 'even', 5, 1, 1, 5, 8, 2),
(24, '2026-09-03', 'even', 7, 2, 1, 7, 6, 4),
(25, '2026-09-04', 'even', 4, 3, 1, 4, 5, 1),
(26, '2026-09-04', 'even', 9, 2, 1, 6, 11, 3),
(27, '2026-09-04', 'all', 6, 1, 1, 6, 7, 5),
(28, '2026-09-08', 'even', 1, 1, 1, 1, 3, 1),
(29, '2026-09-08', 'even', 3, 3, 1, 3, 5, 2),
(30, '2026-09-09', 'even', 2, 1, 1, 2, 1, 1),
(31, '2026-09-09', 'even', 2, 3, 1, 1, 3, 3),
(32, '2026-09-10', 'even', 5, 1, 1, 5, 8, 2),
(33, '2026-09-10', 'even', 7, 2, 1, 7, 6, 4),
(34, '2026-09-11', 'even', 4, 3, 1, 4, 5, 1),
(35, '2026-09-11', 'even', 9, 2, 1, 6, 11, 3),
(36, '2026-09-11', 'all', 6, 1, 1, 6, 7, 5),
(37, '2026-09-01', 'even', 1, 1, 2, 1, 6, 3),
(38, '2026-09-01', 'even', 3, 3, 2, 3, 9, 1),
(39, '2026-09-02', 'even', 2, 2, 2, 2, 8, 2),
(40, '2026-09-03', 'even', 5, 1, 2, 5, 8, 4),
(41, '2026-09-03', 'even', 7, 2, 2, 7, 6, 5),
(42, '2026-09-04', 'even', 4, 3, 2, 4, 5, 2),
(43, '2026-09-04', 'all', 6, 1, 2, 6, 7, 1),
(44, '2026-09-08', 'even', 1, 1, 2, 1, 6, 3),
(45, '2026-09-08', 'even', 3, 3, 2, 3, 9, 1),
(46, '2026-09-09', 'even', 2, 2, 2, 2, 8, 2),
(47, '2026-09-10', 'even', 5, 1, 2, 5, 8, 4),
(48, '2026-09-10', 'even', 7, 2, 2, 7, 6, 5),
(49, '2026-09-11', 'even', 4, 3, 2, 4, 5, 2),
(50, '2026-09-11', 'all', 6, 1, 2, 6, 7, 1),
(51, '2026-09-01', 'even', 1, 1, 3, 1, 1, 3),
(52, '2026-09-01', 'even', 3, 3, 3, 3, 5, 4),
(53, '2026-09-02', 'even', 2, 1, 3, 2, 1, 2),
(54, '2026-09-02', 'even', 7, 2, 3, 7, 6, 5),
(55, '2026-09-03', 'even', 5, 1, 3, 5, 8, 1),
(56, '2026-09-03', 'even', 4, 3, 3, 4, 5, 2),
(57, '2026-09-04', 'all', 6, 1, 3, 6, 7, 3),
(58, '2026-09-04', 'even', 10, 1, 3, 6, 1, 4),
(59, '2026-09-08', 'even', 1, 1, 3, 1, 1, 3),
(60, '2026-09-08', 'even', 3, 3, 3, 3, 5, 4),
(61, '2026-09-09', 'even', 2, 1, 3, 2, 1, 2),
(62, '2026-09-09', 'even', 7, 2, 3, 7, 6, 5),
(63, '2026-09-10', 'even', 5, 1, 3, 5, 8, 1),
(64, '2026-09-10', 'even', 4, 3, 3, 4, 5, 2),
(65, '2026-09-11', 'all', 6, 1, 3, 6, 7, 3),
(66, '2026-09-11', 'even', 10, 1, 3, 6, 1, 4),
(67, '2026-06-19', 'all', 2, 2, 1, 1, 2, 3);

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
(1, 'Лекция', NULL),
(2, 'Практика', NULL),
(3, 'Лабораторная работа', NULL),
(4, 'Зачёт', NULL),
(5, 'Экзамен', NULL),
(6, 'Консультация', NULL);

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

--
-- Дамп данных таблицы `rooms`
--

INSERT INTO `rooms` (`room_id`, `building`, `room_number`, `room_type`, `seats`) VALUES
(1, 'Д', '101', 'lecture', 200),
(2, 'Д', '205', 'lecture', 150),
(3, 'Д', '301', 'lab', 30),
(4, 'Д', '302', 'lab', 25),
(5, 'Д', '303', 'computer', 30),
(6, 'А', '101', 'practical', 40),
(7, 'А', '102', 'practical', 35),
(8, 'А', '201', 'lecture', 120),
(9, 'Б', '101', 'lab', 25),
(10, 'Б', '102', 'computer', 20),
(11, 'Б', '201', 'practical', 30);

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

--
-- Дамп данных таблицы `teachers`
--

INSERT INTO `teachers` (`teacher_id`, `first_name`, `middle_name`, `last_name`, `school`, `department`, `chair`, `degree`, `title`, `position`, `type`) VALUES
(1, 'Ирина', 'Алексеевна', 'Курочкина', 'Школа ИМКТ', 'Департамент Мат. и комп. моделирования', 'Кафедра математики', 'к.ф.-м.н.', 'доцент', 'ст. преподаватель', 'math'),
(2, 'Юрий', 'Александрович', 'Клевчихин', 'Школа ИМКТ', 'Департамент математики', 'Кафедра математики', 'д.ф.-м.н.', 'профессор', 'профессор', 'math'),
(3, 'Алексей', 'Петрович', 'Смирнов', 'Школа ИМКТ', 'Департамент ИТ', 'Кафедра ИТ', 'к.т.н.', 'доцент', 'ст. преподаватель', 'programming'),
(4, 'Мария', 'Ивановна', 'Петрова', 'Школа ИМКТ', 'Департамент ИТ', 'Кафедра ИТ', 'к.т.н.', '', 'ассистент', 'programming'),
(5, 'Владимир', 'Николаевич', 'Иванов', 'Школа ИМКТ', 'Департамент ИТ', 'Кафедра ИТ', 'д.т.н.', 'профессор', 'профессор', 'programming'),
(6, 'Елена', 'Сергеевна', 'Сидорова', 'Школа Экономики', 'Департамент экономики', 'Кафедра экономики', 'к.э.н.', 'доцент', 'доцент', 'economics'),
(7, 'Дмитрий', 'Владимирович', 'Козлов', 'Школа ИМКТ', 'Департамент ИТ', 'Кафедра ИТ', 'к.т.н.', '', 'ассистент', 'programming');

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

--
-- Дамп данных таблицы `teacher_preferences`
--

INSERT INTO `teacher_preferences` (`preference_id`, `teacher_id`, `day_of_week`, `period`, `preference_type`) VALUES
(5, 1, 2, 1, 'prefer'),
(6, 3, 3, 2, 'prefer'),
(7, 3, 4, 1, 'avoid'),
(8, 6, 1, 3, 'prefer');

-- --------------------------------------------------------

--
-- Структура таблицы `time_periods`
--

CREATE TABLE `time_periods` (
  `period_id` int NOT NULL,
  `period_number` tinyint NOT NULL COMMENT 'Номер пары (1-8)',
  `time_range` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Время (напр. 8:30-10:00)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `time_periods`
--

INSERT INTO `time_periods` (`period_id`, `period_number`, `time_range`) VALUES
(1, 1, '8:30-10:00'),
(2, 2, '10:10-11:40'),
(3, 3, '11:50-13:20'),
(4, 4, '13:30-15:00'),
(5, 5, '15:10-16:40'),
(6, 6, '16:50-18:20'),
(7, 7, '18:30-19:00'),
(8, 8, '19:10-20:40');

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
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`user_id`, `login`, `password_hash`, `role`, `teacher_id`, `group_id`) VALUES
(1, 'admin', '$2y$10$.3pObepSTRnOrbyhNyCw4uFNNbP0Gt/aSGBEwMlhS5FDbsNpoyYe2', 'admin', NULL, NULL);

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
  MODIFY `academic_group_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `calendar_days`
--
ALTER TABLE `calendar_days`
  MODIFY `day_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=307;

--
-- AUTO_INCREMENT для таблицы `departments`
--
ALTER TABLE `departments`
  MODIFY `department_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `disciplines`
--
ALTER TABLE `disciplines`
  MODIFY `discipline_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT для таблицы `groups`
--
ALTER TABLE `groups`
  MODIFY `group_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT для таблицы `lesson_card`
--
ALTER TABLE `lesson_card`
  MODIFY `card_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT для таблицы `lesson_types`
--
ALTER TABLE `lesson_types`
  MODIFY `lesson_type_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `rooms`
--
ALTER TABLE `rooms`
  MODIFY `room_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT для таблицы `schedule_changes`
--
ALTER TABLE `schedule_changes`
  MODIFY `change_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `teachers`
--
ALTER TABLE `teachers`
  MODIFY `teacher_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `teacher_preferences`
--
ALTER TABLE `teacher_preferences`
  MODIFY `preference_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT для таблицы `time_periods`
--
ALTER TABLE `time_periods`
  MODIFY `period_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
