-- MySQL dump 10.16  Distrib 10.1.33-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: prism
-- ------------------------------------------------------
-- Server version	10.1.33-MariaDB

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
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` text,
  `exp` text,
  `fund` text,
  `relocation` int(11) DEFAULT NULL,
  `remote` int(11) DEFAULT NULL,
  `regdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `keywords` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `description` text,
  `posterId` varchar(255) DEFAULT NULL,
  `bounty` float DEFAULT NULL,
  `hash` blob,
  `travelPercentage` int(11) DEFAULT NULL,
  `remotePercentage` int(11) DEFAULT NULL,
  `relocationPackage` int(11) DEFAULT NULL,
  `projectId` varchar(255) DEFAULT NULL,
  `other` text,
  `created_at` text,
  `updated_at` text,
  `currency` text,
  `companyId` int(11) DEFAULT NULL,
  `contractType` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=213 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
INSERT INTO `jobs` VALUES (212,'Added Job','7','[11500,15000]',0,1,'2018-05-17 18:14:58','html,nginx,c++','who knows','# Description \n### Job id \n* c++\n* nginx \n* html\n','piotroxp@gmail.com',NULL,'VjFSQ2IxTXlUa2hXYmxKT1ZUTlNWVmxZY0hOU1JsVjRWbXhLVDJGNlJuaFZiR2h6U3pGT2NrOVZUbGRTVjNoV1ZrVldWazVzUmxoVmJYUmhWakZHZVZVeU1EVmhXRFZNVWxac1dGUXhTa1ZWZW5CdlpFY3hjMHBVU2tSaWJXUndZbTVuYkUxclRtcEtWRXBEU2xSS1EzNVFUMU5VUlZKSlJEcHdhVzkwY205NGNDVTBNR2R0WVdsc0xtTnZiUT09flJFR0RBVEU6MjAxOC0wNS0xNysxOCUzQTE0JTNBNTg=',20,20,0,'29','','2018-05-17 18:14:58','2018-05-17 18:14:58','$',11,NULL);
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-05-18 10:10:32
