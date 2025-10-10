CREATE DATABASE  IF NOT EXISTS `dbapicosmart` /*!40100 DEFAULT CHARACTER SET utf8mb3 */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `dbapicosmart`;
-- MySQL dump 10.13  Distrib 8.0.43, for Win64 (x86_64)
--
-- Host: localhost    Database: dbapicosmart
-- ------------------------------------------------------
-- Server version	8.0.43

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
-- Table structure for table `apiario`
--

DROP TABLE IF EXISTS `apiario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `apiario` (
  `idApiario` int unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(40) COLLATE utf8mb4_general_ci NOT NULL,
  `latitud` decimal(10,7) DEFAULT NULL,
  `longitud` decimal(10,7) DEFAULT NULL,
  `departamento` varchar(100) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  `municipio` varchar(100) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  `fechaCreacion` timestamp NULL DEFAULT NULL,
  `fechaActulizacion` timestamp NULL DEFAULT NULL,
  `creadoPor` bigint DEFAULT NULL,
  `estado` enum('activo','inactivo') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'activo',
  PRIMARY KEY (`idApiario`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `apiario`
--

LOCK TABLES `apiario` WRITE;
/*!40000 ALTER TABLE `apiario` DISABLE KEYS */;
INSERT INTO `apiario` VALUES (1,'Apiario1',-17.3756688,-66.1331257,'cercado','cercado','2025-09-09 04:04:51',NULL,1,'activo'),(2,'Apiario 2',-17.3837131,-66.1192748,'Cercado','Tamborada','2025-09-09 04:20:44',NULL,1,'activo');
/*!40000 ALTER TABLE `apiario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `colmena`
--

DROP TABLE IF EXISTS `colmena`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `colmena` (
  `idColmena` int unsigned NOT NULL AUTO_INCREMENT,
  `codigo` varchar(45) NOT NULL,
  `fechaInstalacion` timestamp NULL DEFAULT NULL,
  `fechaFabricacion` timestamp NULL DEFAULT NULL,
  `fechaActualizar` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `creadoPor` int DEFAULT NULL,
  `estado` enum('activo','inactivo') NOT NULL DEFAULT 'activo',
  `idApiario` int unsigned NOT NULL,
  `idReina` int unsigned DEFAULT NULL,
  `cantidadMarco` tinyint unsigned DEFAULT '0',
  PRIMARY KEY (`idColmena`),
  KEY `fk_Colmena_Apiario1_idx` (`idApiario`),
  KEY `fk_Colmena_reina1` (`idReina`),
  CONSTRAINT `fk_Colmena_Apiario1` FOREIGN KEY (`idApiario`) REFERENCES `apiario` (`idApiario`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_Colmena_reina1` FOREIGN KEY (`idReina`) REFERENCES `reina` (`idReina`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `colmena`
--

LOCK TABLES `colmena` WRITE;
/*!40000 ALTER TABLE `colmena` DISABLE KEYS */;
INSERT INTO `colmena` VALUES (1,'1','2025-09-17 22:45:21','2025-09-17 04:00:00',NULL,1,'activo',1,NULL,1);
/*!40000 ALTER TABLE `colmena` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detalles`
--

DROP TABLE IF EXISTS `detalles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `detalles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `idVenta` bigint unsigned NOT NULL,
  `idProducto` bigint unsigned NOT NULL,
  `cantidad` int NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `detalles_idventa_foreign` (`idVenta`),
  KEY `detalles_idproducto_foreign` (`idProducto`),
  CONSTRAINT `detalles_idproducto_foreign` FOREIGN KEY (`idProducto`) REFERENCES `productos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `detalles_idventa_foreign` FOREIGN KEY (`idVenta`) REFERENCES `ventas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalles`
--

LOCK TABLES `detalles` WRITE;
/*!40000 ALTER TABLE `detalles` DISABLE KEYS */;
INSERT INTO `detalles` VALUES (1,1,1,2,15.00,30.00,'2025-09-05 09:17:47','2025-09-05 09:17:47'),(2,1,2,2,20.00,40.00,'2025-09-05 09:17:47','2025-09-05 09:17:47'),(3,2,1,1,15.00,15.00,'2025-09-05 09:22:59','2025-09-05 09:22:59'),(4,3,1,1,15.00,15.00,'2025-09-05 09:23:50','2025-09-05 09:23:50'),(5,4,1,1,15.00,15.00,'2025-09-05 09:26:21','2025-09-05 09:26:21'),(6,5,1,1,15.00,15.00,'2025-09-05 09:26:42','2025-09-05 09:26:42'),(7,6,1,1,15.00,15.00,'2025-09-05 09:27:17','2025-09-05 09:27:17'),(8,7,2,1,20.00,20.00,'2025-09-05 09:27:34','2025-09-05 09:27:34'),(9,8,1,1,15.00,15.00,'2025-09-09 04:48:03','2025-09-09 04:48:03'),(10,9,3,1,10.00,10.00,'2025-09-13 03:13:24','2025-09-13 03:13:24'),(11,9,4,1,15.00,15.00,'2025-09-13 03:13:24','2025-09-13 03:13:24'),(12,10,2,1,20.00,20.00,'2025-09-18 02:55:55','2025-09-18 02:55:55'),(13,10,3,2,10.00,20.00,'2025-09-18 02:55:55','2025-09-18 02:55:55'),(14,10,4,2,15.00,30.00,'2025-09-18 02:55:55','2025-09-18 02:55:55');
/*!40000 ALTER TABLE `detalles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_reset_tokens_table',1),(3,'2019_08_19_000000_create_failed_jobs_table',1),(4,'2019_12_14_000001_create_personal_access_tokens_table',1),(5,'2025_08_15_053502_create_productos_table',1),(6,'2025_08_15_055505_create_ventas_table',1),(7,'2025_08_15_060842_create_detalles_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `productos`
--

DROP TABLE IF EXISTS `productos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `productos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `idUser` bigint unsigned NOT NULL,
  `descripcion` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unidadMedida` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stock` int NOT NULL DEFAULT '0',
  `precio` decimal(18,2) NOT NULL,
  `estado` tinyint NOT NULL DEFAULT '1',
  `imagen` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `productos_iduser_foreign` (`idUser`),
  KEY `productos_descripcion_index` (`descripcion`),
  CONSTRAINT `productos_iduser_foreign` FOREIGN KEY (`idUser`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `productos`
--

LOCK TABLES `productos` WRITE;
/*!40000 ALTER TABLE `productos` DISABLE KEYS */;
INSERT INTO `productos` VALUES (1,1,'Miel','1 kilo',20,15.00,1,'uploads/1757639791_MIEL.png','2025-08-21 22:05:22','2025-09-13 02:59:00'),(2,1,'Propóleo','40 gr',11,20.00,1,'uploads/1757639409_propoleo.jpg','2025-09-05 09:07:42','2025-09-18 02:55:55'),(3,1,'Dulce de Miel','12 Unidades',17,10.00,1,'uploads/1757717031_dulces.png','2025-09-13 02:43:51','2025-09-18 02:55:55'),(4,1,'Cera','kilo',9,15.00,1,'uploads/1757718329_Cera.png','2025-09-13 03:05:29','2025-09-18 02:55:55');
/*!40000 ALTER TABLE `productos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reina`
--

DROP TABLE IF EXISTS `reina`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reina` (
  `idReina` int unsigned NOT NULL AUTO_INCREMENT,
  `codigo` varchar(15) NOT NULL,
  `especie` varchar(20) NOT NULL,
  `fechaCompra` datetime NOT NULL,
  `descripcion` text,
  PRIMARY KEY (`idReina`),
  UNIQUE KEY `codigo_UNIQUE` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reina`
--

LOCK TABLES `reina` WRITE;
/*!40000 ALTER TABLE `reina` DISABLE KEYS */;
/*!40000 ALTER TABLE `reina` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `primerApellido` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `segundoApellido` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefono` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `nombreUsuario` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rol` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'usuario',
  `estado` tinyint NOT NULL DEFAULT '1',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'amalia','rocha','coria','amalia@example.com','70000001','2025-08-21 17:59:53','amalia','$2y$10$SyxkzmBsFDigRl/AsvRjRe5Tl3fr4LSPbeuo7XJu4KCykNcpoxThy','usuario',1,NULL,'2025-08-21 17:59:53','2025-09-09 04:31:05'),(2,'jose','morgana','arias','jose@example.com','7000002','2025-08-21 19:03:31','jmorgana','$2y$10$w2.1kakohQRo2peVafOsv.uf8mPgzmdoxKksMJ0dkEELtgEi8AHDe','administrador',1,NULL,'2025-08-21 19:03:31','2025-08-21 19:03:31'),(3,'Katherine','lopez','Ramoz','Kat@gmail.com','1234567',NULL,'katy','$2y$10$x7ZzBC9WQt7HE3JqGlRA1eJe.ZGwo.iWqkEqPWe.oB5Nr1HO49Roa','usuario',1,NULL,'2025-09-09 04:43:50','2025-09-09 04:43:50');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ventas`
--

DROP TABLE IF EXISTS `ventas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ventas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `idUser` bigint unsigned NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `total` decimal(10,2) NOT NULL,
  `estado` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ventas_iduser_foreign` (`idUser`),
  CONSTRAINT `ventas_iduser_foreign` FOREIGN KEY (`idUser`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ventas`
--

LOCK TABLES `ventas` WRITE;
/*!40000 ALTER TABLE `ventas` DISABLE KEYS */;
INSERT INTO `ventas` VALUES (1,1,'2025-09-05 09:17:47',70.00,1,'2025-09-05 09:17:47','2025-09-05 09:17:47'),(2,1,'2025-09-05 09:22:59',15.00,1,'2025-09-05 09:22:59','2025-09-05 09:22:59'),(3,1,'2025-09-05 09:23:50',15.00,1,'2025-09-05 09:23:50','2025-09-05 09:23:50'),(4,1,'2025-09-05 09:26:21',15.00,1,'2025-09-05 09:26:21','2025-09-05 09:26:21'),(5,1,'2025-09-05 09:26:42',15.00,1,'2025-09-05 09:26:42','2025-09-05 09:26:42'),(6,1,'2025-09-05 09:27:17',15.00,1,'2025-09-05 09:27:17','2025-09-05 09:27:17'),(7,1,'2025-09-05 09:27:34',20.00,1,'2025-09-05 09:27:34','2025-09-05 09:27:34'),(8,1,'2025-09-09 04:48:03',15.00,1,'2025-09-09 04:48:03','2025-09-09 04:48:03'),(9,1,'2025-09-13 03:13:24',25.00,1,'2025-09-13 03:13:24','2025-09-13 03:13:24'),(10,1,'2025-09-18 02:55:55',70.00,1,'2025-09-18 02:55:55','2025-09-18 02:55:55');
/*!40000 ALTER TABLE `ventas` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-10-03 18:19:58
