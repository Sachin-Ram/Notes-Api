-- MySQL dump 10.13  Distrib 8.0.22, for macos10.15 (x86_64)
--
-- Host: localhost    Database: apis
-- ------------------------------------------------------
-- Server version	8.0.25-0ubuntu0.20.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `auth`
--

DROP TABLE IF EXISTS `auth`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `auth` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(45) NOT NULL,
  `password` varchar(512) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `active` int DEFAULT NULL,
  `token` varchar(512) DEFAULT NULL,
  `signup_time` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username_UNIQUE` (`username`),
  UNIQUE KEY `email_UNIQUE` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auth`
--

LOCK TABLES `auth` WRITE;
/*!40000 ALTER TABLE `auth` DISABLE KEYS */;
INSERT INTO `auth` VALUES (9,'sibidharan','$2y$10$SV1rKmZXPMW07RVULXhXl.bxcPc.iyxa.92qB5v63B.TA5Xc50YsK','sibi.nandhu@gmail.com',1,'14dd638063e0e75a5a3e9eb9390a810d','2021-05-06 20:41:26'),(16,'monish','$2y$10$mdoaVu.l4zYExR3L.D3hiOyBRShfAde6LQDYXiRsqEUvY3f9nSIHe','test@gmail.com',0,'24731dbcc1327f13264716b8c4f79d8f','2021-05-08 09:39:50'),(23,'tamil','$2y$10$vs2Ufr/mr/ejXYgcwkHYU.DXM8yJClczEmC3bU8qIHynVSnXxsjz2','tamilg33g@gmail.com',1,'5938e8b36ee31f26d157286ab6add00f','2021-05-10 18:17:39'),(38,'lahtp','$2y$10$Q00jsY1Brj356luLYbiwGOeWegs4k5dsHo51OQExdbCLKezCtZxHG','sec19it079@gmail.com@gmail.com',0,'cb8f40f02b9f56fbf9d51bc008d09a0d','2021-05-11 14:35:37'),(40,'laht','$2y$10$ZuGR3Oi9t9vM8GBb/OKda.QyKBpbjRf3/cfyKQts92CPDMycQJ6Q2','monishvm75@gmail.com',1,'64922e71b87be2331d66b2d68513409f','2021-05-11 14:36:06'),(41,'sibi1995','$2y$10$zHJPw12SPeViH/7ibiN1VeF3yANyCbvjK7.VHQlpOLE/cN.NC.VCe','sibidharan@icloud.com',1,'467f6be1e4db85486a7c6bacef29d5c4','2021-05-14 19:27:45'),(57,'ajay','$2y$10$vj9eZM8//VZmy7fI.a3CY.0H4d.hJeaoxvuswhaxhnnaQm6m1NWs2','ajaywk7@gmail.com',0,'818b136eb359a01b79cb7f9c5b48f962','2021-05-14 21:40:51'),(62,'ajaywk7','$2y$10$j7qJVDgX74J0DCjLAdWlgOwYitp.rXUAWCraoXVpTQW.yjHUb8LFm','ajaywk4@gmail.com',0,'67e57fdb33b3cc6f89f3d1fc841bbbb2','2021-05-14 21:49:10'),(63,'m','$2y$10$BkwSJCogJGb5f9NoQFNfcOCmysvau4ZHQwLqbJi4C5EPODjwHkpbq','fg@g.com',0,'434974849882fed13d5469120ced7995','2021-05-15 14:38:19'),(65,'ajaywk46','$2y$10$8CHdwFS7csKCtLfB9RN9OOwh220tyTZ3emfoL4/NILejf6zPkdrHS','ajaywk3@gmail.com',0,'6d4f97a32fb5e9cf95e241e8bac7fc22','2021-05-15 18:02:17');
/*!40000 ALTER TABLE `auth` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `session`
--

DROP TABLE IF EXISTS `session`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `session` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(45) DEFAULT NULL,
  `token` varchar(128) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `valid` int DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `session`
--

LOCK TABLES `session` WRITE;
/*!40000 ALTER TABLE `session` DISABLE KEYS */;
INSERT INTO `session` VALUES (1,'sibi1995','7cea83a887a660bcb3dbdfb8d6b79f22454c8e91e50c1eb883873afa9b03e6ca','2021-05-14 21:24:22',1),(2,'sibi1995','9aa0b19062857d74102c97d51bc72c41855584252899a3db891ff13d7ae732a5','2021-05-14 21:32:48',1);
/*!40000 ALTER TABLE `session` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-05-16 11:41:14
