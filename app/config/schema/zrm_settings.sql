-- MySQL dump 10.13  Distrib 5.1.73, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: ecd
-- ------------------------------------------------------
-- Server version	5.1.73-1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `zrm_settings`
--

DROP TABLE IF EXISTS `zrm_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zrm_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `request_module` varchar(50) NOT NULL,
  `inkomen` tinyint(1) DEFAULT NULL,
  `dagbesteding` tinyint(1) DEFAULT NULL,
  `huisvesting` tinyint(1) DEFAULT NULL,
  `gezinsrelaties` tinyint(1) DEFAULT NULL,
  `geestelijke_gezondheid` tinyint(1) DEFAULT NULL,
  `fysieke_gezondheid` tinyint(1) DEFAULT NULL,
  `verslaving` tinyint(1) DEFAULT NULL,
  `adl_vaardigheden` tinyint(1) DEFAULT NULL,
  `sociaal_netwerk` tinyint(1) DEFAULT NULL,
  `maatschappelijke_participatie` tinyint(1) DEFAULT NULL,
  `justitie` tinyint(1) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zrm_settings`
--

LOCK TABLES `zrm_settings` WRITE;
/*!40000 ALTER TABLE `zrm_settings` DISABLE KEYS */;
INSERT INTO `zrm_settings` VALUES (1,'Intake',0,0,0,0,0,0,0,0,0,0,0,'2013-11-26 17:57:16','2014-07-08 17:12:12'),(2,'MaatschappelijkWerk',1,1,1,1,1,1,1,1,1,1,1,'2013-11-26 17:57:36','2014-07-08 17:12:12'),(3,'Awbz',0,0,0,0,0,0,0,0,0,0,0,'2013-11-26 17:57:44','2014-07-08 17:12:12'),(4,'Hi5',0,0,0,0,0,0,0,0,0,0,0,'2013-11-29 15:52:10','2014-07-08 17:12:12'),(5,'GroepsactiviteitenIntake',0,0,0,0,0,0,0,0,0,0,0,NULL,'2014-07-08 17:12:12'),(6,'IzIntake',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2015-01-01 21:08:07','2015-01-01 21:08:07');
/*!40000 ALTER TABLE `zrm_settings` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-09-08 12:06:53
