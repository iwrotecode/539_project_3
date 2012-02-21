-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 21, 2012 at 03:30 AM
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

DROP TABLE IF EXISTS `cms_ads`;
CREATE TABLE IF NOT EXISTS `cms_ads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `pubdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `approved` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `cms_ads`
--

INSERT INTO `cms_ads` (`id`, `title`, `content`, `pubdate`, `approved`) VALUES
(1, 'Water Hole', 'Will dig holes for your well to get water from.', '2012-02-21 03:12:53', 1),
(2, 'Bicycle for Sale', 'Bike for sale, good shape.', '2012-02-21 03:14:42', 1),
(3, 'Computer For sale', 'Dead, but good for parts', '2012-02-21 03:14:42', 1),
(4, 'Free Web Hosting', 'Hosting for free, with PHP', '2012-02-21 03:15:50', 1),
(5, 'Free kittens', 'come to my house for free kittens and candy!\r\n\r\n', '2012-02-21 03:15:50', 0),
(6, 'Parking spot for sale', 'RIT parking for sale, right by Xroads.', '2012-02-21 03:20:18', 0);

-- --------------------------------------------------------

--
-- Table structure for table `cms_ads_which_edition`
--

DROP TABLE IF EXISTS `cms_ads_which_edition`;
CREATE TABLE IF NOT EXISTS `cms_ads_which_edition` (
  `ads_id` int(11) NOT NULL,
  `edition_id` int(11) NOT NULL,
  PRIMARY KEY (`ads_id`,`edition_id`),
  KEY `edition_id` (`edition_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cms_ads_which_edition`
--

INSERT INTO `cms_ads_which_edition` (`ads_id`, `edition_id`) VALUES
(1, 1),
(2, 1),
(4, 1),
(1, 2),
(5, 2),
(2, 3),
(5, 3),
(1, 5),
(2, 5);

-- --------------------------------------------------------

--
-- Table structure for table `cms_banner`
--

DROP TABLE IF EXISTS `cms_banner`;
CREATE TABLE IF NOT EXISTS `cms_banner` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(100) NOT NULL,
  `count` int(11) NOT NULL,
  `weight` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21 ;

--
-- Dumping data for table `cms_banner`
--

INSERT INTO `cms_banner` (`id`, `filename`, `count`, `weight`) VALUES
(12, 'ad1.png', 0, 3),
(13, 'ad2.png', 0, 2),
(14, 'ad3.png', 0, 1),
(15, 'banner_images1.png', 57, 1),
(16, 'banners.jpg', 19, 3),
(17, 'dummyBanner3.jpg', 58, 1),
(18, 'spotify586.jpg', 29, 2),
(19, 'dummy-banner.jpg', 29, 2),
(20, 'DummyText-creative-job-ad.png', 29, 2);

-- --------------------------------------------------------

--
-- Table structure for table `cms_edition`
--

DROP TABLE IF EXISTS `cms_edition`;
CREATE TABLE IF NOT EXISTS `cms_edition` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `editionname` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `cms_edition`
--

INSERT INTO `cms_edition` (`id`, `editionname`) VALUES
(1, 'Sports'),
(2, 'Business'),
(3, 'Technology'),
(4, 'Health'),
(5, 'Funnies');

-- --------------------------------------------------------

--
-- Table structure for table `cms_editorial`
--

DROP TABLE IF EXISTS `cms_editorial`;
CREATE TABLE IF NOT EXISTS `cms_editorial` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` text NOT NULL,
  `current` tinyint(1) NOT NULL,
  `pubdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `cms_editorial`
--

INSERT INTO `cms_editorial` (`id`, `content`, `current`, `pubdate`) VALUES
(6, 'Recent technology news from BBC World is aggregated here.At times, articles will be added by the editor (me!).Enjoy, and please leave feedback.', 0, '2012-02-21 08:27:38'),
(7, '<p>Recent technology news from BBC World is aggregated here.</p><p>At times, articles will be added by the editor (me!).</p><p>Enjoy, and please leave feedback.</p>', 1, '2012-02-21 08:29:11');

-- --------------------------------------------------------

--
-- Table structure for table `cms_news`
--

DROP TABLE IF EXISTS `cms_news`;
CREATE TABLE IF NOT EXISTS `cms_news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `pubdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=211 ;

--
-- Dumping data for table `cms_news`
--

INSERT INTO `cms_news` (`id`, `subject`, `content`, `pubdate`) VALUES
(178, 'Smart TV dust settles after CES', 'Are connected TVs ready for prime time?', '2012-01-15 17:04:10'),
(179, 'Brain scientists make cyborg rats', 'Brain microchips may herald health breakthroughs', '2012-01-17 10:00:26'),
(180, 'India''s appetite for ''apps'' grows', 'India is a key base and market for mobile application developers', '2012-01-18 21:15:18'),
(181, 'How to offset your ''conflict mineral'' guilt', 'How to offset your ''conflict mineral'' guilt', '2012-01-19 17:11:07'),
(182, 'Snapshot of Kodak hits and misses', 'The highs and lows of the cash-strapped camera maker', '2012-01-19 20:03:11'),
(183, 'Pearson on Apple''s e-textbooks', 'Pearson on efforts to promote digital books in the classroom', '2012-01-20 00:39:31'),
(184, 'Viewpoint: Piracy protest action', 'Supporters of the site shutdowns and advocates for the anti-piracy laws make their case', '2012-01-18 18:16:05'),
(185, 'Physicist''s one million volt coil', 'A physicist dressed in a metal suit shows off a one million volt homemade Tesla coil at the 2012 Consumer Electronics Show.', '2012-01-14 08:45:55'),
(186, 'Will users pay for online banking?', 'Stephen Whitehouse from PricewaterhouseCoopers explained why online banking was rising in popularity.', '2012-01-16 17:58:12'),
(187, 'Museum visitors asked to scan faces in 3D', 'Visitors to London''s Science Museum are being invited to have their faces scanned in 3D. The ''Me in 3D'' booth at the museum uses nine cameras to build a virtual image visitors can then view and manipulate.', '2012-01-16 21:42:54'),
(188, 'Robot used to see blood vessels', 'Robotic technology has been used by surgeons at St Mary''s Hospital in London to look at a patient''s blood vessels, in what''s claimed to be a ''world first''.', '2012-01-16 19:55:22'),
(189, 'Dual view TV and Kinect 3D scanning', 'How to watch two programmes simultaneously on the same television plus a new application for Microsoft''s Kinect which turns the idea of the device on its head.', '2012-01-16 15:52:29'),
(190, 'Wikipedia co-founder defends shut down', 'Wikipedia''s founder, Jimmy Wales, says he agrees with protecting copyrighted content but not with the way it is being done.', '2012-01-18 07:10:20'),
(191, 'Online teen editor''s secrets to success', 'Tavi Gevinson talks to the BBC about being a blogger, an editor-in-chief at RookieMag.com and a young girl.', '2012-01-17 10:01:50'),
(192, 'TV waves ''make for better broadband''', 'Demand for wireless services means that the airwaves are running out of room', '2011-12-10 07:07:17'),
(193, 'What were the big trends of CES?', 'Connected TVs, new tablets and a look towards the launch of Windows 8', '2012-01-13 22:52:55'),
(194, 'Sculley clears up Jobs ''myths''', 'The tech company''s ex-CEO discusses the impact of its first handheld device', '2012-01-13 05:10:44'),
(195, 'Quantum trick for cloud computing', 'Researchers demonstrate that, if quantum computers fulfil their promise of ultra-fast computing, the job could be farmed out to "the cloud" while remaining secure.', '2012-01-20 00:17:03'),
(196, 'Facebook ''takes Brazil top spot''', 'Figures suggest Facebook passed Google''s Orkut social network as the most visited social network in Brazil last month.', '2012-01-18 17:37:54'),
(197, 'Yahoo founder Jerry Yang resigns', 'Jerry Yang, co-founder and former chief executive of Yahoo!, resigns from the company''s board.', '2012-01-18 11:07:12'),
(198, 'eBay reports jump in its profits', 'Internet auction site eBay reports a jump in profits, helped partly by the sale of its remaining stake in Skype.', '2012-01-19 03:55:16'),
(199, 'Video games prizes up for grabs', 'Video games developers could win &pound;25,000 of funding in a competition run by Abertay University in Dundee.', '2012-01-19 17:16:52'),
(200, 'Scott backs short film festival', 'British director Sir Ridley Scott has joined forces with YouTube to launch an online film festival which will see 10 finalists sent to Venice.', '2012-01-19 17:12:24'),
(201, 'Pirate-hunting solicitor barred', 'Andrew Crossley, the solicitor behind controversial law firm ACS: Law, is suspended from the profession.', '2012-01-18 21:27:35'),
(202, 'Virgin Media UK broadband failure', 'Virgin Media says its broadband service is fully restored following a nationwide failure on Tuesday evening.', '2012-01-18 17:40:43'),
(203, 'Facebook unveils new applications', 'Facebook adds array of new apps in bid to get users to spend more time on the social networking site.', '2012-01-19 09:50:16'),
(204, 'Anti-malware code''s spambot flaw', 'McAfee is releasing a patch to fix a vulnerability in its anti-malware software that allows spammers to hijack users'' computers.', '2012-01-19 16:47:18'),
(205, 'Kodak sues Samsung over patents', 'Kodak sues Samsung over claims that five of its digital imaging patents have been infringed as litigation across the tech sector increases.', '2012-01-19 22:11:47'),
(206, 'Apple launches e-textbook tools', 'Apple has launched new interactive tools which it believes will "reinvent the textbook".', '2012-01-20 00:12:23'),
(207, 'Sopa protest not over - Wikipedia', 'Wikipedia insists its protest over US anti-piracy legislation will continue, while UK interests say they are watching the situation "closely".', '2012-01-19 17:42:47'),
(208, 'Intel profits beat expectations', 'The world''s largest maker of computer chips, Intel, saw quarterly profits beat Wall Street forecasts, amid promises to ramp up spending.', '2012-01-20 04:55:03'),
(209, 'Microsoft profits down slightly', 'Microsoft profits in the three months to the end of December fall slightly as lower computer sales hit its core Windows business.', '2012-01-20 03:50:30'),
(210, 'Google revenue misses estimates', 'Google reports a 27% increase in revenue, but even that is not good enough to meet Wall Street estimates, sending shares tumbling.', '2012-01-20 03:52:24');

-- --------------------------------------------------------

--
-- Table structure for table `cms_news_which_edition`
--

DROP TABLE IF EXISTS `cms_news_which_edition`;
CREATE TABLE IF NOT EXISTS `cms_news_which_edition` (
  `news_id` int(11) NOT NULL,
  `edition_id` int(11) NOT NULL,
  PRIMARY KEY (`news_id`,`edition_id`),
  KEY `edition_id` (`edition_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cms_news_which_edition`
--

INSERT INTO `cms_news_which_edition` (`news_id`, `edition_id`) VALUES
(208, 1),
(206, 2),
(209, 2),
(208, 3),
(207, 4),
(209, 4),
(206, 5),
(208, 5);

-- --------------------------------------------------------

--
-- Table structure for table `cms_user`
--

DROP TABLE IF EXISTS `cms_user`;
CREATE TABLE IF NOT EXISTS `cms_user` (
  `username` varchar(10) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `access` int(11) NOT NULL,
  PRIMARY KEY (`username`),
  KEY `access` (`access`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cms_user`
--

INSERT INTO `cms_user` (`username`, `email`, `password`, `access`) VALUES
('advertiser', 'ad@rit.edu', 'dddd5d7b474d2c78ebbb833789c4bfd721edf4bf', 3),
('editor', 'editor@rit.edu', 'dddd5d7b474d2c78ebbb833789c4bfd721edf4bf', 2),
('met8481', 'met8481@rit.edu', '2a6fa03fcf4e18ea585906d4d7e49f648a1da670', 1),
('none', 'none@rit.edu', 'dddd5d7b474d2c78ebbb833789c4bfd721edf4bf', 0),
('pjm8632', 'pjm8632@rit.edu', '54eba525c173f84b0977e659b79cf0375f4286df', 1),
('test', 'test@rit.edu', 'dddd5d7b474d2c78ebbb833789c4bfd721edf4bf', 1);

-- --------------------------------------------------------

--
-- Table structure for table `cms_user_type`
--

DROP TABLE IF EXISTS `cms_user_type`;
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
(1, 'admin'),
(3, 'advertiser'),
(2, 'editor'),
(0, 'none');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cms_ads_which_edition`
--
ALTER TABLE `cms_ads_which_edition`
  ADD CONSTRAINT `cms_ads_which_edition_ibfk_1` FOREIGN KEY (`ads_id`) REFERENCES `cms_ads` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cms_ads_which_edition_ibfk_2` FOREIGN KEY (`edition_id`) REFERENCES `cms_edition` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cms_news_which_edition`
--
ALTER TABLE `cms_news_which_edition`
  ADD CONSTRAINT `cms_news_which_edition_ibfk_1` FOREIGN KEY (`edition_id`) REFERENCES `cms_edition` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cms_news_which_edition_ibfk_2` FOREIGN KEY (`news_id`) REFERENCES `cms_news` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cms_user`
--
ALTER TABLE `cms_user`
  ADD CONSTRAINT `cms_user_ibfk_1` FOREIGN KEY (`access`) REFERENCES `cms_user_type` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
