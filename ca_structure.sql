SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `ca`
--
CREATE DATABASE IF NOT EXISTS `ca` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `ca`;

-- --------------------------------------------------------

--
-- 表的结构 `tbEvents`
--

CREATE TABLE IF NOT EXISTS `tbEvents` (
`id` int(10) unsigned NOT NULL,
  `groupId` int(10) unsigned NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `place` varchar(50) DEFAULT NULL,
  `comment` varchar(100) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tbGroupMembers`
--

CREATE TABLE IF NOT EXISTS `tbGroupMembers` (
`id` int(10) unsigned NOT NULL,
  `groupId` int(10) unsigned NOT NULL,
  `userId` int(10) unsigned NOT NULL,
  `isAdmin` tinyint(4) NOT NULL DEFAULT '0',
  `balance` decimal(10,3) NOT NULL DEFAULT '0.000'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tbGroups`
--

CREATE TABLE IF NOT EXISTS `tbGroups` (
`id` int(10) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(1000) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tbRecords`
--

CREATE TABLE IF NOT EXISTS `tbRecords` (
`id` int(10) unsigned NOT NULL,
  `eventId` int(10) unsigned NOT NULL,
  `userId` int(10) unsigned NOT NULL,
  `money` decimal(10,3) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tbUsers`
--

CREATE TABLE IF NOT EXISTS `tbUsers` (
`id` int(10) unsigned NOT NULL,
  `name` char(30) NOT NULL,
  `nickname` varchar(50) DEFAULT NULL,
  `password` char(40) NOT NULL COMMENT 'SHA1 with salt',
  `email` varchar(50) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbEvents`
--
ALTER TABLE `tbEvents`
 ADD PRIMARY KEY (`id`), ADD KEY `group_id` (`groupId`);

--
-- Indexes for table `tbGroupMembers`
--
ALTER TABLE `tbGroupMembers`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `group_user` (`groupId`,`userId`), ADD KEY `groupId` (`groupId`), ADD KEY `userId` (`userId`);

--
-- Indexes for table `tbGroups`
--
ALTER TABLE `tbGroups`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `tbRecords`
--
ALTER TABLE `tbRecords`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `event_user` (`eventId`,`userId`), ADD KEY `eventId` (`eventId`), ADD KEY `userId` (`userId`);

--
-- Indexes for table `tbUsers`
--
ALTER TABLE `tbUsers`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `name` (`name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbEvents`
--
ALTER TABLE `tbEvents`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `tbGroupMembers`
--
ALTER TABLE `tbGroupMembers`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `tbGroups`
--
ALTER TABLE `tbGroups`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `tbRecords`
--
ALTER TABLE `tbRecords`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `tbUsers`
--
ALTER TABLE `tbUsers`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- 限制导出的表
--

--
-- 限制表 `tbEvents`
--
ALTER TABLE `tbEvents`
ADD CONSTRAINT `fk_Events_groupId` FOREIGN KEY (`groupId`) REFERENCES `tbGroups` (`id`);

--
-- 限制表 `tbGroupMembers`
--
ALTER TABLE `tbGroupMembers`
ADD CONSTRAINT `fk_GroupMembers_groupId` FOREIGN KEY (`groupId`) REFERENCES `tbGroups` (`id`),
ADD CONSTRAINT `fk_GroupMembers_userId` FOREIGN KEY (`userId`) REFERENCES `tbUsers` (`id`);

--
-- 限制表 `tbRecords`
--
ALTER TABLE `tbRecords`
ADD CONSTRAINT `fk_Records_eventId` FOREIGN KEY (`eventId`) REFERENCES `tbEvents` (`id`),
ADD CONSTRAINT `fk_Records_userId` FOREIGN KEY (`userId`) REFERENCES `tbUsers` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
