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
-- Table structure for table `landen`
--

DROP TABLE IF EXISTS `landen`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `landen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `land` varchar(255) NOT NULL,
  `AFK2` varchar(5) NOT NULL,
  `AFK3` varchar(5) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10004 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `landen`
--

LOCK TABLES `landen` WRITE;
/*!40000 ALTER TABLE `landen` DISABLE KEYS */;
INSERT INTO `landen` VALUES (0,'Onbekend','','',NULL,NULL),(5001,'Canada','','',NULL,NULL),(5002,'Frankrijk','','',NULL,NULL),(5003,'Zwitserland','','',NULL,NULL),(5006,'Cuba','','',NULL,NULL),(5007,'Suriname','','',NULL,NULL),(5008,'Tunesië','','',NULL,NULL),(5009,'Oostenrijk','','',NULL,NULL),(5010,'België','','',NULL,NULL),(5012,'Iran','','',NULL,NULL),(5013,'Nieuwzeeland','','',NULL,NULL),(5014,'Zuidafrika','','',NULL,NULL),(5015,'Denemarken','','',NULL,NULL),(5017,'Hongarije','','',NULL,NULL),(5018,'Saoediarabië','','',NULL,NULL),(5019,'Liberia','','',NULL,NULL),(5020,'Etiopië','','',NULL,NULL),(5021,'Chili','','',NULL,NULL),(5022,'Marokko','','',NULL,NULL),(5023,'Togo','','',NULL,NULL),(5024,'Ghana','','',NULL,NULL),(5026,'Angola','','',NULL,NULL),(5027,'Filipijnen','','',NULL,NULL),(5029,'Mali','','',NULL,NULL),(5030,'Ivoorkust','','',NULL,NULL),(5033,'Colombia','','',NULL,NULL),(5034,'Albanië','','',NULL,NULL),(5035,'Kameroen','','',NULL,NULL),(5037,'Singapore','','',NULL,NULL),(5039,'Zweden','','',NULL,NULL),(5040,'Cyprus','','',NULL,NULL),(5043,'Irak','','',NULL,NULL),(5044,'Mauritius','','',NULL,NULL),(5048,'Jemen','','',NULL,NULL),(5049,'Slovenië','','',NULL,NULL),(5050,'Zaïre','','',NULL,NULL),(5051,'Kroatië','','',NULL,NULL),(5053,'Rusland','','',NULL,NULL),(5054,'Armenië','','',NULL,NULL),(5058,'Bhutan','','',NULL,NULL),(5062,'Frans Guyana','','',NULL,NULL),(5066,'Guadeloupe','','',NULL,NULL),(5067,'Kaapverdische Eilanden','','',NULL,NULL),(5069,'Martinique','','',NULL,NULL),(5072,'Guinee Bissau','','',NULL,NULL),(5077,'Wallis en Futuna','','',NULL,NULL),(5095,'Aruba','','',NULL,NULL),(5096,'Burkina Faso','','',NULL,NULL),(5097,'Azerbajdsjan','','',NULL,NULL),(5098,'Belarus (Wit-Rusland)','','',NULL,NULL),(5099,'Kazachstan','','',NULL,NULL),(5100,'Macedonië','','',NULL,NULL),(5103,'Servië','','',NULL,NULL),(5106,'Bonaire','','',NULL,NULL),(5107,'Curaçao','','',NULL,NULL),(5110,'Sint Maarten','','',NULL,NULL),(6000,'Moldavië','','',NULL,NULL),(6001,'Burundi','','',NULL,NULL),(6002,'Finland','','',NULL,NULL),(6003,'Griekenland','','',NULL,NULL),(6004,'Guatemala','','',NULL,NULL),(6005,'Nigeria','','',NULL,NULL),(6006,'Libië','','',NULL,NULL),(6007,'Ierland','','',NULL,NULL),(6008,'Brazilië','','',NULL,NULL),(6009,'Rwanda','','',NULL,NULL),(6010,'Venezuela','','',NULL,NULL),(6011,'IJsland','','',NULL,NULL),(6013,'Somalia','','',NULL,NULL),(6014,'Verenigde Staten van Amerika','','',NULL,NULL),(6016,'Australië','','',NULL,NULL),(6017,'Jamaica','','',NULL,NULL),(6018,'Luxemburg','','',NULL,NULL),(6020,'Mauritanië','','',NULL,NULL),(6022,'China','','',NULL,NULL),(6023,'Afghanistan','','',NULL,NULL),(6024,'Indonesië','','',NULL,NULL),(6025,'Guyana','','',NULL,NULL),(6027,'Noorwegen','','',NULL,NULL),(6029,'Duitsland','','',NULL,NULL),(6030,'Nederland','','',NULL,NULL),(6034,'Israël','','',NULL,NULL),(6035,'Nepal','','',NULL,NULL),(6036,'Zuidkorea','','',NULL,NULL),(6037,'Spanje','','',NULL,NULL),(6038,'Oekraine','','',NULL,NULL),(6039,'Grootbrittannië','','',NULL,NULL),(6040,'Niger','','',NULL,NULL),(6041,'Haïti','','',NULL,NULL),(6042,'Jordanië','','',NULL,NULL),(6043,'Turkije','','',NULL,NULL),(6045,'Joegoslavië','','',NULL,NULL),(6047,'Algerije','','',NULL,NULL),(6050,'Oezbekistan','','',NULL,NULL),(6051,'Sierra Leone','','',NULL,NULL),(6057,'Tadzjikistan','','',NULL,NULL),(6064,'Georgië','','',NULL,NULL),(6065,'Bosnië-Herzegovina','','',NULL,NULL),(6066,'Tsjechië','','',NULL,NULL),(6067,'Slowakije','','',NULL,NULL),(6069,'Democratische Republiek Congo','','',NULL,NULL),(7001,'Uganda','','',NULL,NULL),(7002,'Kenya','','',NULL,NULL),(7006,'Mexico','','',NULL,NULL),(7008,'Gambia','','',NULL,NULL),(7009,'Syrië','','',NULL,NULL),(7011,'Nederlandse Antillen','','',NULL,NULL),(7014,'Egypte','','',NULL,NULL),(7015,'Argentinië','','',NULL,NULL),(7017,'Honduras','','',NULL,NULL),(7020,'Pakistan','','',NULL,NULL),(7021,'Senegal','','',NULL,NULL),(7024,'Bulgarije','','',NULL,NULL),(7027,'Dominicaanse Republiek','','',NULL,NULL),(7028,'Polen','','',NULL,NULL),(7029,'Rusland (oud)','','',NULL,NULL),(7031,'Tanzania','','',NULL,NULL),(7033,'Sri Lanka','','',NULL,NULL),(7034,'Soedan','','',NULL,NULL),(7035,'Japan','','',NULL,NULL),(7037,'Panama','','',NULL,NULL),(7038,'Uruguay','','',NULL,NULL),(7039,'Ecuador','','',NULL,NULL),(7040,'Guinee','','',NULL,NULL),(7043,'Libanon','','',NULL,NULL),(7044,'Italië','','',NULL,NULL),(7045,'Koeweit','','',NULL,NULL),(7046,'India','','',NULL,NULL),(7047,'Roemenië','','',NULL,NULL),(7048,'Tsjechoslowakije','','',NULL,NULL),(7049,'Peru','','',NULL,NULL),(7050,'Portugal','','',NULL,NULL),(7060,'Palestina','','',NULL,NULL),(7064,'Letland','','',NULL,NULL),(7065,'Estland','','',NULL,NULL),(7066,'Litouwen','','',NULL,NULL),(7073,'Saarland','','',NULL,NULL),(7076,'Goudkust','','',NULL,NULL),(7084,'Bangladesh','','',NULL,NULL),(7085,'Duitse Democratische Republiek','','',NULL,NULL),(8031,'Zimbabwe','','',NULL,NULL),(9003,'Eritrea','','',NULL,NULL),(9008,'Kongo','','',NULL,NULL),(9009,'Kongo Kinshasa','','',NULL,NULL),(9049,'Sovjetunie','','',NULL,NULL),(9068,'Korea','','',NULL,NULL),(9070,'Oem el Koewein','','',NULL,NULL),(9089,'Bondsrepubliek Duitsland','','',NULL,NULL),(9093,'Westelijke Sahara','','',NULL,NULL),(10000,'Overig','','',NULL,NULL),(10001,'Trinidad en Tobago','','',NULL,NULL),(10003,'Vietnam','','',NULL,NULL);
/*!40000 ALTER TABLE `landen` ENABLE KEYS */;
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
