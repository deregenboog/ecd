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
-- Table structure for table `postcodegebieden`
--

DROP TABLE IF EXISTS `postcodegebieden`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `postcodegebieden` (
  `postcodegebied` varchar(255) NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `van` int(11) NOT NULL,
  `tot` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `postcodegebieden`
--

LOCK TABLES `postcodegebieden` WRITE;
/*!40000 ALTER TABLE `postcodegebieden` DISABLE KEYS */;
INSERT INTO `postcodegebieden` VALUES ('Noord-oost',1,1021,1025),('Noord-West',2,1033,1036),('Oud Noord',3,1031,1032),('Westerpark',4,1051,1052),('Centrum West',5,1015,1017),('Centrum Oost',6,1011,1014),('Oostelijke Havengebied - Indische Buurt',7,1092,1094),('Oostelijke Havengebied - Indische Buurt',8,1018,1018),('Oud Oost',9,1091,1091),('Ijburg - Zeeburgereiland',10,1019,1019),('Ijburg - Zeeburgereiland',11,1087,1087),('Watergraafsmeer',12,1096,1098),('De Pijp - Rivierenbuurt',13,1072,1074),('De Pijp - Rivierenbuurt',14,1078,1079),('Buitenveldert - Zuidas',15,1081,1083),('Zuid',16,1071,1077),('De Baarsjes - Oud West',17,1053,1054),('De Baarsjes - Oud West',18,1057,1059),('Bos en Lommer',19,1055,1055),('Geuzenveld - Slotermeer',20,1063,1064),('Geuzenveld - Slotermeer',21,1067,1067),('Slotervaart',22,1062,1065),('Osdorp',23,1067,1069),('De Aker - Nieuw Sloten',24,1060,1060),('De Aker - Nieuw Sloten',25,1066,1066),('Bijlmer Centrum',26,1102,1102),('Bijlmer Oost',27,1103,1104),('Gaaperdam - Driemond',28,1106,1109);
/*!40000 ALTER TABLE `postcodegebieden` ENABLE KEYS */;
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
