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
-- Dumping data for table t3.appmetadata: ~1 rows (approximately)
/*!40000 ALTER TABLE `appmetadata` DISABLE KEYS */;
INSERT INTO `appmetadata` (`publishpath`, `serverassetpath`, `clientassetpath`, `piaggregatecolumncount`) VALUES
	('thecorrespondent/', '../assets/', '/../assets/', 4);
/*!40000 ALTER TABLE `appmetadata` ENABLE KEYS */;

-- Dumping data for table t3.appuser: ~2 rows (approximately)
/*!40000 ALTER TABLE `appuser` DISABLE KEYS */;
INSERT INTO `appuser` (`username`, `password`, `name`) VALUES
	('peterb', 'danielb07', 'Peter G A Barraud'),
	('mktayal', 'krishna15', 'Manoj K Tayal');
/*!40000 ALTER TABLE `appuser` ENABLE KEYS */;

-- Dumping data for table t3.pagetemplatet1: ~5 rows (approximately)
/*!40000 ALTER TABLE `pagetemplate` DISABLE KEYS */;
INSERT INTO `pagetemplate` (`pagetype`, `description`, `title`, `template`) VALUES
	('piaggregate', 'Use this template to create aggregate pages that include references to other pages', 'Content aggregate page', 'aggregate'),
	('picontent', 'Use this template to create detail page that have content such as body text, images etc', 'Content page', 'content'),
	('piyoutubevideo', 'Use this template to create youtube video content', 'Youtube video', 'youtube'),
	('pimediagroup', 'Use this template to create media aggregate pages such as advertizing profiles that you can attach to your other pages', 'Media group', 'mediagroup');
/*!40000 ALTER TABLE `pagetemplate` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
