-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jun 27, 2013 at 07:12 AM
-- Server version: 5.5.27
-- PHP Version: 5.4.7

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `zf2tutorial`
--

-- --------------------------------------------------------

--
-- Table structure for table `album`
--

DROP TABLE IF EXISTS `album`;
CREATE TABLE IF NOT EXISTS `album` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `artist` varchar(100) NOT NULL,
  `title` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `album`
--

INSERT INTO `album` (`id`, `artist`, `title`) VALUES
(1, 'The  Military  Wives', 'In  My  Dreams'),
(2, 'Adele', '21'),
(3, 'Bruce  Springsteen', 'Wrecking Ball (Deluxe)'),
(4, 'Lana  Del  Rey', 'Born  To  Die'),
(5, 'Gotye', 'Making  Mirrors'),
(6, 'Adarsh', 'Adarsh'),
(7, 'Ahmed', 'Ahmed'),
(8, 'Arayan', 'Arayan');

-- --------------------------------------------------------

--
-- Table structure for table `fbprofiles`
--

DROP TABLE IF EXISTS `fbprofiles`;
CREATE TABLE IF NOT EXISTS `fbprofiles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(500) NOT NULL,
  `first_name` varchar(500) NOT NULL,
  `last_name` varchar(500) NOT NULL,
  `link` text NOT NULL,
  `username` varchar(500) NOT NULL,
  `gender` varchar(500) NOT NULL,
  `timezone` varchar(500) DEFAULT NULL,
  `locale` varchar(500) DEFAULT NULL,
  `verified` tinyint(1) NOT NULL DEFAULT '0',
  `updated_time` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `fbprofiles`
--

INSERT INTO `fbprofiles` (`id`, `name`, `first_name`, `last_name`, `link`, `username`, `gender`, `timezone`, `locale`, `verified`, `updated_time`) VALUES
(1, 'Tahmina Khatoon', 'Tahmina', 'Khatoon', 'asdf', 'tahmina', 'Female', '', '', 1, '');

-- --------------------------------------------------------

--
-- Table structure for table `profiles`
--

DROP TABLE IF EXISTS `profiles`;
CREATE TABLE IF NOT EXISTS `profiles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `profiles`
--

INSERT INTO `profiles` (`id`, `user_id`, `first_name`, `last_name`, `created`, `modified`) VALUES
(1, 2, 'Tahmina', 'Khatoon', '2013-06-25 06:24:11', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `resources`
--

DROP TABLE IF EXISTS `resources`;
CREATE TABLE IF NOT EXISTS `resources` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(500) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=23 ;

--
-- Dumping data for table `resources`
--

INSERT INTO `resources` (`id`, `name`) VALUES
(12, 'album'),
(22, 'fbprofiles'),
(1, 'home'),
(18, 'resources/add'),
(20, 'resources/delete'),
(19, 'resources/edit'),
(17, 'resources/index'),
(16, 'resources/search'),
(11, 'roles'),
(13, 'roles/add'),
(15, 'roles/delete'),
(14, 'roles/edit'),
(4, 'users/add'),
(9, 'users/authenticate'),
(21, 'users/confirmEmail'),
(6, 'users/delete'),
(5, 'users/edit'),
(3, 'users/index'),
(7, 'users/login'),
(8, 'users/logout'),
(10, 'users/registration'),
(2, 'users/search');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`) VALUES
(1, 'Administrator'),
(3, 'Guest'),
(2, 'Register user');

-- --------------------------------------------------------

--
-- Table structure for table `role_resources`
--

DROP TABLE IF EXISTS `role_resources`;
CREATE TABLE IF NOT EXISTS `role_resources` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(10) unsigned NOT NULL,
  `resource_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `role_resource` (`role_id`,`resource_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=29 ;

--
-- Dumping data for table `role_resources`
--

INSERT INTO `role_resources` (`id`, `role_id`, `resource_id`) VALUES
(1, 1, 1),
(21, 1, 2),
(17, 1, 3),
(12, 1, 4),
(16, 1, 5),
(15, 1, 6),
(18, 1, 7),
(19, 1, 8),
(13, 1, 9),
(20, 1, 10),
(11, 1, 11),
(10, 1, 12),
(7, 1, 13),
(9, 1, 14),
(8, 1, 15),
(6, 1, 16),
(5, 1, 17),
(2, 1, 18),
(4, 1, 19),
(3, 1, 20),
(14, 1, 21),
(27, 1, 22),
(22, 3, 1),
(23, 3, 7),
(24, 3, 8),
(25, 3, 9),
(26, 3, 10),
(28, 3, 22);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(256) NOT NULL,
  `email` varchar(256) NOT NULL,
  `password` varchar(256) NOT NULL,
  `email_check_code` varchar(256) DEFAULT NULL,
  `is_disabled` tinyint(1) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `email_check_code`, `is_disabled`, `created`, `modified`) VALUES
(2, 'tahmina', 'tahmina@yahoo.com', 'e10adc3949ba59abbe56e057f20f883e', 'b301ad74b7294d0c7c7f92ea36f6b776', 1, '0000-00-00 00:00:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_roles`
--

DROP TABLE IF EXISTS `user_roles`;
CREATE TABLE IF NOT EXISTS `user_roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_role` (`user_id`,`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
SET FOREIGN_KEY_CHECKS=1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
