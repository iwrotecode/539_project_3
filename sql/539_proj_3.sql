-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 11, 2012 at 10:56 PM
-- Server version: 5.5.16
-- PHP Version: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `539_proj_3`
--

-- --------------------------------------------------------

--
-- Table structure for table `cms_ads`
--

CREATE TABLE IF NOT EXISTS `cms_ads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `pubdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `approved` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cms_ads_which_edition`
--

CREATE TABLE IF NOT EXISTS `cms_ads_which_edition` (
  `ads_id` int(11) NOT NULL,
  `edition_id` int(11) NOT NULL,
  PRIMARY KEY (`ads_id`,`edition_id`),
  KEY `edition_id` (`edition_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cms_banner`
--

CREATE TABLE IF NOT EXISTS `cms_banner` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(100) NOT NULL,
  `count` int(11) NOT NULL,
  `weight` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cms_edition`
--

CREATE TABLE IF NOT EXISTS `cms_edition` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `editionname` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cms_editoral`
--

CREATE TABLE IF NOT EXISTS `cms_editoral` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` text NOT NULL,
  `current` tinyint(1) NOT NULL,
  `pubdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cms_news`
--

CREATE TABLE IF NOT EXISTS `cms_news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `pubdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cms_news_which_edition`
--

CREATE TABLE IF NOT EXISTS `cms_news_which_edition` (
  `news_id` int(11) NOT NULL,
  `edition_id` int(11) NOT NULL,
  PRIMARY KEY (`news_id`,`edition_id`),
  KEY `edition_id` (`edition_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cms_user`
--

CREATE TABLE IF NOT EXISTS `cms_user` (
  `username` varchar(10) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `access` int(11) NOT NULL,
  PRIMARY KEY (`username`),
  KEY `access` (`access`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cms_user_type`
--

CREATE TABLE IF NOT EXISTS `cms_user_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_type` varchar(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_type` (`user_type`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `cms_user_type`
--

INSERT INTO `cms_user_type` (`id`, `user_type`) VALUES
(2, 'admin'),
(4, 'advertiser'),
(3, 'editor'),
(1, 'none');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cms_ads_which_edition`
--
ALTER TABLE `cms_ads_which_edition`
  ADD CONSTRAINT `cms_ads_which_edition_ibfk_2` FOREIGN KEY (`edition_id`) REFERENCES `cms_edition` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cms_ads_which_edition_ibfk_1` FOREIGN KEY (`ads_id`) REFERENCES `cms_ads` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cms_news_which_edition`
--
ALTER TABLE `cms_news_which_edition`
  ADD CONSTRAINT `cms_news_which_edition_ibfk_2` FOREIGN KEY (`news_id`) REFERENCES `cms_news` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cms_news_which_edition_ibfk_1` FOREIGN KEY (`edition_id`) REFERENCES `cms_edition` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cms_user`
--
ALTER TABLE `cms_user`
  ADD CONSTRAINT `cms_user_ibfk_1` FOREIGN KEY (`access`) REFERENCES `cms_user_type` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
