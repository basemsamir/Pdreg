-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.1.9-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win32
-- HeidiSQL Version:             9.4.0.5125
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Dumping database structure for pdreg
DROP DATABASE IF EXISTS `pdreg`;
CREATE DATABASE IF NOT EXISTS `pdreg` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `pdreg`;

-- Dumping structure for table pdreg.entrypoints
DROP TABLE IF EXISTS `entrypoints`;
CREATE TABLE IF NOT EXISTS `entrypoints` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` text COLLATE utf8_unicode_ci,
  `location` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table pdreg.entrypoints: ~0 rows (approximately)
/*!40000 ALTER TABLE `entrypoints` DISABLE KEYS */;
INSERT INTO `entrypoints` (`id`, `name`, `type`, `location`, `created_at`, `updated_at`) VALUES
	(4, 'حجز تذاكر', 'r', 'العيادات', '2017-02-26 10:42:51', '2017-02-26 10:43:29');
/*!40000 ALTER TABLE `entrypoints` ENABLE KEYS */;

-- Dumping structure for table pdreg.entrypoint_user
DROP TABLE IF EXISTS `entrypoint_user`;
CREATE TABLE IF NOT EXISTS `entrypoint_user` (
  `user_id` int(10) unsigned NOT NULL,
  `entrypoint_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  KEY `entrypoint_user_user_id_index` (`user_id`),
  KEY `entrypoint_user_entrypoint_id_index` (`entrypoint_id`),
  CONSTRAINT `entrypoint_user_entrypoint_id_foreign` FOREIGN KEY (`entrypoint_id`) REFERENCES `entrypoints` (`id`),
  CONSTRAINT `entrypoint_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table pdreg.entrypoint_user: ~0 rows (approximately)
/*!40000 ALTER TABLE `entrypoint_user` DISABLE KEYS */;
INSERT INTO `entrypoint_user` (`user_id`, `entrypoint_id`, `created_at`, `updated_at`) VALUES
	(5, 4, '2017-02-26 10:45:30', '2017-02-26 10:45:30'),
	(7, 4, '2017-03-02 12:44:07', '2017-03-02 12:44:07');
/*!40000 ALTER TABLE `entrypoint_user` ENABLE KEYS */;

-- Dumping structure for table pdreg.medical_devices
DROP TABLE IF EXISTS `medical_devices`;
CREATE TABLE IF NOT EXISTS `medical_devices` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `location` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table pdreg.medical_devices: ~0 rows (approximately)
/*!40000 ALTER TABLE `medical_devices` DISABLE KEYS */;
/*!40000 ALTER TABLE `medical_devices` ENABLE KEYS */;

-- Dumping structure for table pdreg.medical_order_items
DROP TABLE IF EXISTS `medical_order_items`;
CREATE TABLE IF NOT EXISTS `medical_order_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `visit_id` int(10) unsigned NOT NULL,
  `proc_id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `medical_order_items_visit_id_index` (`visit_id`),
  CONSTRAINT `medical_order_items_visit_id_foreign` FOREIGN KEY (`visit_id`) REFERENCES `visits` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table pdreg.medical_order_items: ~0 rows (approximately)
/*!40000 ALTER TABLE `medical_order_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `medical_order_items` ENABLE KEYS */;

-- Dumping structure for table pdreg.medical_units
DROP TABLE IF EXISTS `medical_units`;
CREATE TABLE IF NOT EXISTS `medical_units` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `parent_department_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table pdreg.medical_units: ~4 rows (approximately)
/*!40000 ALTER TABLE `medical_units` DISABLE KEYS */;
INSERT INTO `medical_units` (`id`, `name`, `type`, `parent_department_id`, `created_at`, `updated_at`) VALUES
	(42, 'اطفال', 'c', 43, '2017-02-26 10:45:41', '2017-02-26 10:45:53'),
	(43, 'اطفال', 'd', NULL, '2017-02-26 10:45:49', '2017-02-26 10:45:49'),
	(44, 'باطنة', 'c', 45, '2017-03-08 08:17:05', '2017-03-08 08:17:16'),
	(45, 'باطنة', 'd', NULL, '2017-03-08 08:17:11', '2017-03-08 08:17:11');
/*!40000 ALTER TABLE `medical_units` ENABLE KEYS */;

-- Dumping structure for table pdreg.medical_unit_user
DROP TABLE IF EXISTS `medical_unit_user`;
CREATE TABLE IF NOT EXISTS `medical_unit_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `medical_unit_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `medical_unit_user_medical_unit_id_index` (`medical_unit_id`),
  KEY `medical_unit_user_user_id_index` (`user_id`),
  CONSTRAINT `medical_unit_user_medical_unit_id_foreign` FOREIGN KEY (`medical_unit_id`) REFERENCES `medical_units` (`id`),
  CONSTRAINT `medical_unit_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table pdreg.medical_unit_user: ~4 rows (approximately)
/*!40000 ALTER TABLE `medical_unit_user` DISABLE KEYS */;
INSERT INTO `medical_unit_user` (`id`, `medical_unit_id`, `user_id`, `created_at`, `updated_at`) VALUES
	(16, 43, 2, '2017-02-26 10:45:59', '2017-02-26 10:45:59'),
	(17, 42, 2, '2017-02-26 10:45:59', '2017-02-26 10:45:59'),
	(18, 45, 12, '2017-03-08 08:17:41', '2017-03-08 08:17:41'),
	(19, 44, 12, '2017-03-08 08:17:41', '2017-03-08 08:17:41');
/*!40000 ALTER TABLE `medical_unit_user` ENABLE KEYS */;

-- Dumping structure for table pdreg.medical_unit_visit
DROP TABLE IF EXISTS `medical_unit_visit`;
CREATE TABLE IF NOT EXISTS `medical_unit_visit` (
  `visit_id` int(10) unsigned NOT NULL,
  `medical_unit_id` int(10) unsigned NOT NULL,
  `user_id` int(11) NOT NULL,
  `convert_to` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  KEY `medical_unit_visit_visit_id_index` (`visit_id`),
  KEY `medical_unit_visit_medical_unit_id_index` (`medical_unit_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table pdreg.medical_unit_visit: ~14 rows (approximately)
/*!40000 ALTER TABLE `medical_unit_visit` DISABLE KEYS */;
INSERT INTO `medical_unit_visit` (`visit_id`, `medical_unit_id`, `user_id`, `convert_to`, `created_at`, `updated_at`) VALUES
	(34, 42, 0, NULL, '2017-02-26 13:13:19', '2017-02-26 13:13:19'),
	(35, 42, 0, NULL, '2017-02-28 08:25:52', '2017-02-28 08:25:52'),
	(36, 42, 0, NULL, '2017-03-01 08:00:36', '2017-03-01 08:00:36'),
	(37, 42, 0, NULL, '2017-03-02 09:26:52', '2017-03-02 09:26:52'),
	(38, 42, 0, NULL, '2017-03-02 12:44:40', '2017-03-02 12:44:40'),
	(39, 44, 0, 42, '2017-03-08 08:18:01', '2017-03-08 08:29:38'),
	(39, 42, 12, NULL, '2017-03-08 08:29:38', '2017-03-08 08:29:38'),
	(40, 42, 0, NULL, '2017-03-08 12:39:00', '2017-03-08 12:39:00'),
	(43, 42, 0, NULL, '2017-03-09 08:16:17', '2017-03-09 08:16:17'),
	(44, 42, 0, NULL, '2017-03-09 08:16:42', '2017-03-09 08:16:42'),
	(45, 42, 0, NULL, '2017-03-12 10:53:13', '2017-03-12 10:53:13'),
	(46, 42, 0, NULL, '2017-03-13 08:45:30', '2017-03-13 08:45:30'),
	(47, 42, 0, NULL, '2017-03-13 08:47:42', '2017-03-13 08:47:42'),
	(48, 42, 0, NULL, '2017-03-13 11:13:05', '2017-03-13 11:13:05'),
	(49, 42, 0, NULL, '2017-03-13 11:17:58', '2017-03-13 11:17:58'),
	(50, 42, 0, NULL, '2017-03-13 11:19:20', '2017-03-13 11:19:20'),
	(51, 42, 0, NULL, '2017-03-14 09:41:33', '2017-03-14 09:41:33'),
	(52, 42, 0, NULL, '2017-03-14 11:23:18', '2017-03-14 11:23:18');
/*!40000 ALTER TABLE `medical_unit_visit` ENABLE KEYS */;

-- Dumping structure for table pdreg.mf_logs
DROP TABLE IF EXISTS `mf_logs`;
CREATE TABLE IF NOT EXISTS `mf_logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `loggable_id` int(11) NOT NULL,
  `loggable_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `action` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `before` text COLLATE utf8_unicode_ci,
  `after` text COLLATE utf8_unicode_ci,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=257 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table pdreg.mf_logs: ~68 rows (approximately)
/*!40000 ALTER TABLE `mf_logs` DISABLE KEYS */;
INSERT INTO `mf_logs` (`id`, `user_id`, `loggable_id`, `loggable_type`, `action`, `before`, `after`, `created_at`) VALUES
	(135, 5, 5, 'App\\User', 'updated', NULL, '{"remember_token":"qIIpSfOUzMGxxo7HKXIaNzE3Bf0LciPqsfgg2hTR12mwOjeQlS3TolGt2vrP"}', '2017-02-26 10:42:08'),
	(136, 1, 4, 'App\\Entrypoint', 'created', NULL, '{"name":"حجز تذاكر","type":"r","location":"العيا"}', '2017-02-26 10:42:51'),
	(137, 1, 4, 'App\\Entrypoint', 'updated', '{"location":"العيا"}', '{"location":"العيادات"}', '2017-02-26 10:43:29'),
	(138, 1, 42, 'App\\MedicalUnit', 'created', NULL, '{"name":"اطفال","type":"c"}', '2017-02-26 10:45:42'),
	(139, 1, 43, 'App\\MedicalUnit', 'created', NULL, '{"name":"اطفال","type":"d"}', '2017-02-26 10:45:49'),
	(140, 1, 42, 'App\\MedicalUnit', 'updated', '{"parent_department_id":null}', '{"parent_department_id":"43"}', '2017-02-26 10:45:53'),
	(141, 1, 1, 'App\\User', 'updated', NULL, '{"remember_token":"FARg3bPbDU54ltD0CPUC6PfPxNIAjtlLBXGVziHYNcBwAkiXK3AnsOJ8BrpV"}', '2017-02-26 10:46:06'),
	(142, 5, 5, 'App\\User', 'updated', NULL, '{"remember_token":"4SWiw0YsKFeJesaqXQ4k1ru2k4fejvSnYbiUIkD0jmQtdCJfHYmNokuuHa65"}', '2017-02-26 10:46:23'),
	(143, 1, 1, 'App\\User', 'updated', NULL, '{"remember_token":"9DgohhThrxoM5YaB1XmGUw6Ed7ngpqjuclGpkNECW8g8BucNnNmDdmhzMI6F"}', '2017-02-26 10:57:18'),
	(144, 1, 1, 'App\\User', 'updated', NULL, '{"remember_token":"5EFWJ5EYd68JzVwlkKh7pnPZCKvogC8dbvr2jteea5NZptbi9tjmn0gWSSzX"}', '2017-02-26 10:58:25'),
	(145, 5, 5, 'App\\User', 'updated', NULL, '{"remember_token":"GNhOuMSPLDTFeOnfic428Audz7aqHZA0OehQpi5pHDrWcwZtgstIWEQyYffm"}', '2017-02-26 10:58:48'),
	(146, 1, 1, 'App\\User', 'updated', NULL, '{"remember_token":"iXVOUnXhEARYn1oIUsDQl4U0lL5H8EQBgUnAHYuJ9BT32Mxm2uxUbTZJJGMi"}', '2017-02-26 10:59:05'),
	(147, 5, 5, 'App\\User', 'updated', NULL, '{"remember_token":"gIbl569kIc1uNhabr0kFSAKPJ54bhuYXV4qlZTk2E8Z90yKaUel7V9ku7HTg"}', '2017-02-26 11:01:17'),
	(148, 1, 1, 'App\\User', 'updated', NULL, '{"remember_token":"1BQai24yE1mDXNgqn6ORNmXUA5fqblxl49FHZqaCNh9WTjtGzMUhzXK4JXrI"}', '2017-02-26 11:02:51'),
	(149, 5, 19, 'App\\Patient', 'created', NULL, '{"name":"باسم سمير عبدالسيد ","gender":"M","address":"اسيوط","birthdate":"2005-02-26","age":"12"}', '2017-02-26 13:13:19'),
	(150, 5, 34, 'App\\Visit', 'created', NULL, '{"patient_id":19,"user_id":5,"entry_id":"4"}', '2017-02-26 13:13:19'),
	(151, 5, 5, 'App\\User', 'updated', NULL, '{"remember_token":"N1VFWxaktzoqJfYSzlAmeZ0StuOrfRLTtfeu85tfg5u1yd3FyTTHzQO5OM7l"}', '2017-02-28 08:12:07'),
	(152, 3, 3, 'App\\User', 'updated', NULL, '{"remember_token":"Dkp3KmVNAIDdiLUpJHGqx0w7kaJTW4CzJEpCPBeCeEThENlB4GsjVe9G2DUL"}', '2017-02-28 08:25:38'),
	(153, 5, 35, 'App\\Visit', 'created', NULL, '{"patient_id":"19","user_id":5,"entry_id":"4"}', '2017-02-28 08:25:52'),
	(154, 5, 5, 'App\\User', 'updated', NULL, '{"remember_token":"K4qas2QiSsbuj1IrqLpyB9abBhn9YXX7BtHgWfvadOkiFhrbVx4WHu5OIV6T"}', '2017-02-28 08:25:57'),
	(155, 2, 2, 'App\\User', 'updated', NULL, '{"remember_token":"6kM1haFxEhKfEv1asGPYJNNWgK3MEuqLTvDPtmFkaeAD3h2zFAM415oFX6lO"}', '2017-02-28 13:35:34'),
	(156, 5, 5, 'App\\User', 'updated', NULL, '{"remember_token":"gO1h8GZEletTM72cQtC6w1KlPAoDLcq4AK3rPDE35XPB7C4tDpNN7LDtTZMh"}', '2017-02-28 13:36:02'),
	(157, 5, 5, 'App\\User', 'updated', NULL, '{"remember_token":"gb7LEOlmbYRtjFRtwICnwXctwl4QGxIwOmPCIkgtQGd8cCJBNYWawmCkEiSU"}', '2017-03-01 08:00:11'),
	(158, 5, 36, 'App\\Visit', 'created', NULL, '{"patient_id":"19","user_id":5,"entry_id":"4"}', '2017-03-01 08:00:36'),
	(159, 5, 5, 'App\\User', 'updated', NULL, '{"remember_token":"zcq6bCq6MgDBDNbsmFZSgXFUJi6UNm22kaWiXso6znpTjuNhbRryrEsRKb27"}', '2017-03-01 08:00:39'),
	(160, 2, 1, 'App\\VisitComplaint', 'created', NULL, '{"visit_id":"36","content":"ألم","typist_id":2}', '2017-03-01 08:08:45'),
	(161, 2, 2, 'App\\User', 'updated', NULL, '{"remember_token":"ZuPpCjx5zlDzque0c8xcFp5v6n8HdSr36CC9Kf8HSbMqwTZk7GfVPVFgAWsc"}', '2017-03-01 08:09:11'),
	(162, 5, 37, 'App\\Visit', 'created', NULL, '{"patient_id":"19","user_id":5,"entry_id":"4"}', '2017-03-02 09:26:52'),
	(163, 7, 20, 'App\\Patient', 'created', NULL, '{"name":"سامي عبدالبديع علي ","gender":"M","address":"اسيوط","birthdate":"2005-03-02","age":"12"}', '2017-03-02 12:44:40'),
	(164, 7, 38, 'App\\Visit', 'created', NULL, '{"patient_id":20,"user_id":7,"entry_id":"4"}', '2017-03-02 12:44:40'),
	(165, 14, 44, 'App\\MedicalUnit', 'created', NULL, '{"name":"باطنة","type":"c"}', '2017-03-08 08:17:05'),
	(166, 14, 45, 'App\\MedicalUnit', 'created', NULL, '{"name":"باطنة","type":"d"}', '2017-03-08 08:17:11'),
	(167, 14, 44, 'App\\MedicalUnit', 'updated', '{"parent_department_id":null}', '{"parent_department_id":"45"}', '2017-03-08 08:17:16'),
	(168, 5, 39, 'App\\Visit', 'created', NULL, '{"patient_id":"19","user_id":5,"entry_id":"4"}', '2017-03-08 08:18:01'),
	(169, 14, 9, 'App\\User', 'updated', '{"name":"أ \\/ عبد الغفور البرعي حمد"}', '{"name":"أ \\/ عبد الغفور البرعي حمد علي"}', '2017-03-08 11:11:37'),
	(170, 14, 7, 'App\\User', 'updated', '{"name":"أ \\/ عبد الغفور البرعي"}', '{"name":"أ \\/ عبد الغفور البرعي محمد"}', '2017-03-08 11:22:01'),
	(171, 14, 9, 'App\\User', 'updated', '{"role_id":5}', '{"role_id":"4"}', '2017-03-08 11:22:48'),
	(172, 2, 2, 'App\\VisitComplaint', 'created', NULL, '{"visit_id":"39","content":"ألم","typist_id":2}', '2017-03-08 12:28:39'),
	(173, 5, 40, 'App\\Visit', 'created', NULL, '{"patient_id":"20","user_id":5,"entry_id":"4"}', '2017-03-08 12:39:00'),
	(174, 2, 1, 'App\\VisitMedicine', 'created', NULL, '{"visit_id":"40","name":"Ketofan","typist_id":2}', '2017-03-08 12:47:24'),
	(175, 5, 21, 'App\\Patient', 'created', NULL, '{"name":"سيد سيد علي ","gender":"M","address":"أسيوط","birthdate":"1962-03-08","age":"55"}', '2017-03-08 12:56:18'),
	(176, 5, 41, 'App\\Visit', 'created', NULL, '{"patient_id":21,"user_id":5,"entry_id":"4"}', '2017-03-08 12:56:18'),
	(181, 5, 41, 'App\\Visit', 'deleted', '{"patient_id":21,"c_name":null,"sid":null,"relation_id":null,"address":null,"job":null,"city":null,"phone_num":null,"entry_id":4,"user_id":5,"closed":0}', NULL, '2017-03-08 12:59:53'),
	(215, 5, 41, 'App\\Visit', 'deleted', '{"patient_id":21,"c_name":null,"sid":null,"relation_id":null,"address":null,"job":null,"city":null,"phone_num":null,"entry_id":4,"user_id":5,"closed":0}', NULL, '2017-03-08 13:06:22'),
	(216, 5, 42, 'App\\Visit', 'created', NULL, '{"patient_id":"21","user_id":5,"entry_id":"4"}', '2017-03-08 13:09:21'),
	(221, 5, 42, 'App\\Visit', 'deleted', '{"patient_id":21,"c_name":null,"sid":null,"relation_id":null,"address":null,"job":null,"city":null,"phone_num":null,"entry_id":4,"user_id":5,"closed":0}', NULL, '2017-03-08 13:11:17'),
	(222, 5, 42, 'App\\Visit', 'deleted', '{"patient_id":21,"c_name":null,"sid":null,"relation_id":null,"address":null,"job":null,"city":null,"phone_num":null,"entry_id":4,"user_id":0,"closed":0,"visit_id":42,"medical_unit_id":44,"convert_to":null}', NULL, '2017-03-08 13:11:17'),
	(223, 5, 41, 'App\\Visit', 'created', NULL, '{"patient_id":"20","user_id":5,"entry_id":"4"}', '2017-03-09 08:02:56'),
	(224, 5, 42, 'App\\Visit', 'created', NULL, '{"patient_id":"19","user_id":5,"entry_id":"4"}', '2017-03-09 08:13:46'),
	(225, 5, 43, 'App\\Visit', 'created', NULL, '{"patient_id":"20","user_id":5,"entry_id":"4"}', '2017-03-09 08:16:17'),
	(226, 5, 44, 'App\\Visit', 'created', NULL, '{"patient_id":"19","user_id":5,"entry_id":"4"}', '2017-03-09 08:16:42'),
	(227, 5, 45, 'App\\Visit', 'created', NULL, '{"patient_id":"21","user_id":5,"entry_id":"4"}', '2017-03-12 10:53:13'),
	(228, 5, 22, 'App\\Patient', 'created', NULL, '{"name":"علي محمد سيد ","gender":"M","address":"اسيوط","birthdate":"1969-07-01","age":""}', '2017-03-13 08:45:30'),
	(229, 5, 46, 'App\\Visit', 'created', NULL, '{"patient_id":22,"user_id":5,"entry_id":"4"}', '2017-03-13 08:45:30'),
	(230, 5, 23, 'App\\Patient', 'created', NULL, '{"name":"هاله سيد علي ","gender":"F","address":"أسيوط","birthdate":"2016-11-13","age":""}', '2017-03-13 08:47:41'),
	(231, 5, 47, 'App\\Visit', 'created', NULL, '{"patient_id":23,"user_id":5,"entry_id":"4"}', '2017-03-13 08:47:42'),
	(232, 5, 24, 'App\\Patient', 'created', NULL, '{"name":"كيرلس ماجد مقبل ","gender":"M","address":"اسيوط","birthdate":"2011-12-13"}', '2017-03-13 11:13:05'),
	(233, 5, 48, 'App\\Visit', 'created', NULL, '{"patient_id":24,"user_id":5,"entry_id":"4"}', '2017-03-13 11:13:05'),
	(234, 5, 25, 'App\\Patient', 'created', NULL, '{"name":"سامي هشام سيد ","gender":"M","address":"أسيوط","birthdate":"2012-01-13"}', '2017-03-13 11:17:58'),
	(235, 5, 49, 'App\\Visit', 'created', NULL, '{"patient_id":25,"user_id":5,"entry_id":"4"}', '2017-03-13 11:17:58'),
	(236, 5, 26, 'App\\Patient', 'created', NULL, '{"name":"خالد ماجد سيد ","gender":"M","address":"أسيوط","birthdate":"2001-10-13","age_in_year":"15","age_in_month":"5"}', '2017-03-13 11:19:19'),
	(237, 5, 50, 'App\\Visit', 'created', NULL, '{"patient_id":26,"user_id":5,"entry_id":"4"}', '2017-03-13 11:19:20'),
	(238, 14, 26, 'App\\Patient', 'updated', '{"name":"خالد ماجد سيد "}', '{"name":"خالد ماجد سيد سيد"}', '2017-03-13 13:23:51'),
	(239, 14, 26, 'App\\Patient', 'updated', '{"name":"خالد ماجد سيد سيد"}', '{"name":"خالد ماجد سيد "}', '2017-03-13 13:31:26'),
	(240, 14, 26, 'App\\Patient', 'updated', '{"name":"خالد ماجد سيد "}', '{"name":"خالد ماجد سيد سيد"}', '2017-03-13 13:32:34'),
	(241, 14, 26, 'App\\Patient', 'updated', '{"gender":"M"}', '{"gender":"F"}', '2017-03-13 13:42:44'),
	(242, 14, 26, 'App\\Patient', 'updated', '{"gender":"F"}', '{"gender":"M"}', '2017-03-13 13:42:50'),
	(243, 14, 26, 'App\\Patient', 'updated', '{"birthdate":"2001-10-13"}', '{"birthdate":"2001-08-13"}', '2017-03-13 13:42:55'),
	(244, 14, 26, 'App\\Patient', 'updated', '{"birthdate":"2001-08-13","age":15}', '{"birthdate":"1997-08-13","age":"19"}', '2017-03-13 13:43:02'),
	(245, 14, 26, 'App\\Patient', 'updated', '{"birthdate":"1997-08-13"}', '{"birthdate":"1998-03-13"}', '2017-03-13 13:43:06'),
	(246, 14, 26, 'App\\Patient', 'updated', '{"address":"أسيوط"}', '{"address":"أسيوط  مركز الفتح"}', '2017-03-13 13:43:17'),
	(247, 5, 51, 'App\\Visit', 'created', NULL, '{"patient_id":"26","user_id":5,"entry_id":"4"}', '2017-03-14 09:41:33'),
	(248, 5, 51, 'App\\Visit', 'updated', '{"cancelled":0}', '{"cancelled":true}', '2017-03-14 09:41:52'),
	(249, 14, 8, 'App\\User', 'deleted', '{"name":"د \\/ ريم ","email":"rim@pdreg.com","role_id":2,"deleted_at":null}', NULL, '2017-03-14 10:26:15'),
	(250, 14, 9, 'App\\User', 'deleted', '{"name":"أ \\/ عبد الغفور البرعي حمد علي","email":"ad@pdreg.com","role_id":4,"deleted_at":null}', NULL, '2017-03-14 10:27:52'),
	(251, 14, 12, 'App\\User', 'deleted', '{"name":"د \\/ مايكل","email":"m@pdreg.com","role_id":2,"deleted_at":null}', NULL, '2017-03-14 10:30:43'),
	(252, 14, 12, 'App\\User', 'deleted', '{"name":"د \\/ مايكل","email":"m@pdreg.com","role_id":2,"deleted_at":null}', NULL, '2017-03-14 10:31:32'),
	(253, 14, 12, 'App\\User', 'deleted', '{"name":"د \\/ مايكل","email":"m@pdreg.com","role_id":2,"deleted_at":null}', NULL, '2017-03-14 10:37:31'),
	(254, 14, 7, 'App\\User', 'deleted', '{"name":"أ \\/ عبد الغفور البرعي محمد","email":"abd@pdreg.com","role_id":5,"deleted_at":null}', NULL, '2017-03-14 10:39:14'),
	(255, 5, 27, 'App\\Patient', 'created', NULL, '{"name":"محمد ماجد سيد ","gender":"M","address":"أسيوط","birthdate":"1992-03-14","age":"25"}', '2017-03-14 11:23:18'),
	(256, 5, 52, 'App\\Visit', 'created', NULL, '{"patient_id":27,"user_id":5,"entry_id":"4"}', '2017-03-14 11:23:18');
/*!40000 ALTER TABLE `mf_logs` ENABLE KEYS */;

-- Dumping structure for table pdreg.migrations
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table pdreg.migrations: ~30 rows (approximately)
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` (`migration`, `batch`) VALUES
	('2014_10_12_000000_create_users_table', 1),
	('2014_10_12_100000_create_password_resets_table', 1),
	('2016_08_11_070935_create_patients_table', 2),
	('2016_08_25_082811_relations_table', 3),
	('2016_08_29_071342_create_epoints_table', 4),
	('2016_08_31_063550_create_visits_table', 5),
	('2016_09_01_104557_create_medical_units_table', 6),
	('2016_09_20_110746_create_medical_order_items_table', 7),
	('2016_09_20_111214_create_medical_devices_table', 8),
	('2016_09_20_112306_create_procedure_types_table', 9),
	('2016_09_20_112143_create_procedures_table', 10),
	('2016_10_13_084547_create_roles_table', 11),
	('2016_10_13_085433_add_role_id_attribute_user_table', 12),
	('2016_10_16_112841_add_diagnoses_attribute_medical_unit_visit_table', 19),
	('2016_10_17_071930_add_close_visit_attrbuite_visit_table', 14),
	('2016_10_20_074618_add_convert_to_attribute_medical_unit_visit_table', 15),
	('2016_10_20_095421_create_medical_unit__user_pivot_table', 16),
	('2016_12_01_095514_add_department_id_to_medical_units', 17),
	('2016_12_05_093717_create_visit_diagnoses_table', 18),
	('2016_12_08_101948_set_some_patients_table_attributes_to_null', 20),
	('2016_12_08_103617_set_some_visit_table_attributes_to_null', 21),
	('2016_12_21_095722_create_mf_logs_table', 21),
	('2016_12_19_082013_create_web_service_config_table', 22),
	('2016_12_28_081006_create_sessions_table', 23),
	('2017_01_01_105929_make_sid_null_patients_table', 23),
	('2017_01_04_091541_create_visit_complaints_table', 24),
	('2017_01_09_130845_add_procedure_ris_id_to_procedure_table', 25),
	('2017_01_10_122712_add_type_attribute_to_entrypoints_table', 25),
	('2017_01_22_100145_create_visit_medicines_table', 26),
	('2017_03_14_092809_add_attr_canceled_visits_table', 27),
	('2017_03_14_100530_add_attr_deleted_at_users_table', 28);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;

-- Dumping structure for table pdreg.patients
DROP TABLE IF EXISTS `patients`;
CREATE TABLE IF NOT EXISTS `patients` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `gender` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `sid` bigint(20) DEFAULT NULL,
  `address` text COLLATE utf8_unicode_ci,
  `birthdate` date NOT NULL,
  `age` int(11) NOT NULL,
  `issuer` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone_num` int(11) DEFAULT NULL,
  `nationality` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `job` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table pdreg.patients: ~8 rows (approximately)
/*!40000 ALTER TABLE `patients` DISABLE KEYS */;
INSERT INTO `patients` (`id`, `name`, `gender`, `sid`, `address`, `birthdate`, `age`, `issuer`, `phone_num`, `nationality`, `job`, `created_at`, `updated_at`) VALUES
	(19, 'باسم سمير عبدالسيد ', 'M', NULL, 'اسيوط', '2005-02-26', 12, NULL, NULL, NULL, NULL, '2017-02-26 13:13:18', '2017-02-26 13:13:18'),
	(20, 'سامي عبدالبديع علي ', 'M', NULL, 'اسيوط', '2005-03-02', 12, NULL, NULL, NULL, NULL, '2017-03-02 12:44:39', '2017-03-02 12:44:39'),
	(21, 'سيد سيد علي ', 'M', NULL, 'أسيوط', '1962-03-08', 55, NULL, NULL, NULL, NULL, '2017-03-08 12:56:18', '2017-03-08 12:56:18'),
	(22, 'علي محمد سيد ', 'M', NULL, 'اسيوط', '2016-10-13', 0, NULL, NULL, NULL, NULL, '2017-03-13 08:45:30', '2017-03-13 08:45:30'),
	(23, 'هاله سيد علي ', 'F', NULL, 'أسيوط', '2016-11-13', 0, NULL, NULL, NULL, NULL, '2017-03-13 08:47:41', '2017-03-13 08:47:41'),
	(24, 'كيرلس ماجد مقبل ', 'M', NULL, 'اسيوط', '2011-12-13', 0, NULL, NULL, NULL, NULL, '2017-03-13 11:13:05', '2017-03-13 11:13:05'),
	(25, 'سامي هشام سيد ', 'M', NULL, 'أسيوط', '2012-01-13', 0, NULL, NULL, NULL, NULL, '2017-03-13 11:17:58', '2017-03-13 11:17:58'),
	(26, 'خالد ماجد سيد سيد', 'M', NULL, 'أسيوط  مركز الفتح', '1998-03-13', 19, NULL, NULL, NULL, NULL, '2017-03-13 11:19:19', '2017-03-13 13:43:17'),
	(27, 'محمد ماجد سيد ', 'M', NULL, 'أسيوط', '1992-03-14', 25, NULL, NULL, NULL, NULL, '2017-03-14 11:23:18', '2017-03-14 11:23:18');
/*!40000 ALTER TABLE `patients` ENABLE KEYS */;

-- Dumping structure for table pdreg.procedures
DROP TABLE IF EXISTS `procedures`;
CREATE TABLE IF NOT EXISTS `procedures` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `proc_ris_id` text COLLATE utf8_unicode_ci,
  `type_id` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `device_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `procedures_type_id_index` (`type_id`),
  CONSTRAINT `procedures_type_id_foreign` FOREIGN KEY (`type_id`) REFERENCES `procedure_types` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table pdreg.procedures: ~0 rows (approximately)
/*!40000 ALTER TABLE `procedures` DISABLE KEYS */;
/*!40000 ALTER TABLE `procedures` ENABLE KEYS */;

-- Dumping structure for table pdreg.procedure_types
DROP TABLE IF EXISTS `procedure_types`;
CREATE TABLE IF NOT EXISTS `procedure_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table pdreg.procedure_types: ~2 rows (approximately)
/*!40000 ALTER TABLE `procedure_types` DISABLE KEYS */;
INSERT INTO `procedure_types` (`id`, `name`, `created_at`, `updated_at`) VALUES
	(1, 'Radiology', '2016-11-21 07:18:01', '2016-11-21 07:18:01'),
	(2, 'Lab', '2016-11-21 07:18:01', '2016-11-21 07:18:01');
/*!40000 ALTER TABLE `procedure_types` ENABLE KEYS */;

-- Dumping structure for table pdreg.relations
DROP TABLE IF EXISTS `relations`;
CREATE TABLE IF NOT EXISTS `relations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table pdreg.relations: ~28 rows (approximately)
/*!40000 ALTER TABLE `relations` DISABLE KEYS */;
INSERT INTO `relations` (`id`, `name`, `created_at`, `updated_at`) VALUES
	(1, '', '2016-11-21 07:16:17', '2016-11-21 07:16:17'),
	(2, 'الوالد', '2016-11-21 07:16:17', '2016-11-21 07:16:17'),
	(3, 'الأم', '2016-11-21 07:16:17', '2016-11-21 07:16:17'),
	(4, 'الزوج', '2016-11-21 07:16:17', '2016-11-21 07:16:17'),
	(5, 'الزوجة', '2016-11-21 07:16:17', '2016-11-21 07:16:17'),
	(6, 'الابن', '2016-11-21 07:16:17', '2016-11-21 07:16:17'),
	(7, 'البنت', '2016-11-21 07:16:17', '2016-11-21 07:16:17'),
	(8, 'الجد', '2016-11-21 07:16:17', '2016-11-21 07:16:17'),
	(9, 'الجدة', '2016-11-21 07:16:17', '2016-11-21 07:16:17'),
	(10, 'الاخ', '2016-11-21 07:16:17', '2016-11-21 07:16:17'),
	(11, 'الاخت', '2016-11-21 07:16:17', '2016-11-21 07:16:17'),
	(12, 'ابن الابن', '2016-11-21 07:16:17', '2016-11-21 07:16:17'),
	(13, 'ابن الاخت', '2016-11-21 07:16:17', '2016-11-21 07:16:17'),
	(14, 'العم', '2016-11-21 07:16:17', '2016-11-21 07:16:17'),
	(15, 'العمة', '2016-11-21 07:16:17', '2016-11-21 07:16:17'),
	(16, 'الخال', '2016-11-21 07:16:17', '2016-11-21 07:16:17'),
	(17, 'الخالة', '2016-11-21 07:16:17', '2016-11-21 07:16:17'),
	(18, 'ابن الاخ', '2016-11-21 07:16:17', '2016-11-21 07:16:17'),
	(19, 'بنت الاخ', '2016-11-21 07:16:17', '2016-11-21 07:16:17'),
	(20, ' ابن الاخت', '2016-11-21 07:16:17', '2016-11-21 07:16:17'),
	(21, 'ابن العم', '2016-11-21 07:16:17', '2016-11-21 07:16:17'),
	(22, 'بنت العم', '2016-11-21 07:16:17', '2016-11-21 07:16:17'),
	(23, 'ابن العمة', '2016-11-21 07:16:17', '2016-11-21 07:16:17'),
	(24, 'بنت العمة', '2016-11-21 07:16:17', '2016-11-21 07:16:17'),
	(25, 'ابن الخال', '2016-11-21 07:16:17', '2016-11-21 07:16:17'),
	(26, 'بنت الخال', '2016-11-21 07:16:17', '2016-11-21 07:16:17'),
	(27, 'ابن الخالة', '2016-11-21 07:16:17', '2016-11-21 07:16:17'),
	(28, 'بنت الخالة', '2016-11-21 07:16:17', '2016-11-21 07:16:17');
/*!40000 ALTER TABLE `relations` ENABLE KEYS */;

-- Dumping structure for table pdreg.roles
DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table pdreg.roles: ~5 rows (approximately)
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` (`id`, `name`, `created_at`, `updated_at`) VALUES
	(1, 'Admin', '2016-11-21 07:18:15', '2016-11-21 07:18:15'),
	(2, 'Doctor', '2016-11-21 07:18:15', '2016-11-21 07:18:15'),
	(3, 'Nursing', '2016-11-21 07:18:15', '2016-11-21 07:18:15'),
	(4, 'Entrypoint', '2016-11-21 07:18:15', '2016-11-21 07:18:15'),
	(5, 'Receiption', '2016-12-26 10:39:11', '2016-12-26 10:39:11'),
	(6, 'SubAdmin', '2017-03-01 08:13:13', '2017-03-01 08:13:13');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;

-- Dumping structure for table pdreg.sessions
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8_unicode_ci,
  `payload` text COLLATE utf8_unicode_ci NOT NULL,
  `last_activity` int(11) NOT NULL,
  UNIQUE KEY `sessions_id_unique` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table pdreg.sessions: ~2 rows (approximately)
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
	('1be77fa91610d10d57b3367b122cf6d58250079d', NULL, '::1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiSXFNYnNKOUlIb3FnSko3OTNrRlhzNk9sQmI2RnlaM0s5QlAwbXJQbSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozNzoiaHR0cDovL2xvY2FsaG9zdDo4MDgwL3d3dy9wZHJlZy9hZG1pbiI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjM3OiJodHRwOi8vbG9jYWxob3N0OjgwODAvd3d3L3BkcmVnL2FkbWluIjt9czo5OiJfc2YyX21ldGEiO2E6Mzp7czoxOiJ1IjtpOjE0ODk0NzA5MTU7czoxOiJjIjtpOjE0ODk0NzA5MTU7czoxOiJsIjtzOjE6IjAiO31zOjU6ImZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1489470915),
	('9a939c4a94dc6e98cda18e22b5de5635ff42bc35', NULL, '::1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36', 'YTo1OntzOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czo0MjoiaHR0cDovL2xvY2FsaG9zdDo4MDgwL3d3dy9wZHJlZy9hdXRoL2xvZ2luIjt9czo1OiJmbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjY6Il90b2tlbiI7czo0MDoiSTB6Ykp6YXVSeUR4UTVmUlZ1d3k1MVV2QzNZU2JFSzhETlBZQ2M1YSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozMToiaHR0cDovL2xvY2FsaG9zdDo4MDgwL3d3dy9wZHJlZyI7fXM6OToiX3NmMl9tZXRhIjthOjM6e3M6MToidSI7aToxNDg5NDgzNDQ1O3M6MToiYyI7aToxNDg5NDcwOTE1O3M6MToibCI7czoxOiIwIjt9fQ==', 1489483445);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;

-- Dumping structure for table pdreg.users
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `role_id` int(11) DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table pdreg.users: ~13 rows (approximately)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `name`, `email`, `password`, `role_id`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 'basem', 'basemlovephp@gmail.com', '$2y$10$X6s9Ev2Q.jV0w/MzJ0SQe./5VX0Niyx5.1X553irnSFFgz..77.uG', 1, '1BQai24yE1mDXNgqn6ORNmXUA5fqblxl49FHZqaCNh9WTjtGzMUhzXK4JXrI', '2016-08-11 07:16:02', '2017-02-26 11:02:51', NULL),
	(2, 'د/ سامي', 'sam@yahoo.com', '$2y$10$2O4SeWctstO9otjYQ6m2r.JrQ35BD7ZFRwW.pzI7r.pJJtEfmOdD2', 2, 'ZuPpCjx5zlDzque0c8xcFp5v6n8HdSr36CC9Kf8HSbMqwTZk7GfVPVFgAWsc', '2016-11-21 07:24:45', '2017-03-01 08:09:11', NULL),
	(3, 'أ/ علي محمد علي', 'ali@yahoo.com', '$2y$10$Tc.EYs20IbiK7mbHko.e3.Yi9svgu2hpntLR.pQUwBXUg3GWS70vq', 4, 'Dkp3KmVNAIDdiLUpJHGqx0w7kaJTW4CzJEpCPBeCeEThENlB4GsjVe9G2DUL', '2016-11-21 07:31:22', '2017-02-28 08:25:38', NULL),
	(4, 'أ/ سعيد سيد', 'said@yahoo.com', '$2y$10$Poii5ypBp4zV9gnjb3/zhuinK00wC3Iy84lL0DxzdQRRR/oSS3JQm', 2, 'EPacQ4Bti2VnYhp4XuWyPTV6Woi0vJNGAkkuM97J5HhIRUYwxvm8i6Q53XvR', '2016-12-05 11:09:04', '2017-01-12 11:12:35', NULL),
	(5, 'أ/ محمد أحمد', 'rec@pdreg.com', '$2y$10$sHbbzc4uCuAjnQEgbp5YfeDpeciwIGDtPicEc8O8v92B5Wv22Bb7u', 5, 'zcq6bCq6MgDBDNbsmFZSgXFUJi6UNm22kaWiXso6znpTjuNhbRryrEsRKb27', '2016-12-07 12:57:50', '2017-03-01 08:00:39', NULL),
	(7, 'أ / عبد الغفور البرعي محمد', 'abd@pdreg.com', '$2y$10$1gG5Q9G6gOvppAKQh2.jzueWWrxZLLNMmdA//PNuhQa5a0dEP6dTK', 5, NULL, '2017-01-09 13:27:47', '2017-03-14 10:39:14', '2017-03-14 10:39:14'),
	(8, 'د / ريم ', 'rim@pdreg.com', '$2y$10$0rioxfd3SyasgRDzX54PoOfPUGAIDssfwQMwEGcOJh2cBujHkXDUW', 2, '9dPRGslGoq1NcuNMkatYGA1NGQvHJCAuUyKwsO2X9P1MVRO5UlXSVPlRSSde', '2017-01-10 11:29:30', '2017-03-14 10:26:15', '2017-03-14 10:26:15'),
	(9, 'أ / عبد الغفور البرعي حمد علي', 'ad@pdreg.com', '$2y$10$7oQTQ6HNrHAP4N0kkPYieOd1zIrGE81tkiPNPFCBm.o8TKhA.4dom', 4, NULL, '2017-01-10 13:03:12', '2017-03-14 10:27:52', '2017-03-14 10:27:52'),
	(10, 'د / ظاظا ', 'zaza@pdreg.com', '$2y$10$OAHAcyEzAcQTlKskYvSeHeSVp7690W0AMDomhlU.GDDuS2.6JarVu', 2, 'x5iXkiwuC1B2TZf6N1yyu2h9f1BsHPjrLnHDkelZyLa97ceroIFb2YZVoWH0', '2017-01-12 08:12:46', '2017-01-15 10:15:16', NULL),
	(11, 'د / حسين', 'hu@pdreg.com', '$2y$10$1dzhTghRpKH4NobtLTK9vO7dN7YrwYQJ6swVm.9LsLxvXnZbC.szi', 2, 'jZ8ZTs5xuD9jJ968RN0duw6jnVDUASKWfH4HcmvNLnEe9orU8jntGm9qpYDZ', '2017-01-12 08:41:30', '2017-01-12 09:26:10', NULL),
	(12, 'د / مايكل', 'm@pdreg.com', '$2y$10$3KE.diC4tuWvpwnVSwJumekOoJfmmG0ja6iLBpGDtCu2CZpJu37Wu', 2, 'GmrHbrvfgCwpqtp8etvh98yNL3VIr1hVEBWfyd1707aLCAa0k3ESW9jWCHfI', '2017-01-15 10:18:05', '2017-03-14 10:37:31', NULL),
	(13, 'أ/ علي', 'a@pdreg.com', '$2y$10$HL.kJC4kVcvVjAa3SPf5EOG54d5TUUm8Q10MsFrghQDmx9YPeLctG', 5, NULL, '2017-01-15 11:22:41', '2017-01-15 11:23:11', NULL),
	(14, 'SubTest', 'sub@pdreg.com', '$2y$10$c5hzzaJEwp/gM0rHiT2jxOtdkTk/MAvulI8m0QQbg7/9JQOlafGK6', 6, 'GiNupcm9S1AM69BfSOc8PG5JwBA37NcDioesJHXaijCfhC6e3q2hqJGt9Jwk', '2017-01-15 11:37:57', '2017-01-15 12:07:17', NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

-- Dumping structure for table pdreg.visits
DROP TABLE IF EXISTS `visits`;
CREATE TABLE IF NOT EXISTS `visits` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `patient_id` int(10) unsigned NOT NULL,
  `c_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sid` bigint(20) DEFAULT NULL,
  `relation_id` int(11) DEFAULT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `job` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone_num` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `entry_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `closed` tinyint(1) NOT NULL DEFAULT '0',
  `cancelled` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `visits_patient_id_index` (`patient_id`),
  KEY `visits_relation_id_index` (`relation_id`),
  CONSTRAINT `visits_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table pdreg.visits: ~15 rows (approximately)
/*!40000 ALTER TABLE `visits` DISABLE KEYS */;
INSERT INTO `visits` (`id`, `patient_id`, `c_name`, `sid`, `relation_id`, `address`, `job`, `city`, `phone_num`, `entry_id`, `user_id`, `closed`, `cancelled`, `created_at`, `updated_at`) VALUES
	(34, 19, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 0, 0, '2017-02-26 13:13:19', '2017-02-26 13:13:19'),
	(35, 19, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 0, 0, '2017-02-28 08:25:52', '2017-02-28 08:25:52'),
	(36, 19, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 0, 0, '2017-03-01 08:00:35', '2017-03-01 08:00:35'),
	(37, 19, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 0, 0, '2017-03-02 09:26:52', '2017-03-02 09:26:52'),
	(38, 20, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 7, 0, 0, '2017-03-02 12:44:40', '2017-03-02 12:44:40'),
	(39, 19, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 0, 0, '2017-03-08 08:18:01', '2017-03-08 08:18:01'),
	(40, 20, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 0, 0, '2017-03-08 12:39:00', '2017-03-08 12:39:00'),
	(43, 20, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 0, 0, '2017-03-09 08:16:17', '2017-03-09 08:16:17'),
	(44, 19, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 0, 0, '2017-03-09 08:16:42', '2017-03-09 08:16:42'),
	(45, 21, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 0, 0, '2017-03-12 10:53:13', '2017-03-12 10:53:13'),
	(46, 22, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 0, 0, '2017-03-13 08:45:30', '2017-03-13 08:45:30'),
	(47, 23, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 0, 0, '2017-03-13 08:47:42', '2017-03-13 08:47:42'),
	(48, 24, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 0, 0, '2017-03-13 11:13:05', '2017-03-13 11:13:05'),
	(49, 25, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 0, 0, '2017-03-13 11:17:58', '2017-03-13 11:17:58'),
	(50, 26, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 0, 0, '2017-03-13 11:19:20', '2017-03-13 11:19:20'),
	(51, 26, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 0, 1, '2017-03-14 09:41:33', '2017-03-14 09:41:52'),
	(52, 27, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 0, 0, '2017-03-14 11:23:18', '2017-03-14 11:23:18');
/*!40000 ALTER TABLE `visits` ENABLE KEYS */;

-- Dumping structure for table pdreg.visit_complaints
DROP TABLE IF EXISTS `visit_complaints`;
CREATE TABLE IF NOT EXISTS `visit_complaints` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `visit_id` int(10) unsigned NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `typist_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `visit_complaints_visit_id_index` (`visit_id`),
  CONSTRAINT `visit_complaints_visit_id_foreign` FOREIGN KEY (`visit_id`) REFERENCES `visits` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table pdreg.visit_complaints: ~0 rows (approximately)
/*!40000 ALTER TABLE `visit_complaints` DISABLE KEYS */;
INSERT INTO `visit_complaints` (`id`, `visit_id`, `content`, `typist_id`, `created_at`, `updated_at`) VALUES
	(1, 36, 'ألم', 2, '2017-03-01 08:08:45', '2017-03-01 08:08:45'),
	(2, 39, 'ألم', 2, '2017-03-08 12:28:39', '2017-03-08 12:28:39');
/*!40000 ALTER TABLE `visit_complaints` ENABLE KEYS */;

-- Dumping structure for table pdreg.visit_diagnoses
DROP TABLE IF EXISTS `visit_diagnoses`;
CREATE TABLE IF NOT EXISTS `visit_diagnoses` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `visit_id` int(10) unsigned NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `typist_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `visit_diagnoses_visit_id_index` (`visit_id`),
  CONSTRAINT `visit_diagnoses_visit_id_foreign` FOREIGN KEY (`visit_id`) REFERENCES `visits` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table pdreg.visit_diagnoses: ~0 rows (approximately)
/*!40000 ALTER TABLE `visit_diagnoses` DISABLE KEYS */;
/*!40000 ALTER TABLE `visit_diagnoses` ENABLE KEYS */;

-- Dumping structure for table pdreg.visit_medicines
DROP TABLE IF EXISTS `visit_medicines`;
CREATE TABLE IF NOT EXISTS `visit_medicines` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `visit_id` int(10) unsigned NOT NULL,
  `name` text COLLATE utf8_unicode_ci NOT NULL,
  `typist_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `visit_medicines_visit_id_index` (`visit_id`),
  CONSTRAINT `visit_medicines_visit_id_foreign` FOREIGN KEY (`visit_id`) REFERENCES `visits` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table pdreg.visit_medicines: ~0 rows (approximately)
/*!40000 ALTER TABLE `visit_medicines` DISABLE KEYS */;
INSERT INTO `visit_medicines` (`id`, `visit_id`, `name`, `typist_id`, `created_at`, `updated_at`) VALUES
	(1, 40, 'Ketofan', 2, '2017-03-08 12:47:24', '2017-03-08 12:47:24');
/*!40000 ALTER TABLE `visit_medicines` ENABLE KEYS */;

-- Dumping structure for table pdreg.wsconfig
DROP TABLE IF EXISTS `wsconfig`;
CREATE TABLE IF NOT EXISTS `wsconfig` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `url` text COLLATE utf8_unicode_ci NOT NULL,
  `sending_app` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `sending_fac` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `receiving_app` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `receiving_fac` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table pdreg.wsconfig: ~0 rows (approximately)
/*!40000 ALTER TABLE `wsconfig` DISABLE KEYS */;
/*!40000 ALTER TABLE `wsconfig` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
