/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

CREATE TABLE IF NOT EXISTS `countries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `iso_code` varchar(2) NOT NULL,
  `flag` varchar(150) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*!40000 ALTER TABLE `countries` DISABLE KEYS */;
INSERT INTO `countries` (`id`, `name`, `iso_code`, `flag`) VALUES
  (1, 'Ukraine', 'UA', NULL);
/*!40000 ALTER TABLE `countries` ENABLE KEYS */;

CREATE TABLE IF NOT EXISTS `departments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `head_id` int(11) NOT NULL,
  `university_id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `description` TEXT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `FK_departments_faculties` (`head_id`),
  KEY `FK_departments_universities` (`university_id`),
  CONSTRAINT `FK_departments_faculties` FOREIGN KEY (`head_id`) REFERENCES `faculties` (`id`),
  CONSTRAINT `FK_departments_universities` FOREIGN KEY (`university_id`) REFERENCES `universities` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `disciplines` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_type_id` int(11) NOT NULL DEFAULT '0',
  `belongs_to` enum('UNIV','DEP','STUD','FAC','DISC') DEFAULT NULL COMMENT 'UNIV - university, DEP - departement, STUD - student, FAC - faculties, DISC - discipline',
  `event_date` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `FK_events_events_type` (`event_type_id`),
  CONSTRAINT `FK_events_events_type` FOREIGN KEY (`event_type_id`) REFERENCES `events_type` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `events_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `short_name` varchar(25) NOT NULL,
  `name` varchar(250) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `faculties` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `inn` BIGINT(20) DEFAULT NULL,
  `location_id` int(11) NOT NULL,
  `staff_type_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `sex` ENUM('M','F') NULL DEFAULT NULL,
  `title` VARCHAR(5) NULL DEFAULT NULL,
  `phone` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `birthday` datetime DEFAULT NULL,
  `photo` varchar(250) DEFAULT NULL,
  `cv` text,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `phone` (`phone`),
  UNIQUE KEY `inn` (`inn`),
  KEY `FK_faculties_locations` (`location_id`),
  KEY `FK_faculties_staff_types` (`staff_type_id`),
  CONSTRAINT `FK_faculties_locations` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`),
  CONSTRAINT `FK_faculties_staff_types` FOREIGN KEY (`staff_type_id`) REFERENCES `staff_types` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `faculties_departments` (
  `faculty_id` INT(11) NOT NULL,
  `department_id` INT(11) NOT NULL,
  `active` SMALLINT(1) NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX `FK_faculties_departments_faculties` (`faculty_id`),
  INDEX `FK_facultues_departments_departments` (`department_id`),
  CONSTRAINT `FK_faculties_departments_departments` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`),
  CONSTRAINT `FK_faculties_departments_faculties` FOREIGN KEY (`faculty_id`) REFERENCES `faculties` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `faculties_disciplines` (
  `faculty_id` int(11) NOT NULL,
  `discipline_id` int(11) NOT NULL,
  `active` smallint(1) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY `FK_faculties_disciplines_faculties` (`faculty_id`),
  KEY `FK_facultues_disciplines_disciplines` (`discipline_id`),
  CONSTRAINT `FK_faculties_disciplines_disciplines` FOREIGN KEY (`discipline_id`) REFERENCES `disciplines` (`id`),
  CONSTRAINT `FK_faculties_disciplines_faculties` FOREIGN KEY (`faculty_id`) REFERENCES `faculties` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `faculties_students` (
  `faculty_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY `FK_faculties_students_faculties` (`faculty_id`),
  KEY `FK_faculties_students_students` (`student_id`),
  CONSTRAINT `FK_faculties_students_faculties` FOREIGN KEY (`faculty_id`) REFERENCES `faculties` (`id`),
  CONSTRAINT `FK_faculties_students_students` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `home_works` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `discipline_id` INT(11) NULL DEFAULT NULL,
  `name` varchar(250) NOT NULL,
  `description` text,
  `deadline` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `FK_home_works_disciplines` (`discipline_id`),
  CONSTRAINT `FK_home_works_disciplines` FOREIGN KEY (`discipline_id`) REFERENCES `disciplines` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `locations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country_id` int(11) NOT NULL,
  `city` varchar(50) NOT NULL,
  `postcode` varchar(15) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `FK_locations_country` (`country_id`),
  CONSTRAINT `FK_locations_country` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `staff_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `staff` varchar(100) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

/*!40000 ALTER TABLE `staff_types` DISABLE KEYS */;
INSERT INTO `staff_types` (`id`, `staff`) VALUES
  (1, 'Department Chairs'),
  (2, 'Dean'),
  (3, 'Vice Presidents'),
  (4, 'President'),
  (5, 'Adjunct Professors'),
  (6, 'Assistant Professors'),
  (7, 'Associate Professors'),
  (8, 'Professors');
/*!40000 ALTER TABLE `staff_types` ENABLE KEYS */;

CREATE TABLE IF NOT EXISTS `students` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `sex` ENUM('M','F') NULL DEFAULT NULL,
  `avatar` varchar(250) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(100) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `FK_students_locations` (`location_id`),
  CONSTRAINT `FK_students_locations` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `students_home_works` (
  `student_id` int(11) NOT NULL,
  `home_work_id` int(11) NOT NULL,
  `grade` float DEFAULT NULL,
  `accepted` tinyint(1) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY `FK_students_home_works_students` (`student_id`),
  KEY `FK_students_home_works_home_works` (`home_work_id`),
  CONSTRAINT `FK_students_home_works_home_works` FOREIGN KEY (`home_work_id`) REFERENCES `home_works` (`id`),
  CONSTRAINT `FK_students_home_works_students` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `universities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(11) NOT NULL,
  `head_id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `url` varchar(250) DEFAULT NULL,
  `logo` varchar(250) DEFAULT NULL,
  `description` text,
  `history` longtext,
  `foundation_date` date DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `FK_universities_locations` (`location_id`),
  KEY `FK_universities_professors` (`head_id`),
  CONSTRAINT `FK_universities_locations` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`),
  CONSTRAINT `FK_universities_faculties` FOREIGN KEY (`head_id`) REFERENCES `faculties` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
