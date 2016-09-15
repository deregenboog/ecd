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
-- Table structure for table `nationaliteiten`
--

DROP TABLE IF EXISTS `nationaliteiten`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `nationaliteiten` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `naam` varchar(255) NOT NULL,
  `afkorting` varchar(255) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=506 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `nationaliteiten`
--

LOCK TABLES `nationaliteiten` WRITE;
/*!40000 ALTER TABLE `nationaliteiten` DISABLE KEYS */;
INSERT INTO `nationaliteiten` VALUES (0,'Onbekend','',NULL,NULL),(1,'Nederlandse','',NULL,NULL),(2,'Behandeld als Nederlander','',NULL,NULL),(27,'Slowaakse','',NULL,NULL),(28,'Tsjechische','',NULL,NULL),(30,'Burger van Georgië','',NULL,NULL),(32,'Burger van Tadzjikistan','',NULL,NULL),(34,'Burger van Oekraine','',NULL,NULL),(36,'Burger van Moldavië','',NULL,NULL),(37,'Burger van Kazachstan','',NULL,NULL),(38,'Burger van Belarus (Wit-Rusland)','',NULL,NULL),(39,'Burger van Azerbajdsjan','',NULL,NULL),(41,'Burger van Rusland','',NULL,NULL),(42,'Burger van Slovenië','',NULL,NULL),(43,'Burger van Kroatië','',NULL,NULL),(44,'Letse','',NULL,NULL),(45,'Estnische','',NULL,NULL),(46,'Litouwse','',NULL,NULL),(50,'Albanese','',NULL,NULL),(52,'Belgische','',NULL,NULL),(53,'Bulgaarse','',NULL,NULL),(54,'Deense','',NULL,NULL),(55,'Burger van de Bondsrepubliek Duitsland','',NULL,NULL),(56,'Finse','',NULL,NULL),(57,'Franse','',NULL,NULL),(58,'Jemenitische','',NULL,NULL),(59,'Griekse','',NULL,NULL),(60,'Brits burger','',NULL,NULL),(61,'Hongaarse','',NULL,NULL),(62,'Ierse','',NULL,NULL),(63,'IJslandse','',NULL,NULL),(64,'Italiaanse','',NULL,NULL),(65,'Joegoslavische','',NULL,NULL),(67,'Luxemburgse','',NULL,NULL),(68,'Maltese','',NULL,NULL),(70,'Noorse','',NULL,NULL),(71,'Oostenrijkse','',NULL,NULL),(72,'Poolse','',NULL,NULL),(73,'Portugese','',NULL,NULL),(74,'Roemeense','',NULL,NULL),(77,'Spaanse','',NULL,NULL),(80,'Zweedse','',NULL,NULL),(81,'Zwitserse','',NULL,NULL),(83,'Brits onderdaan','',NULL,NULL),(84,'Eritrese','',NULL,NULL),(86,'Macedonische','',NULL,NULL),(100,'Algerijnse','',NULL,NULL),(101,'Angolese','',NULL,NULL),(104,'Burundische','',NULL,NULL),(106,'Burger van Burkina Faso','',NULL,NULL),(108,'Centrafrikaanse','',NULL,NULL),(110,'Kongolese','',NULL,NULL),(112,'Egyptische','',NULL,NULL),(114,'Etiopische','',NULL,NULL),(117,'Gambiaanse','',NULL,NULL),(118,'Ghanese','',NULL,NULL),(119,'Guinese','',NULL,NULL),(120,'Ivoriaanse','',NULL,NULL),(122,'Kameroense','',NULL,NULL),(123,'Kenyaanse','',NULL,NULL),(126,'Liberiaanse','',NULL,NULL),(127,'Libische','',NULL,NULL),(130,'Malinese','',NULL,NULL),(131,'Marokkaanse','',NULL,NULL),(132,'Burger van Mauritanië','',NULL,NULL),(136,'Burger van Niger','',NULL,NULL),(137,'Burger van Nigeria','',NULL,NULL),(138,'Ugandese','',NULL,NULL),(139,'Guineebissause','',NULL,NULL),(140,'Zuidafrikaanse','',NULL,NULL),(145,'Senegalese','',NULL,NULL),(147,'Sierraleoonse','',NULL,NULL),(148,'Soedanese','',NULL,NULL),(149,'Somalische','',NULL,NULL),(151,'Tanzaniaanse','',NULL,NULL),(152,'Togolese','',NULL,NULL),(155,'Tunesische','',NULL,NULL),(204,'Canadese','',NULL,NULL),(206,'Cubaanse','',NULL,NULL),(214,'Jamaicaanse','',NULL,NULL),(216,'Mexicaanse','',NULL,NULL),(218,'Nicaraguaanse','',NULL,NULL),(223,'Amerikaans burger','',NULL,NULL),(250,'Argentijnse','',NULL,NULL),(253,'Braziliaanse','',NULL,NULL),(254,'Chileense','',NULL,NULL),(255,'Colombiaanse','',NULL,NULL),(256,'Ecuadoraanse','',NULL,NULL),(259,'Guyaanse','',NULL,NULL),(262,'Peruaanse','',NULL,NULL),(263,'Surinaamse','',NULL,NULL),(265,'Venezolaanse','',NULL,NULL),(300,'Afghaanse','',NULL,NULL),(302,'Bhutaanse','',NULL,NULL),(303,'Burmaanse','',NULL,NULL),(306,'Srilankaanse','',NULL,NULL),(307,'Chinese','',NULL,NULL),(308,'Cyprische','',NULL,NULL),(312,'Burger van India','',NULL,NULL),(313,'Indonesische','',NULL,NULL),(314,'Iraakse','',NULL,NULL),(315,'Iraanse','',NULL,NULL),(316,'Israëlische','',NULL,NULL),(317,'Japanse','',NULL,NULL),(321,'Laotiaanse','',NULL,NULL),(322,'Libanese','',NULL,NULL),(324,'Maldivische','',NULL,NULL),(326,'Mongolische','',NULL,NULL),(328,'Nepalese','',NULL,NULL),(331,'Pakistaanse','',NULL,NULL),(335,'Singaporaanse','',NULL,NULL),(336,'Syrische','',NULL,NULL),(339,'Turkse','',NULL,NULL),(341,'Zuidkoreaanse','',NULL,NULL),(345,'Burger van Bangladesh','',NULL,NULL),(400,'Australische','',NULL,NULL),(402,'Nieuwzeelandse','',NULL,NULL),(404,'Westsamoaanse','',NULL,NULL),(429,'Burger van Britse afhankelijke gebieden','',NULL,NULL),(437,'Amerikaans onderdaan','',NULL,NULL),(450,'British National (overseas)','',NULL,NULL),(454,'Burger van Servië','',NULL,NULL),(499,'Staatloos','',NULL,NULL),(501,'Overig','',NULL,NULL),(502,'Indiaas','',NULL,NULL),(503,'Burger van Trinidad en Tobago','',NULL,NULL),(505,'Vietnamees','',NULL,NULL);
/*!40000 ALTER TABLE `nationaliteiten` ENABLE KEYS */;
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
