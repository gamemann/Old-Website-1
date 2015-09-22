-- phpMyAdmin SQL Dump
-- version 4.2.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 21, 2015 at 04:56 PM
-- Server version: 5.6.17
-- PHP Version: 5.5.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `site1`
--

-- --------------------------------------------------------

--
-- Table structure for table `dropdowns`
--

CREATE TABLE IF NOT EXISTS `dropdowns` (
`id` int(11) NOT NULL,
  `navbarid` int(255) NOT NULL,
  `name` varchar(1024) NOT NULL,
  `newtab` int(255) NOT NULL,
  `link` varchar(1024) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `dropdowns`
--

INSERT INTO `dropdowns` (`id`, `navbarid`, `name`, `newtab`, `link`) VALUES
(1, 2, 'Rates', 0, 'pages/rates/index.php'),
(2, 3, 'GFL (Games for Life)', 1, 'http://GFLClan.com/'),
(3, 2, 'Password Generator', 0, 'pages/pgenerator/index.php'),
(4, 3, 'Site #2', 1, '../site2/index.php');

-- --------------------------------------------------------

--
-- Table structure for table `forumcategories`
--

CREATE TABLE IF NOT EXISTS `forumcategories` (
`id` int(11) NOT NULL,
  `name` varchar(256) NOT NULL,
  `description` varchar(1024) NOT NULL,
  `permissions` varchar(256) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `forumcategories`
--

INSERT INTO `forumcategories` (`id`, `name`, `description`, `permissions`) VALUES
(1, 'General', 'General discussion', ''),
(2, 'Coding', 'Just awesome coding!', '');

-- --------------------------------------------------------

--
-- Table structure for table `forumreplies`
--

CREATE TABLE IF NOT EXISTS `forumreplies` (
`id` int(11) NOT NULL,
  `tid` int(11) NOT NULL,
  `body` mediumtext NOT NULL,
  `authid` int(64) NOT NULL,
  `permissions` varchar(256) NOT NULL,
  `options` varchar(2024) NOT NULL,
  `thetime` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `forums`
--

CREATE TABLE IF NOT EXISTS `forums` (
`id` int(11) NOT NULL,
  `catid` int(11) NOT NULL,
  `name` varchar(1024) NOT NULL,
  `description` varchar(1024) NOT NULL,
  `permissions` varchar(256) NOT NULL,
  `blocked` varchar(256) NOT NULL,
  `password` varchar(64) NOT NULL,
  `image` varchar(256) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `forums`
--

INSERT INTO `forums` (`id`, `catid`, `name`, `description`, `permissions`, `blocked`, `password`, `image`) VALUES
(1, 1, 'General Discussion', 'General Discussion', '', '', '', ''),
(2, 1, 'Other Discussion', 'The other discussion!', '', '', '', ''),
(3, 2, 'PHP', 'Hypertext Preprocessor!', '', '', '', ''),
(4, 2, 'Sourcepawn', 'For Sourcemod.', '', '', '', ''),
(5, 2, 'HTML', 'Hypertext Markup Language.', '', '', '', ''),
(6, 2, 'Javascript', 'Javascript!', '', '', '', ''),
(7, 1, 'Game Request', 'Request for games to be add on the site here!', '', '', '', ''),
(8, 1, 'Website Updates', 'All website updates will be posted here.', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `forumthreads`
--

CREATE TABLE IF NOT EXISTS `forumthreads` (
`id` int(11) NOT NULL,
  `fid` int(11) NOT NULL,
  `topic` varchar(256) NOT NULL,
  `body` mediumtext NOT NULL,
  `authid` int(64) NOT NULL,
  `password` varchar(256) NOT NULL,
  `permissions` varchar(64) NOT NULL,
  `options` varchar(2024) NOT NULL,
  `thetime` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `gamecategories`
--

CREATE TABLE IF NOT EXISTS `gamecategories` (
`id` int(11) NOT NULL,
  `codename` varchar(256) NOT NULL,
  `name` varchar(256) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `gamecategories`
--

INSERT INTO `gamecategories` (`id`, `codename`, `name`) VALUES
(1, 'arcade', 'Arcade'),
(2, 'shooters', 'Shooters'),
(3, 'action', 'Action'),
(4, 'sports', 'Sports');

-- --------------------------------------------------------

--
-- Table structure for table `games`
--

CREATE TABLE IF NOT EXISTS `games` (
`id` int(11) NOT NULL,
  `name` varchar(256) NOT NULL,
  `image` varchar(256) NOT NULL,
  `description` varchar(1024) NOT NULL,
  `codename` varchar(256) NOT NULL,
  `category` varchar(256) NOT NULL,
  `authid` int(11) NOT NULL,
  `thetime` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=18 ;

--
-- Dumping data for table `games`
--

INSERT INTO `games` (`id`, `name`, `image`, `description`, `codename`, `category`, `authid`, `thetime`) VALUES
(1, 'Shooter #1', 'shooter1.jpg', 'Shooter one.', 'shooter1', 'shooters', 0, 0),
(2, 'Air Hockey', 'airhockey.png', 'Awesome Air Hockey!', 'airhockey', 'sports', 0, 0),
(3, 'Balloon Tower Defense 4', 'btd4.jpg', 'Balloon Tower Defense 4 expansion!', 'btd4', 'arcade', 0, 0),
(4, '13 days in Hell', '13days.gif', '13 days in Hell!!!', '13days', 'shooters', 0, 0),
(5, 'Happy Wheels', 'happywheels.jpg', 'One of the best flash games out there!', 'happywheels', 'action', 0, 0),
(6, 'Pacman', 'pacman.png', 'The classic Arcade game!', 'pacman', 'arcade', 0, 0),
(8, 'Big Head Football Championship', 'bhfc.gif', 'Awesome game!', 'bhfc', 'sports', 0, 0),
(9, 'Big Head Football 2', 'bhf2.jpg', 'Another awesome sports game!', 'bhf2', 'sports', 0, 0),
(10, 'Big Head Basketball', 'bhb.gif', 'Big Head Basketball?!?! Yup!', 'bhb', 'sports', 0, 0),
(11, 'Pinch Hitter 2', 'ph2.gif', '...', 'ph2', 'sports', 0, 0),
(14, 'Big Head Ice Hockey', 'sportsheadsicehockey.gif', 'Another awesome game in the Big Head series!', 'bhih', 'sports', 8, 0),
(15, 'Earn To Die 2012', 'tricky_earntodie2012_image1.png', 'Awesome game my friend plays at school!', 'etd12', 'action', 8, 1413943191),
(16, 'Flood Runner', 'floodrunner.jpg', 'An awesome classic game!', 'frunner', 'action', 8, 1414118122),
(17, 'Apple Shooter', 'Apple-Shooter_1.jpg', 'Shooter!', 'appleshooter', 'shooters', 8, 1414177871);

-- --------------------------------------------------------

--
-- Table structure for table `navbars`
--

CREATE TABLE IF NOT EXISTS `navbars` (
`id` int(11) NOT NULL,
  `name` varchar(1024) NOT NULL,
  `dropdown` int(255) NOT NULL,
  `link` varchar(1024) NOT NULL,
  `norder` int(255) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `navbars`
--

INSERT INTO `navbars` (`id`, `name`, `dropdown`, `link`, `norder`) VALUES
(1, 'Home', 0, 'index.php', 1),
(2, 'Projects', 1, '', 2),
(3, 'Other', 1, '', 5),
(4, 'Forums', 0, 'pages/forums/index.php', 3),
(6, 'Games', 0, 'pages/games/index.php', 4);

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE IF NOT EXISTS `news` (
`id` int(11) NOT NULL,
  `headline` varchar(1024) NOT NULL,
  `body` varchar(1024) NOT NULL,
  `thetime` int(11) NOT NULL,
  `authid` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `headline`, `body`, `thetime`, `authid`) VALUES
(1, 'Installed!', 'You have successfully installed this website made by Christian Deacon!', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `userinfo`
--

CREATE TABLE IF NOT EXISTS `userinfo` (
`id` int(11) NOT NULL,
  `uid` int(255) NOT NULL,
  `age` int(11) NOT NULL,
  `about` varchar(2024) NOT NULL,
  `website` varchar(1024) NOT NULL,
  `bio` varchar(1024) NOT NULL,
  `color` varchar(11) NOT NULL,
  `rep` int(11) NOT NULL,
  `group` int(11) NOT NULL,
  `permissions` varchar(1024) NOT NULL,
  `signature` varchar(3016) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
`id` int(11) NOT NULL,
  `username` varchar(1024) NOT NULL,
  `password` varchar(1024) NOT NULL,
  `email` varchar(1024) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;



--
-- Indexes for table `dropdowns`
--
ALTER TABLE `dropdowns`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `forumcategories`
--
ALTER TABLE `forumcategories`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `forumreplies`
--
ALTER TABLE `forumreplies`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `forums`
--
ALTER TABLE `forums`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `forumthreads`
--
ALTER TABLE `forumthreads`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gamecategories`
--
ALTER TABLE `gamecategories`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `games`
--
ALTER TABLE `games`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `navbars`
--
ALTER TABLE `navbars`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `userinfo`
--
ALTER TABLE `userinfo`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `dropdowns`
--
ALTER TABLE `dropdowns`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `forumcategories`
--
ALTER TABLE `forumcategories`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `forumreplies`
--
ALTER TABLE `forumreplies`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `forums`
--
ALTER TABLE `forums`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `forumthreads`
--
ALTER TABLE `forumthreads`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `gamecategories`
--
ALTER TABLE `gamecategories`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `games`
--
ALTER TABLE `games`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT for table `navbars`
--
ALTER TABLE `navbars`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `userinfo`
--
ALTER TABLE `userinfo`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
