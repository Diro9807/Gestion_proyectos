-- MySQL dump 10.13  Distrib 8.0.45, for Win64 (x86_64)
--
-- Host: localhost    Database: herramienta_gestion
-- ------------------------------------------------------
-- Server version	8.0.45

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
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
INSERT INTO `personal_access_tokens` VALUES (1,'App\\Models\\User',2,'auth_token','b0a5b9d54076141ac6e74eb7377e7d96db87523da3ca750adcebc6c247f5c6e9','[\"*\"]','2026-05-25 15:11:58',NULL,'2026-05-25 14:55:09','2026-05-25 15:11:58'),(2,'App\\Models\\User',3,'auth_token','a68ba2a5372121a38d17f20e6ecc05aff4f00b47a88f733b161fe677679302ae','[\"*\"]','2026-05-25 15:14:01',NULL,'2026-05-25 15:13:46','2026-05-25 15:14:01'),(3,'App\\Models\\User',2,'auth_token','8d6c254c62135f6594af66b455d52c1c811ecf9ceb6f9bb87377a9c9562e530d','[\"*\"]','2026-05-29 14:48:58',NULL,'2026-05-25 15:14:08','2026-05-29 14:48:58'),(4,'App\\Models\\User',4,'auth_token','0418884203dc45115d0bfc9be58d73629c992a920e09fe30363d00a997e7775a','[\"*\"]','2026-05-29 14:57:12',NULL,'2026-05-29 14:50:19','2026-05-29 14:57:12'),(5,'App\\Models\\User',4,'auth_token','e77bc28f1571a30e351c4d68a2039e4c755b0448f6f18d687bae810620358cbc','[\"*\"]','2026-06-08 11:57:20',NULL,'2026-05-29 15:00:34','2026-06-08 11:57:20'),(6,'App\\Models\\User',2,'auth_token','449ec1837a0f615deabd33b70797f926065062c4d0abce5ea649ce5ae709a13b','[\"*\"]','2026-06-09 15:13:29',NULL,'2026-06-08 11:57:35','2026-06-09 15:13:29');
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `project_users`
--

DROP TABLE IF EXISTS `project_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `project_users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `project_id` int NOT NULL,
  `user_id` int NOT NULL,
  `role` varchar(50) DEFAULT 'member',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `project_users_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id_project`) ON DELETE CASCADE,
  CONSTRAINT `project_users_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id_user`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `project_users`
--

LOCK TABLES `project_users` WRITE;
/*!40000 ALTER TABLE `project_users` DISABLE KEYS */;
INSERT INTO `project_users` VALUES (1,1,2,'owner',NULL,NULL),(2,2,2,'owner',NULL,NULL),(3,3,2,'owner',NULL,NULL),(4,4,2,'owner',NULL,NULL),(5,5,3,'owner',NULL,NULL),(6,5,2,'member',NULL,NULL),(7,6,4,'owner',NULL,NULL),(8,6,2,'member',NULL,NULL),(9,1,3,'member',NULL,NULL),(10,7,2,'owner',NULL,NULL),(11,7,4,'member',NULL,NULL),(12,8,2,'owner',NULL,NULL),(13,9,2,'owner',NULL,NULL),(14,10,2,'owner',NULL,NULL),(15,11,2,'owner',NULL,NULL);
/*!40000 ALTER TABLE `project_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `projects`
--

DROP TABLE IF EXISTS `projects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `projects` (
  `id_project` int NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_project`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `projects`
--

LOCK TABLES `projects` WRITE;
/*!40000 ALTER TABLE `projects` DISABLE KEYS */;
INSERT INTO `projects` VALUES (1,'sardfasdf','2131','2026-05-25 14:55:15','2026-05-25 14:55:15'),(2,'2','xfscxgf','2026-05-25 15:00:49','2026-05-25 15:00:49'),(3,'43242','rwedsfsg','2026-05-25 15:07:53','2026-05-25 15:07:53'),(4,'gegadsg','sdasfa','2026-05-25 15:11:42','2026-05-25 15:11:42'),(5,'asfasdgf','sadgasdg','2026-05-25 15:13:58','2026-05-25 15:13:58'),(6,'ir al gimnasio','es un viernes cualquiera y me apetece ir al xym con mi supuesto novio','2026-05-29 14:51:51','2026-05-29 14:51:51'),(7,'´ñlkklñ´ñl',NULL,'2026-06-09 14:50:08','2026-06-09 14:50:08'),(8,'´574764567',NULL,'2026-06-09 14:50:18','2026-06-09 14:50:18'),(9,'65735757469833456',NULL,'2026-06-09 14:50:22','2026-06-09 14:50:22'),(10,'123123563','adsrfadsf','2026-06-09 14:50:27','2026-06-09 14:50:27'),(11,'hgdfhdafsf',NULL,'2026-06-09 14:50:31','2026-06-09 14:50:31');
/*!40000 ALTER TABLE `projects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id_rol` int NOT NULL AUTO_INCREMENT,
  `type_rol` varchar(255) NOT NULL,
  PRIMARY KEY (`id_rol`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'admin'),(2,'user');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tasks`
--

DROP TABLE IF EXISTS `tasks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tasks` (
  `id_task` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `status` varchar(50) DEFAULT 'pending',
  `user_id` int DEFAULT NULL,
  `project_task_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `position` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_task`),
  KEY `user_id` (`user_id`),
  KEY `project_task_id` (`project_task_id`),
  CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id_user`),
  CONSTRAINT `tasks_ibfk_2` FOREIGN KEY (`project_task_id`) REFERENCES `projects` (`id_project`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tasks`
--

LOCK TABLES `tasks` WRITE;
/*!40000 ALTER TABLE `tasks` DISABLE KEYS */;
INSERT INTO `tasks` VALUES (2,'espalda triceps y hombro','acompañar a su super hombreton guapeton y super de fuerte','2026-05-29','2026-05-29','2026-05-29','pending',4,6,'2026-05-29 14:55:13','2026-06-09 14:47:12',1),(3,'fgsdgdsfgs',NULL,'2026-06-09','2026-06-27','2026-06-30','pending',3,1,'2026-06-09 14:46:49','2026-06-09 14:48:17',0);
/*!40000 ALTER TABLE `tasks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id_user` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(50) NOT NULL,
  `roles_id` int DEFAULT NULL,
  `project_id` int DEFAULT NULL,
  `api_token` varchar(80) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `email` (`email`),
  KEY `project_id` (`project_id`),
  KEY `roles_id` (`roles_id`),
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id_project`),
  CONSTRAINT `users_ibfk_2` FOREIGN KEY (`roles_id`) REFERENCES `roles` (`id_rol`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (2,'Jordi','$2y$12$Uq.Ex7MYjvzyU9wxUj5mL.EtjMjo.B3IrifYkTxA7r37J1AbtKwm6','j@gmail.com',2,NULL,NULL,'2026-05-25 14:55:00','2026-05-25 14:55:00'),(3,'Hector','$2y$12$8Q0LzhGqcTPDw7/30oXjHe6lmHGL6jzoSJm8t4gXE2uGpKFRcA.Hu','h@gmail.com',2,NULL,NULL,'2026-05-25 15:13:32','2026-05-25 15:13:32'),(4,'Pauleta','$2y$12$d7./pVPDG.kChqKZpdYGJe8/V6HXLZ7eBqqKNRUe6yOj2616EJeNa','pmc@gmail.com',2,NULL,NULL,'2026-05-29 14:49:56','2026-05-29 14:56:12');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-06-11 18:12:58
