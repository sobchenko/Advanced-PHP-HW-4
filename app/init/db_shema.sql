/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

CREATE TABLE IF NOT EXISTS `countries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '0',
  `iso_code` varchar(2) NOT NULL DEFAULT '0',
  `flag` varchar(150) NOT NULL DEFAULT '0',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `departments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `head_id` int(11) NOT NULL DEFAULT '0',
  `university_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(250) NOT NULL DEFAULT '0',
  `description` varchar(250) NOT NULL DEFAULT '0',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `FK_departments_faculty` (`head_id`),
  KEY `FK_departments_universities` (`university_id`),
  CONSTRAINT `FK_departments_faculty` FOREIGN KEY (`head_id`) REFERENCES `faculty` (`id`),
  CONSTRAINT `FK_departments_universities` FOREIGN KEY (`university_id`) REFERENCES `universities` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `disciplines` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '0',
  `description` text,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_type_id` int(11) NOT NULL DEFAULT '0',
  `belongs_to` enum('UNIV','DEP','STUD','FAC','DISC') DEFAULT NULL COMMENT 'UNIV - university, DEP - departement, STUD - student, FAC - faculty, DISC - discipline',
  `event_date` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `FK_events_events_type` (`event_type_id`),
  CONSTRAINT `FK_events_events_type` FOREIGN KEY (`event_type_id`) REFERENCES `events_type` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `events_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `short_name` varchar(25) NOT NULL DEFAULT '0',
  `name` varchar(250) NOT NULL DEFAULT '0',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `faculty` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `inn` int(10) DEFAULT NULL,
  `location_id` int(11) DEFAULT NULL,
  `staff_type_id` int(11) DEFAULT NULL,
  `first_name` varchar(100) NOT NULL DEFAULT '0',
  `last_name` varchar(100) NOT NULL DEFAULT '0',
  `phone` varchar(100) DEFAULT '0',
  `email` varchar(100) DEFAULT '0',
  `birthday` datetime DEFAULT NULL,
  `photo` varchar(250) DEFAULT '0',
  `cv` text,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `phone` (`phone`),
  UNIQUE KEY `inn` (`inn`),
  KEY `FK_faculty_locations` (`location_id`),
  KEY `FK_faculty_staff_types` (`staff_type_id`),
  CONSTRAINT `FK_faculty_locations` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`),
  CONSTRAINT `FK_faculty_staff_types` FOREIGN KEY (`staff_type_id`) REFERENCES `staff_types` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `faculty_disciplines` (
  `faculty_id` int(11) DEFAULT NULL,
  `disciplines_id` int(11) DEFAULT NULL,
  `active` smallint(1) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  KEY `FK_faculty_disciplines_faculty` (`faculty_id`),
  KEY `FK_faculty_disciplines_disciplines` (`disciplines_id`),
  CONSTRAINT `FK_faculty_disciplines_disciplines` FOREIGN KEY (`disciplines_id`) REFERENCES `disciplines` (`id`),
  CONSTRAINT `FK_faculty_disciplines_faculty` FOREIGN KEY (`faculty_id`) REFERENCES `faculty` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `faculty_students` (
  `faculty_id` int(11) DEFAULT NULL,
  `student_id` int(11) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  KEY `FK_faculty_students_faculty` (`faculty_id`),
  KEY `FK_faculty_students_students` (`student_id`),
  CONSTRAINT `FK_faculty_students_faculty` FOREIGN KEY (`faculty_id`) REFERENCES `faculty` (`id`),
  CONSTRAINT `FK_faculty_students_students` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `home_works` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL DEFAULT '0',
  `description` text NOT NULL,
  `deadline` datetime NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `locations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country_id` int(11) NOT NULL DEFAULT '0',
  `city` varchar(50) NOT NULL DEFAULT '0',
  `city_code` varchar(50) NOT NULL DEFAULT '0',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `FK_locations_country` (`country_id`),
  CONSTRAINT `FK_locations_country` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `staff_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `staff` varchar(100) NOT NULL DEFAULT '0',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `students` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(11) NOT NULL DEFAULT '0',
  `fitst_name` varchar(100) NOT NULL DEFAULT '0',
  `last_name` varchar(100) NOT NULL DEFAULT '0',
  `avatar` varchar(250) NOT NULL DEFAULT '0',
  `email` varchar(100) NOT NULL DEFAULT '0',
  `phone` varchar(100) NOT NULL DEFAULT '0',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `FK_students_locations` (`location_id`),
  CONSTRAINT `FK_students_locations` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `students_home_works` (
  `student_id` int(11) DEFAULT NULL,
  `home_work_id` int(11) DEFAULT NULL,
  `grade` float DEFAULT NULL,
  `accepted` tinyint(1) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  KEY `FK_students_home_works_students` (`student_id`),
  KEY `FK_students_home_works_home_works` (`home_work_id`),
  CONSTRAINT `FK_students_home_works_home_works` FOREIGN KEY (`home_work_id`) REFERENCES `home_works` (`id`),
  CONSTRAINT `FK_students_home_works_students` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `universities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(11) NOT NULL DEFAULT '0',
  `head_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(250) NOT NULL DEFAULT '0',
  `url` varchar(250) DEFAULT '0',
  `logo` varchar(250) DEFAULT '0',
  `description` text,
  `history` longtext,
  `foundation_date` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `FK_universities_locations` (`location_id`),
  KEY `FK_universities_professors` (`head_id`),
  CONSTRAINT `FK_universities_locations` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`),
  CONSTRAINT `FK_universities_professors` FOREIGN KEY (`head_id`) REFERENCES `faculty` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;