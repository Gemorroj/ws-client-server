/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


CREATE DATABASE IF NOT EXISTS `test` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `test`;

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `data` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=102 DEFAULT CHARSET=utf8;

/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` (`id`, `name`, `data`) VALUES
	(1, 'test1', NULL),
	(2, 'test0', NULL),
	(3, 'test1', NULL),
	(4, 'test2', NULL),
	(5, 'test3', NULL),
	(6, 'test4', NULL),
	(7, 'test5', NULL),
	(8, 'test6', NULL),
	(9, 'test7', NULL),
	(10, 'test8', NULL),
	(11, 'test9', NULL),
	(12, 'test10', NULL),
	(13, 'test11', NULL),
	(14, 'test12', NULL),
	(15, 'test13', NULL),
	(16, 'test14', NULL),
	(17, 'test15', NULL),
	(18, 'test16', NULL),
	(19, 'test17', NULL),
	(20, 'test18', NULL),
	(21, 'test19', NULL),
	(22, 'test20', NULL),
	(23, 'test21', NULL),
	(24, 'test22', NULL),
	(25, 'test23', NULL),
	(26, 'test24', NULL),
	(27, 'test25', NULL),
	(28, 'test26', NULL),
	(29, 'test27', NULL),
	(30, 'test28', NULL),
	(31, 'test29', NULL),
	(32, 'test30', NULL),
	(33, 'test31', NULL),
	(34, 'test32', NULL),
	(35, 'test33', NULL),
	(36, 'test34', NULL),
	(37, 'test35', NULL),
	(38, 'test36', NULL),
	(39, 'test37', NULL),
	(40, 'test38', NULL),
	(41, 'test39', NULL),
	(42, 'test40', NULL),
	(43, 'test41', NULL),
	(44, 'test42', NULL),
	(45, 'test43', NULL),
	(46, 'test44', NULL),
	(47, 'test45', NULL),
	(48, 'test46', NULL),
	(49, 'test47', NULL),
	(50, 'test48', NULL),
	(51, 'test49', NULL),
	(52, 'test50', NULL),
	(53, 'test51', NULL),
	(54, 'test52', NULL),
	(55, 'test53', NULL),
	(56, 'test54', NULL),
	(57, 'test55', NULL),
	(58, 'test56', NULL),
	(59, 'test57', NULL),
	(60, 'test58', NULL),
	(61, 'test59', NULL),
	(62, 'test60', NULL),
	(63, 'test61', NULL),
	(64, 'test62', NULL),
	(65, 'test63', NULL),
	(66, 'test64', NULL),
	(67, 'test65', NULL),
	(68, 'test66', NULL),
	(69, 'test67', NULL),
	(70, 'test68', NULL),
	(71, 'test69', NULL),
	(72, 'test70', NULL),
	(73, 'test71', NULL),
	(74, 'test72', NULL),
	(75, 'test73', NULL),
	(76, 'test74', NULL),
	(77, 'test75', NULL),
	(78, 'test76', NULL),
	(79, 'test77', NULL),
	(80, 'test78', NULL),
	(81, 'test79', NULL),
	(82, 'test80', NULL),
	(83, 'test81', NULL),
	(84, 'test82', NULL),
	(85, 'test83', NULL),
	(86, 'test84', NULL),
	(87, 'test85', NULL),
	(88, 'test86', NULL),
	(89, 'test87', NULL),
	(90, 'test88', NULL),
	(91, 'test89', NULL),
	(92, 'test90', NULL),
	(93, 'test91', NULL),
	(94, 'test92', NULL),
	(95, 'test93', NULL),
	(96, 'test94', NULL),
	(97, 'test95', NULL),
	(98, 'test96', NULL),
	(99, 'test97', NULL),
	(100, 'test98', NULL),
	(101, 'test99', NULL);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
