-- ============================================================
-- База данных: Schedule
-- MySQL 5.6+, utf8mb4
-- Система управления расписанием учебных занятий
-- ============================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

CREATE DATABASE IF NOT EXISTS `Schedule` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `Schedule`;

-- --------------------------------------------------------
-- 1. Подразделения университета (иерархия: университет -> школа -> кафедра)
-- --------------------------------------------------------
CREATE TABLE `departments` (
  `department_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_id` int DEFAULT NULL,
  `level` enum('university','school','chair') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'chair',
  PRIMARY KEY (`department_id`),
  KEY `parent_id` (`parent_id`),
  CONSTRAINT `departments_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `departments` (`department_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 2. Академические группы (базовые группы по направлению подготовки)
-- --------------------------------------------------------
CREATE TABLE `academic_groups` (
  `academic_group_id` int NOT NULL AUTO_INCREMENT,
  `code` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `direction` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Направление подготовки',
  `course` tinyint DEFAULT NULL COMMENT 'Курс (1-6)',
  PRIMARY KEY (`academic_group_id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 3. Учебные группы (фактические группы для расписания)
-- --------------------------------------------------------
CREATE TABLE `groups` (
  `group_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Уникальное название (напр. Б9124-09.03.03-Пикд)',
  `students_count` int NOT NULL,
  PRIMARY KEY (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 4. Связь учебная группа <-> академическая группа
-- --------------------------------------------------------
CREATE TABLE `group_academic_link` (
  `group_id` int NOT NULL,
  `academic_group_id` int NOT NULL,
  `is_main` tinyint(1) DEFAULT 0 COMMENT 'Основная академическая группа',
  PRIMARY KEY (`group_id`, `academic_group_id`),
  KEY `academic_group_id` (`academic_group_id`),
  CONSTRAINT `group_academic_link_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`group_id`),
  CONSTRAINT `group_academic_link_ibfk_2` FOREIGN KEY (`academic_group_id`) REFERENCES `academic_groups` (`academic_group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 5. Дисциплины
-- --------------------------------------------------------
CREATE TABLE `disciplines` (
  `discipline_id` int NOT NULL AUTO_INCREMENT,
  `discipline_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`discipline_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 6. Часы по видам занятий для каждой дисциплины
-- --------------------------------------------------------
CREATE TABLE `discipline_hours` (
  `discipline_id` int NOT NULL,
  `lecture_hours` int DEFAULT 0,
  `practice_hours` int DEFAULT 0,
  `lab_hours` int DEFAULT 0,
  `assessment_type` enum('credit','exam','none') COLLATE utf8mb4_unicode_ci DEFAULT 'none',
  PRIMARY KEY (`discipline_id`),
  CONSTRAINT `discipline_hours_ibfk_1` FOREIGN KEY (`discipline_id`) REFERENCES `disciplines` (`discipline_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 7. Преподаватели
-- --------------------------------------------------------
CREATE TABLE `teachers` (
  `teacher_id` int NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `middle_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `school` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `department` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `chair` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `degree` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Учёная степень',
  `title` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Учёное звание',
  `position` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Должность',
  `type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`teacher_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 8. Предпочтения преподавателей (опционально)
-- --------------------------------------------------------
CREATE TABLE `teacher_preferences` (
  `preference_id` int NOT NULL AUTO_INCREMENT,
  `teacher_id` int NOT NULL,
  `day_of_week` tinyint DEFAULT NULL COMMENT '1=пн, ..., 6=сб',
  `period` tinyint DEFAULT NULL COMMENT '1-8',
  `preference_type` enum('prefer','avoid') COLLATE utf8mb4_unicode_ci DEFAULT 'prefer',
  PRIMARY KEY (`preference_id`),
  KEY `teacher_id` (`teacher_id`),
  CONSTRAINT `teacher_preferences_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`teacher_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 9. Учебные помещения
-- --------------------------------------------------------
CREATE TABLE `rooms` (
  `room_id` int NOT NULL AUTO_INCREMENT,
  `building` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Корпус',
  `room_number` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Номер аудитории',
  `room_type` enum('lecture','practical','lab','computer') COLLATE utf8mb4_unicode_ci NOT NULL,
  `seats` int NOT NULL,
  PRIMARY KEY (`room_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 10. Расписание звонков (временные интервалы пар)
-- --------------------------------------------------------
CREATE TABLE `time_periods` (
  `period_id` int NOT NULL AUTO_INCREMENT,
  `period_number` tinyint NOT NULL COMMENT 'Номер пары (1-8)',
  `time_range` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Время (напр. 8:30-10:00)',
  PRIMARY KEY (`period_id`),
  UNIQUE KEY `period_number` (`period_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 11. Типы занятий
-- --------------------------------------------------------
CREATE TABLE `lesson_types` (
  `lesson_type_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `duration_minutes` int DEFAULT NULL COMMENT 'Длительность в минутах',
  PRIMARY KEY (`lesson_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 12. Основная таблица расписания (карточки занятий)
-- --------------------------------------------------------
CREATE TABLE `lesson_card` (
  `card_id` int NOT NULL AUTO_INCREMENT,
  `semester_date` date NOT NULL COMMENT 'Дата начала семестра',
  `week_type` enum('even','odd','all') COLLATE utf8mb4_unicode_ci DEFAULT 'all' COMMENT 'Чётная/нечётная/любая неделя',
  `discipline_id` int NOT NULL,
  `lesson_type_id` int NOT NULL,
  `group_id` int NOT NULL,
  `teacher_id` int NOT NULL,
  `room_id` int NOT NULL,
  `period_id` int NOT NULL,
  PRIMARY KEY (`card_id`),
  KEY `semester_date` (`semester_date`),
  KEY `week_type` (`week_type`),
  KEY `group_id` (`group_id`),
  KEY `teacher_id` (`teacher_id`),
  KEY `room_id` (`room_id`),
  KEY `period_id` (`period_id`),
  KEY `discipline_id` (`discipline_id`),
  CONSTRAINT `lesson_card_ibfk_1` FOREIGN KEY (`discipline_id`) REFERENCES `disciplines` (`discipline_id`),
  CONSTRAINT `lesson_card_ibfk_2` FOREIGN KEY (`lesson_type_id`) REFERENCES `lesson_types` (`lesson_type_id`),
  CONSTRAINT `lesson_card_ibfk_3` FOREIGN KEY (`group_id`) REFERENCES `groups` (`group_id`),
  CONSTRAINT `lesson_card_ibfk_4` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`teacher_id`),
  CONSTRAINT `lesson_card_ibfk_5` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`room_id`),
  CONSTRAINT `lesson_card_ibfk_6` FOREIGN KEY (`period_id`) REFERENCES `time_periods` (`period_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 13. Производственный календарь
-- --------------------------------------------------------
CREATE TABLE `calendar_days` (
  `day_id` int NOT NULL AUTO_INCREMENT,
  `day_date` date NOT NULL,
  `is_working` tinyint(1) DEFAULT 1,
  `is_holiday` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`day_id`),
  UNIQUE KEY `day_date` (`day_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 14. История изменений расписания
-- --------------------------------------------------------
CREATE TABLE `schedule_changes` (
  `change_id` int NOT NULL AUTO_INCREMENT,
  `card_id` int DEFAULT NULL,
  `changed_by` int NOT NULL,
  `changed_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `action` enum('create','move','delete') COLLATE utf8mb4_unicode_ci NOT NULL,
  `old_data` json DEFAULT NULL,
  `new_data` json DEFAULT NULL,
  PRIMARY KEY (`change_id`),
  KEY `card_id` (`card_id`),
  CONSTRAINT `schedule_changes_ibfk_1` FOREIGN KEY (`card_id`) REFERENCES `lesson_card` (`card_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 15. Пользователи и права доступа
-- --------------------------------------------------------
CREATE TABLE `users` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `login` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','dispatcher','teacher','student') COLLATE utf8mb4_unicode_ci NOT NULL,
  `teacher_id` int DEFAULT NULL,
  `group_id` int DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `login` (`login`),
  KEY `teacher_id` (`teacher_id`),
  KEY `group_id` (`group_id`),
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`teacher_id`),
  CONSTRAINT `users_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `groups` (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
