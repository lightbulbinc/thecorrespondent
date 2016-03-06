-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.1.10-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             9.1.0.4867
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping structure for table corr1.aggregatecolumn
DROP TABLE IF EXISTS `aggregatecolumn`;
CREATE TABLE IF NOT EXISTS `aggregatecolumn` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `position` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table corr1.aggregatecolumn_map_aggregateitem
DROP TABLE IF EXISTS `aggregatecolumn_map_aggregateitem`;
CREATE TABLE IF NOT EXISTS `aggregatecolumn_map_aggregateitem` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `aggregateitemid` smallint(5) unsigned NOT NULL,
  `aggregatecolumnid` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `aggregateitemid` (`aggregateitemid`),
  KEY `aggregatecolumnid` (`aggregatecolumnid`),
  CONSTRAINT `aggregatecolumn_map_aggregateitem_ibfk_1` FOREIGN KEY (`aggregateitemid`) REFERENCES `aggregateitem` (`id`),
  CONSTRAINT `aggregatecolumn_map_aggregateitem_ibfk_2` FOREIGN KEY (`aggregatecolumnid`) REFERENCES `aggregatecolumn` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table corr1.aggregateitem
DROP TABLE IF EXISTS `aggregateitem`;
CREATE TABLE IF NOT EXISTS `aggregateitem` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `maxlength` smallint(6) NOT NULL,
  `position` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table corr1.appmetadata
DROP TABLE IF EXISTS `appmetadata`;
CREATE TABLE IF NOT EXISTS `appmetadata` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `publishpath` varchar(256) DEFAULT NULL,
  `serverassetpath` varchar(256) DEFAULT NULL,
  `clientassetpath` varchar(256) DEFAULT NULL,
  `piaggregatecolumncount` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table corr1.appuser
DROP TABLE IF EXISTS `appuser`;
CREATE TABLE IF NOT EXISTS `appuser` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `name` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table corr1.asset
DROP TABLE IF EXISTS `asset`;
CREATE TABLE IF NOT EXISTS `asset` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table corr1.menu
DROP TABLE IF EXISTS `menu`;
CREATE TABLE IF NOT EXISTS `menu` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `position` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table corr1.menuitem
DROP TABLE IF EXISTS `menuitem`;
CREATE TABLE IF NOT EXISTS `menuitem` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `position` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table corr1.menuitem_map_menu
DROP TABLE IF EXISTS `menuitem_map_menu`;
CREATE TABLE IF NOT EXISTS `menuitem_map_menu` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `menuitemid` smallint(5) unsigned NOT NULL,
  `menuid` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `menuitemid` (`menuitemid`),
  KEY `menuid` (`menuid`),
  CONSTRAINT `menuitem_map_menu_ibfk_1` FOREIGN KEY (`menuitemid`) REFERENCES `menuitem` (`id`),
  CONSTRAINT `menuitem_map_menu_ibfk_2` FOREIGN KEY (`menuid`) REFERENCES `menu` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table corr1.menuitem_map_pageitem
DROP TABLE IF EXISTS `menuitem_map_pageitem`;
CREATE TABLE IF NOT EXISTS `menuitem_map_pageitem` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `menuitemid` smallint(5) unsigned NOT NULL,
  `pageitemid` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `menuitemid` (`menuitemid`),
  KEY `pageitemid` (`pageitemid`),
  CONSTRAINT `menuitem_map_pageitem_ibfk_1` FOREIGN KEY (`menuitemid`) REFERENCES `menuitem` (`id`),
  CONSTRAINT `menuitem_map_pageitem_ibfk_2` FOREIGN KEY (`pageitemid`) REFERENCES `pageitem` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table corr1.pageitem
DROP TABLE IF EXISTS `pageitem`;
CREATE TABLE IF NOT EXISTS `pageitem` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(1000) NOT NULL,
  `publishdate` datetime DEFAULT NULL,
  `pagename` varchar(256) NOT NULL,
  `createdate` datetime DEFAULT NULL,
  `modifieddate` datetime DEFAULT NULL,
  `readonly` char(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table corr1.pageitem_map_aggregateitem
DROP TABLE IF EXISTS `pageitem_map_aggregateitem`;
CREATE TABLE IF NOT EXISTS `pageitem_map_aggregateitem` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `pageitemid` smallint(5) unsigned NOT NULL,
  `aggregateitemid` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pageitemid` (`pageitemid`),
  KEY `aggregateitemid` (`aggregateitemid`),
  CONSTRAINT `pageitem_map_aggregateitem_ibfk_1` FOREIGN KEY (`pageitemid`) REFERENCES `pageitem` (`id`),
  CONSTRAINT `pageitem_map_aggregateitem_ibfk_2` FOREIGN KEY (`aggregateitemid`) REFERENCES `aggregateitem` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table corr1.pageitem_map_pagetemplate
DROP TABLE IF EXISTS `pageitem_map_pagetemplate`;
CREATE TABLE IF NOT EXISTS `pageitem_map_pagetemplate` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `pageitemid` smallint(5) unsigned NOT NULL,
  `pagetemplateid` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pageitemid` (`pageitemid`),
  KEY `pagetemplateid` (`pagetemplateid`),
  CONSTRAINT `pageitem_map_pagetemplate_ibfk_1` FOREIGN KEY (`pageitemid`) REFERENCES `pageitem` (`id`),
  CONSTRAINT `pageitem_map_pagetemplate_ibfk_2` FOREIGN KEY (`pagetemplateid`) REFERENCES `pagetemplate` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table corr1.pageitem_map_piaggregate
DROP TABLE IF EXISTS `pageitem_map_piaggregate`;
CREATE TABLE IF NOT EXISTS `pageitem_map_piaggregate` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `pageitemid` smallint(5) unsigned NOT NULL,
  `piaggregateid` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pageitemid` (`pageitemid`),
  KEY `piaggregateid` (`piaggregateid`),
  CONSTRAINT `pageitem_map_piaggregate_ibfk_1` FOREIGN KEY (`pageitemid`) REFERENCES `pageitem` (`id`),
  CONSTRAINT `pageitem_map_piaggregate_ibfk_2` FOREIGN KEY (`piaggregateid`) REFERENCES `piaggregate` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table corr1.pageitem_map_picontent
DROP TABLE IF EXISTS `pageitem_map_picontent`;
CREATE TABLE IF NOT EXISTS `pageitem_map_picontent` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `pageitemid` smallint(5) unsigned NOT NULL,
  `picontentid` smallint(6) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pageitemid` (`pageitemid`),
  KEY `picontentid` (`picontentid`),
  CONSTRAINT `pageitem_map_picontent_ibfk_1` FOREIGN KEY (`pageitemid`) REFERENCES `pageitem` (`id`),
  CONSTRAINT `pageitem_map_picontent_ibfk_2` FOREIGN KEY (`picontentid`) REFERENCES `picontent` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table corr1.pageitem_map_pimediagroup
DROP TABLE IF EXISTS `pageitem_map_pimediagroup`;
CREATE TABLE IF NOT EXISTS `pageitem_map_pimediagroup` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `pageitemid` smallint(5) unsigned NOT NULL,
  `pimediagroupid` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pageitemid` (`pageitemid`),
  KEY `pimediagroupid` (`pimediagroupid`),
  CONSTRAINT `pageitem_map_pimediagroup_ibfk_1` FOREIGN KEY (`pageitemid`) REFERENCES `pageitem` (`id`),
  CONSTRAINT `pageitem_map_pimediagroup_ibfk_2` FOREIGN KEY (`pimediagroupid`) REFERENCES `pimediagroup` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table corr1.pageitem_map_piyoutubevideo
DROP TABLE IF EXISTS `pageitem_map_piyoutubevideo`;
CREATE TABLE IF NOT EXISTS `pageitem_map_piyoutubevideo` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `pageitemid` smallint(5) unsigned NOT NULL,
  `piyoutubevideoid` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pageitemid` (`pageitemid`),
  KEY `piyoutubevideoid` (`piyoutubevideoid`),
  CONSTRAINT `pageitem_map_piyoutubevideo_ibfk_1` FOREIGN KEY (`pageitemid`) REFERENCES `pageitem` (`id`),
  CONSTRAINT `pageitem_map_piyoutubevideo_ibfk_2` FOREIGN KEY (`piyoutubevideoid`) REFERENCES `piyoutubevideo` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table corr1.pagetemplate
DROP TABLE IF EXISTS `pagetemplate`;
CREATE TABLE IF NOT EXISTS `pagetemplate` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `pagetype` varchar(20) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `title` varchar(50) NOT NULL,
  `template` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table corr1.piaggregate
DROP TABLE IF EXISTS `piaggregate`;
CREATE TABLE IF NOT EXISTS `piaggregate` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table corr1.piaggregate_map_aggregatecolumn
DROP TABLE IF EXISTS `piaggregate_map_aggregatecolumn`;
CREATE TABLE IF NOT EXISTS `piaggregate_map_aggregatecolumn` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `piaggregateid` smallint(5) unsigned NOT NULL,
  `aggregatecolumnid` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `piaggregateid` (`piaggregateid`),
  KEY `aggregatecolumnid` (`aggregatecolumnid`),
  CONSTRAINT `piaggregate_map_aggregatecolumn_ibfk_1` FOREIGN KEY (`piaggregateid`) REFERENCES `piaggregate` (`id`),
  CONSTRAINT `piaggregate_map_aggregatecolumn_ibfk_2` FOREIGN KEY (`aggregatecolumnid`) REFERENCES `aggregatecolumn` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table corr1.picontent_map_aggregatecolumn
DROP TABLE IF EXISTS `picontent_map_aggregatecolumn`;
CREATE TABLE IF NOT EXISTS `picontent_map_aggregatecolumn` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `picontentid` smallint(5) unsigned NOT NULL,
  `aggregatecolumnid` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `picontentid` (`picontentid`),
  KEY `aggregatecolumnid` (`aggregatecolumnid`),
  CONSTRAINT `picontent_map_aggregatecolumn_ibfk_1` FOREIGN KEY (`picontentid`) REFERENCES `picontent` (`id`),
  CONSTRAINT `picontent_map_aggregatecolumn_ibfk_2` FOREIGN KEY (`aggregatecolumnid`) REFERENCES `aggregatecolumn` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table corr1.picontent
DROP TABLE IF EXISTS `picontent`;
CREATE TABLE IF NOT EXISTS `picontent` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `subtitle` varchar(256) DEFAULT NULL,
  `body` text,
  `titleimage` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table corr1.pimediagroup
DROP TABLE IF EXISTS `pimediagroup`;
CREATE TABLE IF NOT EXISTS `pimediagroup` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `orientation` char(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table corr1.pimediagroup_map_asset
DROP TABLE IF EXISTS `pimediagroup_map_asset`;
CREATE TABLE IF NOT EXISTS `pimediagroup_map_asset` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `assetid` smallint(5) unsigned NOT NULL,
  `pimediagroupid` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pimediagroupid` (`pimediagroupid`),
  KEY `assetid` (`assetid`),
  CONSTRAINT `pimediagroup_map_asset_ibfk_1` FOREIGN KEY (`pimediagroupid`) REFERENCES `pimediagroup` (`id`),
  CONSTRAINT `pimediagroup_map_asset_ibfk_2` FOREIGN KEY (`assetid`) REFERENCES `asset` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table corr1.piyoutubevideo
DROP TABLE IF EXISTS `piyoutubevideo`;
CREATE TABLE IF NOT EXISTS `piyoutubevideo` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table corr1.tag
DROP TABLE IF EXISTS `tag`;
CREATE TABLE IF NOT EXISTS `tag` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table corr1.tag_map_aggregateitem
DROP TABLE IF EXISTS `tag_map_aggregateitem`;
CREATE TABLE IF NOT EXISTS `tag_map_aggregateitem` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `aggregateitemid` smallint(5) unsigned NOT NULL,
  `tagid` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `aggregateitemid` (`aggregateitemid`),
  KEY `tagid` (`tagid`),
  CONSTRAINT `tag_map_aggregateitem_ibfk_1` FOREIGN KEY (`aggregateitemid`) REFERENCES `aggregateitem` (`id`),
  CONSTRAINT `tag_map_aggregateitem_ibfk_2` FOREIGN KEY (`tagid`) REFERENCES `tag` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table corr1.tag_map_pageitem
DROP TABLE IF EXISTS `tag_map_pageitem`;
CREATE TABLE IF NOT EXISTS `tag_map_pageitem` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `tagid` smallint(5) unsigned NOT NULL,
  `pageitemid` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tagid` (`tagid`),
  KEY `pageitemid` (`pageitemid`),
  CONSTRAINT `tag_map_pageitem_ibfk_1` FOREIGN KEY (`tagid`) REFERENCES `tag` (`id`),
  CONSTRAINT `tag_map_pageitem_ibfk_2` FOREIGN KEY (`pageitemid`) REFERENCES `pageitem` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
