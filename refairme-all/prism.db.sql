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
-- Table structure for table `activations`
--

DROP TABLE IF EXISTS `activations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `activations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `activations_user_id_index` (`user_id`),
  CONSTRAINT `activations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activations`
--

LOCK TABLES `activations` WRITE;
/*!40000 ALTER TABLE `activations` DISABLE KEYS */;
/*!40000 ALTER TABLE `activations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `apikeys`
--

DROP TABLE IF EXISTS `apikeys`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `apikeys` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `securekey` varchar(255) DEFAULT NULL,
  `regdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `vendorid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `apikeys`
--

LOCK TABLES `apikeys` WRITE;
/*!40000 ALTER TABLE `apikeys` DISABLE KEYS */;
/*!40000 ALTER TABLE `apikeys` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ci_sessions`
--

DROP TABLE IF EXISTS `ci_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ci_sessions` (
  `id` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `timestamp` int(10) unsigned NOT NULL DEFAULT '0',
  `data` blob NOT NULL,
  KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ci_sessions`
--

LOCK TABLES `ci_sessions` WRITE;
/*!40000 ALTER TABLE `ci_sessions` DISABLE KEYS */;
INSERT INTO `ci_sessions` VALUES ('9j61nj5bnl9k1aajsdtuiur0bf','185.165.169.23',1507931042,'__ci_last_regenerate|i:1507931042;message|a:1:{i:0;s:76:\"Hello, user <a id=\"emailuid\" name=\"emailuid\">NULL</a>. You agree to cookies.\";}__ci_vars|a:1:{s:7:\"message\";s:3:\"new\";}'),('su073vckusdjgsthccqh5tqffl','93.115.95.205',1507931046,'__ci_last_regenerate|i:1507931046;message|a:1:{i:0;s:76:\"Hello, user <a id=\"emailuid\" name=\"emailuid\">NULL</a>. You agree to cookies.\";}__ci_vars|a:1:{s:7:\"message\";s:3:\"new\";}'),('agdmk9h6682v3a9n8bc60slnji','176.10.104.243',1507932180,'__ci_last_regenerate|i:1507932180;message|a:1:{i:0;s:76:\"Hello, user <a id=\"emailuid\" name=\"emailuid\">NULL</a>. You agree to cookies.\";}__ci_vars|a:1:{s:7:\"message\";s:3:\"new\";}'),('u7bfkl2viqq2olkts1r5fs1n0r','176.10.104.243',1507932184,'__ci_last_regenerate|i:1507932184;message|a:1:{i:0;s:76:\"Hello, user <a id=\"emailuid\" name=\"emailuid\">NULL</a>. You agree to cookies.\";}__ci_vars|a:1:{s:7:\"message\";s:3:\"new\";}'),('786l9nj7u77revr582bc1hb24g','185.100.87.245',1507933270,'__ci_last_regenerate|i:1507933270;message|a:1:{i:0;s:76:\"Hello, user <a id=\"emailuid\" name=\"emailuid\">NULL</a>. You agree to cookies.\";}__ci_vars|a:1:{s:7:\"message\";s:3:\"new\";}'),('5lvkrmntbegg5vk458v80it227','185.100.87.245',1507933415,'__ci_last_regenerate|i:1507933415;message|a:1:{i:0;s:76:\"Hello, user <a id=\"emailuid\" name=\"emailuid\">NULL</a>. You agree to cookies.\";}__ci_vars|a:1:{s:7:\"message\";s:3:\"new\";}'),('il9d0rtao5ubmnkda0qgauo6ni','185.100.87.245',1507933416,'__ci_last_regenerate|i:1507933416;message|a:1:{i:0;s:76:\"Hello, user <a id=\"emailuid\" name=\"emailuid\">NULL</a>. You agree to cookies.\";}__ci_vars|a:1:{s:7:\"message\";s:3:\"new\";}'),('k6f4frt8heqjah7e1rrdqfgabe','185.100.87.245',1507933417,'__ci_last_regenerate|i:1507933417;message|a:1:{i:0;s:76:\"Hello, user <a id=\"emailuid\" name=\"emailuid\">NULL</a>. You agree to cookies.\";}__ci_vars|a:1:{s:7:\"message\";s:3:\"new\";}'),('7395us60jjf90d5q3fu451kbbr','180.76.15.151',1507935204,'__ci_last_regenerate|i:1507935204;message|a:1:{i:0;s:76:\"Hello, user <a id=\"emailuid\" name=\"emailuid\">NULL</a>. You agree to cookies.\";}__ci_vars|a:1:{s:7:\"message\";s:3:\"new\";}'),('75oab0ln8u7cisc16gu92et5qs','180.76.15.15',1507935239,'__ci_last_regenerate|i:1507935239;message|a:1:{i:0;s:76:\"Hello, user <a id=\"emailuid\" name=\"emailuid\">NULL</a>. You agree to cookies.\";}__ci_vars|a:1:{s:7:\"message\";s:3:\"new\";}'),('0998j20357tg5dl5jmmfs551nk','51.15.40.233',1507935333,'__ci_last_regenerate|i:1507935333;message|a:1:{i:0;s:76:\"Hello, user <a id=\"emailuid\" name=\"emailuid\">NULL</a>. You agree to cookies.\";}__ci_vars|a:1:{s:7:\"message\";s:3:\"new\";}'),('6v606i0l5nip9lqre8pa4oiuc8','51.15.40.233',1507935336,'__ci_last_regenerate|i:1507935336;message|a:1:{i:0;s:76:\"Hello, user <a id=\"emailuid\" name=\"emailuid\">NULL</a>. You agree to cookies.\";}__ci_vars|a:1:{s:7:\"message\";s:3:\"new\";}'),('qsk225id728s9ckhq1mq4toera','46.166.162.53',1507940792,'__ci_last_regenerate|i:1507940792;message|a:1:{i:0;s:76:\"Hello, user <a id=\"emailuid\" name=\"emailuid\">NULL</a>. You agree to cookies.\";}__ci_vars|a:1:{s:7:\"message\";s:3:\"new\";}'),('fq0f3od3cgjtcamf9d18qkebld','162.247.72.201',1507940795,'__ci_last_regenerate|i:1507940795;message|a:1:{i:0;s:76:\"Hello, user <a id=\"emailuid\" name=\"emailuid\">NULL</a>. You agree to cookies.\";}__ci_vars|a:1:{s:7:\"message\";s:3:\"new\";}'),('79hatbo5t5d48rujl79esmjr1g','180.76.15.143',1507951546,'__ci_last_regenerate|i:1507951546;message|a:1:{i:0;s:76:\"Hello, user <a id=\"emailuid\" name=\"emailuid\">NULL</a>. You agree to cookies.\";}__ci_vars|a:1:{s:7:\"message\";s:3:\"new\";}'),('h2q1soiq29acmtfia0260nv9gb','157.55.39.156',1507959037,'__ci_last_regenerate|i:1507959037;message|a:1:{i:0;s:76:\"Hello, user <a id=\"emailuid\" name=\"emailuid\">NULL</a>. You agree to cookies.\";}__ci_vars|a:1:{s:7:\"message\";s:3:\"new\";}'),('loqf77auke4ti5vulgb5o89sm7','184.105.139.69',1507975799,'__ci_last_regenerate|i:1507975799;message|a:1:{i:0;s:76:\"Hello, user <a id=\"emailuid\" name=\"emailuid\">NULL</a>. You agree to cookies.\";}__ci_vars|a:1:{s:7:\"message\";s:3:\"new\";}'),('v4vr94rhg4csinqjod7gup6ilv','164.132.91.13',1507999443,'__ci_last_regenerate|i:1507999443;message|a:1:{i:0;s:76:\"Hello, user <a id=\"emailuid\" name=\"emailuid\">NULL</a>. You agree to cookies.\";}__ci_vars|a:1:{s:7:\"message\";s:3:\"new\";}'),('m2kdmdo1gjb96n1n1i9b6u4095','157.55.39.156',1508009749,'__ci_last_regenerate|i:1508009749;message|a:1:{i:0;s:76:\"Hello, user <a id=\"emailuid\" name=\"emailuid\">NULL</a>. You agree to cookies.\";}__ci_vars|a:1:{s:7:\"message\";s:3:\"new\";}'),('t8njgeqf0vuiu3eionh5e2hnm2','40.77.167.35',1508021531,'__ci_last_regenerate|i:1508021531;message|a:1:{i:0;s:76:\"Hello, user <a id=\"emailuid\" name=\"emailuid\">NULL</a>. You agree to cookies.\";}__ci_vars|a:1:{s:7:\"message\";s:3:\"new\";}'),('voofpivn2idu2094ml1oapcodg','141.8.144.51',1508030453,'__ci_last_regenerate|i:1508030453;'),('2gjouf21iu129jmnnjdqr53sqn','180.76.15.5',1508033333,'__ci_last_regenerate|i:1508033333;message|a:1:{i:0;s:76:\"Hello, user <a id=\"emailuid\" name=\"emailuid\">NULL</a>. You agree to cookies.\";}__ci_vars|a:1:{s:7:\"message\";s:3:\"new\";}'),('duiqvvf6ara5nhgfdh7gvhq42a','139.162.116.133',1508039801,'__ci_last_regenerate|i:1508039801;message|a:1:{i:0;s:76:\"Hello, user <a id=\"emailuid\" name=\"emailuid\">NULL</a>. You agree to cookies.\";}__ci_vars|a:1:{s:7:\"message\";s:3:\"new\";}'),('196tdaipdc13pm37vchgtb0l8a','180.76.15.136',1508040526,'__ci_last_regenerate|i:1508040526;'),('b0135imdvkjphuq6njqbnmc10m','180.76.15.157',1508057808,'__ci_last_regenerate|i:1508057808;message|a:1:{i:0;s:76:\"Hello, user <a id=\"emailuid\" name=\"emailuid\">NULL</a>. You agree to cookies.\";}__ci_vars|a:1:{s:7:\"message\";s:3:\"new\";}'),('qa5sefvf8dki39s2plce7luns8','157.55.39.75',1508058973,'__ci_last_regenerate|i:1508058973;message|a:1:{i:0;s:76:\"Hello, user <a id=\"emailuid\" name=\"emailuid\">NULL</a>. You agree to cookies.\";}__ci_vars|a:1:{s:7:\"message\";s:3:\"new\";}'),('56tofh4mna9m8chu4db6k5585c','184.105.139.68',1508060574,'__ci_last_regenerate|i:1508060574;message|a:1:{i:0;s:76:\"Hello, user <a id=\"emailuid\" name=\"emailuid\">NULL</a>. You agree to cookies.\";}__ci_vars|a:1:{s:7:\"message\";s:3:\"new\";}'),('1kdeat2abr86pklob7bkqemddk','213.183.51.28',1508064922,'__ci_last_regenerate|i:1508064922;message|a:1:{i:0;s:76:\"Hello, user <a id=\"emailuid\" name=\"emailuid\">NULL</a>. You agree to cookies.\";}__ci_vars|a:1:{s:7:\"message\";s:3:\"new\";}'),('n3q59pfj0iaa5aursg4ksodre1','167.114.232.156',1508071724,'__ci_last_regenerate|i:1508071724;message|a:1:{i:0;s:76:\"Hello, user <a id=\"emailuid\" name=\"emailuid\">NULL</a>. You agree to cookies.\";}__ci_vars|a:1:{s:7:\"message\";s:3:\"new\";}'),('rs1amhr51oco7o821g036csd7v','196.52.43.58',1508071873,'__ci_last_regenerate|i:1508071873;message|a:1:{i:0;s:76:\"Hello, user <a id=\"emailuid\" name=\"emailuid\">NULL</a>. You agree to cookies.\";}__ci_vars|a:1:{s:7:\"message\";s:3:\"new\";}'),('7ur2p85uit6o4e482hvujakmpf','180.76.15.138',1508079929,'__ci_last_regenerate|i:1508079929;message|a:1:{i:0;s:76:\"Hello, user <a id=\"emailuid\" name=\"emailuid\">NULL</a>. You agree to cookies.\";}__ci_vars|a:1:{s:7:\"message\";s:3:\"new\";}'),('1fash92qjln5ggp4ocf20rm2bh','199.249.224.45',1508081776,'__ci_last_regenerate|i:1508081776;message|a:1:{i:0;s:76:\"Hello, user <a id=\"emailuid\" name=\"emailuid\">NULL</a>. You agree to cookies.\";}__ci_vars|a:1:{s:7:\"message\";s:3:\"new\";}'),('u82520g6p1q9l66u2vj1m39hph','144.217.240.34',1508081781,'__ci_last_regenerate|i:1508081781;message|a:1:{i:0;s:76:\"Hello, user <a id=\"emailuid\" name=\"emailuid\">NULL</a>. You agree to cookies.\";}__ci_vars|a:1:{s:7:\"message\";s:3:\"new\";}'),('4k9p2b6o7s22fdhh9r7c7m21s8','163.172.101.137',1508086549,'__ci_last_regenerate|i:1508086549;message|a:1:{i:0;s:76:\"Hello, user <a id=\"emailuid\" name=\"emailuid\">NULL</a>. You agree to cookies.\";}__ci_vars|a:1:{s:7:\"message\";s:3:\"new\";}'),('5jhm0v5njiep8mqpqqetg259sd','137.74.73.179',1508087873,'__ci_last_regenerate|i:1508087873;message|a:1:{i:0;s:76:\"Hello, user <a id=\"emailuid\" name=\"emailuid\">NULL</a>. You agree to cookies.\";}__ci_vars|a:1:{s:7:\"message\";s:3:\"new\";}'),('t7ansq7n0vd67cdlcnciv9ecg0','137.74.73.179',1508087876,'__ci_last_regenerate|i:1508087876;message|a:1:{i:0;s:76:\"Hello, user <a id=\"emailuid\" name=\"emailuid\">NULL</a>. You agree to cookies.\";}__ci_vars|a:1:{s:7:\"message\";s:3:\"new\";}'),('ndc13kvqh1icd602uffheolbfe','104.223.123.98',1508090932,'__ci_last_regenerate|i:1508090932;message|a:1:{i:0;s:76:\"Hello, user <a id=\"emailuid\" name=\"emailuid\">NULL</a>. You agree to cookies.\";}__ci_vars|a:1:{s:7:\"message\";s:3:\"new\";}'),('iucv7n76gip575pob9ahbv0jkv','51.15.84.183',1508090937,'__ci_last_regenerate|i:1508090937;message|a:1:{i:0;s:76:\"Hello, user <a id=\"emailuid\" name=\"emailuid\">NULL</a>. You agree to cookies.\";}__ci_vars|a:1:{s:7:\"message\";s:3:\"new\";}'),('u5u2d3jlbr2s2up8ome35hsvvq','104.218.63.74',1508093293,'__ci_last_regenerate|i:1508093293;message|a:1:{i:0;s:76:\"Hello, user <a id=\"emailuid\" name=\"emailuid\">NULL</a>. You agree to cookies.\";}__ci_vars|a:1:{s:7:\"message\";s:3:\"new\";}'),('4llh3c4hil9m3p005libd094sk','104.218.63.74',1508093296,'__ci_last_regenerate|i:1508093296;message|a:1:{i:0;s:76:\"Hello, user <a id=\"emailuid\" name=\"emailuid\">NULL</a>. You agree to cookies.\";}__ci_vars|a:1:{s:7:\"message\";s:3:\"new\";}'),('fvvjj3urdumgo7hgmfqd99920q','173.254.216.66',1508096321,'__ci_last_regenerate|i:1508096321;message|a:1:{i:0;s:76:\"Hello, user <a id=\"emailuid\" name=\"emailuid\">NULL</a>. You agree to cookies.\";}__ci_vars|a:1:{s:7:\"message\";s:3:\"new\";}'),('jmang716eh1m37du1ksbbnnpti','185.31.172.234',1508096326,'__ci_last_regenerate|i:1508096326;message|a:1:{i:0;s:76:\"Hello, user <a id=\"emailuid\" name=\"emailuid\">NULL</a>. You agree to cookies.\";}__ci_vars|a:1:{s:7:\"message\";s:3:\"new\";}'),('9aa4ltc1629vmc5buheosltm2k','198.50.200.147',1508096595,'__ci_last_regenerate|i:1508096595;message|a:1:{i:0;s:76:\"Hello, user <a id=\"emailuid\" name=\"emailuid\">NULL</a>. You agree to cookies.\";}__ci_vars|a:1:{s:7:\"message\";s:3:\"new\";}'),('oshgd29ci2fpg9oaqplsh8cau9','198.50.200.147',1508096598,'__ci_last_regenerate|i:1508096598;message|a:1:{i:0;s:76:\"Hello, user <a id=\"emailuid\" name=\"emailuid\">NULL</a>. You agree to cookies.\";}__ci_vars|a:1:{s:7:\"message\";s:3:\"new\";}'),('fj2kh0e8ktc9v9oredpqjg0ink','71.6.135.131',1508098320,'__ci_last_regenerate|i:1508098319;message|a:1:{i:0;s:76:\"Hello, user <a id=\"emailuid\" name=\"emailuid\">NULL</a>. You agree to cookies.\";}__ci_vars|a:1:{s:7:\"message\";s:3:\"new\";}'),('pl8sdd8dd39u72k0nku9bdskrl','23.129.64.11',1508100248,'__ci_last_regenerate|i:1508100248;message|a:1:{i:0;s:76:\"Hello, user <a id=\"emailuid\" name=\"emailuid\">NULL</a>. You agree to cookies.\";}__ci_vars|a:1:{s:7:\"message\";s:3:\"new\";}'),('vkuq77fhotodtotlj76okd58g7','23.129.64.11',1508100252,'__ci_last_regenerate|i:1508100252;message|a:1:{i:0;s:76:\"Hello, user <a id=\"emailuid\" name=\"emailuid\">NULL</a>. You agree to cookies.\";}__ci_vars|a:1:{s:7:\"message\";s:3:\"new\";}'),('9mdihvebj613p40dau4cdh4qum','185.107.81.233',1508100272,'__ci_last_regenerate|i:1508100272;message|a:1:{i:0;s:76:\"Hello, user <a id=\"emailuid\" name=\"emailuid\">NULL</a>. You agree to cookies.\";}__ci_vars|a:1:{s:7:\"message\";s:3:\"new\";}'),('156vl97t0ekl7vtetqsmm6dnhn','123.59.146.153',1508101268,'__ci_last_regenerate|i:1508101268;message|a:1:{i:0;s:76:\"Hello, user <a id=\"emailuid\" name=\"emailuid\">NULL</a>. You agree to cookies.\";}__ci_vars|a:1:{s:7:\"message\";s:3:\"new\";}'),('hbf017t3mgahtm9q5ehmfcgfj9','23.129.64.15',1508108884,'__ci_last_regenerate|i:1508108884;message|a:1:{i:0;s:76:\"Hello, user <a id=\"emailuid\" name=\"emailuid\">NULL</a>. You agree to cookies.\";}__ci_vars|a:1:{s:7:\"message\";s:3:\"new\";}'),('tveg2jbaiosilqjdrrp56mr32s','163.172.179.129',1508120875,'__ci_last_regenerate|i:1508120875;message|a:1:{i:0;s:76:\"Hello, user <a id=\"emailuid\" name=\"emailuid\">NULL</a>. You agree to cookies.\";}__ci_vars|a:1:{s:7:\"message\";s:3:\"new\";}'),('5spu3i03qa618p7hvdcuck5t4i','163.172.179.129',1508120878,'__ci_last_regenerate|i:1508120878;message|a:1:{i:0;s:76:\"Hello, user <a id=\"emailuid\" name=\"emailuid\">NULL</a>. You agree to cookies.\";}__ci_vars|a:1:{s:7:\"message\";s:3:\"new\";}'),('d813qpldl0vk1tbbfdom5qd4vl','60.191.49.188',1508121252,'__ci_last_regenerate|i:1508121252;message|a:1:{i:0;s:76:\"Hello, user <a id=\"emailuid\" name=\"emailuid\">NULL</a>. You agree to cookies.\";}__ci_vars|a:1:{s:7:\"message\";s:3:\"new\";}'),('r3n535i8iit19a1p4201oekjd0','137.226.113.23',1508132093,'__ci_last_regenerate|i:1508132093;message|a:1:{i:0;s:76:\"Hello, user <a id=\"emailuid\" name=\"emailuid\">NULL</a>. You agree to cookies.\";}__ci_vars|a:1:{s:7:\"message\";s:3:\"new\";}'),('e8rq02hc0i1jkjnplj66f7r026','195.228.45.176',1508132100,'__ci_last_regenerate|i:1508132100;message|a:1:{i:0;s:76:\"Hello, user <a id=\"emailuid\" name=\"emailuid\">NULL</a>. You agree to cookies.\";}__ci_vars|a:1:{s:7:\"message\";s:3:\"new\";}'),('9jebp2lsnplnakt3ggjlavn9j1','193.15.16.4',1508135806,'__ci_last_regenerate|i:1508135806;message|a:1:{i:0;s:76:\"Hello, user <a id=\"emailuid\" name=\"emailuid\">NULL</a>. You agree to cookies.\";}__ci_vars|a:1:{s:7:\"message\";s:3:\"new\";}'),('rkr0767qadt4plvjcqj3s1mmv3','193.15.16.4',1508135809,'__ci_last_regenerate|i:1508135809;message|a:1:{i:0;s:76:\"Hello, user <a id=\"emailuid\" name=\"emailuid\">NULL</a>. You agree to cookies.\";}__ci_vars|a:1:{s:7:\"message\";s:3:\"new\";}'),('7gshfj0eseoi3ena6ls2ke7j7r','117.50.7.159',1508145928,'__ci_last_regenerate|i:1508145928;message|a:1:{i:0;s:76:\"Hello, user <a id=\"emailuid\" name=\"emailuid\">NULL</a>. You agree to cookies.\";}__ci_vars|a:1:{s:7:\"message\";s:3:\"new\";}'),('780epht4gmi1l7lb9d2hpg1kea','78.88.253.199',1508147605,'__ci_last_regenerate|i:1508147605;message|a:1:{i:0;s:76:\"Hello, user <a id=\"emailuid\" name=\"emailuid\">NULL</a>. You agree to cookies.\";}__ci_vars|a:1:{s:7:\"message\";s:3:\"new\";}'),('5s3aro3dqabe4cod8vvcrke5fq','78.88.253.199',1508148228,'__ci_last_regenerate|i:1508148228;'),('fi1hp9ju0hp7h8jsf9v29cuk4p','78.88.253.199',1508148232,'__ci_last_regenerate|i:1508148232;message|a:1:{i:0;s:76:\"Hello, user <a id=\"emailuid\" name=\"emailuid\">NULL</a>. You agree to cookies.\";}__ci_vars|a:1:{s:7:\"message\";s:3:\"new\";}'),('ehlf2tu7uak7df65bgmq4cp9a2','78.88.253.199',1508149259,'__ci_last_regenerate|i:1508149259;message|a:1:{i:0;s:76:\"Hello, user <a id=\"emailuid\" name=\"emailuid\">NULL</a>. You agree to cookies.\";}__ci_vars|a:1:{s:7:\"message\";s:3:\"new\";}'),('om7d40c0hvk4ba9fd43f90ej32','78.88.253.199',1508149474,'__ci_last_regenerate|i:1508149474;'),('7v38horkncdmtl17j78vje8t9u','78.88.253.199',1508150186,'__ci_last_regenerate|i:1508150186;message|a:1:{i:0;s:76:\"Hello, user <a id=\"emailuid\" name=\"emailuid\">NULL</a>. You agree to cookies.\";}__ci_vars|a:1:{s:7:\"message\";s:3:\"new\";}'),('ur8ofbba1393ifh4ej6ts8q37b','78.88.253.199',1508150936,'__ci_last_regenerate|i:1508150936;'),('dkkcroll1d2puhh9jdpqdmi30d','78.88.253.199',1508151020,'__ci_last_regenerate|i:1508151020;'),('j99q1lq5b2dkun5u3pfduk3r52','78.88.253.199',1508151037,'__ci_last_regenerate|i:1508151037;'),('79h6tlte19gh9n8lgffcdc6n6p','78.88.253.199',1508151164,'__ci_last_regenerate|i:1508151164;message|s:22:\"<p>Incorrect Login</p>\";__ci_vars|a:1:{s:7:\"message\";s:3:\"new\";}'),('79ag3lnv8639co80f09l2tlil7','78.88.253.199',1508151165,'__ci_last_regenerate|i:1508151165;'),('c1n066u56spqagjjbgpbb8qrio','78.88.253.199',1508151193,'__ci_last_regenerate|i:1508151193;message|s:22:\"<p>Incorrect Login</p>\";__ci_vars|a:1:{s:7:\"message\";s:3:\"new\";}'),('rl825t29050nrfvkqvov96cj27','78.88.253.199',1508151202,'__ci_last_regenerate|i:1508151202;'),('d6dfg3f4023fhn2nrgkirj9eip','78.88.253.199',1508151218,'__ci_last_regenerate|i:1508151218;message|a:1:{i:0;s:76:\"Hello, user <a id=\"emailuid\" name=\"emailuid\">NULL</a>. You agree to cookies.\";}__ci_vars|a:1:{s:7:\"message\";s:3:\"new\";}'),('ofks9k12bb3g238bkqa7j5cqdr','78.88.253.199',1508151247,'__ci_last_regenerate|i:1508151247;'),('juru0mrp37mp2jnbtdn24cg7o0','78.88.253.199',1508151430,'__ci_last_regenerate|i:1508151430;'),('1n58nvn80vrptobedq3449l3u3','141.8.144.51',1508151478,'__ci_last_regenerate|i:1508151478;message|a:1:{i:0;s:76:\"Hello, user <a id=\"emailuid\" name=\"emailuid\">NULL</a>. You agree to cookies.\";}__ci_vars|a:1:{s:7:\"message\";s:3:\"new\";}'),('7uqunkt6j692aa48to5bh1frjr','78.88.253.199',1508151582,'__ci_last_regenerate|i:1508151582;'),('65dv4shb83dcjudbj3f2aa9854','78.88.253.199',1508151710,'__ci_last_regenerate|i:1508151710;message|a:1:{i:0;s:76:\"Hello, user <a id=\"emailuid\" name=\"emailuid\">NULL</a>. You agree to cookies.\";}__ci_vars|a:1:{s:7:\"message\";s:3:\"new\";}'),('gq2hl1bt2s4l9njgqtvme133vc','78.88.253.199',1508151718,'__ci_last_regenerate|i:1508151718;'),('cfhos9mkrg13q6cma0l9v44gcr','78.88.253.199',1508151719,'__ci_last_regenerate|i:1508151719;'),('viof4ms5tga9qaautu2gighaf2','78.88.253.199',1508151771,'__ci_last_regenerate|i:1508151771;'),('c9lv0a4c5neejue33t3i10mngs','78.88.253.199',1508151966,'__ci_last_regenerate|i:1508151966;'),('h90ckjj2nadlotr1vr5q80fi7v','78.88.253.199',1508152029,'__ci_last_regenerate|i:1508152029;'),('ri5vd3h9p7trur74c9a3tp6k1a','78.88.253.199',1508152064,'__ci_last_regenerate|i:1508152064;message|s:22:\"<p>Incorrect Login</p>\";__ci_vars|a:1:{s:7:\"message\";s:3:\"new\";}'),('td1tr4ablpsvnsuijhq13qh1pa','78.88.253.199',1508152069,'__ci_last_regenerate|i:1508152069;'),('jgrn48g1rhpka1iidl5jq56tfi','78.88.253.199',1508153315,'__ci_last_regenerate|i:1508153315;message|s:22:\"<p>Incorrect Login</p>\";__ci_vars|a:1:{s:7:\"message\";s:3:\"new\";}'),('g6j45ekh4jdrkcogmt3vunirvj','78.88.253.199',1508153316,'__ci_last_regenerate|i:1508153316;');
/*!40000 ALTER TABLE `ci_sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `companies`
--

DROP TABLE IF EXISTS `companies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `companies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text,
  `description` text,
  `created_at` text,
  `updated_at` text,
  `currency` text,
  `posterId` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `companies`
--

LOCK TABLES `companies` WRITE;
/*!40000 ALTER TABLE `companies` DISABLE KEYS */;
INSERT INTO `companies` VALUES (11,'Prizm','Testing endpoint','2018-05-17 17:57:54','2018-05-17 17:57:54',NULL,'piotroxp%40gmail.com');
/*!40000 ALTER TABLE `companies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `files`
--

DROP TABLE IF EXISTS `files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hash` blob,
  `regdate` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `files`
--

LOCK TABLES `files` WRITE;
/*!40000 ALTER TABLE `files` DISABLE KEYS */;
INSERT INTO `files` VALUES (6,'1f20f0dfd71d8fcb48bb25b87fa23971.csv','test','cGlvdHJveHBAZ21haWwuY29tfiQyeSQxMCRPUUdGaGNiYS5tQjhyMEt6ejYuNGFPQXB4c3h6b1RpNTRBd3BkR3A0dDV5NE8zTlgubWIxQ35wb2lzb24xMQ==','2017-04-22 18:05:32'),(7,'d67366b30b2f0ab4464a214037f4a867.csv','','cGlvdHJveHBAZ21haWwuY29tfiQyeSQxMCRQN205UmhJOE90eWhpWkJiRlZQeTguRjdlSHdIaGRnQ2R0emVQTko5Ukk3bFNYQWs0R0hubX5wb2lzb24xMQ==','2017-04-25 13:27:02'),(8,'5d72eb0fb8d64151ba7cd7d0c1814755.csv','','c2x1cHNraS5waW90ckBnbWFpbC5jb21+JDJ5JDEwJHFzQXoyanhCamhyaS9hTHNsSEgySU9qWnBKU2pUOTBxM3FjVnhpL0I4dzNrSmYuT2k0MVlpfnBvaXNvbg==','2017-04-25 15:24:17'),(9,'eee90022826e3325142aec5133b0de41.csv','','cGlvdHJveHBAZ21haWwuY29tfiQyeSQxMCRXemNwekZWOVBIM2RINUhOYlhMUnF1M1RpdkxqbjZiUGxlZlVYYjJIUUVLT2VDVmthVEZyLn5wb2lzb24xMQ==','2017-04-26 11:54:43'),(10,'6590b22ea04e1feac8c994ea5839394f.csv','testDermot.txt','cGlvdHIuc2x1cHNraUBwcnl6bWF0LnBsfiQyeSQxMCRjcHZsQ3NacHJxbkxYRWVHSmhJZEt1LmpVcndnSEFsUTdEeVpmb01jUTF2enFrRGt2cGR2eX5wb2lzb24=','2017-05-10 14:00:10'),(11,'ca3555bb60db3fc0142db77cc1247b82.csv','testforDermot.csv','cGlvdHIuc2x1cHNraUBwcnl6bWF0LnBsfiQyeSQxMCRjcHZsQ3NacHJxbkxYRWVHSmhJZEt1LmpVcndnSEFsUTdEeVpmb01jUTF2enFrRGt2cGR2eX5wb2lzb24=','2017-05-10 14:05:16'),(12,'41b653d0d5859cd96ea9d9d4dcfdf2e4.csv','tes.csv','cGlvdHIuc2x1cHNraUBwcnl6bWF0LnBsfiQyeSQxMCRjcHZsQ3NacHJxbkxYRWVHSmhJZEt1LmpVcndnSEFsUTdEeVpmb01jUTF2enFrRGt2cGR2eX5wb2lzb24=','2017-05-10 14:06:50'),(13,'5c57616237629ca9bb7e1445961b020b.csv','test.csv','cGlvdHIuc2x1cHNraUBwcnl6bWF0LnBsfiQyeSQxMCRjcHZsQ3NacHJxbkxYRWVHSmhJZEt1LmpVcndnSEFsUTdEeVpmb01jUTF2enFrRGt2cGR2eX5wb2lzb24=','2017-05-10 14:08:05'),(14,'6737331f5d8c31892bf8747701ff81c8.csv','test.csv','cGlvdHIuc2x1cHNraUBwcnl6bWF0LnBsfiQyeSQxMCRjcHZsQ3NacHJxbkxYRWVHSmhJZEt1LmpVcndnSEFsUTdEeVpmb01jUTF2enFrRGt2cGR2eX5wb2lzb24=','2017-05-10 14:09:23'),(15,'067c7b04ce23b0b1c51bde43c2076ec5.csv','','cGlvdHIuc2x1cHNraUBwcnl6bWF0LnBsfiQyeSQxMCRjcHZsQ3NacHJxbkxYRWVHSmhJZEt1LmpVcndnSEFsUTdEeVpmb01jUTF2enFrRGt2cGR2eX5wb2lzb24=','2017-05-10 14:18:52'),(16,'be0d317dc38f87e5ec25a967b417e23c.csv','','cGlvdHIuc2x1cHNraUBwcnl6bWF0LnBsfiQyeSQxMCRjcHZsQ3NacHJxbkxYRWVHSmhJZEt1LmpVcndnSEFsUTdEeVpmb01jUTF2enFrRGt2cGR2eX5wb2lzb24=','2017-05-10 14:21:28'),(17,'746bb86156d6fd279d8bc39f1e439484.csv','','cGlvdHIuc2x1cHNraUBwcnl6bWF0LnBsfiQyeSQxMCRjcHZsQ3NacHJxbkxYRWVHSmhJZEt1LmpVcndnSEFsUTdEeVpmb01jUTF2enFrRGt2cGR2eX5wb2lzb24=','2017-05-10 14:22:12'),(18,'bab98f096b01b3701adb6623e9fedca3.csv','','cGlvdHIuc2x1cHNraUBwcnl6bWF0LnBsfiQyeSQxMCRpQ0FlQWxJaXNKNkJBb0xsWVNiVEVPWVA5VWRUdEdjZThFUzhRS25sbm5pQzB2MHp5Q1ZLaX5wb2lzb24=','2017-05-10 14:25:37'),(19,'0b93378d4d313006abd1499fa49f64b9.csv','','cGlvdHIuc2x1cHNraUBwcnl6bWF0LnBsfiQyeSQxMCRpQ0FlQWxJaXNKNkJBb0xsWVNiVEVPWVA5VWRUdEdjZThFUzhRS25sbm5pQzB2MHp5Q1ZLaX5wb2lzb24=','2017-05-10 14:26:08'),(20,'3baaff25b3e258dba1d7b6cd949e4353.csv','','cGlvdHIuc2x1cHNraUBwcnl6bWF0LnBsfiQyeSQxMCRUSGR1aENVbDgxOWhHOWEvZTc3cjZ1OWNzbFBoN21ESVNnMWkzSHlQU3lvODhaM2YySFJObX5wb2lzb24=','2017-05-10 14:28:15'),(21,'1b5cdfaee63732a7dfff4a34d8326fdf.csv','','cGlvdHIuc2x1cHNraUBwcnl6bWF0LnBsfiQyeSQxMCRUSGR1aENVbDgxOWhHOWEvZTc3cjZ1OWNzbFBoN21ESVNnMWkzSHlQU3lvODhaM2YySFJObX5wb2lzb24=','2017-05-10 14:29:07'),(22,'e5bb367f9c5571bf095f5ce9b41bf6fd.csv','','cGlvdHIuc2x1cHNraUBwcnl6bWF0LnBsfiQyeSQxMCRUSGR1aENVbDgxOWhHOWEvZTc3cjZ1OWNzbFBoN21ESVNnMWkzSHlQU3lvODhaM2YySFJObX5wb2lzb24=','2017-05-10 14:30:02'),(23,'795a4a036407e8c4f3a3f79229eb3120.csv','','cGlvdHIuc2x1cHNraUBwcnl6bWF0LnBsfiQyeSQxMCRUSGR1aENVbDgxOWhHOWEvZTc3cjZ1OWNzbFBoN21ESVNnMWkzSHlQU3lvODhaM2YySFJObX5wb2lzb24=','2017-05-10 14:32:35'),(24,'07875544417b4ad98f3f64d484c71d80.csv','','cGlvdHIuc2x1cHNraUBwcnl6bWF0LnBsfiQyeSQxMCRUSGR1aENVbDgxOWhHOWEvZTc3cjZ1OWNzbFBoN21ESVNnMWkzSHlQU3lvODhaM2YySFJObX5wb2lzb24=','2017-05-10 14:37:11');
/*!40000 ALTER TABLE `files` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobrefs`
--

DROP TABLE IF EXISTS `jobrefs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobrefs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `referred_id` varchar(255) DEFAULT NULL,
  `location_id` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `keywords` varchar(255) DEFAULT NULL,
  `regdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `hash` blob,
  `state` varchar(255) DEFAULT NULL,
  `jobid` int(11) DEFAULT NULL,
  `referrer_id` varchar(255) DEFAULT NULL,
  `created_at` text,
  `updated_at` text,
  `interview_begin_hour` text,
  `interview_end_hour` text,
  `interview_date` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobrefs`
--

LOCK TABLES `jobrefs` WRITE;
/*!40000 ALTER TABLE `jobrefs` DISABLE KEYS */;
INSERT INTO `jobrefs` VALUES (33,'piotroxp@gmail.com',NULL,'Chief Content Manager',NULL,'2017-12-02 12:42:53','cHJpem1%2BVU5JUVJFRjpjSEpwZW0xK1NrOUNTVVE2TVRVd2ZreFBRMGxFT241TFJWbFhUMUpFVXpveU1ERTNMVEV5TFRBeUlERXpPalF5T2pVemZsTlVRVlJGT2taSlVsTlVJRk5JVDFRPQ%3D%3D','FIRST SHOT',150,'piotroxp@gmail.com','2017-12-02 13:42:53','2017-12-02 13:42:53',NULL,NULL,NULL),(34,'piotroxp@gmail.com',NULL,'Chief Content Manager',NULL,'2017-12-02 12:51:11','cHJpem1%2BVU5JUVJFRjpjSEpwZW0xK1NrOUNTVVE2TVRVd2ZreFBRMGxFT241TFJWbFhUMUpFVXpveU1ERTNMVEV5TFRBeUlERXpPalV4T2pFeGZsTlVRVlJGT2taSlVsTlVJRk5JVDFRPQ%3D%3D','FIRST SHOT',150,'piotroxp@gmail.com','2017-12-02 13:51:11','2017-12-02 13:51:11',NULL,NULL,NULL),(35,'slupski.piotr@gmail.com',NULL,'Prizm Full Stack Engineer',NULL,'2017-12-02 13:26:31','cHJpem1%2BVU5JUVJFRjpjSEpwZW0xK1NrOUNTVVE2TVRRNGZreFBRMGxFT241TFJWbFhUMUpFVXpveU1ERTNMVEV5TFRBeUlERTBPakkyT2pNeGZsTlVRVlJGT2taSlVsTlVJRk5JVDFRPQ%3D%3D','FIRST SHOT',148,'piotroxp@gmail.com','2017-12-02 14:26:31','2017-12-02 14:26:31',NULL,NULL,NULL),(36,'piotroxp@gmail.com','108','Chief Content Manager',NULL,'2017-12-11 21:23:06','cHJpem1%2BVU5JUVJFRjpjSEpwZW0xK1NrOUNTVVE2TVRVd2ZreFBRMGxFT241TFJWbFhUMUpFVXpveU1ERTNMVEV5TFRFeElESXlPakl6T2pBMmZsTlVRVlJGT2taSlVsTlVJRk5JVDFRPQ%3D%3D','FIRST SHOT',150,'slupski.piotr@gmail.com','2017-12-11 22:23:06','2017-12-11 22:23:06',NULL,NULL,NULL),(37,'piotroxp@gmail.com',NULL,'Prizm Full Stack Engineer',NULL,'2017-12-15 13:15:42','cHJpem1%2BVU5JUVJFRjpjSEpwZW0xK1NrOUNTVVE2TVRRNGZreFBRMGxFT241TFJWbFhUMUpFVXpveU1ERTNMVEV5TFRFMUlERTBPakUxT2pReWZsTlVRVlJGT2taSlVsTlVJRk5JVDFRPQ%3D%3D','FIRST SHOT',148,'piotroxp@o2.pl','2017-12-15 14:15:42','2017-12-15 14:15:42',NULL,NULL,NULL);
/*!40000 ALTER TABLE `jobrefs` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=InnoDB AUTO_INCREMENT=214 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
INSERT INTO `jobs` VALUES (212,'Added Job','7','[11500,15000]',0,1,'2018-05-18 12:24:11','html,c++','who knows','# Description \n### Job id \n* c++\n* nginx \n* html\n','',NULL,'VjFSQ2IxTXlUa2hXYmxKT1ZUTlNWVmxZY0hOU1JsVjRWbXhLVDJGNlJuaFZiR2h6U3pGT2NrOVZUbGRTVjNoV1ZrVldWazVzUmxoVmJYUmhWakZHZVZVeU1EVmhXRFZNVWxac1dGUXhTa1ZWZW5CdlpFY3hjMHBVU2tSaWJXUndZbTVuYkUxclRtcEtWRXBEU2xSS1EzNVFUMU5VUlZKSlJEcHdhVzkwY205NGNDVTBNR2R0WVdsc0xtTnZiUT09flJFR0RBVEU6MjAxOC0wNS0xNysxOCUzQTE0JTNBNTg=',20,20,0,'2','','2018-05-17 18:14:58','2018-05-18 14:24:11','fdsa',1,NULL),(213,'sdafsdafdsa','3','[3000,15000]',1,1,'2018-05-18 14:44:30','nginx,c++','fdsafdsa','fdsafdsa','piotroxp@gmail.com',NULL,'VjFSQ2IxTXlUa2hXYmxKT1ZUTlNWVmxZY0hOU1JsVjRWbXhLVDJGNlJuaFZiR2gzU3pGT2NrOVZUbGRTVjNoV1ZrVldWazV0VFhsVmJXaGhZbXMxY2xkV1pHRmhNazE1VWxReEsxTXdWbHBXTURsVFVrWk5ObUp0WkhCaWJtZHNUV3RPYWtwVVNrTktWRXBEZmxCUFUxUkZVa2xFT25CcGIzUnliM2h3SlRRd1oyMWhhV3d1WTI5dH5SRUdEQVRFOjIwMTgtMDUtMTgrMTQlM0E0NCUzQTMw',69,20,1,'29','','2018-05-18 14:44:30','2018-05-18 14:44:30','$',11,'fdsa');
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobweights`
--

DROP TABLE IF EXISTS `jobweights`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobweights` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `aone` double NOT NULL,
  `atwo` double NOT NULL,
  `athree` double NOT NULL,
  `afour` double NOT NULL,
  `afive` double NOT NULL,
  `asix` double NOT NULL,
  `aseven` double NOT NULL,
  `aeight` double NOT NULL,
  `anine` double NOT NULL,
  `aten` double NOT NULL,
  `aeleven` double NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `jobid` int(11) DEFAULT NULL,
  `keywords` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=122 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobweights`
--

LOCK TABLES `jobweights` WRITE;
/*!40000 ALTER TABLE `jobweights` DISABLE KEYS */;
INSERT INTO `jobweights` VALUES (119,0.10371350272711913,0.0000001719019487269323,0.0000022848455369934605,0.00043010199537239877,0.8110607009241738,0.08461583935079098,0.0000027808744363538247,0.000000013275492603194531,0.0001406582842430908,0.00003394280343692622,0.0000000030174492924253197,'2018-05-17 16:14:59','2018-05-17 16:14:59',212,'Array'),(120,0.9942955389207568,0.00010230315511482467,0.00020356404634872395,0.003418644458060051,0.0009969067181066144,0.0001623646596865405,0.0002649412078952143,0.000008229062612552443,0.0002630853746830455,0.0002738645155850461,0.000010557881150737093,'2018-05-18 12:24:12','2018-05-18 12:24:12',212,NULL),(121,0.9835872560215433,0.0001324303240092336,0.00028162242124619145,0.00992029108292766,0.004742296016022363,0.00006927863529910375,0.0005369495200685937,0.000010406635159848755,0.0003884983840993398,0.00031736643526931183,0.000013604524354897295,'2018-05-18 12:44:31','2018-05-18 12:44:31',213,'Array');
/*!40000 ALTER TABLE `jobweights` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `keywords`
--

DROP TABLE IF EXISTS `keywords`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `keywords` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `termid` int(11) DEFAULT NULL,
  `keyone` varchar(255) DEFAULT NULL,
  `keytwo` varchar(255) DEFAULT NULL,
  `keythree` varchar(255) DEFAULT NULL,
  `searchterm` varchar(255) DEFAULT NULL,
  `regdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `cnt` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `keywords`
--

LOCK TABLES `keywords` WRITE;
/*!40000 ALTER TABLE `keywords` DISABLE KEYS */;
/*!40000 ALTER TABLE `keywords` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `linkedin_imports`
--

DROP TABLE IF EXISTS `linkedin_imports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `linkedin_imports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstName` varchar(255) NOT NULL,
  `lastName` varchar(255) NOT NULL,
  `company` varchar(255) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `notes` varchar(255) DEFAULT NULL,
  `tags` varchar(255) DEFAULT NULL,
  `uidInviter` varchar(255) DEFAULT NULL,
  `regdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `skills` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `linkedin_imports`
--

LOCK TABLES `linkedin_imports` WRITE;
/*!40000 ALTER TABLE `linkedin_imports` DISABLE KEYS */;
INSERT INTO `linkedin_imports` VALUES (2,'Piotr','Słupski','BASIC PROFILE PERMISSIONS - UPDATE YOUR LI PERMISSIONS ','BASIC PROFILE PERMISSIONS - hidden','slupski.piotr@gmail.com',NULL,NULL,'{\"body\":\"{\\n  \\\"firstName\\\": \\\"Piotr\\\",\\n  \\\"headline\\\": \\\"Having fun patterning.\\\",\\n  \\\"id\\\": \\\"Fy8qzy9cRX\\\",\\n  \\\"lastName\\\": \\\"S\\u0142upski\\\",\\n  \\\"siteStandardProfileRequest\\\": {\\\"url\\\": \\\"https:\\/\\/www.linkedin.com\\/profile\\/view?id=AAoAAAYwgCMBBuhU','V1RCb1MyTkhWblJOVTNSWFZtdEtRMVpVUms1T2JFcEdWbXRrVWxac1drNVdhMVkwVTIwMWVsbHJNVTFpTVZwcFlucE9WRnBxU2xGaFJYQjRXa1paZUdRd1JUbFFXRFZXVkZWR1NsUkVjSHBpU0ZaM1l6SjBjRXh1UW5CaU0xSjVVVWRrZEZsWGJITk1iVTUyWWxFOVBYNVVUMHRGVGpwQlVWWmxhV1JaUW14VE5sQjBNM0Z1YUZaVFpUUjJOVmx','2017-12-12 14:55:30','2017-12-12 13:55:30','2017-12-12 13:55:30','null');
/*!40000 ALTER TABLE `linkedin_imports` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `locations`
--

DROP TABLE IF EXISTS `locations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `locations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jobref_hashes` mediumblob,
  `name` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `zip` varchar(255) NOT NULL,
  `lat` text,
  `lng` text,
  `hash` blob,
  `regdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `userid` varchar(255) DEFAULT NULL,
  `description` blob,
  `updated_at` text,
  `created_at` text,
  `currency` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=109 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `locations`
--

LOCK TABLES `locations` WRITE;
/*!40000 ALTER TABLE `locations` DISABLE KEYS */;
INSERT INTO `locations` VALUES (107,NULL,'PRIZM','Wrocław','Poland','Kilińskiego 28/16','50-264','---','---','cHJpem1%2BY0hKcGVtMStUa0ZOUlRwUVVrbGFUWDVEU1ZSWk9sZHliMlBGZ21GM2ZscEpVRG8xTUMweU5qUT0%3D','2017-11-23 11:54:45',NULL,'Wrocław wymowa i (łac. Vratislavia lub Wratislavia lub Budorgis[4], niem. Breslau i, śl-niem. Brassel, czes. Vratislav, węg. Boroszló) – miasto na prawach powiatu w południowo-zachodniej Polsce, siedziba władz województwa dolnośląskiego i powiatu wrocławskiego. Położone w Europie Środkowej na Nizinie Śląskiej, w Pradolinie Wrocławskiej, nad rzeką Odrą i czterema jej dopływami. Jest historyczną stolicą Dolnego Śląska, a także całego Śląska.<br />\n<br />\nJest głównym miastem aglomeracji wrocławskiej, a także największym miastem leżącym na Ziemiach Odzyskanych. Czwarte pod względem liczby ludności miasto w Polsce – 638 364 mieszkańców[3], piąte pod względem powierzchni – 292,82 km².<br />\n<br />\nDawna stolica księstwa wrocławskiego, siedziba władz pruskiej prowincji Śląsk i rejencji wrocławskiej. Od 28 czerwca 1946 stolica województwa wrocławskiego. Od 1 stycznia 1999 stolica województwa dolnośląskiego.<br />\n<br />\nWrocław znalazł się wśród 230 miast świata w rankingu firmy doradczej Mercer „Najlepsze miasta do życia” w roku 2015 oraz jako jedyne polskie miasto został w tym rankingu ujęty jako miasto wyrastające na centrum biznesowe[5].','2017-11-23 12:54:45','2017-11-23 12:54:45','PLN'),(108,NULL,'PRIZM Coders Lounge','Wrocław','Poland','Kilinskiego 28/16','50-264','---','---','cHJpem1%2BY0hKcGVtMStUa0ZOUlRwUVVrbGFUU0JEYjJSbGNuTWdURzkxYm1kbGZrTkpWRms2VjNKdlk4V0NZWGQrV2tsUU9qVXdMVEkyTkE9PQ%3D%3D','2017-11-23 14:16:16',NULL,'A hackerspace (also referred to as a hacklab, makerspace or hackspace) is a community-operated, often \"Not For Profit\" (501(c)(3) in the United States), work space where people with common interests, often in computers, machining, technology, science, digital art or electronic art, can meet, socialize and collaborate.[1] <br />\n<br />\nHackerspaces have also been compared to other community-operated s with similar aims and mechanisms such as Fab Lab, men\'s sheds, and commercial \"for-profit\" companies such as TechShop.<br />\n<br />\nIn general, hackerspaces function as centers for peer learning and knowledge sharing, in the form of workshops, presentations, and lectures. They usually also offer social activities for their members, such as game nights and parties. Hackerspaces can be viewed as open community labs incorporating elements of machine shops, workshops, and/or studios where hackers can come together to share resources and knowledge to build and make things.[2]','2017-11-23 15:16:16','2017-11-23 15:16:16','PLN');
/*!40000 ALTER TABLE `locations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `login_attempts`
--

DROP TABLE IF EXISTS `login_attempts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `login_attempts` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(45) NOT NULL,
  `login` varchar(100) NOT NULL,
  `time` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `login_attempts`
--

LOCK TABLES `login_attempts` WRITE;
/*!40000 ALTER TABLE `login_attempts` DISABLE KEYS */;
INSERT INTO `login_attempts` VALUES (1,'78.88.253.199','piotr.slupski@pryzmat.pl',1508151164),(2,'78.88.253.199','piotr.slupski@pryzmat.pl',1508151193),(3,'78.88.253.199','piotr.slupski@pryzmat.pl',1508152064),(4,'78.88.253.199','piotr.slupski@pryzmat.pl',1508153315);
/*!40000 ALTER TABLE `login_attempts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2017_06_06_142328_create_apikeys_table',0),(2,'2017_06_06_142328_create_ci_sessions_table',0),(3,'2017_06_06_142328_create_files_table',0),(4,'2017_06_06_142328_create_groups_table',0),(5,'2017_06_06_142328_create_jobdescs_table',0),(6,'2017_06_06_142328_create_jobrefs_table',0),(7,'2017_06_06_142328_create_linkedin_imports_table',0),(8,'2017_06_06_142328_create_locations_table',0),(9,'2017_06_06_142328_create_login_attempts_table',0),(10,'2017_06_06_142328_create_perks_table',0),(11,'2017_06_06_142328_create_users_table',0),(12,'2017_06_06_142328_create_users_groups_table',0),(13,'2017_06_06_142328_create_vendors_table',0),(14,'2017_06_06_142329_add_foreign_keys_to_users_groups_table',0),(15,'2014_10_12_000000_create_users_table',1),(16,'2014_10_12_100000_create_password_resets_table',1),(17,'2016_01_15_105324_create_roles_table',1),(18,'2016_01_15_114412_create_role_user_table',1),(19,'2016_01_26_115212_create_permissions_table',1),(20,'2016_01_26_115523_create_permission_role_table',1),(21,'2016_02_09_132439_create_permission_user_table',1),(22,'2017_03_09_082449_create_social_logins_table',1),(23,'2017_03_09_082526_create_activations_table',1),(24,'2017_03_20_213554_create_themes_table',1),(25,'2017_03_21_042918_create_profiles_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oauth_access_tokens`
--

DROP TABLE IF EXISTS `oauth_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `oauth_access_tokens` (
  `access_token` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `client_id` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expires` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `scope` varchar(2000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`access_token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oauth_access_tokens`
--

LOCK TABLES `oauth_access_tokens` WRITE;
/*!40000 ALTER TABLE `oauth_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `oauth_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oauth_authorization_codes`
--

DROP TABLE IF EXISTS `oauth_authorization_codes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `oauth_authorization_codes` (
  `authorization_code` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `client_id` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `redirect_uri` varchar(2000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expires` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `scope` varchar(2000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`authorization_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oauth_authorization_codes`
--

LOCK TABLES `oauth_authorization_codes` WRITE;
/*!40000 ALTER TABLE `oauth_authorization_codes` DISABLE KEYS */;
/*!40000 ALTER TABLE `oauth_authorization_codes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oauth_clients`
--

DROP TABLE IF EXISTS `oauth_clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `oauth_clients` (
  `client_id` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `client_secret` varchar(5000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `redirect_uri` varchar(2000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `grant_types` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scope` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oauth_clients`
--

LOCK TABLES `oauth_clients` WRITE;
/*!40000 ALTER TABLE `oauth_clients` DISABLE KEYS */;
INSERT INTO `oauth_clients` VALUES ('AQVeidYBlS6Pt3qnhVSe4v5YFTbVdT99Au0QS44xj-HyZQq_JDs9VbkXSyB8mMQDpe5OE-H-HNqa5srL','V1RCb1MyTkhWblJOVTNSWFZtdEtRMVpVUms1T2JFcEdWbXRrVWxac1drNVdhMVkwVTIwMWVsbHJNVTFpTVZwcFlucE9WRnBxU2xGaFJYQjRXa1paZUdRd1JUbFFXRFZXVkZWR1NsUkVjSHBpU0ZaM1l6SjBjRXh1UW5CaU0xSjVVVWRrZEZsWGJITk1iVTUyWWxFOVBYNVVUMHRGVGpwQlVWWmxhV1JaUW14VE5sQjBNM0Z1YUZaVFpUUjJOVmxHVkdKV1pGUTVPVUYxTUZGVE5EUjRhaTFJZVZwUmNWOUtSSE01Vm1KcldGTjVRamh0VFZGRWNHVTFUMFV0U0MxSVRuRmhOWE55VEc4MVJGOVVhbUZHTjJkNWVsRlRlRGx3YzJGRmFWVnBiVTVhVTFGUlJERnRSVUpXVWxsb2MwaHVkbE5mVms1NWFGZHBMVXRRWjI1bWEyUXlXVTlGZFhKdlRuVkhiSFJqUWtoZlNWTkJXbWd0WXpSdVpUUlpVa05FU0dsZlNUVktNMGhDVGtaUE0zUkxUMU01VGtGU2VteEVZVmRXYlRabGRqRnlVVzFaZFZkTVpWUXdaRFpYUjBWWlZFRkxOemxoUkVwblp6SXRTMlJuUkZoNE5FODBibWRIZEdzMU1tRnFNa3hSWXprNE9VOU5ZblpLTWs5cFFrVlRTVmhTV1RnM1VHcHdTR2xvTjNvMFVFRklVMVUzWDBaMFJVNVJhbk52TTI5blRWVnBWMHMxY1dOd2FtcG5VbTVyVW5GVFVsVlNTRFJ0TldGNWEzTnBlbGRWYVZsRVgwTnFlVloxTnpKV1JHVldNVFZJWnc9PX5EQVRFOjIwMTcxMjEyMDI1NTI5','','MINIMAL','DEFAULT','slupski.piotr@gmail.com','2017-12-12 13:55:29','2017-12-12 13:55:29');
/*!40000 ALTER TABLE `oauth_clients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oauth_jwt`
--

DROP TABLE IF EXISTS `oauth_jwt`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `oauth_jwt` (
  `client_id` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `public_key` varchar(2000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oauth_jwt`
--

LOCK TABLES `oauth_jwt` WRITE;
/*!40000 ALTER TABLE `oauth_jwt` DISABLE KEYS */;
/*!40000 ALTER TABLE `oauth_jwt` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oauth_refresh_tokens`
--

DROP TABLE IF EXISTS `oauth_refresh_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `oauth_refresh_tokens` (
  `refresh_token` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `client_id` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expires` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `scope` varchar(2000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`refresh_token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oauth_refresh_tokens`
--

LOCK TABLES `oauth_refresh_tokens` WRITE;
/*!40000 ALTER TABLE `oauth_refresh_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `oauth_refresh_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oauth_scopes`
--

DROP TABLE IF EXISTS `oauth_scopes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `oauth_scopes` (
  `scope` text COLLATE utf8mb4_unicode_ci,
  `is_default` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oauth_scopes`
--

LOCK TABLES `oauth_scopes` WRITE;
/*!40000 ALTER TABLE `oauth_scopes` DISABLE KEYS */;
/*!40000 ALTER TABLE `oauth_scopes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_resets` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `password_resets_email_index` (`email`),
  KEY `password_resets_token_index` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `perks`
--

DROP TABLE IF EXISTS `perks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `perks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jobid` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `uid` int(11) NOT NULL,
  `agreed_employer` int(11) DEFAULT NULL,
  `agreed_employee` int(11) DEFAULT NULL,
  `hash` blob,
  `regdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `target` varchar(255) DEFAULT NULL,
  `agreed_referee` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=74 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `perks`
--

LOCK TABLES `perks` WRITE;
/*!40000 ALTER TABLE `perks` DISABLE KEYS */;
INSERT INTO `perks` VALUES (49,45,'3% budget',2,1,NULL,'fjMlIGJ1ZGdldH4yMDE3LTA1LTAzIDEzOjQyOjExfjJ+NDU=','2017-05-03 13:42:11','both',NULL),(50,46,'3% of bounty',2,1,NULL,'fjMlIG9mIGJvdW50eX4yMDE3LTA1LTAzIDEzOjQ3OjU3fjJ+NDY=','2017-05-03 13:47:57','both',NULL),(51,46,'3%',2,1,NULL,'fjMlfjIwMTctMDUtMDMgMTM6NDg6MTh+Mn40Ng==','2017-05-03 13:48:18','both',NULL),(52,48,'Bounty 3% from the contract\'s worth',3,1,NULL,'fkJvdW50eSAzJSBmcm9tIHRoZSBjb250cmFjdCdzIHdvcnRofjIwMTctMDUtMDMgMTU6MTM6MjB+M340OA==','2017-05-03 15:13:20','both',NULL),(53,48,'lubie placki',3,1,NULL,'fmx1YmllIHBsYWNraX4yMDE3LTA1LTAzIDE1OjU1OjA2fjN+NDg=','2017-05-03 15:55:06','both',NULL),(56,45,'1000 PLN Cash for the referral',2,1,NULL,'fjEwMDAgUExOIENhc2ggZm9yIHRoZSByZWZlcnJhbH4yMDE3LTA1LTA0IDE3OjM3OjA5fjJ+NDU=','2017-05-04 17:37:09','both',NULL),(63,48,'1000 PLN for sucessful referral',2,1,NULL,'fjEwMDAgUExOIGZvciBzdWNlc3NmdWwgcmVmZXJyYWx+MjAxNy0wNS0wNiAxODowNDowMH4yfjQ4','2017-05-06 18:04:00','for-referral',NULL),(64,52,'bounty 1000',2,1,NULL,'fmJvdW50eSAxMDAwfjIwMTctMDUtMDYgMjI6MDg6NTR+Mn41Mg==','2017-05-06 22:08:54','for-referral',NULL),(65,51,'3000$ for succesfull hire',2,1,NULL,'fjMwMDAkIGZvciBzdWNjZXNmdWxsIGhpcmV+MjAxNy0wNS0xMCAxMDo0NzoxNH4yfjUx','2017-05-10 10:47:14','for-referral',NULL),(69,55,'3000 $ for a sucessfull referral',2,1,NULL,'fjMwMDAgJCBmb3IgYSBzdWNlc3NmdWxsIHJlZmVycmFsfjIwMTctMDUtMTAgMTU6MjM6Mjl+Mn41NQ==','2017-05-10 15:23:29','for-referral',NULL),(70,55,'Free coffee for a year',2,1,NULL,'fkZyZWUgY29mZmVlIGZvciBhIHllYXJ+MjAxNy0wNS0xMCAxNToyMzo1Nn4yfjU1','2017-05-10 15:23:56','for-hire',NULL),(71,55,'1% of yearly value of hire',2,1,NULL,'fjElIG9mIHllYXJseSB2YWx1ZSBvZiBoaXJlfjIwMTctMDUtMTAgMTU6MjQ6MjN+Mn41NQ==','2017-05-10 15:24:23','both',NULL),(72,82,'test',2,1,NULL,'fnRlc3R+MjAxNy0wNS0yMyAwMDoyMTowMX4yfjgy','2017-05-23 00:21:01','both',NULL),(73,82,'test12',2,1,NULL,'fnRlc3QxMn4yMDE3LTA1LTIzIDAwOjIxOjA4fjJ+ODI=','2017-05-23 00:21:08','for-hire',NULL);
/*!40000 ALTER TABLE `perks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permission_role`
--

DROP TABLE IF EXISTS `permission_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permission_role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `permission_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `permission_role_permission_id_index` (`permission_id`),
  KEY `permission_role_role_id_index` (`role_id`),
  CONSTRAINT `permission_role_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `permission_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permission_role`
--

LOCK TABLES `permission_role` WRITE;
/*!40000 ALTER TABLE `permission_role` DISABLE KEYS */;
INSERT INTO `permission_role` VALUES (1,1,1,'2017-06-08 16:39:11','2017-06-08 16:39:11'),(2,2,1,'2017-06-08 16:39:11','2017-06-08 16:39:11'),(3,3,1,'2017-06-08 16:39:11','2017-06-08 16:39:11'),(4,4,1,'2017-06-08 16:39:11','2017-06-08 16:39:11');
/*!40000 ALTER TABLE `permission_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permission_user`
--

DROP TABLE IF EXISTS `permission_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permission_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `permission_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `permission_user_permission_id_index` (`permission_id`),
  KEY `permission_user_user_id_index` (`user_id`),
  CONSTRAINT `permission_user_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `permission_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permission_user`
--

LOCK TABLES `permission_user` WRITE;
/*!40000 ALTER TABLE `permission_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `permission_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permissions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `model` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (1,'Can View Users','view.users','Can view users','Permission','2017-06-08 16:39:11','2017-06-08 16:39:11'),(2,'Can Create Users','create.users','Can create new users','Permission','2017-06-08 16:39:11','2017-06-08 16:39:11'),(3,'Can Edit Users','edit.users','Can edit users','Permission','2017-06-08 16:39:11','2017-06-08 16:39:11'),(4,'Can Delete Users','delete.users','Can delete users','Permission','2017-06-08 16:39:11','2017-06-08 16:39:11');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `profiles`
--

DROP TABLE IF EXISTS `profiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `profiles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `theme_id` int(10) unsigned NOT NULL DEFAULT '1',
  `location` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bio` text COLLATE utf8mb4_unicode_ci,
  `twitter_username` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `github_username` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar_status` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `profiles_theme_id_foreign` (`theme_id`),
  KEY `profiles_user_id_index` (`user_id`),
  CONSTRAINT `profiles_theme_id_foreign` FOREIGN KEY (`theme_id`) REFERENCES `themes` (`id`),
  CONSTRAINT `profiles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `profiles`
--

LOCK TABLES `profiles` WRITE;
/*!40000 ALTER TABLE `profiles` DISABLE KEYS */;
/*!40000 ALTER TABLE `profiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `projects`
--

DROP TABLE IF EXISTS `projects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `projects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` text,
  `posterId` varchar(255) DEFAULT NULL,
  `staff` int(11) DEFAULT NULL,
  `stack` text,
  `breakdown` text,
  `companyId` text,
  `created_at` text,
  `updated_at` text,
  `currency` text,
  `methodology` text,
  `stage` text,
  `name` text,
  `contractType` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `projects`
--

LOCK TABLES `projects` WRITE;
/*!40000 ALTER TABLE `projects` DISABLE KEYS */;
INSERT INTO `projects` VALUES (28,'Because It\'s awesome','piotroxp%40gmail.com',321,'\"c++,nginx\"','{\"newFeatures\":27,\"maintenance\":34,\"support\":55,\"documentation\":20,\"meetings\":27}','11','2018-05-17 17:58:43','2018-05-17 17:58:43',NULL,'[\"unit testing\",\"code reviews\",\"knowledge repository\"]','greenfield','Testing Project',NULL),(29,'Because It\'s awesome',NULL,321,'\"c++,nginx\"','{\"newFeatures\":39,\"maintenance\":20,\"support\":42,\"documentation\":20,\"meetings\":20}','11','2018-05-17 18:13:33','2018-05-17 18:13:33',NULL,'[\"pair programming\",\"code reviews\",\"issue tracking tool\"]','greenfield','Testing Project 2',NULL);
/*!40000 ALTER TABLE `projects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_user`
--

DROP TABLE IF EXISTS `role_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `role_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `role_user_role_id_index` (`role_id`),
  KEY `role_user_user_id_index` (`user_id`),
  CONSTRAINT `role_user_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_user`
--

LOCK TABLES `role_user` WRITE;
/*!40000 ALTER TABLE `role_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `role_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `level` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'Admin','admin','Admin Role',5,'2017-06-08 16:39:11','2017-06-08 16:39:11'),(2,'User','user','User Role',1,'2017-06-08 16:39:11','2017-06-08 16:39:11'),(3,'Unverified','unverified','Unverified Role',0,'2017-06-08 16:39:11','2017-06-08 16:39:11');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `signoffs`
--

DROP TABLE IF EXISTS `signoffs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `signoffs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `referrer_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `referred_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reviewers_hash` blob,
  `statehash` text COLLATE utf8mb4_unicode_ci,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `signup_ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `signup_confirmation_ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `signup_sm_ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `admin_ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `jobid` int(10) DEFAULT NULL,
  `cvfile` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=452 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `signoffs`
--

LOCK TABLES `signoffs` WRITE;
/*!40000 ALTER TABLE `signoffs` DISABLE KEYS */;
INSERT INTO `signoffs` VALUES (367,'ANONYMOUSSIGNOFF','BOOT','192.168.1.1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyMjIyMjIw','cHJpem1%2BSVA6MTkyLjE2OC4xLjF%2BREFURToxNTEyMjIyMjIw','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyMjIyMjIw','192.168.1.1',NULL,NULL,NULL,NULL,NULL,'2017-12-02 12:43:40','2017-12-02 12:43:40',NULL,150,'EMPTY'),(368,'ANONYMOUSSIGNOFF','BOOT','192.168.1.1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyMjIyMjM1','cHJpem1%2BSVA6MTkyLjE2OC4xLjF%2BREFURToxNTEyMjIyMjM1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyMjIyMjM1','192.168.1.1',NULL,NULL,NULL,NULL,NULL,'2017-12-02 12:43:55','2017-12-02 12:43:55',NULL,150,'EMPTY'),(369,'ANONYMOUSSIGNOFF','BOOT','192.168.1.1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyMjIyNzIy','cHJpem1%2BSVA6MTkyLjE2OC4xLjF%2BREFURToxNTEyMjIyNzIy','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyMjIyNzIy','192.168.1.1',NULL,NULL,NULL,NULL,NULL,'2017-12-02 12:52:02','2017-12-02 12:52:02',NULL,150,'EMPTY'),(370,'ANONYMOUSSIGNOFF','BOOT','192.168.1.1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyMjIyNzM3','cHJpem1%2BSVA6MTkyLjE2OC4xLjF%2BREFURToxNTEyMjIyNzM3','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyMjIyNzM3','192.168.1.1',NULL,NULL,NULL,NULL,NULL,'2017-12-02 12:52:17','2017-12-02 12:52:17',NULL,150,'EMPTY'),(371,'ANONYMOUSSIGNOFF','BOOT','192.168.1.1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyMjIyOTIy','cHJpem1%2BSVA6MTkyLjE2OC4xLjF%2BREFURToxNTEyMjIyOTIy','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyMjIyOTIy','192.168.1.1',NULL,NULL,NULL,NULL,NULL,'2017-12-02 12:55:22','2017-12-02 12:55:22',NULL,150,'EMPTY'),(372,'ANONYMOUSSIGNOFF','BOOT','192.168.1.1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyMjI0MDk5','cHJpem1%2BSVA6MTkyLjE2OC4xLjF%2BREFURToxNTEyMjI0MDk5','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyMjI0MDk5','192.168.1.1',NULL,NULL,NULL,NULL,NULL,'2017-12-02 13:14:59','2017-12-02 13:14:59',NULL,150,'EMPTY'),(373,'ANONYMOUSSIGNOFF','BOOT','192.168.1.1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyMjI0MTA3','cHJpem1%2BSVA6MTkyLjE2OC4xLjF%2BREFURToxNTEyMjI0MTA3','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyMjI0MTA3','192.168.1.1',NULL,NULL,NULL,NULL,NULL,'2017-12-02 13:15:07','2017-12-02 13:15:07',NULL,150,'EMPTY'),(374,'ANONYMOUSSIGNOFF','BOOT','192.168.1.1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyMjI0MjAx','cHJpem1%2BSVA6MTkyLjE2OC4xLjF%2BREFURToxNTEyMjI0MjAx','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyMjI0MjAx','192.168.1.1',NULL,NULL,NULL,NULL,NULL,'2017-12-02 13:16:41','2017-12-02 13:16:41',NULL,150,'EMPTY'),(375,'ANONYMOUSSIGNOFF','BOOT','192.168.1.1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyMjI0NDYy','cHJpem1%2BSVA6MTkyLjE2OC4xLjF%2BREFURToxNTEyMjI0NDYy','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyMjI0NDYy','192.168.1.1',NULL,NULL,NULL,NULL,NULL,'2017-12-02 13:21:02','2017-12-02 13:21:02',NULL,150,'EMPTY'),(376,'ANONYMOUSSIGNOFF','BOOT','192.168.1.1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyMjI0ODQ0','cHJpem1%2BSVA6MTkyLjE2OC4xLjF%2BREFURToxNTEyMjI0ODQ0','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyMjI0ODQ0','192.168.1.1',NULL,NULL,NULL,NULL,NULL,'2017-12-02 13:27:24','2017-12-02 13:27:24',NULL,148,'EMPTY'),(377,'ANONYMOUSSIGNOFF','BOOT','192.168.1.1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyMjI2MDUy','cHJpem1%2BSVA6MTkyLjE2OC4xLjF%2BREFURToxNTEyMjI2MDUy','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyMjI2MDUy','192.168.1.1',NULL,NULL,NULL,NULL,NULL,'2017-12-02 13:47:32','2017-12-02 13:47:32',NULL,150,'EMPTY'),(378,'ANONYMOUSSIGNOFF','BOOT','192.168.1.1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyMjI2MTAw','cHJpem1%2BSVA6MTkyLjE2OC4xLjF%2BREFURToxNTEyMjI2MTAw','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyMjI2MTAw','192.168.1.1',NULL,NULL,NULL,NULL,NULL,'2017-12-02 13:48:20','2017-12-02 13:48:20',NULL,150,'EMPTY'),(379,'ANONYMOUSSIGNOFF','BOOT','192.168.1.1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyMjI2MTky','cHJpem1%2BSVA6MTkyLjE2OC4xLjF%2BREFURToxNTEyMjI2MTky','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyMjI2MTky','192.168.1.1',NULL,NULL,NULL,NULL,NULL,'2017-12-02 13:49:52','2017-12-02 13:49:52',NULL,150,'EMPTY'),(380,'ANONYMOUSSIGNOFF','BOOT','192.168.1.1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyMjI2Mjc0','cHJpem1%2BSVA6MTkyLjE2OC4xLjF%2BREFURToxNTEyMjI2Mjc0','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyMjI2Mjc0','192.168.1.1',NULL,NULL,NULL,NULL,NULL,'2017-12-02 13:51:14','2017-12-02 13:51:14',NULL,150,'EMPTY'),(381,'ANONYMOUSSIGNOFF','BOOT','192.168.1.1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyMjI2MzM4','cHJpem1%2BSVA6MTkyLjE2OC4xLjF%2BREFURToxNTEyMjI2MzM4','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyMjI2MzM4','192.168.1.1',NULL,NULL,NULL,NULL,NULL,'2017-12-02 13:52:18','2017-12-02 13:52:18',NULL,150,'EMPTY'),(382,'ANONYMOUSSIGNOFF','BOOT','192.168.1.1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyMjI2Mzk2','cHJpem1%2BSVA6MTkyLjE2OC4xLjF%2BREFURToxNTEyMjI2Mzk2','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyMjI2Mzk2','192.168.1.1',NULL,NULL,NULL,NULL,NULL,'2017-12-02 13:53:16','2017-12-02 13:53:16',NULL,148,'EMPTY'),(383,'ANONYMOUSSIGNOFF','BOOT','192.168.1.1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyMjI2NDE1','cHJpem1%2BSVA6MTkyLjE2OC4xLjF%2BREFURToxNTEyMjI2NDE1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyMjI2NDE1','192.168.1.1',NULL,NULL,NULL,NULL,NULL,'2017-12-02 13:53:35','2017-12-02 13:53:35',NULL,148,'EMPTY'),(384,'ANONYMOUSSIGNOFF','BOOT','192.168.1.1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyMjI3MDM3','cHJpem1%2BSVA6MTkyLjE2OC4xLjF%2BREFURToxNTEyMjI3MDM3','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyMjI3MDM3','192.168.1.1',NULL,NULL,NULL,NULL,NULL,'2017-12-02 14:03:57','2017-12-02 14:03:57',NULL,148,'EMPTY'),(385,'ANONYMOUSSIGNOFF','BOOT','192.168.1.1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyMzk1ODc1','cHJpem1%2BSVA6MTkyLjE2OC4xLjF%2BREFURToxNTEyMzk1ODc1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyMzk1ODc1','192.168.1.1',NULL,NULL,NULL,NULL,NULL,'2017-12-04 12:57:55','2017-12-04 12:57:55',NULL,151,'EMPTY'),(386,'ANONYMOUSSIGNOFF','BOOT','192.168.1.1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyMzk2Mzkz','cHJpem1%2BSVA6MTkyLjE2OC4xLjF%2BREFURToxNTEyMzk2Mzkz','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyMzk2Mzkz','192.168.1.1',NULL,NULL,NULL,NULL,NULL,'2017-12-04 13:06:33','2017-12-04 13:06:33',NULL,151,'EMPTY'),(387,'ANONYMOUSSIGNOFF','BOOT','192.168.1.1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyMzk2NTE1','cHJpem1%2BSVA6MTkyLjE2OC4xLjF%2BREFURToxNTEyMzk2NTE1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyMzk2NTE1','192.168.1.1',NULL,NULL,NULL,NULL,NULL,'2017-12-04 13:08:35','2017-12-04 13:08:35',NULL,151,'EMPTY'),(388,'ANONYMOUSSIGNOFF','BOOT','192.168.1.1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyMzk2OTAz','cHJpem1%2BSVA6MTkyLjE2OC4xLjF%2BREFURToxNTEyMzk2OTAz','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyMzk2OTAz','192.168.1.1',NULL,NULL,NULL,NULL,NULL,'2017-12-04 13:15:03','2017-12-04 13:15:03',NULL,151,'EMPTY'),(389,'ANONYMOUSSIGNOFF','BOOT','192.168.1.1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyMzk3MTE3','cHJpem1%2BSVA6MTkyLjE2OC4xLjF%2BREFURToxNTEyMzk3MTE3','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyMzk3MTE3','192.168.1.1',NULL,NULL,NULL,NULL,NULL,'2017-12-04 13:18:37','2017-12-04 13:18:37',NULL,151,'EMPTY'),(390,'ANONYMOUSSIGNOFF','BOOT','192.168.1.1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyMzk3MzQy','cHJpem1%2BSVA6MTkyLjE2OC4xLjF%2BREFURToxNTEyMzk3MzQy','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyMzk3MzQy','192.168.1.1',NULL,NULL,NULL,NULL,NULL,'2017-12-04 13:22:22','2017-12-04 13:22:22',NULL,151,'EMPTY'),(391,'ANONYMOUSSIGNOFF','BOOT','192.168.1.1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyMzk3NjI2','cHJpem1%2BSVA6MTkyLjE2OC4xLjF%2BREFURToxNTEyMzk3NjI2','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyMzk3NjI2','192.168.1.1',NULL,NULL,NULL,NULL,NULL,'2017-12-04 13:27:06','2017-12-04 13:27:06',NULL,151,'EMPTY'),(392,'ANONYMOUSSIGNOFF','BOOT','192.168.1.1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyMzk3NzI0','cHJpem1%2BSVA6MTkyLjE2OC4xLjF%2BREFURToxNTEyMzk3NzI0','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyMzk3NzI0','192.168.1.1',NULL,NULL,NULL,NULL,NULL,'2017-12-04 13:28:44','2017-12-04 13:28:44',NULL,151,'EMPTY'),(393,'ANONYMOUSSIGNOFF','BOOT','192.168.1.1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyMzk3OTcy','cHJpem1%2BSVA6MTkyLjE2OC4xLjF%2BREFURToxNTEyMzk3OTcy','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyMzk3OTcy','192.168.1.1',NULL,NULL,NULL,NULL,NULL,'2017-12-04 13:32:52','2017-12-04 13:32:52',NULL,151,'EMPTY'),(394,'ANONYMOUSSIGNOFF','BOOT','192.168.1.1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDAxODAy','cHJpem1%2BSVA6MTkyLjE2OC4xLjF%2BREFURToxNTEyNDAxODAy','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDAxODAy','192.168.1.1',NULL,NULL,NULL,NULL,NULL,'2017-12-04 14:36:42','2017-12-04 14:36:42',NULL,151,'EMPTY'),(395,'ANONYMOUSSIGNOFF','BOOT','192.168.1.1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDAyODAy','cHJpem1%2BSVA6MTkyLjE2OC4xLjF%2BREFURToxNTEyNDAyODAy','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDAyODAy','192.168.1.1',NULL,NULL,NULL,NULL,NULL,'2017-12-04 14:53:22','2017-12-04 14:53:22',NULL,151,'EMPTY'),(396,'ANONYMOUSSIGNOFF','BOOT','192.168.1.1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDAyODkw','cHJpem1%2BSVA6MTkyLjE2OC4xLjF%2BREFURToxNTEyNDAyODkw','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDAyODkw','192.168.1.1',NULL,NULL,NULL,NULL,NULL,'2017-12-04 14:54:50','2017-12-04 14:54:50',NULL,151,'EMPTY'),(397,'ANONYMOUSSIGNOFF','BOOT','192.168.1.1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDAyODk2','cHJpem1%2BSVA6MTkyLjE2OC4xLjF%2BREFURToxNTEyNDAyODk2','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDAyODk2','192.168.1.1',NULL,NULL,NULL,NULL,NULL,'2017-12-04 14:54:56','2017-12-04 14:54:56',NULL,151,'EMPTY'),(398,'ANONYMOUSSIGNOFF','BOOT','192.168.1.1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDAyOTg2','cHJpem1%2BSVA6MTkyLjE2OC4xLjF%2BREFURToxNTEyNDAyOTg2','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDAyOTg2','192.168.1.1',NULL,NULL,NULL,NULL,NULL,'2017-12-04 14:56:26','2017-12-04 14:56:26',NULL,151,'EMPTY'),(399,'ANONYMOUSSIGNOFF','BOOT','192.168.1.1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDAzMjI4','cHJpem1%2BSVA6MTkyLjE2OC4xLjF%2BREFURToxNTEyNDAzMjI4','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDAzMjI4','192.168.1.1',NULL,NULL,NULL,NULL,NULL,'2017-12-04 15:00:28','2017-12-04 15:00:28',NULL,151,'EMPTY'),(400,'ANONYMOUSSIGNOFF','BOOT','192.168.1.1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDAzNjI1','cHJpem1%2BSVA6MTkyLjE2OC4xLjF%2BREFURToxNTEyNDAzNjI1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDAzNjI1','192.168.1.1',NULL,NULL,NULL,NULL,NULL,'2017-12-04 15:07:05','2017-12-04 15:07:05',NULL,151,'EMPTY'),(401,'ANONYMOUSSIGNOFF','BOOT','192.168.1.1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDA0Mjkw','cHJpem1%2BSVA6MTkyLjE2OC4xLjF%2BREFURToxNTEyNDA0Mjkw','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDA0Mjkw','192.168.1.1',NULL,NULL,NULL,NULL,NULL,'2017-12-04 15:18:10','2017-12-04 15:18:10',NULL,151,'EMPTY'),(402,'ANONYMOUSSIGNOFF','BOOT','192.168.1.1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDA0NjI3','cHJpem1%2BSVA6MTkyLjE2OC4xLjF%2BREFURToxNTEyNDA0NjI3','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDA0NjI3','192.168.1.1',NULL,NULL,NULL,NULL,NULL,'2017-12-04 15:23:47','2017-12-04 15:23:47',NULL,151,'EMPTY'),(403,'ANONYMOUSSIGNOFF','BOOT','192.168.1.1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDA0Njk2','cHJpem1%2BSVA6MTkyLjE2OC4xLjF%2BREFURToxNTEyNDA0Njk2','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDA0Njk2','192.168.1.1',NULL,NULL,NULL,NULL,NULL,'2017-12-04 15:24:56','2017-12-04 15:24:56',NULL,151,'EMPTY'),(404,'ANONYMOUSSIGNOFF','BOOT','192.168.1.1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDA0NzMx','cHJpem1%2BSVA6MTkyLjE2OC4xLjF%2BREFURToxNTEyNDA0NzMx','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDA0NzMx','192.168.1.1',NULL,NULL,NULL,NULL,NULL,'2017-12-04 15:25:31','2017-12-04 15:25:31',NULL,151,'EMPTY'),(405,'ANONYMOUSSIGNOFF','BOOT','192.168.1.1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDA0ODIx','cHJpem1%2BSVA6MTkyLjE2OC4xLjF%2BREFURToxNTEyNDA0ODIx','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDA0ODIx','192.168.1.1',NULL,NULL,NULL,NULL,NULL,'2017-12-04 15:27:01','2017-12-04 15:27:01',NULL,151,'EMPTY'),(406,'ANONYMOUSSIGNOFF','BOOT','192.168.1.1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDA0ODU4','cHJpem1%2BSVA6MTkyLjE2OC4xLjF%2BREFURToxNTEyNDA0ODU4','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDA0ODU4','192.168.1.1',NULL,NULL,NULL,NULL,NULL,'2017-12-04 15:27:38','2017-12-04 15:27:38',NULL,151,'EMPTY'),(407,'ANONYMOUSSIGNOFF','BOOT','192.168.1.1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDIwNDM5','cHJpem1%2BSVA6MTkyLjE2OC4xLjF%2BREFURToxNTEyNDIwNDM5','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDIwNDM5','192.168.1.1',NULL,NULL,NULL,NULL,NULL,'2017-12-04 19:47:19','2017-12-04 19:47:19',NULL,152,'EMPTY'),(408,'ANONYMOUSSIGNOFF','BOOT','192.168.1.1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDIwNDU1','cHJpem1%2BSVA6MTkyLjE2OC4xLjF%2BREFURToxNTEyNDIwNDU1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDIwNDU1','192.168.1.1',NULL,NULL,NULL,NULL,NULL,'2017-12-04 19:47:35','2017-12-04 19:47:35',NULL,152,'EMPTY'),(409,'ANONYMOUSSIGNOFF','BOOT','192.168.1.1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDIwNzU4','cHJpem1%2BSVA6MTkyLjE2OC4xLjF%2BREFURToxNTEyNDIwNzU4','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDIwNzU4','192.168.1.1',NULL,NULL,NULL,NULL,NULL,'2017-12-04 19:52:38','2017-12-04 19:52:38',NULL,152,'EMPTY'),(410,'ANONYMOUSSIGNOFF','BOOT','192.168.1.1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDIxNzI1','cHJpem1%2BSVA6MTkyLjE2OC4xLjF%2BREFURToxNTEyNDIxNzI1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDIxNzI1','192.168.1.1',NULL,NULL,NULL,NULL,NULL,'2017-12-04 20:08:45','2017-12-04 20:08:45',NULL,152,'EMPTY'),(411,'ANONYMOUSSIGNOFF','BOOT','192.168.1.1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDIxODE0','cHJpem1%2BSVA6MTkyLjE2OC4xLjF%2BREFURToxNTEyNDIxODE0','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDIxODE0','192.168.1.1',NULL,NULL,NULL,NULL,NULL,'2017-12-04 20:10:14','2017-12-04 20:10:14',NULL,152,'EMPTY'),(412,'ANONYMOUSSIGNOFF','BOOT','192.168.1.1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDIyMDQx','cHJpem1%2BSVA6MTkyLjE2OC4xLjF%2BREFURToxNTEyNDIyMDQx','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDIyMDQx','192.168.1.1',NULL,NULL,NULL,NULL,NULL,'2017-12-04 20:14:01','2017-12-04 20:14:01',NULL,152,'EMPTY'),(413,'ANONYMOUSSIGNOFF','BOOT','192.168.1.1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDIyMDc3','cHJpem1%2BSVA6MTkyLjE2OC4xLjF%2BREFURToxNTEyNDIyMDc3','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDIyMDc3','192.168.1.1',NULL,NULL,NULL,NULL,NULL,'2017-12-04 20:14:37','2017-12-04 20:14:37',NULL,152,'EMPTY'),(414,'ANONYMOUSSIGNOFF','BOOT','192.168.1.1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDIyMDgy','cHJpem1%2BSVA6MTkyLjE2OC4xLjF%2BREFURToxNTEyNDIyMDgy','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDIyMDgy','192.168.1.1',NULL,NULL,NULL,NULL,NULL,'2017-12-04 20:14:42','2017-12-04 20:14:42',NULL,152,'EMPTY'),(415,'ANONYMOUSSIGNOFF','BOOT','192.168.1.1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDIyMDg5','cHJpem1%2BSVA6MTkyLjE2OC4xLjF%2BREFURToxNTEyNDIyMDg5','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDIyMDg5','192.168.1.1',NULL,NULL,NULL,NULL,NULL,'2017-12-04 20:14:49','2017-12-04 20:14:49',NULL,152,'EMPTY'),(416,'ANONYMOUSSIGNOFF','BOOT','192.168.1.1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDIyMTk1','cHJpem1%2BSVA6MTkyLjE2OC4xLjF%2BREFURToxNTEyNDIyMTk1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDIyMTk1','192.168.1.1',NULL,NULL,NULL,NULL,NULL,'2017-12-04 20:16:35','2017-12-04 20:16:35',NULL,152,'EMPTY'),(417,'ANONYMOUSSIGNOFF','BOOT','192.168.1.1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDIyMjM1','cHJpem1%2BSVA6MTkyLjE2OC4xLjF%2BREFURToxNTEyNDIyMjM1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDIyMjM1','192.168.1.1',NULL,NULL,NULL,NULL,NULL,'2017-12-04 20:17:15','2017-12-04 20:17:15',NULL,152,'EMPTY'),(418,'ANONYMOUSSIGNOFF','BOOT','192.168.1.1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDIyMzUx','cHJpem1%2BSVA6MTkyLjE2OC4xLjF%2BREFURToxNTEyNDIyMzUx','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDIyMzUx','192.168.1.1',NULL,NULL,NULL,NULL,NULL,'2017-12-04 20:19:11','2017-12-04 20:19:11',NULL,152,'EMPTY'),(419,'ANONYMOUSSIGNOFF','BOOT','192.168.1.1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDIyMzk2','cHJpem1%2BSVA6MTkyLjE2OC4xLjF%2BREFURToxNTEyNDIyMzk2','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDIyMzk2','192.168.1.1',NULL,NULL,NULL,NULL,NULL,'2017-12-04 20:19:56','2017-12-04 20:19:56',NULL,152,'EMPTY'),(420,'ANONYMOUSSIGNOFF','BOOT','192.168.1.1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDIyNDQz','cHJpem1%2BSVA6MTkyLjE2OC4xLjF%2BREFURToxNTEyNDIyNDQz','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDIyNDQz','192.168.1.1',NULL,NULL,NULL,NULL,NULL,'2017-12-04 20:20:43','2017-12-04 20:20:43',NULL,152,'EMPTY'),(421,'ANONYMOUSSIGNOFF','BOOT','192.168.1.1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDIyNDQ4','cHJpem1%2BSVA6MTkyLjE2OC4xLjF%2BREFURToxNTEyNDIyNDQ4','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDIyNDQ4','192.168.1.1',NULL,NULL,NULL,NULL,NULL,'2017-12-04 20:20:48','2017-12-04 20:20:48',NULL,152,'EMPTY'),(422,'ANONYMOUSSIGNOFF','BOOT','192.168.1.1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDIyNTMy','cHJpem1%2BSVA6MTkyLjE2OC4xLjF%2BREFURToxNTEyNDIyNTMy','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDIyNTMy','192.168.1.1',NULL,NULL,NULL,NULL,NULL,'2017-12-04 20:22:12','2017-12-04 20:22:12',NULL,152,'EMPTY'),(423,'ANONYMOUSSIGNOFF','BOOT','192.168.1.1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDIyOTM2','cHJpem1%2BSVA6MTkyLjE2OC4xLjF%2BREFURToxNTEyNDIyOTM2','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDIyOTM2','192.168.1.1',NULL,NULL,NULL,NULL,NULL,'2017-12-04 20:28:56','2017-12-04 20:28:56',NULL,152,'EMPTY'),(424,'ANONYMOUSSIGNOFF','BOOT','192.168.1.1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDIyOTg5','cHJpem1%2BSVA6MTkyLjE2OC4xLjF%2BREFURToxNTEyNDIyOTg5','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDIyOTg5','192.168.1.1',NULL,NULL,NULL,NULL,NULL,'2017-12-04 20:29:49','2017-12-04 20:29:49',NULL,152,'EMPTY'),(425,'ANONYMOUSSIGNOFF','BOOT','192.168.1.1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDIzMDc2','cHJpem1%2BSVA6MTkyLjE2OC4xLjF%2BREFURToxNTEyNDIzMDc2','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDIzMDc2','192.168.1.1',NULL,NULL,NULL,NULL,NULL,'2017-12-04 20:31:16','2017-12-04 20:31:16',NULL,152,'EMPTY'),(426,'ANONYMOUSSIGNOFF','BOOT','192.168.1.1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDIzMDgy','cHJpem1%2BSVA6MTkyLjE2OC4xLjF%2BREFURToxNTEyNDIzMDgy','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDIzMDgy','192.168.1.1',NULL,NULL,NULL,NULL,NULL,'2017-12-04 20:31:22','2017-12-04 20:31:22',NULL,152,'EMPTY'),(427,'ANONYMOUSSIGNOFF','BOOT','192.168.1.1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDIzMDg1','cHJpem1%2BSVA6MTkyLjE2OC4xLjF%2BREFURToxNTEyNDIzMDg1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDIzMDg1','192.168.1.1',NULL,NULL,NULL,NULL,NULL,'2017-12-04 20:31:25','2017-12-04 20:31:25',NULL,152,'EMPTY'),(428,'ANONYMOUSSIGNOFF','BOOT','192.168.1.1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDgxMjQw','cHJpem1%2BSVA6MTkyLjE2OC4xLjF%2BREFURToxNTEyNDgxMjQw','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDgxMjQw','192.168.1.1',NULL,NULL,NULL,NULL,NULL,'2017-12-05 12:40:40','2017-12-05 12:40:40',NULL,148,'EMPTY'),(429,'ANONYMOUSSIGNOFF','BOOT','192.168.1.1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDgxMjg4','cHJpem1%2BSVA6MTkyLjE2OC4xLjF%2BREFURToxNTEyNDgxMjg4','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDgxMjg4','192.168.1.1',NULL,NULL,NULL,NULL,NULL,'2017-12-05 12:41:28','2017-12-05 12:41:28',NULL,148,'EMPTY'),(430,'ANONYMOUSSIGNOFF','BOOT','192.168.1.1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDgxMjk3','cHJpem1%2BSVA6MTkyLjE2OC4xLjF%2BREFURToxNTEyNDgxMjk3','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDgxMjk3','192.168.1.1',NULL,NULL,NULL,NULL,NULL,'2017-12-05 12:41:37','2017-12-05 12:41:37',NULL,148,'EMPTY'),(431,'ANONYMOUSSIGNOFF','BOOT','192.168.1.1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDgxMzQ2','cHJpem1%2BSVA6MTkyLjE2OC4xLjF%2BREFURToxNTEyNDgxMzQ2','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDgxMzQ2','192.168.1.1',NULL,NULL,NULL,NULL,NULL,'2017-12-05 12:42:26','2017-12-05 12:42:26',NULL,148,'EMPTY'),(432,'ANONYMOUSSIGNOFF','BOOT','192.168.1.1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDgxMzU2','cHJpem1%2BSVA6MTkyLjE2OC4xLjF%2BREFURToxNTEyNDgxMzU2','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDgxMzU2','192.168.1.1',NULL,NULL,NULL,NULL,NULL,'2017-12-05 12:42:36','2017-12-05 12:42:36',NULL,148,'EMPTY'),(433,'ANONYMOUSSIGNOFF','BOOT','192.168.1.1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDgxOTUy','cHJpem1%2BSVA6MTkyLjE2OC4xLjF%2BREFURToxNTEyNDgxOTUy','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDgxOTUy','192.168.1.1',NULL,NULL,NULL,NULL,NULL,'2017-12-05 12:52:32','2017-12-05 12:52:32',NULL,148,'EMPTY'),(434,'ANONYMOUSSIGNOFF','BOOT','192.168.1.1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDgyMDA2','cHJpem1%2BSVA6MTkyLjE2OC4xLjF%2BREFURToxNTEyNDgyMDA2','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDgyMDA2','192.168.1.1',NULL,NULL,NULL,NULL,NULL,'2017-12-05 12:53:26','2017-12-05 12:53:26',NULL,148,'EMPTY'),(435,'ANONYMOUSSIGNOFF','BOOT','192.168.1.1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDgyMzIz','cHJpem1%2BSVA6MTkyLjE2OC4xLjF%2BREFURToxNTEyNDgyMzIz','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDgyMzIz','192.168.1.1',NULL,NULL,NULL,NULL,NULL,'2017-12-05 12:58:43','2017-12-05 12:58:43',NULL,148,'EMPTY'),(436,'ANONYMOUSSIGNOFF','BOOT','192.168.1.1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDgyMzM2','cHJpem1%2BSVA6MTkyLjE2OC4xLjF%2BREFURToxNTEyNDgyMzM2','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDgyMzM2','192.168.1.1',NULL,NULL,NULL,NULL,NULL,'2017-12-05 12:58:56','2017-12-05 12:58:56',NULL,148,'EMPTY'),(437,'ANONYMOUSSIGNOFF','BOOT','192.168.1.1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDgyNDk0','cHJpem1%2BSVA6MTkyLjE2OC4xLjF%2BREFURToxNTEyNDgyNDk0','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDgyNDk0','192.168.1.1',NULL,NULL,NULL,NULL,NULL,'2017-12-05 13:01:34','2017-12-05 13:01:34',NULL,148,'EMPTY'),(438,'ANONYMOUSSIGNOFF','BOOT','192.168.1.1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDgyNTEy','cHJpem1%2BSVA6MTkyLjE2OC4xLjF%2BREFURToxNTEyNDgyNTEy','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDgyNTEy','192.168.1.1',NULL,NULL,NULL,NULL,NULL,'2017-12-05 13:01:52','2017-12-05 13:01:52',NULL,148,'EMPTY'),(439,'ANONYMOUSSIGNOFF','BOOT','192.168.1.1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDgyNTI0','cHJpem1%2BSVA6MTkyLjE2OC4xLjF%2BREFURToxNTEyNDgyNTI0','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDgyNTI0','192.168.1.1',NULL,NULL,NULL,NULL,NULL,'2017-12-05 13:02:04','2017-12-05 13:02:04',NULL,148,'EMPTY'),(440,'ANONYMOUSSIGNOFF','BOOT','192.168.1.1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDgyODMz','cHJpem1%2BSVA6MTkyLjE2OC4xLjF%2BREFURToxNTEyNDgyODMz','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDgyODMz','192.168.1.1',NULL,NULL,NULL,NULL,NULL,'2017-12-05 13:07:13','2017-12-05 13:07:13',NULL,148,'EMPTY'),(441,'ANONYMOUSSIGNOFF','BOOT','192.168.1.1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDgyODQ4','cHJpem1%2BSVA6MTkyLjE2OC4xLjF%2BREFURToxNTEyNDgyODQ4','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDgyODQ4','192.168.1.1',NULL,NULL,NULL,NULL,NULL,'2017-12-05 13:07:28','2017-12-05 13:07:28',NULL,148,'EMPTY'),(442,'ANONYMOUSSIGNOFF','BOOT','192.168.1.1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDgzMzIy','cHJpem1%2BSVA6MTkyLjE2OC4xLjF%2BREFURToxNTEyNDgzMzIy','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDgzMzIy','192.168.1.1',NULL,NULL,NULL,NULL,NULL,'2017-12-05 13:15:22','2017-12-05 13:15:22',NULL,148,'EMPTY'),(443,'ANONYMOUSSIGNOFF','BOOT','192.168.1.1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDgzMzMx','cHJpem1%2BSVA6MTkyLjE2OC4xLjF%2BREFURToxNTEyNDgzMzMx','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDgzMzMx','192.168.1.1',NULL,NULL,NULL,NULL,NULL,'2017-12-05 13:15:31','2017-12-05 13:15:31',NULL,148,'EMPTY'),(444,'ANONYMOUSSIGNOFF','BOOT','192.168.1.1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDgzNDUy','cHJpem1%2BSVA6MTkyLjE2OC4xLjF%2BREFURToxNTEyNDgzNDUy','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDgzNDUy','192.168.1.1',NULL,NULL,NULL,NULL,NULL,'2017-12-05 13:17:32','2017-12-05 13:17:32',NULL,148,'EMPTY'),(445,'ANONYMOUSSIGNOFF','BOOT','192.168.1.1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDgzNDY4','cHJpem1%2BSVA6MTkyLjE2OC4xLjF%2BREFURToxNTEyNDgzNDY4','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDgzNDY4','192.168.1.1',NULL,NULL,NULL,NULL,NULL,'2017-12-05 13:17:48','2017-12-05 13:17:48',NULL,148,'EMPTY'),(446,'ANONYMOUSSIGNOFF','BOOT','192.168.1.1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDgzNDg5','cHJpem1%2BSVA6MTkyLjE2OC4xLjF%2BREFURToxNTEyNDgzNDg5','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDgzNDg5','192.168.1.1',NULL,NULL,NULL,NULL,NULL,'2017-12-05 13:18:09','2017-12-05 13:18:09',NULL,148,'EMPTY'),(447,'ANONYMOUSSIGNOFF','BOOT','192.168.1.1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDg0MjQy','cHJpem1%2BSVA6MTkyLjE2OC4xLjF%2BREFURToxNTEyNDg0MjQy','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDg0MjQy','192.168.1.1',NULL,NULL,NULL,NULL,NULL,'2017-12-05 13:30:42','2017-12-05 13:30:42',NULL,148,'EMPTY'),(448,'ANONYMOUSSIGNOFF','BOOT','192.168.1.1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDg0MzQ4','cHJpem1%2BSVA6MTkyLjE2OC4xLjF%2BREFURToxNTEyNDg0MzQ4','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDg0MzQ4','192.168.1.1',NULL,NULL,NULL,NULL,NULL,'2017-12-05 13:32:28','2017-12-05 13:32:28',NULL,148,'EMPTY'),(449,'ANONYMOUSSIGNOFF','BOOT','192.168.1.1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDk4NjE4','cHJpem1%2BSVA6MTkyLjE2OC4xLjF%2BREFURToxNTEyNDk4NjE4','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNDk4NjE4','192.168.1.1',NULL,NULL,NULL,NULL,NULL,'2017-12-05 17:30:18','2017-12-05 17:30:18',NULL,148,'EMPTY'),(450,'ANONYMOUSSIGNOFF','BOOT','192.168.1.1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNTU2NzQ0','cHJpem1%2BSVA6MTkyLjE2OC4xLjF%2BREFURToxNTEyNTU2NzQ0','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEyNTU2NzQ0','192.168.1.1',NULL,NULL,NULL,NULL,NULL,'2017-12-06 09:39:04','2017-12-06 09:39:04',NULL,148,'EMPTY'),(451,'ANONYMOUSSIGNOFF','BOOT','192.168.1.1','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEzMDMxMjg3','cHJpem1%2BSVA6MTkyLjE2OC4xLjF%2BREFURToxNTEzMDMxMjg3','cHJpem1+SVA6MTkyLjE2OC4xLjF+REFURToxNTEzMDMxMjg3','192.168.1.1',NULL,NULL,NULL,NULL,NULL,'2017-12-11 21:28:07','2017-12-11 21:28:07',NULL,150,'EMPTY');
/*!40000 ALTER TABLE `signoffs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `social_logins`
--

DROP TABLE IF EXISTS `social_logins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `social_logins` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `provider` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `social_id` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `social_logins_user_id_index` (`user_id`),
  CONSTRAINT `social_logins_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `social_logins`
--

LOCK TABLES `social_logins` WRITE;
/*!40000 ALTER TABLE `social_logins` DISABLE KEYS */;
/*!40000 ALTER TABLE `social_logins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `themes`
--

DROP TABLE IF EXISTS `themes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `themes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `link` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `taggable_id` int(10) unsigned NOT NULL,
  `taggable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `themes_name_unique` (`name`),
  UNIQUE KEY `themes_link_unique` (`link`),
  KEY `themes_taggable_id_taggable_type_index` (`taggable_id`,`taggable_type`),
  KEY `themes_id_index` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `themes`
--

LOCK TABLES `themes` WRITE;
/*!40000 ALTER TABLE `themes` DISABLE KEYS */;
INSERT INTO `themes` VALUES (1,'Default','null',NULL,1,1,'theme','2017-06-08 16:39:11','2017-06-08 16:39:12',NULL),(2,'Darkly','https://maxcdn.bootstrapcdn.com/bootswatch/3.3.7/darkly/bootstrap.min.css',NULL,1,2,'theme','2017-06-08 16:39:11','2017-06-08 16:39:12',NULL),(3,'Cyborg','https://maxcdn.bootstrapcdn.com/bootswatch/3.3.7/cyborg/bootstrap.min.css',NULL,1,3,'theme','2017-06-08 16:39:11','2017-06-08 16:39:12',NULL),(4,'Cosmo','https://maxcdn.bootstrapcdn.com/bootswatch/3.3.7/cosmo/bootstrap.min.css',NULL,1,4,'theme','2017-06-08 16:39:11','2017-06-08 16:39:12',NULL),(5,'Cerulean','https://maxcdn.bootstrapcdn.com/bootswatch/3.3.7/cerulean/bootstrap.min.css',NULL,1,5,'theme','2017-06-08 16:39:11','2017-06-08 16:39:12',NULL),(6,'Flatly','https://maxcdn.bootstrapcdn.com/bootswatch/3.3.7/flatly/bootstrap.min.css',NULL,1,6,'theme','2017-06-08 16:39:11','2017-06-08 16:39:12',NULL),(7,'Journal','https://maxcdn.bootstrapcdn.com/bootswatch/3.3.7/journal/bootstrap.min.css',NULL,1,7,'theme','2017-06-08 16:39:11','2017-06-08 16:39:12',NULL),(8,'Lumen','https://maxcdn.bootstrapcdn.com/bootswatch/3.3.7/lumen/bootstrap.min.css',NULL,1,8,'theme','2017-06-08 16:39:11','2017-06-08 16:39:12',NULL),(9,'Paper','https://maxcdn.bootstrapcdn.com/bootswatch/3.3.7/paper/bootstrap.min.css',NULL,1,9,'theme','2017-06-08 16:39:11','2017-06-08 16:39:12',NULL),(10,'Readable','https://maxcdn.bootstrapcdn.com/bootswatch/3.3.7/readable/bootstrap.min.css',NULL,1,10,'theme','2017-06-08 16:39:11','2017-06-08 16:39:12',NULL),(11,'Sandstone','https://maxcdn.bootstrapcdn.com/bootswatch/3.3.7/sandstone/bootstrap.min.css',NULL,1,11,'theme','2017-06-08 16:39:11','2017-06-08 16:39:12',NULL),(12,'Simplex','https://maxcdn.bootstrapcdn.com/bootswatch/3.3.7/simplex/bootstrap.min.css',NULL,1,12,'theme','2017-06-08 16:39:11','2017-06-08 16:39:12',NULL),(13,'Slate','https://maxcdn.bootstrapcdn.com/bootswatch/3.3.7/slate/bootstrap.min.css',NULL,1,13,'theme','2017-06-08 16:39:11','2017-06-08 16:39:12',NULL),(14,'Spacelab','https://maxcdn.bootstrapcdn.com/bootswatch/3.3.7/spacelab/bootstrap.min.css',NULL,1,14,'theme','2017-06-08 16:39:11','2017-06-08 16:39:12',NULL),(15,'Superhero','https://maxcdn.bootstrapcdn.com/bootswatch/3.3.7/superhero/bootstrap.min.css',NULL,1,15,'theme','2017-06-08 16:39:11','2017-06-08 16:39:12',NULL),(16,'United','https://maxcdn.bootstrapcdn.com/bootswatch/3.3.7/united/bootstrap.min.css',NULL,1,16,'theme','2017-06-08 16:39:12','2017-06-08 16:39:12',NULL),(17,'Yeti','https://maxcdn.bootstrapcdn.com/bootswatch/3.3.7/yeti/bootstrap.min.css',NULL,1,17,'theme','2017-06-08 16:39:12','2017-06-08 16:39:12',NULL),(18,'Bootstrap 4.0.0 Alpha','https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css',NULL,1,18,'theme','2017-06-08 16:39:12','2017-06-08 16:39:12',NULL),(19,'Materialize','https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.0/css/materialize.min.css',NULL,1,19,'theme','2017-06-08 16:39:12','2017-06-08 16:39:12',NULL),(20,'Bootstrap Material Design 0.3.0','https://cdnjs.cloudflare.com/ajax/libs/bootstrap-material-design/0.3.0/css/material-fullpalette.min.css',NULL,1,20,'theme','2017-06-08 16:39:12','2017-06-08 16:39:12',NULL),(21,'Bootstrap Material Design 0.5.10','https://cdnjs.cloudflare.com/ajax/libs/bootstrap-material-design/0.5.10/css/bootstrap-material-design.min.css',NULL,1,21,'theme','2017-06-08 16:39:12','2017-06-08 16:39:12',NULL),(22,'Bootstrap Material Design 4.0.0','https://cdnjs.cloudflare.com/ajax/libs/bootstrap-material-design/4.0.0/bootstrap-material-design.min.css',NULL,1,22,'theme','2017-06-08 16:39:12','2017-06-08 16:39:12',NULL),(23,'Bootstrap Material Design 4.0.2','https://cdnjs.cloudflare.com/ajax/libs/bootstrap-material-design/4.0.2/bootstrap-material-design.min.css',NULL,1,23,'theme','2017-06-08 16:39:12','2017-06-08 16:39:12',NULL),(24,'mdbootstrap','https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.3.1/css/mdb.min.css',NULL,1,24,'theme','2017-06-08 16:39:12','2017-06-08 16:39:12',NULL),(25,'bootflat','https://cdnjs.cloudflare.com/ajax/libs/bootflat/2.0.4/css/bootflat.min.css',NULL,1,25,'theme','2017-06-08 16:39:12','2017-06-08 16:39:12',NULL),(26,'flat-ui','https://cdnjs.cloudflare.com/ajax/libs/flat-ui/2.3.0/css/flat-ui.min.css',NULL,1,26,'theme','2017-06-08 16:39:12','2017-06-08 16:39:12',NULL),(27,'m8tro-bootstrap','https://cdnjs.cloudflare.com/ajax/libs/m8tro-bootstrap/3.3.7/m8tro.min.css',NULL,1,27,'theme','2017-06-08 16:39:12','2017-06-08 16:39:12',NULL);
/*!40000 ALTER TABLE `themes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activated` tinyint(1) NOT NULL DEFAULT '0',
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `signup_ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `signup_confirmation_ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `signup_sm_ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `admin_ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `last_login` text COLLATE utf8mb4_unicode_ci,
  `activ_code` text COLLATE utf8mb4_unicode_ci,
  `group_id` int(10) DEFAULT NULL,
  `activ` int(1) DEFAULT NULL,
  `cvadded` tinyint(1) DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=93 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (90,NULL,NULL,'$2y$10$IWA75SdPb/Olb.H0YxNe7OiU9T/WXxAB6UTgusL1F63OY8LivEjkm',NULL,0,'',NULL,NULL,NULL,NULL,NULL,NULL,'2018-04-07 12:05:54','2018-04-07 12:12:09',NULL,NULL,'WTBoS2NHVnRNU3RXVlRGQ1UxVjNObU5IYkhaa1NFcDJaVWhCYkU1RVFtNWlWMFp3WWtNMWFtSXlNRDErVkU5TFJVNDZ%2BREFURToyMDE4MDQwNzAyMDU1NA%3D%3D',5,1,0,'piotroxpgmail.com03b437d2af1395bfaf20a3aa6433cb96','piotroxp@gmail.com'),(92,NULL,NULL,'$2y$10$2QQlUcp4KADvX11aknJg4.amgM7PTDVBcR/FzO9fPUabj1ow5WXz6',NULL,0,'',NULL,NULL,NULL,NULL,NULL,NULL,'2018-05-15 10:52:30','2018-05-15 10:54:31',NULL,NULL,'WTBoS2NHVnRNU3RXVlRGQ1UxVjNObU5IYkhaa1NFcDJaVWhCYkU1RVFuWk5hVFYzWWtFOVBYNVVUMHRGVGpwdWIyOXVaV1Y0Y0dWamRITjBhR1Z6Y0dGdWFYTm9hVzV4ZFdsemFYUnBiMjQ9fkRBVEU6MjAxODA1MTUxMjUyMzA%3D',0,1,0,'piotroxpo2.pl0479ee5d831fb9b4ab0d0abbf43cb93d','piotroxp@o2.pl');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `userweights`
--

DROP TABLE IF EXISTS `userweights`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `userweights` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `aone` double NOT NULL,
  `atwo` double NOT NULL,
  `athree` double NOT NULL,
  `afour` double NOT NULL,
  `afive` double NOT NULL,
  `asix` double NOT NULL,
  `aseven` double NOT NULL,
  `aeight` double NOT NULL,
  `anine` double NOT NULL,
  `aten` double NOT NULL,
  `aeleven` double NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `userid` int(11) DEFAULT NULL,
  `keywords` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `userweights`
--

LOCK TABLES `userweights` WRITE;
/*!40000 ALTER TABLE `userweights` DISABLE KEYS */;
INSERT INTO `userweights` VALUES (1,0.05,0.2,0.1,0.5,0.2,0.05,0,0,0,0,0,'2018-04-10 10:46:11','2018-04-10 10:46:11',90,NULL),(2,0.9938809764361783,0.00008979727593341644,0.00020089990530856066,0.004190582707930101,0.0008360534751108409,0.00008088370261555338,0.00026120708422444606,0.000009122138273663329,0.00014559926296679957,0.0002568304717244261,0.00004804753973365981,'2018-04-18 15:21:48','2018-04-18 15:21:48',90,NULL),(3,0.9938809764361783,0.00008979727593341644,0.00020089990530856066,0.004190582707930101,0.0008360534751108409,0.00008088370261555338,0.00026120708422444606,0.000009122138273663329,0.00014559926296679957,0.0002568304717244261,0.00004804753973365981,'2018-04-18 15:23:22','2018-04-18 15:23:22',90,NULL),(4,0.9938809764361783,0.00008979727593341644,0.00020089990530856066,0.004190582707930101,0.0008360534751108409,0.00008088370261555338,0.00026120708422444606,0.000009122138273663329,0.00014559926296679957,0.0002568304717244261,0.00004804753973365981,'2018-04-18 15:28:00','2018-04-18 15:28:00',90,NULL),(5,0.00008772203691941827,0.0000000026803211777221324,0.000000019507389564484414,0.0000027024787884823667,0.9998729454157719,0.00003462525072456448,0.00000010547660195373683,0.00000000021021969110554931,0.0000010533348312164548,0.000000823560413932599,0.00000000004801782378902705,'2018-04-18 15:34:03','2018-04-18 15:34:03',90,NULL),(6,0.0000003910578672591239,0.000000000000015437069455165746,0.0000000000009121586571836929,0.000000004174876298548527,0.9999864257530554,0.000013177359046729308,0.0000000000048762564714489274,0.0000000000000010544313800886406,0.000000001371422062973585,0.00000000027792725175764004,4.492468005810809e-17,'2018-04-23 09:14:46','2018-04-23 09:14:46',90,NULL),(7,0.000015955147903066007,0.0000000006590913547238202,0.000000006427136943106631,0.0000012529517340938506,0.999979535573713,0.000002735765169981906,0.000000043724412715639834,0.000000000048598162630604944,0.0000002857246982135046,0.00000018396639354055715,0.00000000001114903481707073,'2018-04-23 09:42:02','2018-04-23 09:42:02',90,NULL),(8,0.000014616872029076783,0.00000000000011809646461704816,0.00000000000885618031819967,0.0000000779421205010168,0.9922771736402507,0.007708119540306627,0.000000000019307555294886583,0.000000000000006539306374695099,0.000000010043739859163644,0.000000001933264385822087,3.6050360005832703e-16,'2018-04-23 09:46:00','2018-04-23 09:46:00',90,NULL),(9,0.00008772203691941827,0.0000000026803211777221324,0.000000019507389564484414,0.0000027024787884823667,0.9998729454157719,0.00003462525072456448,0.00000010547660195373683,0.00000000021021969110554931,0.0000010533348312164548,0.000000823560413932599,0.00000000004801782378902705,'2018-04-23 09:47:00','2018-04-23 09:47:00',90,NULL),(10,0.0000000014320192628177194,3.0871773352181255e-16,0.000000000000013600594660669709,0.00000000026492872083431455,0.999999561363846,0.00000043689205680368374,0.00000000000009087555695448,2.326990341358334e-17,0.00000000003466159396631082,0.000000000012382881265394086,1.0686944183493814e-18,'2018-04-23 09:50:46','2018-04-23 09:50:46',90,NULL),(11,0.0000003910578672591239,0.000000000000015437069455165746,0.0000000000009121586571836929,0.000000004174876298548527,0.9999864257530554,0.000013177359046729308,0.0000000000048762564714489274,0.0000000000000010544313800886406,0.000000001371422062973585,0.00000000027792725175764004,4.492468005810809e-17,'2018-04-23 10:04:31','2018-04-23 10:04:31',90,NULL),(12,0.00008772203691941827,0.0000000026803211777221324,0.000000019507389564484414,0.0000027024787884823667,0.9998729454157719,0.00003462525072456448,0.00000010547660195373683,0.00000000021021969110554931,0.0000010533348312164548,0.000000823560413932599,0.00000000004801782378902705,'2018-04-23 10:08:48','2018-04-23 10:08:48',90,NULL),(13,0.00008772203691941827,0.0000000026803211777221324,0.000000019507389564484414,0.0000027024787884823667,0.9998729454157719,0.00003462525072456448,0.00000010547660195373683,0.00000000021021969110554931,0.0000010533348312164548,0.000000823560413932599,0.00000000004801782378902705,'2018-04-23 10:17:22','2018-04-23 10:17:22',90,NULL),(14,0.00000005876877487604431,0.000000000014338538256162116,0.00000000010568774113494982,0.00000010634473354650295,0.9999996764899671,0.00000013855141263542984,0.0000000007854783286276451,0.000000000001283230423755056,0.000000009428636007024437,0.000000009509322965134967,0.0000000000003652774139496278,'2018-04-23 10:24:21','2018-04-23 10:24:21',90,NULL),(15,0.00000005876877487604431,0.000000000014338538256162116,0.00000000010568774113494982,0.00000010634473354650295,0.9999996764899671,0.00000013855141263542984,0.0000000007854783286276451,0.000000000001283230423755056,0.000000009428636007024437,0.000000009509322965134967,0.0000000000003652774139496278,'2018-04-23 10:24:49','2018-04-23 10:24:49',90,NULL),(16,0.00000005876877487604431,0.000000000014338538256162116,0.00000000010568774113494982,0.00000010634473354650295,0.9999996764899671,0.00000013855141263542984,0.0000000007854783286276451,0.000000000001283230423755056,0.000000009428636007024437,0.000000009509322965134967,0.0000000000003652774139496278,'2018-04-23 10:25:58','2018-04-23 10:25:58',90,NULL),(17,0.00000005876877487604431,0.000000000014338538256162116,0.00000000010568774113494982,0.00000010634473354650295,0.9999996764899671,0.00000013855141263542984,0.0000000007854783286276451,0.000000000001283230423755056,0.000000009428636007024437,0.000000009509322965134967,0.0000000000003652774139496278,'2018-04-23 10:26:18','2018-04-23 10:26:18',90,NULL),(18,0.00000005876877487604431,0.000000000014338538256162116,0.00000000010568774113494982,0.00000010634473354650295,0.9999996764899671,0.00000013855141263542984,0.0000000007854783286276451,0.000000000001283230423755056,0.000000009428636007024437,0.000000009509322965134967,0.0000000000003652774139496278,'2018-04-23 10:27:44','2018-04-23 10:27:44',90,NULL),(19,0.00000005876877487604431,0.000000000014338538256162116,0.00000000010568774113494982,0.00000010634473354650295,0.9999996764899671,0.00000013855141263542984,0.0000000007854783286276451,0.000000000001283230423755056,0.000000009428636007024437,0.000000009509322965134967,0.0000000000003652774139496278,'2018-04-23 10:28:30','2018-04-23 10:28:30',90,NULL),(20,0.2351621158142293,0.0000002857017808371689,0.0000032186656664134473,0.0003970188773462199,0.2852882659203413,0.47890390520322107,0.000003070381448558028,0.00000002134185715473739,0.0001907277768040196,0.00005136495524586881,0.00000000536205921967048,'2018-04-23 10:29:34','2018-04-23 10:29:34',90,NULL),(21,0.2351621158142293,0.0000002857017808371689,0.0000032186656664134473,0.0003970188773462199,0.2852882659203413,0.47890390520322107,0.000003070381448558028,0.00000002134185715473739,0.0001907277768040196,0.00005136495524586881,0.00000000536205921967048,'2018-04-23 10:30:13','2018-04-23 10:30:13',90,NULL),(22,0.2351621158142293,0.0000002857017808371689,0.0000032186656664134473,0.0003970188773462199,0.2852882659203413,0.47890390520322107,0.000003070381448558028,0.00000002134185715473739,0.0001907277768040196,0.00005136495524586881,0.00000000536205921967048,'2018-04-23 10:31:02','2018-04-23 10:31:02',90,NULL),(23,0.000002211115688432396,0.000000000000056711789017173766,0.000000000002829290592494777,0.000000008813264443865516,0.9998427499021002,0.0001550247000324405,0.000000000012923314609805063,0.0000000000000042805947115319485,0.0000000044184993993571754,0.0000000010346014155876408,1.7470401730685426e-16,'2018-04-23 10:31:30','2018-04-23 10:31:30',90,NULL),(24,0.000002211115688432396,0.000000000000056711789017173766,0.000000000002829290592494777,0.000000008813264443865516,0.9998427499021002,0.0001550247000324405,0.000000000012923314609805063,0.0000000000000042805947115319485,0.0000000044184993993571754,0.0000000010346014155876408,1.7470401730685426e-16,'2018-04-23 10:32:10','2018-04-23 10:32:10',90,NULL),(25,0.000002211115688432396,0.000000000000056711789017173766,0.000000000002829290592494777,0.000000008813264443865516,0.9998427499021002,0.0001550247000324405,0.000000000012923314609805063,0.0000000000000042805947115319485,0.0000000044184993993571754,0.0000000010346014155876408,1.7470401730685426e-16,'2018-04-23 10:32:44','2018-04-23 10:32:44',90,NULL),(26,0.00008772203691941827,0.0000000026803211777221324,0.000000019507389564484414,0.0000027024787884823667,0.9998729454157719,0.00003462525072456448,0.00000010547660195373683,0.00000000021021969110554931,0.0000010533348312164548,0.000000823560413932599,0.00000000004801782378902705,'2018-04-23 10:35:34','2018-04-23 10:35:34',90,NULL),(27,0.00008772203691941827,0.0000000026803211777221324,0.000000019507389564484414,0.0000027024787884823667,0.9998729454157719,0.00003462525072456448,0.00000010547660195373683,0.00000000021021969110554931,0.0000010533348312164548,0.000000823560413932599,0.00000000004801782378902705,'2018-04-23 10:36:20','2018-04-23 10:36:20',90,NULL),(28,0.3139073687309505,0.0000000010193345396014321,0.00000007318713562257972,0.000002068232284951337,0.00004869559239838993,0.6860311479016572,0.0000002737560291172313,0.000000000019366185637149675,0.000009818712271144671,0.0000005528083520160449,0.0000000000402203420252083,'2018-04-23 10:37:29','2018-04-23 10:37:29',90,NULL),(29,0.3139073687309505,0.0000000010193345396014321,0.00000007318713562257972,0.000002068232284951337,0.00004869559239838993,0.6860311479016572,0.0000002737560291172313,0.000000000019366185637149675,0.000009818712271144671,0.0000005528083520160449,0.0000000000402203420252083,'2018-04-23 10:38:18','2018-04-23 10:38:18',90,'php,css'),(30,0.00008772203691941827,0.0000000026803211777221324,0.000000019507389564484414,0.0000027024787884823667,0.9998729454157719,0.00003462525072456448,0.00000010547660195373683,0.00000000021021969110554931,0.0000010533348312164548,0.000000823560413932599,0.00000000004801782378902705,'2018-04-23 10:41:53','2018-04-23 10:41:53',90,'css,c++'),(31,0.00008772203691941827,0.0000000026803211777221324,0.000000019507389564484414,0.0000027024787884823667,0.9998729454157719,0.00003462525072456448,0.00000010547660195373683,0.00000000021021969110554931,0.0000010533348312164548,0.000000823560413932599,0.00000000004801782378902705,'2018-04-23 11:13:36','2018-04-23 11:13:36',90,'css'),(32,0.00008772203691941827,0.0000000026803211777221324,0.000000019507389564484414,0.0000027024787884823667,0.9998729454157719,0.00003462525072456448,0.00000010547660195373683,0.00000000021021969110554931,0.0000010533348312164548,0.000000823560413932599,0.00000000004801782378902705,'2018-04-23 11:13:50','2018-04-23 11:13:50',90,'css'),(33,0.00008772203691941827,0.0000000026803211777221324,0.000000019507389564484414,0.0000027024787884823667,0.9998729454157719,0.00003462525072456448,0.00000010547660195373683,0.00000000021021969110554931,0.0000010533348312164548,0.000000823560413932599,0.00000000004801782378902705,'2018-04-23 11:14:07','2018-04-23 11:14:07',90,'css,c++'),(34,0.00008772203691941827,0.0000000026803211777221324,0.000000019507389564484414,0.0000027024787884823667,0.9998729454157719,0.00003462525072456448,0.00000010547660195373683,0.00000000021021969110554931,0.0000010533348312164548,0.000000823560413932599,0.00000000004801782378902705,'2018-04-23 11:16:03','2018-04-23 11:16:03',90,'css'),(35,0.00008772203691941827,0.0000000026803211777221324,0.000000019507389564484414,0.0000027024787884823667,0.9998729454157719,0.00003462525072456448,0.00000010547660195373683,0.00000000021021969110554931,0.0000010533348312164548,0.000000823560413932599,0.00000000004801782378902705,'2018-04-23 11:17:56','2018-04-23 11:17:56',90,'css'),(36,0.000002211115688432396,0.000000000000056711789017173766,0.000000000002829290592494777,0.000000008813264443865516,0.9998427499021002,0.0001550247000324405,0.000000000012923314609805063,0.0000000000000042805947115319485,0.0000000044184993993571754,0.0000000010346014155876408,1.7470401730685426e-16,'2018-04-23 11:18:16','2018-04-23 11:18:16',90,'css,html'),(37,0.00008772203691941827,0.0000000026803211777221324,0.000000019507389564484414,0.0000027024787884823667,0.9998729454157719,0.00003462525072456448,0.00000010547660195373683,0.00000000021021969110554931,0.0000010533348312164548,0.000000823560413932599,0.00000000004801782378902705,'2018-04-23 11:32:03','2018-04-23 11:32:03',90,'css'),(38,0.06750243748028921,0.00000002445650366629366,0.0000004217396728027143,0.00013982340008719676,0.01026346590352603,0.9220769925587857,0.00000014704561859940572,0.0000000011084081779265379,0.000013656764736774453,0.0000030290730064426163,0.0000000004693655489709857,'2018-04-23 12:19:22','2018-04-23 12:19:22',90,'html,devops,photoshop'),(39,0.9942955389207568,0.00010230315511482467,0.00020356404634872395,0.003418644458060051,0.0009969067181066144,0.0001623646596865405,0.0002649412078952143,0.000008229062612552443,0.0002630853746830455,0.0002738645155850461,0.000010557881150737093,'2018-05-15 11:12:44','2018-05-15 11:12:44',92,'c++,nginx');
/*!40000 ALTER TABLE `userweights` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-05-21 11:10:48
