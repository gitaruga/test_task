-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Feb 17, 2013 at 03:06 PM
-- Server version: 5.5.25
-- PHP Version: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `test_task`
--
CREATE DATABASE `test_task` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `test_task`;

-- --------------------------------------------------------

--
-- Table structure for table `marks`
--

CREATE TABLE IF NOT EXISTS `marks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mark` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `marks`
--

INSERT INTO `marks` (`id`, `mark`) VALUES
(2, 'Samsung'),
(3, 'Bosch'),
(4, 'LG'),
(5, 'Sony'),
(7, 'Braun'),
(8, 'Kenwood'),
(9, 'Atlant'),
(11, 'Indesit');

-- --------------------------------------------------------

--
-- Table structure for table `traffic`
--

CREATE TABLE IF NOT EXISTS `traffic` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `type` enum('I','O') NOT NULL,
  `ware_id` int(10) unsigned NOT NULL,
  `quantity` int(10) unsigned NOT NULL,
  PRIMARY KEY (`date`,`type`,`ware_id`),
  UNIQUE KEY `id` (`id`),
  KEY `quantity` (`quantity`),
  KEY `quantity_2` (`quantity`),
  KEY `id_2` (`id`),
  KEY `date` (`date`),
  KEY `type` (`type`),
  KEY `ware_id` (`ware_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=53 ;

--
-- Dumping data for table `traffic`
--

INSERT INTO `traffic` (`id`, `date`, `type`, `ware_id`, `quantity`) VALUES
(43, '2013-02-01', 'I', 4, 9),
(32, '2013-02-01', 'I', 11, 3),
(49, '2013-02-01', 'I', 12, 22),
(44, '2013-02-01', 'I', 15, 12),
(12, '2013-02-02', 'I', 5, 40),
(35, '2013-02-02', 'I', 13, 15),
(14, '2013-02-02', 'O', 5, 20),
(15, '2013-02-03', 'I', 5, 10),
(13, '2013-02-03', 'O', 5, 10),
(16, '2013-02-04', 'I', 5, 10),
(17, '2013-02-04', 'O', 5, 30),
(36, '2013-02-04', 'O', 13, 2),
(38, '2013-02-06', 'I', 2, 4),
(41, '2013-02-07', 'I', 14, 3),
(39, '2013-02-09', 'I', 2, 8),
(37, '2013-02-10', 'I', 13, 6),
(47, '2013-02-10', 'I', 16, 5),
(50, '2013-02-10', 'I', 17, 15),
(30, '2013-02-10', 'I', 19, 12),
(23, '2013-02-10', 'O', 5, 500),
(11, '2013-02-11', 'I', 5, 1000),
(51, '2013-02-12', 'O', 12, 10),
(52, '2013-02-14', 'O', 17, 3),
(40, '2013-02-15', 'O', 2, 5),
(33, '2013-02-15', 'O', 11, 1),
(31, '2013-02-17', 'I', 11, 5),
(34, '2013-02-17', 'I', 13, 18),
(46, '2013-02-17', 'O', 4, 3),
(45, '2013-02-17', 'O', 5, 1),
(42, '2013-02-17', 'O', 14, 3),
(48, '2013-02-17', 'O', 16, 4);

-- --------------------------------------------------------

--
-- Table structure for table `wares`
--

CREATE TABLE IF NOT EXISTS `wares` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mark_id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `price` float NOT NULL,
  `weight` float NOT NULL,
  `quantity` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

--
-- Dumping data for table `wares`
--

INSERT INTO `wares` (`id`, `mark_id`, `title`, `price`, `weight`, `quantity`) VALUES
(2, 11, 'Кух. комб. KT-001', 500, 2.3, 7),
(4, 4, 'Миксер MX-920', 300, 1.5, 6),
(5, 9, 'Холодильник 6016', 3000, 50, 499),
(7, 4, 'Телевизор TT410M', 1000, 4.5, 0),
(11, 3, 'Холодильник KGV 39VI30', 9000, 16, 7),
(12, 2, 'RL55TTE2C1', 10000, 25, 12),
(13, 7, 'Миксер MT-120L', 220, 2.1, 37),
(14, 11, 'СМА WER1236', 6547, 16, 0),
(15, 4, 'Телевизор', 2000, 5.3, 12),
(16, 5, 'Телевизор LKJ-6598', 6549, 9, 1),
(17, 2, 'Телевизор OFO-65', 9873, 8, 12),
(18, 4, 'Телевизор GDS987F', 1999, 3, 0),
(19, 3, 'СМА KLJH-65', 500, 32, 12);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
