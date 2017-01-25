-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server Version:               10.1.13-MariaDB - mariadb.org binary distribution
-- Server Betriebssystem:        Win32
-- HeidiSQL Version:             9.3.0.5049
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Exportiere Struktur von Tabelle bugtracker.groups
CREATE TABLE IF NOT EXISTS `groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Daten Export vom Benutzer nicht ausgewählt
-- Exportiere Struktur von Tabelle bugtracker.group_permissions
CREATE TABLE IF NOT EXISTS `group_permissions` (
  `groupID` int(11) NOT NULL,
  `permissionID` int(11) NOT NULL,
  KEY `FK__groups` (`groupID`),
  KEY `FK__permissions_groups` (`permissionID`),
  CONSTRAINT `FK__groups` FOREIGN KEY (`groupID`) REFERENCES `groups` (`id`),
  CONSTRAINT `FK__permissions_groups` FOREIGN KEY (`permissionID`) REFERENCES `permissions` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Daten Export vom Benutzer nicht ausgewählt
-- Exportiere Struktur von Tabelle bugtracker.group_user_relation
CREATE TABLE IF NOT EXISTS `group_user_relation` (
  `userID` int(11) NOT NULL,
  `groupID` int(11) NOT NULL,
  KEY `FK__users_groups` (`userID`),
  KEY `FK__groups_user` (`groupID`),
  CONSTRAINT `FK__groups_user` FOREIGN KEY (`groupID`) REFERENCES `groups` (`id`),
  CONSTRAINT `FK__users_groups` FOREIGN KEY (`userID`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Daten Export vom Benutzer nicht ausgewählt
-- Exportiere Struktur von Tabelle bugtracker.permissions
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  `errorCode` int(11) NOT NULL,
  `errorMessage` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=latin1;

-- Daten Export vom Benutzer nicht ausgewählt
-- Exportiere Struktur von Tabelle bugtracker.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(250) NOT NULL,
  `rankName` varchar(50) NOT NULL,
  `lastLogin` datetime NOT NULL,
  `lastIP` varchar(50) NOT NULL DEFAULT '0.0.0.0',
  `registerDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `online` enum('0','1') NOT NULL DEFAULT '0',
  `profilePicture` varchar(50) NOT NULL,
  `banned` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

-- Daten Export vom Benutzer nicht ausgewählt
-- Exportiere Struktur von Tabelle bugtracker.user_permissions
CREATE TABLE IF NOT EXISTS `user_permissions` (
  `userID` int(11) NOT NULL,
  `permissionID` int(11) NOT NULL,
  KEY `FK__users` (`userID`),
  KEY `FK__permissions` (`permissionID`),
  CONSTRAINT `FK__permissions` FOREIGN KEY (`permissionID`) REFERENCES `permissions` (`id`),
  CONSTRAINT `FK__users` FOREIGN KEY (`userID`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Daten Export vom Benutzer nicht ausgewählt
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
