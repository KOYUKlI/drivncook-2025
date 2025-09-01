-- MySQL dump 10.13  Distrib 8.0.32, for Linux (x86_64)
--
-- Host: localhost    Database: laravel
-- ------------------------------------------------------
-- Server version	8.0.32

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `audit_logs`
--

DROP TABLE IF EXISTS `audit_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `audit_logs` (
  `id` char(26) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `route` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `method` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `action` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject_id` char(26) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `meta` json DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `audit_logs_user_id_foreign` (`user_id`),
  CONSTRAINT `audit_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `audit_logs`
--

LOCK TABLES `audit_logs` WRITE;
/*!40000 ALTER TABLE `audit_logs` DISABLE KEYS */;
INSERT INTO `audit_logs` VALUES ('01K40KPF4DE7FY0D8N03SK3P7Y',1,'bo.reports.monthly.generate','POST','App\\Http\\Controllers\\BO\\ReportController@generate',NULL,NULL,'172.18.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36 OPR/120.0.0.0 (Edition std-2)','{\"year\": \"2025\", \"month\": \"8\", \"franchisee_id\": \"01K40JQN6MTCVJC5NQZNRGETDE\"}','2025-08-31 17:44:43');
/*!40000 ALTER TABLE `audit_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('laravel-cache-spatie.permission.cache','a:3:{s:5:\"alias\";a:0:{}s:11:\"permissions\";a:0:{}s:5:\"roles\";a:0:{}}',1756748685);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `deployments`
--

DROP TABLE IF EXISTS `deployments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `deployments` (
  `id` char(26) COLLATE utf8mb4_unicode_ci NOT NULL,
  `truck_id` char(26) COLLATE utf8mb4_unicode_ci NOT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `deployments_truck_id_foreign` (`truck_id`),
  CONSTRAINT `deployments_truck_id_foreign` FOREIGN KEY (`truck_id`) REFERENCES `trucks` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `deployments`
--

LOCK TABLES `deployments` WRITE;
/*!40000 ALTER TABLE `deployments` DISABLE KEYS */;
/*!40000 ALTER TABLE `deployments` ENABLE KEYS */;
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
-- Table structure for table `franchise_application_documents`
--

DROP TABLE IF EXISTS `franchise_application_documents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `franchise_application_documents` (
  `id` char(26) COLLATE utf8mb4_unicode_ci NOT NULL,
  `franchise_application_id` char(26) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kind` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `franchise_application_documents_franchise_application_id_foreign` (`franchise_application_id`),
  CONSTRAINT `franchise_application_documents_franchise_application_id_foreign` FOREIGN KEY (`franchise_application_id`) REFERENCES `franchise_applications` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `franchise_application_documents`
--

LOCK TABLES `franchise_application_documents` WRITE;
/*!40000 ALTER TABLE `franchise_application_documents` DISABLE KEYS */;
/*!40000 ALTER TABLE `franchise_application_documents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `franchise_application_events`
--

DROP TABLE IF EXISTS `franchise_application_events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `franchise_application_events` (
  `id` char(26) COLLATE utf8mb4_unicode_ci NOT NULL,
  `franchise_application_id` char(26) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `from_status` varchar(24) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `to_status` varchar(24) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `franchise_application_events_franchise_application_id_foreign` (`franchise_application_id`),
  KEY `franchise_application_events_user_id_foreign` (`user_id`),
  CONSTRAINT `franchise_application_events_franchise_application_id_foreign` FOREIGN KEY (`franchise_application_id`) REFERENCES `franchise_applications` (`id`) ON DELETE CASCADE,
  CONSTRAINT `franchise_application_events_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `franchise_application_events`
--

LOCK TABLES `franchise_application_events` WRITE;
/*!40000 ALTER TABLE `franchise_application_events` DISABLE KEYS */;
/*!40000 ALTER TABLE `franchise_application_events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `franchise_applications`
--

DROP TABLE IF EXISTS `franchise_applications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `franchise_applications` (
  `id` char(26) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `full_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `desired_area` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `entry_fee_ack` tinyint(1) NOT NULL DEFAULT '0',
  `royalty_ack` tinyint(1) NOT NULL DEFAULT '0',
  `central80_ack` tinyint(1) NOT NULL DEFAULT '0',
  `status` enum('draft','submitted','prequalified','interview','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `franchise_applications_user_id_foreign` (`user_id`),
  KEY `franchise_applications_email_status_index` (`email`,`status`),
  CONSTRAINT `franchise_applications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `franchise_applications`
--

LOCK TABLES `franchise_applications` WRITE;
/*!40000 ALTER TABLE `franchise_applications` DISABLE KEYS */;
/*!40000 ALTER TABLE `franchise_applications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `franchisees`
--

DROP TABLE IF EXISTS `franchisees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `franchisees` (
  `id` char(26) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `billing_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `royalty_rate` decimal(5,4) NOT NULL DEFAULT '0.0400',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `franchisees_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `franchisees`
--

LOCK TABLES `franchisees` WRITE;
/*!40000 ALTER TABLE `franchisees` DISABLE KEYS */;
INSERT INTO `franchisees` VALUES ('01K40JQN6MTCVJC5NQZNRGETDE','FR1 — Paris 11e','fr1@dc.test','+33 6 11 22 33 44','11 Rue Oberkampf, 75011 Paris',0.0400,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQN6R6SJ6724FRFA3TVS6','FR2 — Nanterre','fr2@dc.test','+33 6 22 33 44 55','5 Avenue Joliot-Curie, 92000 Nanterre',0.0400,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQN6W55YXQ6AKPEZBZ59V','FR3 — Créteil','fr3@dc.test','+33 6 33 44 55 66','2 Rue du Général Leclerc, 94000 Créteil',0.0400,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL);
/*!40000 ALTER TABLE `franchisees` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `maintenance_attachments`
--

DROP TABLE IF EXISTS `maintenance_attachments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `maintenance_attachments` (
  `id` char(26) COLLATE utf8mb4_unicode_ci NOT NULL,
  `maintenance_log_id` char(26) COLLATE utf8mb4_unicode_ci NOT NULL,
  `label` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mime_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `size_bytes` int NOT NULL,
  `uploaded_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `maintenance_attachments_maintenance_log_id_foreign` (`maintenance_log_id`),
  KEY `maintenance_attachments_uploaded_by_foreign` (`uploaded_by`),
  CONSTRAINT `maintenance_attachments_maintenance_log_id_foreign` FOREIGN KEY (`maintenance_log_id`) REFERENCES `maintenance_logs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `maintenance_attachments_uploaded_by_foreign` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `maintenance_attachments`
--

LOCK TABLES `maintenance_attachments` WRITE;
/*!40000 ALTER TABLE `maintenance_attachments` DISABLE KEYS */;
/*!40000 ALTER TABLE `maintenance_attachments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `maintenance_logs`
--

DROP TABLE IF EXISTS `maintenance_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `maintenance_logs` (
  `id` char(26) COLLATE utf8mb4_unicode_ci NOT NULL,
  `truck_id` char(26) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kind` enum('Preventive','Corrective') COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('planned','open','paused','closed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'planned',
  `description` text COLLATE utf8mb4_unicode_ci,
  `started_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `closed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `severity` enum('low','medium','high') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `priority` enum('P3','P2','P1') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `planned_start_at` timestamp NULL DEFAULT NULL,
  `planned_end_at` timestamp NULL DEFAULT NULL,
  `due_at` timestamp NULL DEFAULT NULL,
  `mileage_open_km` int DEFAULT NULL,
  `mileage_close_km` int DEFAULT NULL,
  `provider_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider_contact` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cost_cents` int DEFAULT NULL,
  `labor_cents` int DEFAULT NULL,
  `parts_cents` int DEFAULT NULL,
  `paused_at` timestamp NULL DEFAULT NULL,
  `resumed_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `maintenance_logs_truck_id_foreign` (`truck_id`),
  KEY `maintenance_logs_started_at_index` (`started_at`),
  CONSTRAINT `maintenance_logs_truck_id_foreign` FOREIGN KEY (`truck_id`) REFERENCES `trucks` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `maintenance_logs`
--

LOCK TABLES `maintenance_logs` WRITE;
/*!40000 ALTER TABLE `maintenance_logs` DISABLE KEYS */;
INSERT INTO `maintenance_logs` VALUES ('01K40JQN7ZAGWFZEPMXTCJHANH','01K40JQN74NHYMXBHQ6DTSZ02Z','Preventive','planned','Maintenance #0 for TRK-01','2025-08-12 17:27:53',NULL,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Ziemann-Ernser','914.532.5567',44253,39944,4309,NULL,NULL),('01K40JQN83CYKVYAB1CW6SSE9A','01K40JQN79R4VMFMZQVFD58K6E','Corrective','planned','Maintenance #0 for TRK-02','2025-08-05 17:27:53',NULL,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Okuneva Inc','+1-725-809-8909',41890,18053,23837,NULL,NULL),('01K40JQN87K46QGHKCM0CB1NPQ','01K40JQN7DZ6YK0JKG5EPJV9C3','Preventive','planned','Maintenance #0 for TRK-03','2025-07-15 17:27:53',NULL,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Dickinson-Pacocha','956.439.8881',56040,36449,19591,NULL,NULL),('01K40JQN8BTW44M6JE403R2083','01K40JQN7J716WX3J15R1XZDFF','Corrective','planned','Maintenance #0 for TRK-04','2025-07-28 17:27:53',NULL,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Kshlerin LLC','1-279-424-2139',48508,24868,23640,NULL,NULL),('01K40JQN8F9PR6GZ6NB3EGTS9H','01K40JQN7J716WX3J15R1XZDFF','Corrective','planned','Maintenance #1 for TRK-04','2025-08-08 17:27:53',NULL,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Murphy, Nienow and Bins','520.290.3526',13466,9017,4449,NULL,NULL),('01K40JQN8JT92AVWCX5CW5YS9Z','01K40JQN7PR9HB0VP4DM8HAS6K','Preventive','planned','Maintenance #0 for TRK-05','2025-07-22 17:27:53',NULL,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Swaniawski, Beer and Thompson','+14179236960',39885,20898,18987,NULL,NULL),('01K40JQN8PBAG2TAH2KZGSF9TA','01K40JQN7PR9HB0VP4DM8HAS6K','Preventive','planned','Maintenance #1 for TRK-05','2025-07-21 17:27:53',NULL,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Kunde PLC','1-785-714-3059',39804,28979,10825,NULL,NULL),('01K40JQN8TXPCRF6D5EF47HEA0','01K40JQN7TCM8WRH6740FQ5NTE','Preventive','closed','Maintenance #0 for TRK-06','2025-08-10 17:27:53','2025-08-22 17:27:53','2025-08-31 17:27:53','2025-08-31 17:27:53',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Kuvalis-O\'Kon','1-317-786-4623',56398,38966,17432,NULL,NULL),('01K40JQN8XFFRHFTS47K3RRBZG','01K40JQN7TCM8WRH6740FQ5NTE','Preventive','planned','Maintenance #1 for TRK-06','2025-08-03 17:27:53',NULL,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Bauch-Littel','(513) 637-7258',48014,36571,11443,NULL,NULL);
/*!40000 ALTER TABLE `maintenance_logs` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2023_11_27_000000_add_warehouse_reception_fields',1),(5,'2025_08_27_000000_create_core_tables',1),(6,'2025_08_27_000100_create_franchise_applications_tables',1),(7,'2025_08_27_000200_add_franchisee_id_to_users_table',1),(8,'2025_08_27_023456_create_permission_tables',1),(9,'2025_08_27_023826_create_customer_columns',1),(10,'2025_08_27_023827_create_subscriptions_table',1),(11,'2025_08_27_023828_create_subscription_items_table',1),(12,'2025_08_28_000000_create_report_pdfs_table',1),(13,'2025_08_28_125435_update_franchisees_table_make_email_nullable',1),(14,'2025_08_28_133208_update_franchisees_table_make_email_required_again',1),(15,'2025_08_28_175817_update_report_pdfs_table_schema',1),(16,'2025_08_30_000001_extend_maintenance_logs_table',1),(17,'2025_08_30_000002_create_maintenance_attachments_table',1),(18,'2025_08_30_140000_alter_trucks_add_creation_fields',1),(19,'2025_08_30_170000_create_truck_deployments_table',1),(20,'2025_08_30_210000_add_geo_and_cancel_reason_to_truck_deployments',1),(21,'2025_08_30_213000_fix_missing_geo_columns_in_truck_deployments',1),(22,'2025_08_31_000000_enhance_warehouses_and_stock_items',1),(23,'2025_08_31_043040_create_warehouse_inventories_table',1),(24,'2025_08_31_043633_fix_maintenance_logs_table',1),(25,'2025_08_31_100000_create_warehouse_inventory_system',1),(26,'2025_08_31_110000_update_warehouse_inventories_schema',1),(27,'2025_08_31_120000_add_reference_to_purchase_orders',1),(28,'2025_08_31_130000_add_shipped_and_delivered_timestamps_to_purchase_orders',1),(29,'2025_08_31_140000_add_check_non_negative_qty_on_hand',1),(30,'2025_09_01_000500_add_replenishment_support',1),(31,'2025_09_02_000100_alter_purchase_orders_status_enum',1),(32,'2025_09_02_010000_add_indexes_for_compliance_report',1),(33,'2025_09_02_020000_create_audit_logs_table',1),(34,'2025_09_02_021000_add_generated_at_and_indexes_to_report_pdfs',1),(35,'2025_09_02_021500_make_report_pdfs_franchisee_nullable',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_permissions`
--

DROP TABLE IF EXISTS `model_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_permissions`
--

LOCK TABLES `model_has_permissions` WRITE;
/*!40000 ALTER TABLE `model_has_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `model_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_roles`
--

DROP TABLE IF EXISTS `model_has_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_roles`
--

LOCK TABLES `model_has_roles` WRITE;
/*!40000 ALTER TABLE `model_has_roles` DISABLE KEYS */;
INSERT INTO `model_has_roles` VALUES (1,'App\\Models\\User',1),(2,'App\\Models\\User',2),(3,'App\\Models\\User',3),(4,'App\\Models\\User',4),(4,'App\\Models\\User',5),(4,'App\\Models\\User',6);
/*!40000 ALTER TABLE `model_has_roles` ENABLE KEYS */;
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
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `purchase_lines`
--

DROP TABLE IF EXISTS `purchase_lines`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `purchase_lines` (
  `id` char(26) COLLATE utf8mb4_unicode_ci NOT NULL,
  `purchase_order_id` char(26) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stock_item_id` char(26) COLLATE utf8mb4_unicode_ci NOT NULL,
  `qty` int unsigned NOT NULL,
  `qty_picked` int unsigned NOT NULL DEFAULT '0',
  `qty_shipped` int unsigned NOT NULL DEFAULT '0',
  `qty_delivered` int unsigned NOT NULL DEFAULT '0',
  `unit_price_cents` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pl_order_idx` (`purchase_order_id`),
  KEY `pl_stock_item_idx` (`stock_item_id`),
  CONSTRAINT `purchase_lines_purchase_order_id_foreign` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `purchase_lines_stock_item_id_foreign` FOREIGN KEY (`stock_item_id`) REFERENCES `stock_items` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `purchase_lines`
--

LOCK TABLES `purchase_lines` WRITE;
/*!40000 ALTER TABLE `purchase_lines` DISABLE KEYS */;
INSERT INTO `purchase_lines` VALUES ('01K40JQNAJ388Y2TY1K1SX3M2B','01K40JQNAFVR08H1KADB971F1S','01K40JQMSAE0Z3KHAVJBYT54W4',15,0,0,0,14733,'2025-08-31 17:27:53','2025-08-31 17:27:53'),('01K40JQNAP315TZ47AJ2MPTM8N','01K40JQNAFVR08H1KADB971F1S','01K40JQMSEP3JMZ8D1VTVVY1Q8',6,0,0,0,2561,'2025-08-31 17:27:53','2025-08-31 17:27:53'),('01K40JQNASRV93094WD3RTG4V8','01K40JQNAFVR08H1KADB971F1S','01K40JQMSV8ACVYVDKDRPC18YG',17,0,0,0,5206,'2025-08-31 17:27:53','2025-08-31 17:27:53'),('01K40JQNAXBFSJHYP6AMFAVBKX','01K40JQNAFVR08H1KADB971F1S','01K40JQMSY930MSDZK29TWM94H',1,0,0,0,14141,'2025-08-31 17:27:53','2025-08-31 17:27:53'),('01K40JQNB1VFQQ9G8ZSAJFYHKW','01K40JQNAFVR08H1KADB971F1S','01K40JQMTPFA6ZW3B0KXQWEGDQ',20,0,0,0,8699,'2025-08-31 17:27:53','2025-08-31 17:27:53'),('01K40JQNB5AT42GRR1J9J9P87C','01K40JQNAFVR08H1KADB971F1S','01K40JQMVDV6EEJYY6J4PBG85X',7,0,0,0,11159,'2025-08-31 17:27:53','2025-08-31 17:27:53'),('01K40JQNCFGCXH5F45QZESBTB4','01K40JQNCCKA886NPMGWX47GPG','01K40JQMSV8ACVYVDKDRPC18YG',18,0,0,0,5206,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNCK4WSX0TS0FWKYCMAN','01K40JQNCCKA886NPMGWX47GPG','01K40JQMT2BFFVRXWAE4HFRBPS',8,0,0,0,11249,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNCPGEGMFMH976XGN5AN','01K40JQNCCKA886NPMGWX47GPG','01K40JQMTDDC5V7N4ASHMCBEPP',6,0,0,0,6084,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNCS9D2TBFAGRQGCZ8A9','01K40JQNCCKA886NPMGWX47GPG','01K40JQMV1RCV1SQRQ49HZK8CY',1,0,0,0,8780,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNCX6ZJ2ZRMP0AHWM187','01K40JQNCCKA886NPMGWX47GPG','01K40JQMVH7FZZM9C1V7T15NBR',1,0,0,0,10425,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQND0PW4146NGPX5G9KDC','01K40JQNCCKA886NPMGWX47GPG','01K40JQMVMRMVW5B0EZTT5DKSY',20,0,0,0,2176,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQND35FKHP3ZW15RSD3F2','01K40JQNCCKA886NPMGWX47GPG','01K40JQMVZ7J7HMPV396JKD1ZC',2,0,0,0,13225,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNDEK7CZ1KDTC22J939G','01K40JQNDAXC7J6K4DQ3SBVGDG','01K40JQMRQAD2R066JMJPAY6CX',9,0,0,0,12401,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNDHYZSDECB857EC76MF','01K40JQNDAXC7J6K4DQ3SBVGDG','01K40JQMSAE0Z3KHAVJBYT54W4',13,0,0,0,14733,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNDN97VCR3TMZD7EWCQR','01K40JQNDAXC7J6K4DQ3SBVGDG','01K40JQMSQFFJE8RP0HKB1D570',1,0,0,0,10144,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNDSP379HSZH3Z93H5H3','01K40JQNDAXC7J6K4DQ3SBVGDG','01K40JQMTTYWX9W7NM29RTE9FQ',15,0,0,0,6818,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNDYKYF1KDTGSSXZ5GJN','01K40JQNDAXC7J6K4DQ3SBVGDG','01K40JQMTXF0W5N9F59MD488AD',20,0,0,0,5472,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNE2A2PK45KVXRKVGMHA','01K40JQNDAXC7J6K4DQ3SBVGDG','01K40JQMVAVN57GGGTK1C8HB9C',7,0,0,0,4109,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNE5SCQP8WM9X6T27AT1','01K40JQNDAXC7J6K4DQ3SBVGDG','01K40JQMVH7FZZM9C1V7T15NBR',8,0,0,0,10425,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNFG4HQMDA6EY74ZBXQH','01K40JQNFCMAXV523JW19M9VKV','01K40JQMTG7NR85FH40JV3P47W',20,0,0,0,14210,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNFM7JSN2FFXBWDD4HNZ','01K40JQNFCMAXV523JW19M9VKV','01K40JQMTKZ0RR2N980DBE3MG8',19,0,0,0,11598,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNFRXBJAAW5DC3Y8TEC7','01K40JQNFCMAXV523JW19M9VKV','01K40JQMTTYWX9W7NM29RTE9FQ',3,0,0,0,6818,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNFV3VTWTNDPDH5H1RVE','01K40JQNFCMAXV523JW19M9VKV','01K40JQMV4367TRSN828TX5NB0',2,0,0,0,981,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNFZ5V01Z0K3234DBNEN','01K40JQNFCMAXV523JW19M9VKV','01K40JQMV70JVWE49MD9FHAW65',16,0,0,0,12806,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNG342HWJ1JZT0SQG13F','01K40JQNFCMAXV523JW19M9VKV','01K40JQMVAVN57GGGTK1C8HB9C',13,0,0,0,4109,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNGJ0C208A2ABNP48BV2','01K40JQNGEZ4SBMEM8Y9044JGK','01K40JQMRQAD2R066JMJPAY6CX',16,0,0,0,12401,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNGN75PWF7YJ0JXMFZ9X','01K40JQNGEZ4SBMEM8Y9044JGK','01K40JQMRW90SACGNZ481031VF',11,0,0,0,11144,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNGSXET9T1KHC47M13VM','01K40JQNGEZ4SBMEM8Y9044JGK','01K40JQMSQFFJE8RP0HKB1D570',14,0,0,0,10144,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNGYTTT0149W9X8E8ADG','01K40JQNGEZ4SBMEM8Y9044JGK','01K40JQMT69WBT1PYNSPGRXE38',14,0,0,0,2273,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNH2Z4616YJC4GFHG7B3','01K40JQNGEZ4SBMEM8Y9044JGK','01K40JQMTXF0W5N9F59MD488AD',2,0,0,0,5472,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNH5139BAR69KTSM2FM9','01K40JQNGEZ4SBMEM8Y9044JGK','01K40JQMVMRMVW5B0EZTT5DKSY',2,0,0,0,2176,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNHJ1SE34M9P1NCGE24Q','01K40JQNHEZB7Z4ZXC31G2V0D4','01K40JQMS0C7WQR3NVTN3RGQ7X',19,0,0,0,8854,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNHPY2B11YWK8RCSDEXY','01K40JQNHEZB7Z4ZXC31G2V0D4','01K40JQMSAE0Z3KHAVJBYT54W4',10,0,0,0,14733,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNHSDVET6JE1NXM7S28W','01K40JQNHEZB7Z4ZXC31G2V0D4','01K40JQMSQFFJE8RP0HKB1D570',17,0,0,0,10144,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNHXGWMK88XPGM2023RB','01K40JQNHEZB7Z4ZXC31G2V0D4','01K40JQMTTYWX9W7NM29RTE9FQ',15,0,0,0,6818,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNJ1MFVKKDNZ23ZMSWN8','01K40JQNHEZB7Z4ZXC31G2V0D4','01K40JQMVH7FZZM9C1V7T15NBR',4,0,0,0,10425,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNJ4W7GXZN1E27E6A470','01K40JQNHEZB7Z4ZXC31G2V0D4','01K40JQMVZ7J7HMPV396JKD1ZC',18,0,0,0,13225,'2025-08-31 17:27:54','2025-08-31 17:27:54');
/*!40000 ALTER TABLE `purchase_lines` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `purchase_orders`
--

DROP TABLE IF EXISTS `purchase_orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `purchase_orders` (
  `id` char(26) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reference` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `warehouse_id` char(26) COLLATE utf8mb4_unicode_ci NOT NULL,
  `franchisee_id` char(26) COLLATE utf8mb4_unicode_ci NOT NULL,
  `placed_by` bigint unsigned DEFAULT NULL,
  `status` enum('Draft','Approved','Prepared','Picked','Shipped','Received','Delivered','Closed','Cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Draft',
  `kind` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Standard',
  `corp_ratio_cached` decimal(5,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status_updated_at` timestamp NULL DEFAULT NULL,
  `status_updated_by` bigint unsigned DEFAULT NULL,
  `shipped_at` timestamp NULL DEFAULT NULL,
  `delivered_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `purchase_orders_reference_unique` (`reference`),
  KEY `purchase_orders_warehouse_id_foreign` (`warehouse_id`),
  KEY `purchase_orders_status_index` (`status`),
  KEY `purchase_orders_reference_index` (`reference`),
  KEY `purchase_orders_shipped_at_index` (`shipped_at`),
  KEY `purchase_orders_delivered_at_index` (`delivered_at`),
  KEY `purchase_orders_kind_index` (`kind`),
  KEY `purchase_orders_status_updated_by_foreign` (`status_updated_by`),
  KEY `po_kind_idx` (`kind`),
  KEY `po_franchisee_idx` (`franchisee_id`),
  KEY `po_status_idx` (`status`),
  KEY `po_created_at_idx` (`created_at`),
  KEY `po_placed_by_idx` (`placed_by`),
  CONSTRAINT `purchase_orders_franchisee_id_foreign` FOREIGN KEY (`franchisee_id`) REFERENCES `franchisees` (`id`) ON DELETE CASCADE,
  CONSTRAINT `purchase_orders_placed_by_foreign` FOREIGN KEY (`placed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `purchase_orders_status_updated_by_foreign` FOREIGN KEY (`status_updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `purchase_orders_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `purchase_orders`
--

LOCK TABLES `purchase_orders` WRITE;
/*!40000 ALTER TABLE `purchase_orders` DISABLE KEYS */;
INSERT INTO `purchase_orders` VALUES ('01K40JQNAFVR08H1KADB971F1S','REP-202508-0001','01K40JQMR53P3ZQWBMYJW2RGCN','01K40JQN6MTCVJC5NQZNRGETDE',NULL,'Closed','Replenishment',86.79,'2025-07-27 17:27:53','2025-08-31 17:27:53',NULL,NULL,NULL,NULL),('01K40JQNCCKA886NPMGWX47GPG','REP-202508-0002','01K40JQMRACAMEMWAC92SB2BF4','01K40JQN6R6SJ6724FRFA3TVS6',NULL,'Approved','Replenishment',71.18,'2025-08-10 17:27:53','2025-08-31 17:27:54',NULL,NULL,NULL,NULL),('01K40JQNDAXC7J6K4DQ3SBVGDG','REP-202508-0003','01K40JQMRD3D8KHGQ60TBHF9QZ','01K40JQN6W55YXQ6AKPEZBZ59V',NULL,'Delivered','Replenishment',49.17,'2025-07-31 17:27:54','2025-08-31 17:27:54',NULL,NULL,NULL,NULL),('01K40JQNFCMAXV523JW19M9VKV','REP-202508-0004','01K40JQMRHACB4TN0AYNAFFVK1','01K40JQN6MTCVJC5NQZNRGETDE',NULL,'Approved','Replenishment',64.25,'2025-08-01 17:27:54','2025-08-31 17:27:54',NULL,NULL,NULL,NULL),('01K40JQNGEZ4SBMEM8Y9044JGK','REP-202508-0005','01K40JQMR53P3ZQWBMYJW2RGCN','01K40JQN6R6SJ6724FRFA3TVS6',NULL,'Draft','Replenishment',97.00,'2025-08-10 17:27:54','2025-08-31 17:27:54',NULL,NULL,NULL,NULL),('01K40JQNHEZB7Z4ZXC31G2V0D4','REP-202508-0006','01K40JQMRACAMEMWAC92SB2BF4','01K40JQN6W55YXQ6AKPEZBZ59V',NULL,'Draft','Replenishment',56.09,'2025-08-25 17:27:54','2025-08-31 17:27:54',NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `purchase_orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `report_pdfs`
--

DROP TABLE IF EXISTS `report_pdfs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `report_pdfs` (
  `id` char(26) COLLATE utf8mb4_unicode_ci NOT NULL,
  `franchisee_id` char(26) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `year` smallint unsigned NOT NULL,
  `month` tinyint unsigned DEFAULT NULL,
  `storage_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_size` bigint DEFAULT NULL,
  `generated_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `report_pdfs_unique_period` (`type`,`franchisee_id`,`year`,`month`),
  KEY `report_pdfs_fym_idx` (`franchisee_id`,`year`,`month`),
  KEY `report_pdfs_type_period_idx` (`type`,`year`,`month`),
  CONSTRAINT `report_pdfs_franchisee_id_foreign` FOREIGN KEY (`franchisee_id`) REFERENCES `franchisees` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `report_pdfs`
--

LOCK TABLES `report_pdfs` WRITE;
/*!40000 ALTER TABLE `report_pdfs` DISABLE KEYS */;
INSERT INTO `report_pdfs` VALUES ('01K40JQR4P59N1GRFWNY5V3818','01K40JQN6MTCVJC5NQZNRGETDE','monthly_sales',2025,7,'reports/202507/placeholder.pdf',NULL,'2025-08-21 17:27:56','2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQR4T5Q6CN8MX611FTVP7','01K40JQN6R6SJ6724FRFA3TVS6','monthly_sales',2025,7,'reports/202507/placeholder.pdf',NULL,'2025-08-26 17:27:56','2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQR4YR65VRAKT683XJ2PG','01K40JQN6W55YXQ6AKPEZBZ59V','monthly_sales',2025,7,'reports/202507/placeholder.pdf',NULL,'2025-08-29 17:27:56','2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40KPF48NZVW6PPY9J9MD8F8','01K40JQN6MTCVJC5NQZNRGETDE','monthly_sales',2025,8,'reports/monthly/2025/08/monthly-2025-08-01K40KPEW3TV5WZXTE9FMCT6XB.pdf',NULL,'2025-08-31 17:44:43','2025-08-31 17:44:43','2025-08-31 17:44:43');
/*!40000 ALTER TABLE `report_pdfs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_has_permissions`
--

DROP TABLE IF EXISTS `role_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_has_permissions`
--

LOCK TABLES `role_has_permissions` WRITE;
/*!40000 ALTER TABLE `role_has_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `role_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'admin','web','2025-08-31 17:27:52','2025-08-31 17:27:52'),(2,'warehouse','web','2025-08-31 17:27:52','2025-08-31 17:27:52'),(3,'fleet','web','2025-08-31 17:27:52','2025-08-31 17:27:52'),(4,'franchisee','web','2025-08-31 17:27:52','2025-08-31 17:27:52'),(5,'tech','web','2025-08-31 17:42:48','2025-08-31 17:42:48'),(6,'applicant','web','2025-08-31 17:42:48','2025-08-31 17:42:48');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sale_lines`
--

DROP TABLE IF EXISTS `sale_lines`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sale_lines` (
  `id` char(26) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sale_id` char(26) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stock_item_id` char(26) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qty` int unsigned NOT NULL,
  `unit_price_cents` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sale_lines_sale_id_foreign` (`sale_id`),
  KEY `sale_lines_stock_item_id_foreign` (`stock_item_id`),
  CONSTRAINT `sale_lines_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE,
  CONSTRAINT `sale_lines_stock_item_id_foreign` FOREIGN KEY (`stock_item_id`) REFERENCES `stock_items` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sale_lines`
--

LOCK TABLES `sale_lines` WRITE;
/*!40000 ALTER TABLE `sale_lines` DISABLE KEYS */;
INSERT INTO `sale_lines` VALUES ('01K40JQNJJBWKKF02GHH26Y9QT','01K40JQNJD1XHQM8CRTTHRR5TQ','01K40JQMS0C7WQR3NVTN3RGQ7X',3,7950,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNJSNS8Z1XJ7TQXZTSS2','01K40JQNJD1XHQM8CRTTHRR5TQ','01K40JQMTPFA6ZW3B0KXQWEGDQ',3,7733,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNJXB9RDM29PWP35V387','01K40JQNJD1XHQM8CRTTHRR5TQ','01K40JQMVQAJ49T6GGRVH3KGZS',5,10510,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNK8XFB02TP7WWJNH29J','01K40JQNK3DW8FQ5YQPQY3R3WY','01K40JQMRQAD2R066JMJPAY6CX',2,13895,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNKC2ZVDABSYTFN0Z5F5','01K40JQNK3DW8FQ5YQPQY3R3WY','01K40JQMSQFFJE8RP0HKB1D570',5,8622,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNKGZ0BDF3T6M41R3M8G','01K40JQNK3DW8FQ5YQPQY3R3WY','01K40JQMVAVN57GGGTK1C8HB9C',6,4205,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNKNR3E5KPP11HG7FATB','01K40JQNK3DW8FQ5YQPQY3R3WY','01K40JQMVW1SVZMYHPX6YPTEZ2',3,10423,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNM5VNAPSSHHB9XMZGQ2','01K40JQNKYNWPPX05JP2DFF3GJ','01K40JQMS35DSZ5Y89XJK02YSY',3,5517,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNMBN9RCHF0T1SS6EJ05','01K40JQNKYNWPPX05JP2DFF3GJ','01K40JQMSEP3JMZ8D1VTVVY1Q8',6,2579,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNMEHNGRHQK3FX3WB40H','01K40JQNKYNWPPX05JP2DFF3GJ','01K40JQMV1RCV1SQRQ49HZK8CY',8,8533,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNMJFRQR69KPMQ36M8DB','01K40JQNKYNWPPX05JP2DFF3GJ','01K40JQMVAVN57GGGTK1C8HB9C',3,3588,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNMW2QB2NH3M144YZJCP','01K40JQNMSDEM7RTJWJ44F5BQ1','01K40JQMSY930MSDZK29TWM94H',6,13763,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNN0ZSRQQHMBH3DQ9YDB','01K40JQNMSDEM7RTJWJ44F5BQ1','01K40JQMVH7FZZM9C1V7T15NBR',3,10858,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNNBFF598Z310D8YMB1D','01K40JQNN7YJR4W5JC03JTZA0N','01K40JQMS6XGK1X01HT0BDY76X',6,8660,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNNEQXCWM9WDW86RDG0H','01K40JQNN7YJR4W5JC03JTZA0N','01K40JQMTKZ0RR2N980DBE3MG8',8,11253,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNNJBXWZ0NJGMSSQNZP4','01K40JQNN7YJR4W5JC03JTZA0N','01K40JQMTPFA6ZW3B0KXQWEGDQ',4,9072,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNNNR1A8J896NTXZ029P','01K40JQNN7YJR4W5JC03JTZA0N','01K40JQMV70JVWE49MD9FHAW65',2,11340,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNP074HJ6116A28Q33NB','01K40JQNNWYPP0R7SFZEE186HQ','01K40JQMSAE0Z3KHAVJBYT54W4',4,13046,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNP4R854NE16N630RGWQ','01K40JQNNWYPP0R7SFZEE186HQ','01K40JQMSQFFJE8RP0HKB1D570',2,10695,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNPG48MZAY8MTY6YMMT1','01K40JQNPB6RGBWCSRJD24ZDT4','01K40JQMSAE0Z3KHAVJBYT54W4',7,17124,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNPTQMWFA8QSW6HYHFWS','01K40JQNPQGFX00H6TNJVEBB72','01K40JQMTTYWX9W7NM29RTE9FQ',2,5661,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNQ3X1EVPTR5YCJ54M9G','01K40JQNQ0EGA349ER27XG9B1J','01K40JQMSK0B7BXPK0XZTD0XZB',7,4659,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNQ6FFYPBC6DH7Q79JY6','01K40JQNQ0EGA349ER27XG9B1J','01K40JQMT69WBT1PYNSPGRXE38',2,1840,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNQH0NNHK9RNPNCVZ7NP','01K40JQNQDXSR1X9X7TWSM1YMN','01K40JQMVZ7J7HMPV396JKD1ZC',8,14273,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNQVPZPZ971DHZB8PNVV','01K40JQNQQ8NJXDM8EFFCX46JG','01K40JQMSAE0Z3KHAVJBYT54W4',3,15618,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNQYTGX56Z6PB0TQR4WE','01K40JQNQQ8NJXDM8EFFCX46JG','01K40JQMTPFA6ZW3B0KXQWEGDQ',3,10419,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNR2QSBAATJ6E4E9KYXQ','01K40JQNQQ8NJXDM8EFFCX46JG','01K40JQMV1RCV1SQRQ49HZK8CY',4,9132,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNR6ZXRQZN2MGKK9W7JQ','01K40JQNQQ8NJXDM8EFFCX46JG','01K40JQMVAVN57GGGTK1C8HB9C',6,3912,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNRG0DK0KP9SC0M8A6CT','01K40JQNRC3YFV02KBWHFKK8B6','01K40JQMSV8ACVYVDKDRPC18YG',3,5368,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNRVF7JGE53MCMVXZRT9','01K40JQNRRFKH9JX60MF7VA7FK','01K40JQMT69WBT1PYNSPGRXE38',5,2401,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNS53TAP54KNAQD204CF','01K40JQNS2R6TMB6EJRDT2HKT3','01K40JQMSEP3JMZ8D1VTVVY1Q8',1,2095,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNS9BDZKMNH4A5027FNC','01K40JQNS2R6TMB6EJRDT2HKT3','01K40JQMV1RCV1SQRQ49HZK8CY',1,9202,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNSC38K1A0BDXY6Q3NG7','01K40JQNS2R6TMB6EJRDT2HKT3','01K40JQMVZ7J7HMPV396JKD1ZC',1,15676,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNSNJ9FZFM27ATWTNGJZ','01K40JQNSJWSYGPT7FR6K577JT','01K40JQMSY930MSDZK29TWM94H',7,15543,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNSSHKPA76DG06XPH6WS','01K40JQNSJWSYGPT7FR6K577JT','01K40JQMTG7NR85FH40JV3P47W',8,16092,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNSWS3Y8JJ16TDPDHV5Z','01K40JQNSJWSYGPT7FR6K577JT','01K40JQMV4367TRSN828TX5NB0',2,1110,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNT6AW5J2SYZ25RP0FSB','01K40JQNT2CKJE5JW9E730S1JS','01K40JQMTKZ0RR2N980DBE3MG8',7,12693,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNTFT9RHXTV0FCG1B6MV','01K40JQNTC4XARQJ92G8686P2B','01K40JQMTG7NR85FH40JV3P47W',6,13798,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNTJP2QA872X60GW3HB4','01K40JQNTC4XARQJ92G8686P2B','01K40JQMTTYWX9W7NM29RTE9FQ',3,5936,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNTQB5926J5YMKEZQA09','01K40JQNTC4XARQJ92G8686P2B','01K40JQMVH7FZZM9C1V7T15NBR',5,9327,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNTVC9CQES6Y41MSXSE1','01K40JQNTC4XARQJ92G8686P2B','01K40JQMVW1SVZMYHPX6YPTEZ2',8,10341,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNV6EEHD8ABMBRPHDDEY','01K40JQNV2P7C0BQP7K8WSZPY4','01K40JQMV4367TRSN828TX5NB0',2,1061,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNVFAMT6JKH2689EP77W','01K40JQNVC657C9PN6V43F8YES','01K40JQMSK0B7BXPK0XZTD0XZB',6,5107,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNVJA4VW39G2JAXQMN4H','01K40JQNVC657C9PN6V43F8YES','01K40JQMTDDC5V7N4ASHMCBEPP',7,6681,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNVXFSPKGQQZ21FDRR8Y','01K40JQNVSJ6NRA7WG2CTN1G95','01K40JQMVW1SVZMYHPX6YPTEZ2',4,8180,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNW7BWE0P4H39TH9ZJHG','01K40JQNW34DWREVHX79ZPYBMM','01K40JQMVW1SVZMYHPX6YPTEZ2',3,10860,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNWHCPNG0E7F5S2VTYR1','01K40JQNWEKCY7RAQN1PMM0JJ3','01K40JQMT2BFFVRXWAE4HFRBPS',3,11927,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNWMV8PR17JEHKQMY9N7','01K40JQNWEKCY7RAQN1PMM0JJ3','01K40JQMVDV6EEJYY6J4PBG85X',5,9970,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNX1SQEQ4BYKCE6S3ZZA','01K40JQNWV75T090T2HM2AE3ZF','01K40JQMS35DSZ5Y89XJK02YSY',6,6591,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNX5GDYYVVK2V580C3TA','01K40JQNWV75T090T2HM2AE3ZF','01K40JQMTKZ0RR2N980DBE3MG8',3,11089,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNXHV271FSE0ZQAK5TNH','01K40JQNXD4KVA8A7XFAB2J6VS','01K40JQMV1RCV1SQRQ49HZK8CY',4,8191,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNXV1FYGZHVGYZWDNS4T','01K40JQNXR1H8R594BRCPSTEWC','01K40JQMTTYWX9W7NM29RTE9FQ',5,7588,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNY0MWMBNTPKFH2TJYX6','01K40JQNXR1H8R594BRCPSTEWC','01K40JQMVW1SVZMYHPX6YPTEZ2',6,9243,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNYB7CXBZPK6R41GQ9GN','01K40JQNY7W9GRYFXJ0K9XHQ1P','01K40JQMSV8ACVYVDKDRPC18YG',8,5703,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNYEM1ZVTSP2VCR8A1BQ','01K40JQNY7W9GRYFXJ0K9XHQ1P','01K40JQMT9PC1QRAN3A9EDVE3E',3,10305,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNYJ1BDJ0WK3E14P62Z8','01K40JQNY7W9GRYFXJ0K9XHQ1P','01K40JQMTG7NR85FH40JV3P47W',3,12024,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNYNPZ3QCF1JD9WHFSWD','01K40JQNY7W9GRYFXJ0K9XHQ1P','01K40JQMTPFA6ZW3B0KXQWEGDQ',3,7107,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNZ1SB41H4S00V8PV4M1','01K40JQNYX6PRBKGFPB14PDVAD','01K40JQMSY930MSDZK29TWM94H',5,11312,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNZ6W5NE2JE53S69TYDY','01K40JQNYX6PRBKGFPB14PDVAD','01K40JQMT2BFFVRXWAE4HFRBPS',5,9269,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNZJJZKZQGXVTTK7HYE4','01K40JQNZFF249YYP4S4ES828A','01K40JQMTXF0W5N9F59MD488AD',3,6550,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNZNMFHRBXVQSPBXB2X2','01K40JQNZFF249YYP4S4ES828A','01K40JQMVAVN57GGGTK1C8HB9C',5,4816,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNZSFX1TCABG6RJ6KA09','01K40JQNZFF249YYP4S4ES828A','01K40JQMVH7FZZM9C1V7T15NBR',7,12170,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQP03YRE7SBKM9XS06PD1','01K40JQP00S2BA6PFWMGQ9CASK','01K40JQMS35DSZ5Y89XJK02YSY',2,5839,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQP06M4TQRBAE0DXK31CX','01K40JQP00S2BA6PFWMGQ9CASK','01K40JQMSV8ACVYVDKDRPC18YG',6,5721,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQP0A96MWR9D0DJ07221Z','01K40JQP00S2BA6PFWMGQ9CASK','01K40JQMVMRMVW5B0EZTT5DKSY',7,2425,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQP0N03E12KHD6QKKTJRE','01K40JQP0H4PT1N9N1KM9RQZVR','01K40JQMRQAD2R066JMJPAY6CX',7,10396,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQP0SVD3MFWM03MD4Z256','01K40JQP0H4PT1N9N1KM9RQZVR','01K40JQMT69WBT1PYNSPGRXE38',5,2324,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQP0XP85J6047RM2C49D4','01K40JQP0H4PT1N9N1KM9RQZVR','01K40JQMTPFA6ZW3B0KXQWEGDQ',3,7872,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQP183FRDD8VAQP9K1PPX','01K40JQP14C40C8FX34H9XF150','01K40JQMSQFFJE8RP0HKB1D570',6,12047,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQP1BSWGEJ4KPXPXH8GWJ','01K40JQP14C40C8FX34H9XF150','01K40JQMTG7NR85FH40JV3P47W',6,14150,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQP1G7BWS40XB0W8WC8YF','01K40JQP14C40C8FX34H9XF150','01K40JQMV1RCV1SQRQ49HZK8CY',1,9703,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQP1WT4K5CX5DB21XHS0X','01K40JQP1RB642M6JBWZ153FRM','01K40JQMTXF0W5N9F59MD488AD',3,5888,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQP27DPMJ1JJSPRED19K9','01K40JQP232CKYW59VNM5KZJK2','01K40JQMVZ7J7HMPV396JKD1ZC',7,12455,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQP2GNEVZXSRJQDYEJW0E','01K40JQP2D3HZVEA1NHR4VDYZT','01K40JQMSK0B7BXPK0XZTD0XZB',8,4735,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQP2M7TFHFBAZZG940AQA','01K40JQP2D3HZVEA1NHR4VDYZT','01K40JQMSV8ACVYVDKDRPC18YG',8,4489,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQP2Y78C9RWQ78QEX87D1','01K40JQP2T4QEQM2B6P7NVEN14','01K40JQMTKZ0RR2N980DBE3MG8',2,10019,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQP324XWJH5172WQPGFCW','01K40JQP2T4QEQM2B6P7NVEN14','01K40JQMVDV6EEJYY6J4PBG85X',7,12852,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQP35T63XSTJQWPTTX0FY','01K40JQP2T4QEQM2B6P7NVEN14','01K40JQMVH7FZZM9C1V7T15NBR',6,12383,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQP3E9D7R2KBPEZ6SE0YF','01K40JQP3BAJVGGQP4585QW2EN','01K40JQMSEP3JMZ8D1VTVVY1Q8',2,2339,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQP3HCE3V8TEG53V96TZ1','01K40JQP3BAJVGGQP4585QW2EN','01K40JQMSQFFJE8RP0HKB1D570',5,8853,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQP3NMD7AB032VMSQGVAS','01K40JQP3BAJVGGQP4585QW2EN','01K40JQMT2BFFVRXWAE4HFRBPS',6,10986,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQP42YSWXABN8RTZ49Q0B','01K40JQP3Y0W4SNQ52NWNX4QY6','01K40JQMSK0B7BXPK0XZTD0XZB',6,5156,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQP45JFJ9NPM12N542KTD','01K40JQP3Y0W4SNQ52NWNX4QY6','01K40JQMV70JVWE49MD9FHAW65',8,11441,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQP49686J4GT95XJ5HRXX','01K40JQP3Y0W4SNQ52NWNX4QY6','01K40JQMVQAJ49T6GGRVH3KGZS',6,10353,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQP4KKNRN95KXYTYKSBNF','01K40JQP4FH1Y8SWSKWRT8YDE3','01K40JQMRQAD2R066JMJPAY6CX',1,10308,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQP4P8Q43KD5BK2VXFA1J','01K40JQP4FH1Y8SWSKWRT8YDE3','01K40JQMSY930MSDZK29TWM94H',3,14215,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQP514NW1JH8P8376V5BV','01K40JQP4XMJNSCDJVPJT3558F','01K40JQMTKZ0RR2N980DBE3MG8',8,13836,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQP5B4VYN65EMX5EHMWY8','01K40JQP57CVFRPGA8ZNFF487T','01K40JQMVDV6EEJYY6J4PBG85X',6,11372,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQP5NZJX50DR68BBR6K1S','01K40JQP5HGA0V8BRX11N9E3H3','01K40JQMS6XGK1X01HT0BDY76X',3,12125,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQP5SRP4C948XJNBEB10E','01K40JQP5HGA0V8BRX11N9E3H3','01K40JQMSK0B7BXPK0XZTD0XZB',7,4203,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQP5WTDXPF6ZVC5ZVT5M9','01K40JQP5HGA0V8BRX11N9E3H3','01K40JQMSV8ACVYVDKDRPC18YG',8,4279,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQP61B5M4JNW05GKX7V2H','01K40JQP5HGA0V8BRX11N9E3H3','01K40JQMVMRMVW5B0EZTT5DKSY',8,2283,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQP6C36PS5B2NPAKGEJNQ','01K40JQP69EE4Z2EB2DQT11TR0','01K40JQMSEP3JMZ8D1VTVVY1Q8',5,2115,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQP6FTW9X2PAA2VV1SFS0','01K40JQP69EE4Z2EB2DQT11TR0','01K40JQMTXF0W5N9F59MD488AD',2,4891,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQP6J0EBPVYDNWJFJG4YK','01K40JQP69EE4Z2EB2DQT11TR0','01K40JQMVW1SVZMYHPX6YPTEZ2',2,8407,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQP6WAJ7EZCBAB19S6TBN','01K40JQP6RNPHJ5REZDSB3XHPT','01K40JQMT2BFFVRXWAE4HFRBPS',2,11983,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQP6ZAVQZ7P7TMY2RHYBV','01K40JQP6RNPHJ5REZDSB3XHPT','01K40JQMTXF0W5N9F59MD488AD',7,5815,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQP74TVT64DDCYYN70Q9Z','01K40JQP6RNPHJ5REZDSB3XHPT','01K40JQMV70JVWE49MD9FHAW65',2,15109,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQP78V8GHD8ZFVHHPR1HQ','01K40JQP6RNPHJ5REZDSB3XHPT','01K40JQMVZ7J7HMPV396JKD1ZC',5,10599,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQP7J0F715AZSQD9XBK4Q','01K40JQP7F9DCFBJDHKDGGPGTD','01K40JQMT2BFFVRXWAE4HFRBPS',3,10213,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQP7PF38KQHFY47R36YPN','01K40JQP7F9DCFBJDHKDGGPGTD','01K40JQMV4367TRSN828TX5NB0',5,1150,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQP80FXFEP73NX10GT74K','01K40JQP7WXGN4JM4HK4BMV5ZQ','01K40JQMSAE0Z3KHAVJBYT54W4',3,15508,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQP83MBB13JDDC9495G9J','01K40JQP7WXGN4JM4HK4BMV5ZQ','01K40JQMSEP3JMZ8D1VTVVY1Q8',3,3049,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQP88NQ6G9KXV2WTDVB0E','01K40JQP7WXGN4JM4HK4BMV5ZQ','01K40JQMTXF0W5N9F59MD488AD',1,4681,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQP8KJZF3ZGK6MXEC8CQT','01K40JQP8FYYZ20PHR3XQYRFT7','01K40JQMS0C7WQR3NVTN3RGQ7X',6,10114,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQP8P67T389K4SVQC4K9H','01K40JQP8FYYZ20PHR3XQYRFT7','01K40JQMTG7NR85FH40JV3P47W',7,15625,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQP8TK23YMM39PFFZ4TW4','01K40JQP8FYYZ20PHR3XQYRFT7','01K40JQMVMRMVW5B0EZTT5DKSY',5,1770,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQP935S5HH3EX36JQV63R','01K40JQP907Q2X9H0E4HD07FTY','01K40JQMS0C7WQR3NVTN3RGQ7X',2,8575,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQP97H7PPXWBV1A441YJM','01K40JQP907Q2X9H0E4HD07FTY','01K40JQMT69WBT1PYNSPGRXE38',4,2276,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQP9HWGWY64MNADJJ6JCE','01K40JQP9DC23N5GKKPR66CEGS','01K40JQMTTYWX9W7NM29RTE9FQ',4,7444,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQP9M747DPNC46YE03BWA','01K40JQP9DC23N5GKKPR66CEGS','01K40JQMV4367TRSN828TX5NB0',2,842,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQP9Z2X5CS7FT8CDHP4VG','01K40JQP9WW2PK8QEB4V3R6AE3','01K40JQMRW90SACGNZ481031VF',2,11478,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQPA28AG1FG3XA4KQWH14','01K40JQP9WW2PK8QEB4V3R6AE3','01K40JQMS35DSZ5Y89XJK02YSY',3,5617,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQPA5VDW33WGT5YZGJNVC','01K40JQP9WW2PK8QEB4V3R6AE3','01K40JQMTKZ0RR2N980DBE3MG8',1,12850,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQPA9HYVS803R3775JBA7','01K40JQP9WW2PK8QEB4V3R6AE3','01K40JQMTPFA6ZW3B0KXQWEGDQ',5,7698,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQPANJ5SXFE7YSQ4AWVXV','01K40JQPAJD0974THX6W2DXS2X','01K40JQMSAE0Z3KHAVJBYT54W4',1,14843,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQPASD8D32V64CCVMKCA4','01K40JQPAJD0974THX6W2DXS2X','01K40JQMV4367TRSN828TX5NB0',5,1012,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQPB2BF6WAQGN72H69ABN','01K40JQPAZJQKTT5GMJTXM1R26','01K40JQMVMRMVW5B0EZTT5DKSY',2,2209,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQPBD8ZN8TPVZDT9RCHH3','01K40JQPB98EG7C0PZPS2FRDWC','01K40JQMRW90SACGNZ481031VF',5,9382,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQPBVM03GXVFTQT61EFBP','01K40JQPB98EG7C0PZPS2FRDWC','01K40JQMTDDC5V7N4ASHMCBEPP',8,6843,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPBZGPCFNTS6JVNY0JPD','01K40JQPB98EG7C0PZPS2FRDWC','01K40JQMVAVN57GGGTK1C8HB9C',5,3559,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPC3FNDDCH4XX8RWEG9D','01K40JQPB98EG7C0PZPS2FRDWC','01K40JQMVQAJ49T6GGRVH3KGZS',1,8360,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPCEKDHMJ8AHSPSWSBT3','01K40JQPCAFS8WGKW3ZNJA3BEV','01K40JQMS6XGK1X01HT0BDY76X',3,12448,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPCJEDWB3DR44ZTCVTMR','01K40JQPCAFS8WGKW3ZNJA3BEV','01K40JQMTPFA6ZW3B0KXQWEGDQ',4,8728,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPCPZRXATJ0W9F0VK350','01K40JQPCAFS8WGKW3ZNJA3BEV','01K40JQMVMRMVW5B0EZTT5DKSY',3,2322,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPCS6JEGBVND0G2RYXBP','01K40JQPCAFS8WGKW3ZNJA3BEV','01K40JQMVZ7J7HMPV396JKD1ZC',3,15424,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPD5C9S85PPA6DKGGWMB','01K40JQPD0YC2ZVV3V5NTHJXDB','01K40JQMSEP3JMZ8D1VTVVY1Q8',6,2969,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPDACH7Q8REWPDB6RAYG','01K40JQPD0YC2ZVV3V5NTHJXDB','01K40JQMT9PC1QRAN3A9EDVE3E',8,10202,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPDNYHVXR46R0HTBZ3F9','01K40JQPDJ670J0WWJN658RHRK','01K40JQMS0C7WQR3NVTN3RGQ7X',6,8155,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPDZXV4NSWJYK3J3K1N5','01K40JQPDWC5FYJ9QPVD0ZE0VA','01K40JQMS0C7WQR3NVTN3RGQ7X',7,10131,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPE2613Z687EP7K34517','01K40JQPDWC5FYJ9QPVD0ZE0VA','01K40JQMT9PC1QRAN3A9EDVE3E',7,9892,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPE507QT46ZT71N8X32D','01K40JQPDWC5FYJ9QPVD0ZE0VA','01K40JQMVQAJ49T6GGRVH3KGZS',8,10810,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPEE4EP6DJ3BXK9R2Q1T','01K40JQPEBKMWXH7SG6D9R0AJ4','01K40JQMSEP3JMZ8D1VTVVY1Q8',6,2317,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPEJX6ZV0JSACDQVM0Z6','01K40JQPEBKMWXH7SG6D9R0AJ4','01K40JQMT69WBT1PYNSPGRXE38',8,1859,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPEPMT8EY9K4ZDZEMDFX','01K40JQPEBKMWXH7SG6D9R0AJ4','01K40JQMT9PC1QRAN3A9EDVE3E',6,7600,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPES7HMMRYRHMNAP964W','01K40JQPEBKMWXH7SG6D9R0AJ4','01K40JQMVQAJ49T6GGRVH3KGZS',1,9706,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPF3M14X98S28Z1HKD6M','01K40JQPF0Y60GTQAF3889HGVZ','01K40JQMT9PC1QRAN3A9EDVE3E',2,7453,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPFF5H3CEMTH2V4XW2QT','01K40JQPFB2VY5B02R6SCFR70Z','01K40JQMVMRMVW5B0EZTT5DKSY',4,2546,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPFT6KSRA0V34RMM5H9M','01K40JQPFPJ66EPS6TDZEY3W4G','01K40JQMRQAD2R066JMJPAY6CX',2,13212,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPFX58K83QE6A706W9YW','01K40JQPFPJ66EPS6TDZEY3W4G','01K40JQMS35DSZ5Y89XJK02YSY',8,6661,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPG1VKEVHZPEDXDV8GJ2','01K40JQPFPJ66EPS6TDZEY3W4G','01K40JQMV70JVWE49MD9FHAW65',7,11914,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPG5KVZKVKVJDT00W54J','01K40JQPFPJ66EPS6TDZEY3W4G','01K40JQMVQAJ49T6GGRVH3KGZS',5,9419,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPGEMGE9QK3ZSR1GWZ7A','01K40JQPGBSY2D66P6A5TS2J7A','01K40JQMSV8ACVYVDKDRPC18YG',4,6225,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPGJCDMX3F3KHB7SJT2Y','01K40JQPGBSY2D66P6A5TS2J7A','01K40JQMVAVN57GGGTK1C8HB9C',5,3968,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPGV264J3FQ5T4Z0JR61','01K40JQPGRCAJFFSGSCY0FR9HP','01K40JQMVQAJ49T6GGRVH3KGZS',8,10555,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPH502VBJHHCGGDCBCK9','01K40JQPH1G0JGWPKHDSGRSEM0','01K40JQMTDDC5V7N4ASHMCBEPP',2,5678,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPHH98112VQQAV1CYHCP','01K40JQPHCYS9WZ4YAN4HH8N9T','01K40JQMS35DSZ5Y89XJK02YSY',6,6338,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPHMVMWB4RDXSWKZBTH3','01K40JQPHCYS9WZ4YAN4HH8N9T','01K40JQMTPFA6ZW3B0KXQWEGDQ',1,9763,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPHR083YBYR9QFRJ61XN','01K40JQPHCYS9WZ4YAN4HH8N9T','01K40JQMVDV6EEJYY6J4PBG85X',6,9354,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPJ2G2WX2N1B9M5NNTS4','01K40JQPHZ0YME7YG7JRQF9455','01K40JQMT2BFFVRXWAE4HFRBPS',3,12892,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPJ67EAE1B2QXACSZTBN','01K40JQPHZ0YME7YG7JRQF9455','01K40JQMV4367TRSN828TX5NB0',1,816,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPJHDKGZAMWPJYVFYGMG','01K40JQPJDBTDS3XXK85Z7ZKXZ','01K40JQMS6XGK1X01HT0BDY76X',1,11562,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPJQAHMH07TBAG92K954','01K40JQPJDBTDS3XXK85Z7ZKXZ','01K40JQMSQFFJE8RP0HKB1D570',4,8505,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPKAGX20A1GNNE2NCN5R','01K40JQPK2M6C3ZHS3RB64SSYB','01K40JQMVH7FZZM9C1V7T15NBR',5,11842,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPKFRJ6GEMR6CGRC62NG','01K40JQPK2M6C3ZHS3RB64SSYB','01K40JQMVMRMVW5B0EZTT5DKSY',6,2220,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPKY31TBZ0R1JGE503AD','01K40JQPKSBW545A2JBFNNGNBK','01K40JQMSQFFJE8RP0HKB1D570',3,8515,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPM9PNFVPH8XH10RVDYM','01K40JQPM543PFK7Z2SP6AZA9P','01K40JQMSK0B7BXPK0XZTD0XZB',3,4436,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPMD6W336N1E02G8RK0Y','01K40JQPM543PFK7Z2SP6AZA9P','01K40JQMT9PC1QRAN3A9EDVE3E',2,9693,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPMRGJHX5NWA1952XXFT','01K40JQPMN9DBR2XB39MZ6ZEG9','01K40JQMRW90SACGNZ481031VF',1,12968,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPMWV08GWNBS85Y4E21M','01K40JQPMN9DBR2XB39MZ6ZEG9','01K40JQMSAE0Z3KHAVJBYT54W4',6,14575,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPN0DMMKDBAKX5J9S7VD','01K40JQPMN9DBR2XB39MZ6ZEG9','01K40JQMV1RCV1SQRQ49HZK8CY',5,10165,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPNC3XK8DBPGHT6K7JSP','01K40JQPN84E1DK6Y7P9J99Z7G','01K40JQMSK0B7BXPK0XZTD0XZB',4,4938,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPNQJQNCV1STW47P507Q','01K40JQPNMVZ6M8127EDZJ2BGX','01K40JQMS0C7WQR3NVTN3RGQ7X',2,10050,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPNTX6G5SQBYZNZ084Z7','01K40JQPNMVZ6M8127EDZJ2BGX','01K40JQMV4367TRSN828TX5NB0',6,819,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPNXNK24NKKPCF53QJW1','01K40JQPNMVZ6M8127EDZJ2BGX','01K40JQMVH7FZZM9C1V7T15NBR',4,8977,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPP7Z282GX8TYKPTQCQ1','01K40JQPP3BTR1C8X6HMPPT6GM','01K40JQMSEP3JMZ8D1VTVVY1Q8',6,2919,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPPBXC1AT9ZTYSY7T3Y2','01K40JQPP3BTR1C8X6HMPPT6GM','01K40JQMV1RCV1SQRQ49HZK8CY',1,7603,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPPMM44KWN4ENBZR2SFB','01K40JQPPH23PXW8B2KWYNG9HV','01K40JQMSV8ACVYVDKDRPC18YG',5,5402,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPPQHZCMFSDS4QAVAF84','01K40JQPPH23PXW8B2KWYNG9HV','01K40JQMTDDC5V7N4ASHMCBEPP',2,5428,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPPTJ47CNQH2KW3F9G6Q','01K40JQPPH23PXW8B2KWYNG9HV','01K40JQMTXF0W5N9F59MD488AD',7,6492,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPQ3SYVBPAQH2TAT8SBD','01K40JQPQ0DTXJYB2GSBZ2QZA6','01K40JQMSK0B7BXPK0XZTD0XZB',3,5597,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPQ8B2HB5WHMKY6QWWN4','01K40JQPQ0DTXJYB2GSBZ2QZA6','01K40JQMTG7NR85FH40JV3P47W',2,17031,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPQJ62PRV00TN8NNDR9H','01K40JQPQFFMB14B88WJXZ699A','01K40JQMSEP3JMZ8D1VTVVY1Q8',1,2560,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPQPGKBSSCGT33PP15NX','01K40JQPQFFMB14B88WJXZ699A','01K40JQMVAVN57GGGTK1C8HB9C',5,3533,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPQZ6VVVCGP6SH7RM9SZ','01K40JQPQWWRB4EP5TBEVSX8VS','01K40JQMS35DSZ5Y89XJK02YSY',8,5753,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPR234XSD6GNME8F29N1','01K40JQPQWWRB4EP5TBEVSX8VS','01K40JQMSY930MSDZK29TWM94H',5,16115,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPR5K3WQ5V18R4DCABTS','01K40JQPQWWRB4EP5TBEVSX8VS','01K40JQMT9PC1QRAN3A9EDVE3E',8,8863,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPR82PHH01BJP1XBWKZZ','01K40JQPQWWRB4EP5TBEVSX8VS','01K40JQMV1RCV1SQRQ49HZK8CY',4,10203,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPRJZ3J74GHC07GMM62G','01K40JQPRFGHG70Q27FE2AV4WK','01K40JQMSAE0Z3KHAVJBYT54W4',4,12248,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPRNWFBGRTJ08E50HMNE','01K40JQPRFGHG70Q27FE2AV4WK','01K40JQMT69WBT1PYNSPGRXE38',8,2164,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPRREB4KAVZMYSAHY6CN','01K40JQPRFGHG70Q27FE2AV4WK','01K40JQMTG7NR85FH40JV3P47W',8,11833,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPRV6ZKED1FQ86TC1YN6','01K40JQPRFGHG70Q27FE2AV4WK','01K40JQMV1RCV1SQRQ49HZK8CY',2,9607,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPS6MTGATGPVH8W5D4KX','01K40JQPS29BBVHZF80ENBJK9F','01K40JQMSAE0Z3KHAVJBYT54W4',1,12027,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPSBNR1WN2EBW9NZ4PWT','01K40JQPS29BBVHZF80ENBJK9F','01K40JQMVH7FZZM9C1V7T15NBR',7,8515,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPSGAW4MBKDRQTD0GW0Y','01K40JQPS29BBVHZF80ENBJK9F','01K40JQMVW1SVZMYHPX6YPTEZ2',4,8964,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPST0BXC2JVVFD1TQF94','01K40JQPSPQPV3P8JV60DHMVHR','01K40JQMRQAD2R066JMJPAY6CX',6,12649,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPT4C8D1W99A5ZSQQ82W','01K40JQPT1APSQDF7B71ZX4AJQ','01K40JQMRQAD2R066JMJPAY6CX',1,12702,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPT7GD18BFPAQB9VC5BE','01K40JQPT1APSQDF7B71ZX4AJQ','01K40JQMSQFFJE8RP0HKB1D570',8,8253,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPTA5VCNAZB58P8X7GF3','01K40JQPT1APSQDF7B71ZX4AJQ','01K40JQMTPFA6ZW3B0KXQWEGDQ',2,7621,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPTD65F8HZ2HVTPS4H4V','01K40JQPT1APSQDF7B71ZX4AJQ','01K40JQMVQAJ49T6GGRVH3KGZS',3,7985,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPTT84J5NGXHCM3SAZ8W','01K40JQPTPH2MMA7W0N84AVHGV','01K40JQMTG7NR85FH40JV3P47W',6,11659,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPV3CGE2HPQK3XBJT6SN','01K40JQPTZZ1Q25Q2K8SGX8MMJ','01K40JQMSY930MSDZK29TWM94H',3,11319,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPV726HG08XKXQ6MM6H7','01K40JQPTZZ1Q25Q2K8SGX8MMJ','01K40JQMT9PC1QRAN3A9EDVE3E',8,9212,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPVK66QTPMC6NKCCCY3B','01K40JQPVE1HGCPYW6J86BQBPD','01K40JQMS35DSZ5Y89XJK02YSY',3,5455,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPVQ6K67ZENKK1GVZ0NG','01K40JQPVE1HGCPYW6J86BQBPD','01K40JQMS6XGK1X01HT0BDY76X',6,10168,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPVTSRH2T5XMFDHP9RTP','01K40JQPVE1HGCPYW6J86BQBPD','01K40JQMTTYWX9W7NM29RTE9FQ',1,6356,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPVYKQY18YH5SMVQJK5C','01K40JQPVE1HGCPYW6J86BQBPD','01K40JQMVQAJ49T6GGRVH3KGZS',5,8600,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPW7H54T4BFZ5FFWGVZ0','01K40JQPW4D0FMKA673FFC80SN','01K40JQMVAVN57GGGTK1C8HB9C',6,3754,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPWAVDDV3RP4AB1HFQ3N','01K40JQPW4D0FMKA673FFC80SN','01K40JQMVZ7J7HMPV396JKD1ZC',8,10670,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPWK39M0RVMCGPGJAVTD','01K40JQPWGD7BVTNKYNSQ013WF','01K40JQMTKZ0RR2N980DBE3MG8',1,12306,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPWXE2YQBCYCSSW5MN63','01K40JQPWSTB33BJH22XZYMAF5','01K40JQMSY930MSDZK29TWM94H',2,15643,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPX1AZ2ZHBJY8782RD4X','01K40JQPWSTB33BJH22XZYMAF5','01K40JQMTTYWX9W7NM29RTE9FQ',5,7971,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPX468YJM8DRD368DQA9','01K40JQPWSTB33BJH22XZYMAF5','01K40JQMV1RCV1SQRQ49HZK8CY',1,8525,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPX7G6CBF34MPT38MKBP','01K40JQPWSTB33BJH22XZYMAF5','01K40JQMV70JVWE49MD9FHAW65',2,13304,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPXJXZW87NFV1HKDS96C','01K40JQPXEFN11TTS33CR76DC5','01K40JQMRW90SACGNZ481031VF',6,12509,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPY0TQFANXX5BGY89GX4','01K40JQPXWZ5YKW5E3CP1ZW3JR','01K40JQMRW90SACGNZ481031VF',4,12967,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPY3DRZ831F6THPAT2Z6','01K40JQPXWZ5YKW5E3CP1ZW3JR','01K40JQMSEP3JMZ8D1VTVVY1Q8',2,2912,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPY7AM5BWHK62J01SGXV','01K40JQPXWZ5YKW5E3CP1ZW3JR','01K40JQMVAVN57GGGTK1C8HB9C',2,4625,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPYB6VBSR4G7WGGX0ZWS','01K40JQPXWZ5YKW5E3CP1ZW3JR','01K40JQMVH7FZZM9C1V7T15NBR',8,8657,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPYNV1E8VA1M7RZ7HGMJ','01K40JQPYJQJW2YNTS223BCTCX','01K40JQMTXF0W5N9F59MD488AD',7,6120,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPYSXE4W290AHPC3T7M1','01K40JQPYJQJW2YNTS223BCTCX','01K40JQMVQAJ49T6GGRVH3KGZS',1,11189,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPZ3WN0S4Q55173VA8AV','01K40JQPYZK12XWQQENFZM0KQP','01K40JQMS6XGK1X01HT0BDY76X',7,10150,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPZ612E29D5NM1CEC3FD','01K40JQPYZK12XWQQENFZM0KQP','01K40JQMTG7NR85FH40JV3P47W',8,12044,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPZAGCCPQYMFJ7TD7JV2','01K40JQPYZK12XWQQENFZM0KQP','01K40JQMVQAJ49T6GGRVH3KGZS',4,9988,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPZMM2B7S4803PVDNTFG','01K40JQPZHV7FC9GRVB6B2Z0PG','01K40JQMT9PC1QRAN3A9EDVE3E',1,10085,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPZRJMSRNBPDJFHZB8GQ','01K40JQPZHV7FC9GRVB6B2Z0PG','01K40JQMV1RCV1SQRQ49HZK8CY',6,10184,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPZWT9BWQT69V3015M83','01K40JQPZHV7FC9GRVB6B2Z0PG','01K40JQMVMRMVW5B0EZTT5DKSY',5,2494,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQQ01FJBW6Q3XM6CCZ1PG','01K40JQPZHV7FC9GRVB6B2Z0PG','01K40JQMVW1SVZMYHPX6YPTEZ2',3,9378,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQQ0ASH2TZ507R2DX2RKW','01K40JQQ07YAKEG7NVH8AQR7X5','01K40JQMSY930MSDZK29TWM94H',4,16813,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQQ0DFS37TH1EA4HKASJ2','01K40JQQ07YAKEG7NVH8AQR7X5','01K40JQMTDDC5V7N4ASHMCBEPP',5,5803,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQQ0HKCHCFKYX28WKN3HY','01K40JQQ07YAKEG7NVH8AQR7X5','01K40JQMVW1SVZMYHPX6YPTEZ2',1,9537,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQQ0W9PVEF37M41Z0EWN5','01K40JQQ0RDJS4KJW6NH9PCP0C','01K40JQMTG7NR85FH40JV3P47W',7,16589,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQQ10381V2P2RC12C80DP','01K40JQQ0RDJS4KJW6NH9PCP0C','01K40JQMVH7FZZM9C1V7T15NBR',2,8837,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQQ1AP1PSHW4WZT26YTXB','01K40JQQ16JV2DNVHAE6NQZ67Z','01K40JQMS35DSZ5Y89XJK02YSY',6,7096,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQQ1DRWFFHBJ1GC3FJP1Y','01K40JQQ16JV2DNVHAE6NQZ67Z','01K40JQMS6XGK1X01HT0BDY76X',4,12148,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQQ1G9C2HW8ETZ1Y52WH9','01K40JQQ16JV2DNVHAE6NQZ67Z','01K40JQMSEP3JMZ8D1VTVVY1Q8',4,2970,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQQ1KWPMYGX7MRBP43H2R','01K40JQQ16JV2DNVHAE6NQZ67Z','01K40JQMT2BFFVRXWAE4HFRBPS',7,10550,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQQ1XXF361H0Q0Z926VYN','01K40JQQ1S70S1VP5VQEZK896H','01K40JQMS0C7WQR3NVTN3RGQ7X',2,10081,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQQ22GNDQMY5G29XQS9TJ','01K40JQQ1S70S1VP5VQEZK896H','01K40JQMS6XGK1X01HT0BDY76X',1,8887,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQQ26WQZE8DHTT9PF4JJF','01K40JQQ1S70S1VP5VQEZK896H','01K40JQMV70JVWE49MD9FHAW65',3,12745,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQQ2G1R9H4W1A6XGMR895','01K40JQQ2DH78ADPB7FQ7H1P3C','01K40JQMSAE0Z3KHAVJBYT54W4',1,17270,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQQ2MR1ZYJ46E6XKHY5NT','01K40JQQ2DH78ADPB7FQ7H1P3C','01K40JQMV1RCV1SQRQ49HZK8CY',5,7135,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQQ2QCC2EAPGCV7MQN3CP','01K40JQQ2DH78ADPB7FQ7H1P3C','01K40JQMV70JVWE49MD9FHAW65',7,13754,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQQ32AM6TNKNY8N14386R','01K40JQQ2YNF5FH3ZQ7AS0QHEJ','01K40JQMTXF0W5N9F59MD488AD',4,5666,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQQ350MQTHRS09NJYCHPJ','01K40JQQ2YNF5FH3ZQ7AS0QHEJ','01K40JQMVAVN57GGGTK1C8HB9C',7,4232,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQQ38RDKMA42R0GZBAVMN','01K40JQQ2YNF5FH3ZQ7AS0QHEJ','01K40JQMVH7FZZM9C1V7T15NBR',7,11110,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQQ3BMCTZKGFE8NJ9Q1QP','01K40JQQ2YNF5FH3ZQ7AS0QHEJ','01K40JQMVMRMVW5B0EZTT5DKSY',2,2156,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQQ3NSJ2AR9NWK1QGNZ6N','01K40JQQ3HVSHPV59BM60ADJMG','01K40JQMRQAD2R066JMJPAY6CX',3,12727,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQQ3RGX1KZA5VJH0YA10H','01K40JQQ3HVSHPV59BM60ADJMG','01K40JQMSAE0Z3KHAVJBYT54W4',4,16565,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQQ3V6XTFG8BWEWWHAQED','01K40JQQ3HVSHPV59BM60ADJMG','01K40JQMSY930MSDZK29TWM94H',2,15701,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQQ3Y2G0XNTZFFA1671NK','01K40JQQ3HVSHPV59BM60ADJMG','01K40JQMVZ7J7HMPV396JKD1ZC',1,11461,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQQ4A1QPG83X8Y22QQV0F','01K40JQQ46QXK958X77QPD2R41','01K40JQMTPFA6ZW3B0KXQWEGDQ',2,9668,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQQ4WFS9D9N0P9NS6HXZ3','01K40JQQ4PY574P9DFM3R8X8HK','01K40JQMT69WBT1PYNSPGRXE38',3,2253,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQQ4ZFFGCEKW7CYP63BP6','01K40JQQ4PY574P9DFM3R8X8HK','01K40JQMV1RCV1SQRQ49HZK8CY',8,9375,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQQ5ATGFECZ7676XFRTQ2','01K40JQQ57PDMNZZV3ZS3YCZ9A','01K40JQMS35DSZ5Y89XJK02YSY',5,4988,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQQ5EHAHZPJWHNFWE7GW6','01K40JQQ57PDMNZZV3ZS3YCZ9A','01K40JQMSEP3JMZ8D1VTVVY1Q8',6,2633,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQQ5H5P5Q1X0AXGFRJDWF','01K40JQQ57PDMNZZV3ZS3YCZ9A','01K40JQMTPFA6ZW3B0KXQWEGDQ',3,7708,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQQ5N8PH8J5SFM8V2Q08Y','01K40JQQ57PDMNZZV3ZS3YCZ9A','01K40JQMVW1SVZMYHPX6YPTEZ2',7,11622,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQQ5Y424021KYKMGRS923','01K40JQQ5VP9KNE8DWRPTRRHDQ','01K40JQMTXF0W5N9F59MD488AD',7,6336,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQQ692478Y7CWF9YBNTT3','01K40JQQ66VQWPVWDG1XG3WM9Y','01K40JQMSEP3JMZ8D1VTVVY1Q8',2,2874,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQQ6D3JFAEV9NMXC6QZJF','01K40JQQ66VQWPVWDG1XG3WM9Y','01K40JQMT9PC1QRAN3A9EDVE3E',3,8963,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQQ6H5NEN0NT1F36G5CWT','01K40JQQ66VQWPVWDG1XG3WM9Y','01K40JQMVQAJ49T6GGRVH3KGZS',8,10324,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQQ6MGMNPKNCHPHJN8S5J','01K40JQQ66VQWPVWDG1XG3WM9Y','01K40JQMVZ7J7HMPV396JKD1ZC',6,11747,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQQ70V21HN3TNSX7BD73S','01K40JQQ6XPNNWY6RJM1Z2N3P9','01K40JQMTKZ0RR2N980DBE3MG8',6,9327,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQQ74492AY6H7T47D02MG','01K40JQQ6XPNNWY6RJM1Z2N3P9','01K40JQMVDV6EEJYY6J4PBG85X',7,10102,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQQ77HSKCXCCFSSQ6JHJC','01K40JQQ6XPNNWY6RJM1Z2N3P9','01K40JQMVMRMVW5B0EZTT5DKSY',6,1954,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQQ7ADVS2AH5QXNZYP11T','01K40JQQ6XPNNWY6RJM1Z2N3P9','01K40JQMVQAJ49T6GGRVH3KGZS',6,8961,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQQ7KFFNQ3ZNPMJXDVGGG','01K40JQQ7GX7N97D170HE4PYYW','01K40JQMS6XGK1X01HT0BDY76X',7,12357,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQQ7P79Q6B43KCJPWVFSD','01K40JQQ7GX7N97D170HE4PYYW','01K40JQMV4367TRSN828TX5NB0',5,1030,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQQ7S1DXETKWY3M0050VR','01K40JQQ7GX7N97D170HE4PYYW','01K40JQMVDV6EEJYY6J4PBG85X',1,9489,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQQ7X3PZM7TBSZSSDC73C','01K40JQQ7GX7N97D170HE4PYYW','01K40JQMVQAJ49T6GGRVH3KGZS',5,10271,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQQ872ZTQR5WWDFKRWAQ4','01K40JQQ835X88N62TM2T4083P','01K40JQMTKZ0RR2N980DBE3MG8',3,13016,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQQ8BMDKTNSCA6M09N991','01K40JQQ835X88N62TM2T4083P','01K40JQMV70JVWE49MD9FHAW65',4,14959,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQQ8NSMVGSEVAA1D81M0E','01K40JQQ8H1MHDZYMRJ9DA5VF9','01K40JQMS6XGK1X01HT0BDY76X',3,9784,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQQ8VQVRPN0RDHFNDWDVG','01K40JQQ8H1MHDZYMRJ9DA5VF9','01K40JQMT2BFFVRXWAE4HFRBPS',8,12814,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQQ8Z393R82WCKEBR96CF','01K40JQQ8H1MHDZYMRJ9DA5VF9','01K40JQMV4367TRSN828TX5NB0',2,1033,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQQ93984Z46DGAD8KXQ9P','01K40JQQ8H1MHDZYMRJ9DA5VF9','01K40JQMVH7FZZM9C1V7T15NBR',3,11369,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQQ9DJB9YWKSGZC6CR3M4','01K40JQQ9ASX8566QNVJ9JGD4M','01K40JQMSEP3JMZ8D1VTVVY1Q8',3,2176,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQQ9HA5WJHCYSHH8R6A90','01K40JQQ9ASX8566QNVJ9JGD4M','01K40JQMSY930MSDZK29TWM94H',3,11601,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQQ9ZH7V27AG96DPK0NAC','01K40JQQ9VEC4996AHF592PA2S','01K40JQMVAVN57GGGTK1C8HB9C',3,4134,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQQA9N2337NB4PYYMJWQ1','01K40JQQA6SGFE9GZE6V7C10XF','01K40JQMT2BFFVRXWAE4HFRBPS',7,9658,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQQADTYF7T65N4NB8VCF0','01K40JQQA6SGFE9GZE6V7C10XF','01K40JQMT69WBT1PYNSPGRXE38',5,2120,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQQAQ4AKT1P2FTHFWH1E0','01K40JQQAKJQX3J6NRGFYNAYNY','01K40JQMRW90SACGNZ481031VF',3,9092,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQQATZ6T7QWAN5M4CCD6V','01K40JQQAKJQX3J6NRGFYNAYNY','01K40JQMS0C7WQR3NVTN3RGQ7X',1,10557,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQQB5WPY2YWFXCJQE9QXY','01K40JQQAKJQX3J6NRGFYNAYNY','01K40JQMTXF0W5N9F59MD488AD',3,4753,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQBFK07RA029R0GE7RG6','01K40JQQBBKS05SE5JG68YWZQM','01K40JQMRQAD2R066JMJPAY6CX',8,13804,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQBJX9VX8XNPXMFT0Q1Z','01K40JQQBBKS05SE5JG68YWZQM','01K40JQMS0C7WQR3NVTN3RGQ7X',1,7305,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQBNV81PGV0NNS310JEH','01K40JQQBBKS05SE5JG68YWZQM','01K40JQMSQFFJE8RP0HKB1D570',6,9184,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQC0B07341K9K8BFXYX3','01K40JQQBW78CNP227SDBRMS8E','01K40JQMS0C7WQR3NVTN3RGQ7X',3,7693,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQC3PY709W0F0JQFG4RA','01K40JQQBW78CNP227SDBRMS8E','01K40JQMS6XGK1X01HT0BDY76X',2,11605,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQC7HFJFQDGGHPV9FWQ0','01K40JQQBW78CNP227SDBRMS8E','01K40JQMSAE0Z3KHAVJBYT54W4',1,16910,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQCBXNMAAVJR4HQRWJ58','01K40JQQBW78CNP227SDBRMS8E','01K40JQMTTYWX9W7NM29RTE9FQ',7,7987,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQCP4FZSTNDSY3XZ9Y3W','01K40JQQCJSWGRWVK8JP2H3AMZ','01K40JQMSQFFJE8RP0HKB1D570',4,9092,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQCSJMMCFCS95QBNQ67E','01K40JQQCJSWGRWVK8JP2H3AMZ','01K40JQMT9PC1QRAN3A9EDVE3E',8,8934,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQCW20H0TTNPFSRGAH2F','01K40JQQCJSWGRWVK8JP2H3AMZ','01K40JQMTKZ0RR2N980DBE3MG8',7,12265,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQCZVKF61D0D23QBPEMA','01K40JQQCJSWGRWVK8JP2H3AMZ','01K40JQMVDV6EEJYY6J4PBG85X',6,12523,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQDCGTFE9WWJXN8JX8XS','01K40JQQD7TK6QG57KXZJPVR1A','01K40JQMS0C7WQR3NVTN3RGQ7X',4,7242,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQDP83MDDDSFW6AK3M2T','01K40JQQDJ2DNK3YHZ2VKKFW5H','01K40JQMTTYWX9W7NM29RTE9FQ',8,6072,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQDTA7GRFH2TTNXJDNDD','01K40JQQDJ2DNK3YHZ2VKKFW5H','01K40JQMTXF0W5N9F59MD488AD',4,5479,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQE30AXDNT0JPRMKN3W7','01K40JQQE0ZN9AWM0SQ7KAW53G','01K40JQMS6XGK1X01HT0BDY76X',5,11023,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQEC7QKG8TN0QCH1JAD2','01K40JQQE9M72TK8P7K5CX03FJ','01K40JQMV1RCV1SQRQ49HZK8CY',1,7973,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQEPSH532K6QB41KYEXH','01K40JQQEJK8189YHCF05DRTWP','01K40JQMVAVN57GGGTK1C8HB9C',6,3684,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQETZR9TY1HD8FHRZH6Z','01K40JQQEJK8189YHCF05DRTWP','01K40JQMVDV6EEJYY6J4PBG85X',5,11306,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQEXWQ72QZTDBYHH89WA','01K40JQQEJK8189YHCF05DRTWP','01K40JQMVQAJ49T6GGRVH3KGZS',5,7622,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQF7MS60C99G79BWVNCY','01K40JQQF38XTH3N2XEFETJ11Q','01K40JQMRW90SACGNZ481031VF',6,9845,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQFBHEVSKR4K1QYQ28HV','01K40JQQF38XTH3N2XEFETJ11Q','01K40JQMSAE0Z3KHAVJBYT54W4',4,13257,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQFQ064QT2MPDRSW8Z84','01K40JQQFKPXGW3Q9P9KSSGK5R','01K40JQMRQAD2R066JMJPAY6CX',5,13873,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQG16R6SGBD6T11B5PBW','01K40JQQFYQ1M3QPJF8N98KTG2','01K40JQMRW90SACGNZ481031VF',2,11627,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQG5RGFCQRT1C9ADC8GA','01K40JQQFYQ1M3QPJF8N98KTG2','01K40JQMT69WBT1PYNSPGRXE38',6,1947,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQG80K57N59TENWCKPW7','01K40JQQFYQ1M3QPJF8N98KTG2','01K40JQMVDV6EEJYY6J4PBG85X',6,12943,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQGCCS33XWAR0NV6MHB3','01K40JQQFYQ1M3QPJF8N98KTG2','01K40JQMVW1SVZMYHPX6YPTEZ2',1,8457,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQGQ5F75P6RDGRVJ41W1','01K40JQQGKAMEHBHH5S6EZEKZ1','01K40JQMRQAD2R066JMJPAY6CX',5,12141,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQGT3P7F0J9DMPK847H7','01K40JQQGKAMEHBHH5S6EZEKZ1','01K40JQMSQFFJE8RP0HKB1D570',5,9301,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQH6S9P7Q8YJ6DZAQCAH','01K40JQQH2GHS5C6T86KBZ2VHK','01K40JQMSAE0Z3KHAVJBYT54W4',6,13445,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQHA163894PVXSJKZBCT','01K40JQQH2GHS5C6T86KBZ2VHK','01K40JQMTDDC5V7N4ASHMCBEPP',7,6107,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQHDTVTRWN9FZVTC2W8V','01K40JQQH2GHS5C6T86KBZ2VHK','01K40JQMV70JVWE49MD9FHAW65',5,14022,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQHJ257226FGZ7PZQBMN','01K40JQQH2GHS5C6T86KBZ2VHK','01K40JQMVAVN57GGGTK1C8HB9C',5,4863,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQJ0P56SJE5CWJFRCZME','01K40JQQHWQXGR4FXJE5M3BJPQ','01K40JQMRQAD2R066JMJPAY6CX',3,12485,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQJ6YNXM0X04Y15CTF72','01K40JQQHWQXGR4FXJE5M3BJPQ','01K40JQMSAE0Z3KHAVJBYT54W4',1,16549,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQJBN63G7RNRXWGGYTC9','01K40JQQHWQXGR4FXJE5M3BJPQ','01K40JQMTTYWX9W7NM29RTE9FQ',6,6846,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQJG1VMRKTFDFHBXSPW0','01K40JQQHWQXGR4FXJE5M3BJPQ','01K40JQMVQAJ49T6GGRVH3KGZS',1,10770,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQJYW8P2YER6J7X6VNS9','01K40JQQJTE8THN4X58MC23CDZ','01K40JQMTG7NR85FH40JV3P47W',6,12963,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQK3XW9811C5ZC23BM3R','01K40JQQJTE8THN4X58MC23CDZ','01K40JQMV1RCV1SQRQ49HZK8CY',3,7644,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQK7BF887CKJ8P9S0QEQ','01K40JQQJTE8THN4X58MC23CDZ','01K40JQMVMRMVW5B0EZTT5DKSY',2,2105,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQKHKJN522NGGHEKKMGJ','01K40JQQKDPNHQMZ7Q05BBWC8G','01K40JQMS35DSZ5Y89XJK02YSY',3,5546,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQKMWNHWRQ9F014HA17S','01K40JQQKDPNHQMZ7Q05BBWC8G','01K40JQMSY930MSDZK29TWM94H',2,14226,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQKTMFWSCN4HDE3T4PP0','01K40JQQKDPNHQMZ7Q05BBWC8G','01K40JQMVMRMVW5B0EZTT5DKSY',8,2331,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQKX6B81RPHSZR9CD152','01K40JQQKDPNHQMZ7Q05BBWC8G','01K40JQMVZ7J7HMPV396JKD1ZC',3,13133,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQM8MW468NF13Q4WNG3R','01K40JQQM4STPZF3FA9FBVKHF0','01K40JQMSK0B7BXPK0XZTD0XZB',3,5166,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQMBN6WCAGA3739FDMZS','01K40JQQM4STPZF3FA9FBVKHF0','01K40JQMSQFFJE8RP0HKB1D570',3,9802,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQME4VBV938JPNMQ53BS','01K40JQQM4STPZF3FA9FBVKHF0','01K40JQMTG7NR85FH40JV3P47W',6,15933,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQMJJCJ8GPSKGGDRH1Q0','01K40JQQM4STPZF3FA9FBVKHF0','01K40JQMTPFA6ZW3B0KXQWEGDQ',7,10018,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQMVXEP7V9Z8GXNP4S0G','01K40JQQMRX0MJPEJY63A9FP6G','01K40JQMRQAD2R066JMJPAY6CX',3,10078,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQMZCJAS2QBJP5GBTXW5','01K40JQQMRX0MJPEJY63A9FP6G','01K40JQMRW90SACGNZ481031VF',5,11720,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQN2YRNDBBFVAKRD2SEH','01K40JQQMRX0MJPEJY63A9FP6G','01K40JQMV4367TRSN828TX5NB0',5,811,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQN6S84QSR70E0955Y5V','01K40JQQMRX0MJPEJY63A9FP6G','01K40JQMVMRMVW5B0EZTT5DKSY',7,1870,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQNJKTK0RCTR3G117R0W','01K40JQQNFVBPFE63WXGSX1KZN','01K40JQMT2BFFVRXWAE4HFRBPS',5,10591,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQNP681SFM8N0TNZDASG','01K40JQQNFVBPFE63WXGSX1KZN','01K40JQMVW1SVZMYHPX6YPTEZ2',8,9562,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQNZKCBBRHN8XPW4Y8HX','01K40JQQNW7C7WEXC209FDD0EH','01K40JQMS6XGK1X01HT0BDY76X',3,10005,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQP2XCR3AD0HWQAWCTBG','01K40JQQNW7C7WEXC209FDD0EH','01K40JQMT69WBT1PYNSPGRXE38',6,2720,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQP5SV61Y5P4FB8M1E02','01K40JQQNW7C7WEXC209FDD0EH','01K40JQMTXF0W5N9F59MD488AD',4,5239,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQP90AY069YH016R8E5Q','01K40JQQNW7C7WEXC209FDD0EH','01K40JQMV70JVWE49MD9FHAW65',4,14901,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQPK6YQ7E9ZTCH6MKP7D','01K40JQQPFACQ19Z1GHASY86W2','01K40JQMT69WBT1PYNSPGRXE38',7,1885,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQPP4D5MFT9C8XAE3BAJ','01K40JQQPFACQ19Z1GHASY86W2','01K40JQMTTYWX9W7NM29RTE9FQ',2,6533,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQPSZ87BSWYKJGSKM37P','01K40JQQPFACQ19Z1GHASY86W2','01K40JQMV1RCV1SQRQ49HZK8CY',6,7844,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQQ3B8FX9KBQGNWHFJ25','01K40JQQQ0Q64HQE7G6GJ9C89S','01K40JQMS6XGK1X01HT0BDY76X',3,11834,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQQ6QRJSRZ7PRK7JQE3A','01K40JQQQ0Q64HQE7G6GJ9C89S','01K40JQMT69WBT1PYNSPGRXE38',3,2068,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQQ983W7JYVPMFMEVEFE','01K40JQQQ0Q64HQE7G6GJ9C89S','01K40JQMTTYWX9W7NM29RTE9FQ',2,6787,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQQEBMJ3W427PG4JK90A','01K40JQQQ0Q64HQE7G6GJ9C89S','01K40JQMVZ7J7HMPV396JKD1ZC',3,13125,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQQSGYG1Q8V5NVWV0EBS','01K40JQQQP1PM5G3XQ0GJYY7N4','01K40JQMRW90SACGNZ481031VF',1,12562,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQQXD19ZBBFQQPBKAWGW','01K40JQQQP1PM5G3XQ0GJYY7N4','01K40JQMV1RCV1SQRQ49HZK8CY',3,9375,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQR7TBJ8FN98B7ETVW0D','01K40JQQR3TB9DVBYWTXYXA6Z0','01K40JQMSY930MSDZK29TWM94H',3,13046,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQRANAQBPWMTDZVAX151','01K40JQQR3TB9DVBYWTXYXA6Z0','01K40JQMT69WBT1PYNSPGRXE38',1,2634,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQREYG1Y79NK8GQKAMF7','01K40JQQR3TB9DVBYWTXYXA6Z0','01K40JQMV1RCV1SQRQ49HZK8CY',1,8937,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQRJGX1TG0TSW2ZYRD6W','01K40JQQR3TB9DVBYWTXYXA6Z0','01K40JQMVDV6EEJYY6J4PBG85X',5,10575,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQRX9HJDJEHGJJFJZFDE','01K40JQQRS1REZ8AMQDQH71NSV','01K40JQMRQAD2R066JMJPAY6CX',5,10308,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQS0KRA3ZWCHYEWFCJ4M','01K40JQQRS1REZ8AMQDQH71NSV','01K40JQMVMRMVW5B0EZTT5DKSY',7,2117,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQSBPXCRPE7ZXZF537G8','01K40JQQS78PQFFBGKM9B53SP4','01K40JQMS0C7WQR3NVTN3RGQ7X',4,10384,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQSF0DAX82YXFPD02Z7A','01K40JQQS78PQFFBGKM9B53SP4','01K40JQMS6XGK1X01HT0BDY76X',3,8975,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQSKN4G932YVXPTQ7ZSH','01K40JQQS78PQFFBGKM9B53SP4','01K40JQMSK0B7BXPK0XZTD0XZB',7,3924,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQSRGRDHZ0FAVJBCECT3','01K40JQQS78PQFFBGKM9B53SP4','01K40JQMSQFFJE8RP0HKB1D570',3,8552,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQT6S7SEVH67DEX6B8TR','01K40JQQT2CW12TJFYP3WRRF5M','01K40JQMTG7NR85FH40JV3P47W',2,14314,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQTABSKA0HYM8G7VXTY3','01K40JQQT2CW12TJFYP3WRRF5M','01K40JQMV70JVWE49MD9FHAW65',2,11690,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQTP7ZKDJ44TBYW6TAV2','01K40JQQTKAVNGFP1WRA7Y59RV','01K40JQMTG7NR85FH40JV3P47W',4,15685,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQTVCX3TDY7HPG9FS8XD','01K40JQQTKAVNGFP1WRA7Y59RV','01K40JQMTXF0W5N9F59MD488AD',5,5370,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQV9E22ATW9EEQ49XHAP','01K40JQQV452224S1HXGGG4Y8Y','01K40JQMTXF0W5N9F59MD488AD',7,5999,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQVFA111PC5WA83T5XE4','01K40JQQV452224S1HXGGG4Y8Y','01K40JQMVMRMVW5B0EZTT5DKSY',1,2222,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQVMW6BNATJAZ7WEJP4F','01K40JQQV452224S1HXGGG4Y8Y','01K40JQMVQAJ49T6GGRVH3KGZS',4,8601,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQVZEJG6AEBWC72CTJYQ','01K40JQQVVJ0NQMPE1CN2BF6JK','01K40JQMSK0B7BXPK0XZTD0XZB',5,4414,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQW27ER5HG924SVBHKKW','01K40JQQVVJ0NQMPE1CN2BF6JK','01K40JQMSV8ACVYVDKDRPC18YG',4,4422,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQW60ZJ63MJ4YSDEHKJY','01K40JQQVVJ0NQMPE1CN2BF6JK','01K40JQMTDDC5V7N4ASHMCBEPP',1,6620,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQWBHN4JRNC5Z69SVX9F','01K40JQQVVJ0NQMPE1CN2BF6JK','01K40JQMVAVN57GGGTK1C8HB9C',6,4719,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQWQWV22YFCBW7GVQV9E','01K40JQQWMB7NTQAGBMFSCFQD2','01K40JQMTDDC5V7N4ASHMCBEPP',5,6574,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQWTAVWERJW44AG5DH61','01K40JQQWMB7NTQAGBMFSCFQD2','01K40JQMTG7NR85FH40JV3P47W',3,14691,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQX52J8510Y5M2CATTYF','01K40JQQX190SK2Y2GHKXG2P9E','01K40JQMSY930MSDZK29TWM94H',7,14458,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQX8B5MC17XZH6T1HFH7','01K40JQQX190SK2Y2GHKXG2P9E','01K40JQMT9PC1QRAN3A9EDVE3E',3,8418,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQXBZ3282071Z4ECQSJM','01K40JQQX190SK2Y2GHKXG2P9E','01K40JQMTG7NR85FH40JV3P47W',2,13065,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQXEXRN5H81FSPGGWW1X','01K40JQQX190SK2Y2GHKXG2P9E','01K40JQMVH7FZZM9C1V7T15NBR',7,9435,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQXQB4CZ2A3AE9VXA8PW','01K40JQQXMNESZX96J7K62APRG','01K40JQMSY930MSDZK29TWM94H',6,15376,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQXV67H9AJV63CQ9XKHC','01K40JQQXMNESZX96J7K62APRG','01K40JQMT69WBT1PYNSPGRXE38',6,2695,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQY518TWEERSTW4WSNCT','01K40JQQY1D5TZC4AADX11RC1G','01K40JQMT2BFFVRXWAE4HFRBPS',6,10086,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQY9XQJGH69HJXRX18NC','01K40JQQY1D5TZC4AADX11RC1G','01K40JQMT9PC1QRAN3A9EDVE3E',1,9521,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQYCB3KYRH87XJKDQYRQ','01K40JQQY1D5TZC4AADX11RC1G','01K40JQMV70JVWE49MD9FHAW65',5,15181,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQYQ2YVR61EY1A60B0FC','01K40JQQYMGX90HZRXPZQ6BM07','01K40JQMSY930MSDZK29TWM94H',2,16369,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQYVFN5GT7RYDMEJC0C3','01K40JQQYMGX90HZRXPZQ6BM07','01K40JQMT69WBT1PYNSPGRXE38',6,1872,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQYZPGMJZYYZ47WTWM9D','01K40JQQYMGX90HZRXPZQ6BM07','01K40JQMTXF0W5N9F59MD488AD',8,4651,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQZ8BMCK9XGDNK3059F8','01K40JQQZ58Q1MGX182DSJ9WR2','01K40JQMS0C7WQR3NVTN3RGQ7X',1,10280,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQZBYP4G6W0QBDSFJ9CE','01K40JQQZ58Q1MGX182DSJ9WR2','01K40JQMS6XGK1X01HT0BDY76X',1,12560,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQZETV1CY44YH175EKCV','01K40JQQZ58Q1MGX182DSJ9WR2','01K40JQMVH7FZZM9C1V7T15NBR',3,11941,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQZQFZNTBGB70XXCBM31','01K40JQQZMF69W8MQJ5YNRHBT3','01K40JQMRQAD2R066JMJPAY6CX',8,10692,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQZT5J2D3D1QXG73SSF0','01K40JQQZMF69W8MQJ5YNRHBT3','01K40JQMSQFFJE8RP0HKB1D570',4,10072,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQR03W2GKYQYTC5WNVCF4','01K40JQR002G6XKNWVB8AXQ70S','01K40JQMTPFA6ZW3B0KXQWEGDQ',4,10360,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQR0DAT4DZ2DWET7GJYF6','01K40JQR099JEF29C15MT2C6GF','01K40JQMT69WBT1PYNSPGRXE38',6,1836,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQR0G4QPD4FB6XTD24577','01K40JQR099JEF29C15MT2C6GF','01K40JQMV4367TRSN828TX5NB0',8,1052,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQR0N1922RPM8TVPQFJM0','01K40JQR099JEF29C15MT2C6GF','01K40JQMVMRMVW5B0EZTT5DKSY',7,2008,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQR10GA3D1V3C26FDXHN7','01K40JQR0WCZZYH777XVV92HPF','01K40JQMT9PC1QRAN3A9EDVE3E',1,10213,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQR14VDENEQGRAQ8N3E2B','01K40JQR0WCZZYH777XVV92HPF','01K40JQMTKZ0RR2N980DBE3MG8',2,9696,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQR17WSR8T683CWVZSHZ5','01K40JQR0WCZZYH777XVV92HPF','01K40JQMVH7FZZM9C1V7T15NBR',6,8395,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQR1J60BC2E0FFNNQ7BRN','01K40JQR1EEREDKCV9JJ9SBMX9','01K40JQMS35DSZ5Y89XJK02YSY',7,7267,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQR1XJMEQ5WP33HRHPTJH','01K40JQR1SSVF6CADAVXSCSR01','01K40JQMSY930MSDZK29TWM94H',6,14579,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQR20F1XTTX003P2BY4KM','01K40JQR1SSVF6CADAVXSCSR01','01K40JQMT69WBT1PYNSPGRXE38',6,1953,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQR237VZSTGASZFNHG6SP','01K40JQR1SSVF6CADAVXSCSR01','01K40JQMTG7NR85FH40JV3P47W',5,13136,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQR26AGK9XB6K95442GJ8','01K40JQR1SSVF6CADAVXSCSR01','01K40JQMV1RCV1SQRQ49HZK8CY',3,10280,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQR2H8BG9H18GCCBW8RKX','01K40JQR2EK35FJXFEB40DDNJV','01K40JQMSV8ACVYVDKDRPC18YG',1,5185,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQR2YYEH1CSE8YCE9597J','01K40JQR2RDFF2B9YPHF4RPM37','01K40JQMT69WBT1PYNSPGRXE38',6,2011,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQR32R2SCSR3DFXSE06M0','01K40JQR2RDFF2B9YPHF4RPM37','01K40JQMVW1SVZMYHPX6YPTEZ2',1,8384,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQR3BZZVJVVBWVN3B18KC','01K40JQR3878KFHSKK4WGFS5KT','01K40JQMSK0B7BXPK0XZTD0XZB',4,4237,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQR3F39D0WDS25XNA4Y87','01K40JQR3878KFHSKK4WGFS5KT','01K40JQMVZ7J7HMPV396JKD1ZC',1,10601,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQR3SH7PAKWND9MBMZ70A','01K40JQR3NAKSFCSD3HXCKD4G4','01K40JQMRQAD2R066JMJPAY6CX',1,11772,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQR3X866ZAGZK07Y5D54D','01K40JQR3NAKSFCSD3HXCKD4G4','01K40JQMSQFFJE8RP0HKB1D570',5,10461,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQR400JSYCQDT26MF8JDR','01K40JQR3NAKSFCSD3HXCKD4G4','01K40JQMT2BFFVRXWAE4HFRBPS',2,11835,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQR43S9EKR9PBM1QZ0P2F','01K40JQR3NAKSFCSD3HXCKD4G4','01K40JQMVW1SVZMYHPX6YPTEZ2',8,10356,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQR4DK54E2Y01MFATPGP4','01K40JQR4ADM67X98TPKG75FK1','01K40JQMSAE0Z3KHAVJBYT54W4',4,16215,'2025-08-31 17:27:56','2025-08-31 17:27:56');
/*!40000 ALTER TABLE `sale_lines` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sales`
--

DROP TABLE IF EXISTS `sales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sales` (
  `id` char(26) COLLATE utf8mb4_unicode_ci NOT NULL,
  `franchisee_id` char(26) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sale_date` date NOT NULL,
  `total_cents` bigint unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sales_franchisee_id_foreign` (`franchisee_id`),
  KEY `sales_sale_date_index` (`sale_date`),
  CONSTRAINT `sales_franchisee_id_foreign` FOREIGN KEY (`franchisee_id`) REFERENCES `franchisees` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sales`
--

LOCK TABLES `sales` WRITE;
/*!40000 ALTER TABLE `sales` DISABLE KEYS */;
INSERT INTO `sales` VALUES ('01K40JQNJD1XHQM8CRTTHRR5TQ','01K40JQN6MTCVJC5NQZNRGETDE','2025-07-02',99599,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNK3DW8FQ5YQPQY3R3WY','01K40JQN6MTCVJC5NQZNRGETDE','2025-07-03',127399,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNKYNWPPX05JP2DFF3GJ','01K40JQN6MTCVJC5NQZNRGETDE','2025-07-04',111053,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNMSDEM7RTJWJ44F5BQ1','01K40JQN6MTCVJC5NQZNRGETDE','2025-07-05',115152,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNN7YJR4W5JC03JTZA0N','01K40JQN6MTCVJC5NQZNRGETDE','2025-07-06',200952,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNNWYPP0R7SFZEE186HQ','01K40JQN6MTCVJC5NQZNRGETDE','2025-07-07',73574,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNPB6RGBWCSRJD24ZDT4','01K40JQN6MTCVJC5NQZNRGETDE','2025-07-09',119868,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNPQGFX00H6TNJVEBB72','01K40JQN6MTCVJC5NQZNRGETDE','2025-07-10',11322,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNQ0EGA349ER27XG9B1J','01K40JQN6MTCVJC5NQZNRGETDE','2025-07-11',36293,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNQDXSR1X9X7TWSM1YMN','01K40JQN6MTCVJC5NQZNRGETDE','2025-07-12',114184,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNQQ8NJXDM8EFFCX46JG','01K40JQN6MTCVJC5NQZNRGETDE','2025-07-13',138111,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNRC3YFV02KBWHFKK8B6','01K40JQN6MTCVJC5NQZNRGETDE','2025-07-15',16104,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNRRFKH9JX60MF7VA7FK','01K40JQN6MTCVJC5NQZNRGETDE','2025-07-16',12005,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNS2R6TMB6EJRDT2HKT3','01K40JQN6MTCVJC5NQZNRGETDE','2025-07-17',26973,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNSJWSYGPT7FR6K577JT','01K40JQN6MTCVJC5NQZNRGETDE','2025-07-18',239757,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNT2CKJE5JW9E730S1JS','01K40JQN6MTCVJC5NQZNRGETDE','2025-07-19',88851,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNTC4XARQJ92G8686P2B','01K40JQN6MTCVJC5NQZNRGETDE','2025-07-21',229959,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNV2P7C0BQP7K8WSZPY4','01K40JQN6MTCVJC5NQZNRGETDE','2025-07-22',2122,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNVC657C9PN6V43F8YES','01K40JQN6MTCVJC5NQZNRGETDE','2025-07-24',77409,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNVSJ6NRA7WG2CTN1G95','01K40JQN6MTCVJC5NQZNRGETDE','2025-07-25',32720,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNW34DWREVHX79ZPYBMM','01K40JQN6MTCVJC5NQZNRGETDE','2025-07-26',32580,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNWEKCY7RAQN1PMM0JJ3','01K40JQN6MTCVJC5NQZNRGETDE','2025-07-27',85631,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNWV75T090T2HM2AE3ZF','01K40JQN6MTCVJC5NQZNRGETDE','2025-07-28',72813,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNXD4KVA8A7XFAB2J6VS','01K40JQN6MTCVJC5NQZNRGETDE','2025-07-29',32764,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNXR1H8R594BRCPSTEWC','01K40JQN6MTCVJC5NQZNRGETDE','2025-07-30',93398,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNY7W9GRYFXJ0K9XHQ1P','01K40JQN6MTCVJC5NQZNRGETDE','2025-07-31',133932,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNYX6PRBKGFPB14PDVAD','01K40JQN6MTCVJC5NQZNRGETDE','2025-08-01',102905,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNZFF249YYP4S4ES828A','01K40JQN6MTCVJC5NQZNRGETDE','2025-08-02',128920,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQP00S2BA6PFWMGQ9CASK','01K40JQN6MTCVJC5NQZNRGETDE','2025-08-04',62979,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQP0H4PT1N9N1KM9RQZVR','01K40JQN6MTCVJC5NQZNRGETDE','2025-08-06',108008,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQP14C40C8FX34H9XF150','01K40JQN6MTCVJC5NQZNRGETDE','2025-08-07',166885,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQP1RB642M6JBWZ153FRM','01K40JQN6MTCVJC5NQZNRGETDE','2025-08-08',17664,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQP232CKYW59VNM5KZJK2','01K40JQN6MTCVJC5NQZNRGETDE','2025-08-09',87185,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQP2D3HZVEA1NHR4VDYZT','01K40JQN6MTCVJC5NQZNRGETDE','2025-08-10',73792,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQP2T4QEQM2B6P7NVEN14','01K40JQN6MTCVJC5NQZNRGETDE','2025-08-11',184300,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQP3BAJVGGQP4585QW2EN','01K40JQN6MTCVJC5NQZNRGETDE','2025-08-12',114859,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQP3Y0W4SNQ52NWNX4QY6','01K40JQN6MTCVJC5NQZNRGETDE','2025-08-13',184582,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQP4FH1Y8SWSKWRT8YDE3','01K40JQN6MTCVJC5NQZNRGETDE','2025-08-15',52953,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQP4XMJNSCDJVPJT3558F','01K40JQN6MTCVJC5NQZNRGETDE','2025-08-16',110688,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQP57CVFRPGA8ZNFF487T','01K40JQN6MTCVJC5NQZNRGETDE','2025-08-17',68232,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQP5HGA0V8BRX11N9E3H3','01K40JQN6MTCVJC5NQZNRGETDE','2025-08-18',118292,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQP69EE4Z2EB2DQT11TR0','01K40JQN6MTCVJC5NQZNRGETDE','2025-08-19',37171,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQP6RNPHJ5REZDSB3XHPT','01K40JQN6MTCVJC5NQZNRGETDE','2025-08-20',147884,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQP7F9DCFBJDHKDGGPGTD','01K40JQN6MTCVJC5NQZNRGETDE','2025-08-21',36389,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQP7WXGN4JM4HK4BMV5ZQ','01K40JQN6MTCVJC5NQZNRGETDE','2025-08-22',60352,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQP8FYYZ20PHR3XQYRFT7','01K40JQN6MTCVJC5NQZNRGETDE','2025-08-23',178909,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQP907Q2X9H0E4HD07FTY','01K40JQN6MTCVJC5NQZNRGETDE','2025-08-24',26254,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQP9DC23N5GKKPR66CEGS','01K40JQN6MTCVJC5NQZNRGETDE','2025-08-25',31460,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQP9WW2PK8QEB4V3R6AE3','01K40JQN6MTCVJC5NQZNRGETDE','2025-08-26',91147,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQPAJD0974THX6W2DXS2X','01K40JQN6MTCVJC5NQZNRGETDE','2025-08-28',19903,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQPAZJQKTT5GMJTXM1R26','01K40JQN6MTCVJC5NQZNRGETDE','2025-08-29',4418,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQPB98EG7C0PZPS2FRDWC','01K40JQN6MTCVJC5NQZNRGETDE','2025-08-30',127809,'2025-08-31 17:27:54','2025-08-31 17:27:55'),('01K40JQPCAFS8WGKW3ZNJA3BEV','01K40JQN6R6SJ6724FRFA3TVS6','2025-07-02',125494,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPD0YC2ZVV3V5NTHJXDB','01K40JQN6R6SJ6724FRFA3TVS6','2025-07-03',99430,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPDJ670J0WWJN658RHRK','01K40JQN6R6SJ6724FRFA3TVS6','2025-07-04',48930,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPDWC5FYJ9QPVD0ZE0VA','01K40JQN6R6SJ6724FRFA3TVS6','2025-07-05',226641,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPEBKMWXH7SG6D9R0AJ4','01K40JQN6R6SJ6724FRFA3TVS6','2025-07-06',84080,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPF0Y60GTQAF3889HGVZ','01K40JQN6R6SJ6724FRFA3TVS6','2025-07-07',14906,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPFB2VY5B02R6SCFR70Z','01K40JQN6R6SJ6724FRFA3TVS6','2025-07-08',10184,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPFPJ66EPS6TDZEY3W4G','01K40JQN6R6SJ6724FRFA3TVS6','2025-07-09',210205,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPGBSY2D66P6A5TS2J7A','01K40JQN6R6SJ6724FRFA3TVS6','2025-07-10',44740,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPGRCAJFFSGSCY0FR9HP','01K40JQN6R6SJ6724FRFA3TVS6','2025-07-11',84440,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPH1G0JGWPKHDSGRSEM0','01K40JQN6R6SJ6724FRFA3TVS6','2025-07-12',11356,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPHCYS9WZ4YAN4HH8N9T','01K40JQN6R6SJ6724FRFA3TVS6','2025-07-13',103915,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPHZ0YME7YG7JRQF9455','01K40JQN6R6SJ6724FRFA3TVS6','2025-07-15',39492,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPJDBTDS3XXK85Z7ZKXZ','01K40JQN6R6SJ6724FRFA3TVS6','2025-07-16',45582,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPK2M6C3ZHS3RB64SSYB','01K40JQN6R6SJ6724FRFA3TVS6','2025-07-17',72530,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPKSBW545A2JBFNNGNBK','01K40JQN6R6SJ6724FRFA3TVS6','2025-07-18',25545,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPM543PFK7Z2SP6AZA9P','01K40JQN6R6SJ6724FRFA3TVS6','2025-07-19',32694,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPMN9DBR2XB39MZ6ZEG9','01K40JQN6R6SJ6724FRFA3TVS6','2025-07-20',151243,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPN84E1DK6Y7P9J99Z7G','01K40JQN6R6SJ6724FRFA3TVS6','2025-07-21',19752,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPNMVZ6M8127EDZJ2BGX','01K40JQN6R6SJ6724FRFA3TVS6','2025-07-22',60922,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPP3BTR1C8X6HMPPT6GM','01K40JQN6R6SJ6724FRFA3TVS6','2025-07-23',25117,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPPH23PXW8B2KWYNG9HV','01K40JQN6R6SJ6724FRFA3TVS6','2025-07-24',83310,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPQ0DTXJYB2GSBZ2QZA6','01K40JQN6R6SJ6724FRFA3TVS6','2025-07-25',50853,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPQFFMB14B88WJXZ699A','01K40JQN6R6SJ6724FRFA3TVS6','2025-07-26',20225,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPQWWRB4EP5TBEVSX8VS','01K40JQN6R6SJ6724FRFA3TVS6','2025-07-27',238315,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPRFGHG70Q27FE2AV4WK','01K40JQN6R6SJ6724FRFA3TVS6','2025-07-28',180182,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPS29BBVHZF80ENBJK9F','01K40JQN6R6SJ6724FRFA3TVS6','2025-07-29',107488,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPSPQPV3P8JV60DHMVHR','01K40JQN6R6SJ6724FRFA3TVS6','2025-07-30',75894,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPT1APSQDF7B71ZX4AJQ','01K40JQN6R6SJ6724FRFA3TVS6','2025-08-01',117923,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPTPH2MMA7W0N84AVHGV','01K40JQN6R6SJ6724FRFA3TVS6','2025-08-02',69954,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPTZZ1Q25Q2K8SGX8MMJ','01K40JQN6R6SJ6724FRFA3TVS6','2025-08-04',107653,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPVE1HGCPYW6J86BQBPD','01K40JQN6R6SJ6724FRFA3TVS6','2025-08-06',126729,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPW4D0FMKA673FFC80SN','01K40JQN6R6SJ6724FRFA3TVS6','2025-08-07',107884,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPWGD7BVTNKYNSQ013WF','01K40JQN6R6SJ6724FRFA3TVS6','2025-08-08',12306,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPWSTB33BJH22XZYMAF5','01K40JQN6R6SJ6724FRFA3TVS6','2025-08-09',106274,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPXEFN11TTS33CR76DC5','01K40JQN6R6SJ6724FRFA3TVS6','2025-08-10',75054,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPXWZ5YKW5E3CP1ZW3JR','01K40JQN6R6SJ6724FRFA3TVS6','2025-08-11',136198,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPYJQJW2YNTS223BCTCX','01K40JQN6R6SJ6724FRFA3TVS6','2025-08-12',54029,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPYZK12XWQQENFZM0KQP','01K40JQN6R6SJ6724FRFA3TVS6','2025-08-13',207354,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQPZHV7FC9GRVB6B2Z0PG','01K40JQN6R6SJ6724FRFA3TVS6','2025-08-14',111793,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQQ07YAKEG7NVH8AQR7X5','01K40JQN6R6SJ6724FRFA3TVS6','2025-08-15',105804,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQQ0RDJS4KJW6NH9PCP0C','01K40JQN6R6SJ6724FRFA3TVS6','2025-08-17',133797,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQQ16JV2DNVHAE6NQZ67Z','01K40JQN6R6SJ6724FRFA3TVS6','2025-08-19',176898,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQQ1S70S1VP5VQEZK896H','01K40JQN6R6SJ6724FRFA3TVS6','2025-08-20',67284,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQQ2DH78ADPB7FQ7H1P3C','01K40JQN6R6SJ6724FRFA3TVS6','2025-08-21',149223,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQQ2YNF5FH3ZQ7AS0QHEJ','01K40JQN6R6SJ6724FRFA3TVS6','2025-08-22',134370,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQQ3HVSHPV59BM60ADJMG','01K40JQN6R6SJ6724FRFA3TVS6','2025-08-23',147304,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQQ46QXK958X77QPD2R41','01K40JQN6R6SJ6724FRFA3TVS6','2025-08-24',19336,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQQ4PY574P9DFM3R8X8HK','01K40JQN6R6SJ6724FRFA3TVS6','2025-08-25',81759,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQQ57PDMNZZV3ZS3YCZ9A','01K40JQN6R6SJ6724FRFA3TVS6','2025-08-26',145216,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQQ5VP9KNE8DWRPTRRHDQ','01K40JQN6R6SJ6724FRFA3TVS6','2025-08-27',44352,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQQ66VQWPVWDG1XG3WM9Y','01K40JQN6R6SJ6724FRFA3TVS6','2025-08-28',185711,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQQ6XPNNWY6RJM1Z2N3P9','01K40JQN6R6SJ6724FRFA3TVS6','2025-08-29',192166,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQQ7GX7N97D170HE4PYYW','01K40JQN6R6SJ6724FRFA3TVS6','2025-08-30',152493,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQQ835X88N62TM2T4083P','01K40JQN6W55YXQ6AKPEZBZ59V','2025-07-02',98884,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQQ8H1MHDZYMRJ9DA5VF9','01K40JQN6W55YXQ6AKPEZBZ59V','2025-07-03',168037,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQQ9ASX8566QNVJ9JGD4M','01K40JQN6W55YXQ6AKPEZBZ59V','2025-07-04',41331,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQQ9VEC4996AHF592PA2S','01K40JQN6W55YXQ6AKPEZBZ59V','2025-07-05',12402,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQQA6SGFE9GZE6V7C10XF','01K40JQN6W55YXQ6AKPEZBZ59V','2025-07-06',78206,'2025-08-31 17:27:55','2025-08-31 17:27:55'),('01K40JQQAKJQX3J6NRGFYNAYNY','01K40JQN6W55YXQ6AKPEZBZ59V','2025-07-07',52092,'2025-08-31 17:27:55','2025-08-31 17:27:56'),('01K40JQQBBKS05SE5JG68YWZQM','01K40JQN6W55YXQ6AKPEZBZ59V','2025-07-08',172841,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQBW78CNP227SDBRMS8E','01K40JQN6W55YXQ6AKPEZBZ59V','2025-07-09',119108,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQCJSWGRWVK8JP2H3AMZ','01K40JQN6W55YXQ6AKPEZBZ59V','2025-07-10',268833,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQD7TK6QG57KXZJPVR1A','01K40JQN6W55YXQ6AKPEZBZ59V','2025-07-11',28968,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQDJ2DNK3YHZ2VKKFW5H','01K40JQN6W55YXQ6AKPEZBZ59V','2025-07-12',70492,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQE0ZN9AWM0SQ7KAW53G','01K40JQN6W55YXQ6AKPEZBZ59V','2025-07-13',55115,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQE9M72TK8P7K5CX03FJ','01K40JQN6W55YXQ6AKPEZBZ59V','2025-07-14',7973,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQEJK8189YHCF05DRTWP','01K40JQN6W55YXQ6AKPEZBZ59V','2025-07-15',116744,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQF38XTH3N2XEFETJ11Q','01K40JQN6W55YXQ6AKPEZBZ59V','2025-07-16',112098,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQFKPXGW3Q9P9KSSGK5R','01K40JQN6W55YXQ6AKPEZBZ59V','2025-07-18',69365,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQFYQ1M3QPJF8N98KTG2','01K40JQN6W55YXQ6AKPEZBZ59V','2025-07-19',121051,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQGKAMEHBHH5S6EZEKZ1','01K40JQN6W55YXQ6AKPEZBZ59V','2025-07-20',107210,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQH2GHS5C6T86KBZ2VHK','01K40JQN6W55YXQ6AKPEZBZ59V','2025-07-21',217844,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQHWQXGR4FXJE5M3BJPQ','01K40JQN6W55YXQ6AKPEZBZ59V','2025-07-23',105850,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQJTE8THN4X58MC23CDZ','01K40JQN6W55YXQ6AKPEZBZ59V','2025-07-24',104920,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQKDPNHQMZ7Q05BBWC8G','01K40JQN6W55YXQ6AKPEZBZ59V','2025-07-25',103137,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQM4STPZF3FA9FBVKHF0','01K40JQN6W55YXQ6AKPEZBZ59V','2025-07-27',210628,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQMRX0MJPEJY63A9FP6G','01K40JQN6W55YXQ6AKPEZBZ59V','2025-07-28',105979,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQNFVBPFE63WXGSX1KZN','01K40JQN6W55YXQ6AKPEZBZ59V','2025-07-29',129451,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQNW7C7WEXC209FDD0EH','01K40JQN6W55YXQ6AKPEZBZ59V','2025-07-30',126895,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQPFACQ19Z1GHASY86W2','01K40JQN6W55YXQ6AKPEZBZ59V','2025-07-31',73325,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQQ0Q64HQE7G6GJ9C89S','01K40JQN6W55YXQ6AKPEZBZ59V','2025-08-01',94655,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQQP1PM5G3XQ0GJYY7N4','01K40JQN6W55YXQ6AKPEZBZ59V','2025-08-02',40687,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQR3TB9DVBYWTXYXA6Z0','01K40JQN6W55YXQ6AKPEZBZ59V','2025-08-03',103584,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQRS1REZ8AMQDQH71NSV','01K40JQN6W55YXQ6AKPEZBZ59V','2025-08-04',66359,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQS78PQFFBGKM9B53SP4','01K40JQN6W55YXQ6AKPEZBZ59V','2025-08-05',121585,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQT2CW12TJFYP3WRRF5M','01K40JQN6W55YXQ6AKPEZBZ59V','2025-08-06',52008,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQTKAVNGFP1WRA7Y59RV','01K40JQN6W55YXQ6AKPEZBZ59V','2025-08-07',89590,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQV452224S1HXGGG4Y8Y','01K40JQN6W55YXQ6AKPEZBZ59V','2025-08-08',78619,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQVVJ0NQMPE1CN2BF6JK','01K40JQN6W55YXQ6AKPEZBZ59V','2025-08-10',74692,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQWMB7NTQAGBMFSCFQD2','01K40JQN6W55YXQ6AKPEZBZ59V','2025-08-11',76943,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQX190SK2Y2GHKXG2P9E','01K40JQN6W55YXQ6AKPEZBZ59V','2025-08-12',218635,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQXMNESZX96J7K62APRG','01K40JQN6W55YXQ6AKPEZBZ59V','2025-08-13',108426,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQY1D5TZC4AADX11RC1G','01K40JQN6W55YXQ6AKPEZBZ59V','2025-08-14',145942,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQYMGX90HZRXPZQ6BM07','01K40JQN6W55YXQ6AKPEZBZ59V','2025-08-15',81178,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQZ58Q1MGX182DSJ9WR2','01K40JQN6W55YXQ6AKPEZBZ59V','2025-08-16',58663,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQQZMF69W8MQJ5YNRHBT3','01K40JQN6W55YXQ6AKPEZBZ59V','2025-08-18',125824,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQR002G6XKNWVB8AXQ70S','01K40JQN6W55YXQ6AKPEZBZ59V','2025-08-19',41440,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQR099JEF29C15MT2C6GF','01K40JQN6W55YXQ6AKPEZBZ59V','2025-08-20',33488,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQR0WCZZYH777XVV92HPF','01K40JQN6W55YXQ6AKPEZBZ59V','2025-08-21',79975,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQR1EEREDKCV9JJ9SBMX9','01K40JQN6W55YXQ6AKPEZBZ59V','2025-08-23',50869,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQR1SSVF6CADAVXSCSR01','01K40JQN6W55YXQ6AKPEZBZ59V','2025-08-24',195712,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQR2EK35FJXFEB40DDNJV','01K40JQN6W55YXQ6AKPEZBZ59V','2025-08-25',5185,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQR2RDFF2B9YPHF4RPM37','01K40JQN6W55YXQ6AKPEZBZ59V','2025-08-26',20450,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQR3878KFHSKK4WGFS5KT','01K40JQN6W55YXQ6AKPEZBZ59V','2025-08-27',27549,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQR3NAKSFCSD3HXCKD4G4','01K40JQN6W55YXQ6AKPEZBZ59V','2025-08-28',170595,'2025-08-31 17:27:56','2025-08-31 17:27:56'),('01K40JQR4ADM67X98TPKG75FK1','01K40JQN6W55YXQ6AKPEZBZ59V','2025-08-29',64860,'2025-08-31 17:27:56','2025-08-31 17:27:56');
/*!40000 ALTER TABLE `sales` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('Wr6dVnZw2crr1aeyH4j8L6YKaoRzHaYaMi9UHjxW',1,'172.18.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36 OPR/120.0.0.0 (Edition std-2)','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiSW1RTG9jdjhtVXV1Q0l6OTVEQzVyeXNGVkxheW1jdGo5TTliRmN3cSI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI2OiJodHRwOi8vbG9jYWxob3N0L2JvL3RydWNrcyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==',1756666887);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stock_items`
--

DROP TABLE IF EXISTS `stock_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `stock_items` (
  `id` char(26) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sku` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pcs',
  `price_cents` bigint unsigned NOT NULL DEFAULT '0',
  `is_central` tinyint(1) NOT NULL DEFAULT '1',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `stock_items_sku_unique` (`sku`),
  KEY `stock_items_is_central_index` (`is_central`),
  KEY `stock_items_is_active_index` (`is_active`),
  KEY `si_is_central_idx` (`is_central`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock_items`
--

LOCK TABLES `stock_items` WRITE;
/*!40000 ALTER TABLE `stock_items` DISABLE KEYS */;
INSERT INTO `stock_items` VALUES ('01K40JQMRQAD2R066JMJPAY6CX','SKU-0001','at quisquam','kg',12401,1,1,NULL,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQMRW90SACGNZ481031VF','SKU-0002','iste harum','pc',11144,1,1,NULL,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQMS0C7WQR3NVTN3RGQ7X','SKU-0003','et nam','L',8854,1,1,NULL,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQMS35DSZ5Y89XJK02YSY','SKU-0004','vel rerum','pc',6065,1,1,NULL,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQMS6XGK1X01HT0BDY76X','SKU-0005','qui atque','kg',10630,1,1,NULL,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQMSAE0Z3KHAVJBYT54W4','SKU-0006','et natus','kg',14733,1,1,NULL,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQMSEP3JMZ8D1VTVVY1Q8','SKU-0007','quo sed','L',2561,1,1,NULL,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQMSK0B7BXPK0XZTD0XZB','SKU-0008','reprehenderit consectetur','L',4764,1,1,NULL,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQMSQFFJE8RP0HKB1D570','SKU-0009','iusto culpa','L',10144,1,1,NULL,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQMSV8ACVYVDKDRPC18YG','SKU-0010','sunt deserunt','L',5206,1,1,NULL,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQMSY930MSDZK29TWM94H','SKU-0011','quam eos','L',14141,1,1,NULL,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQMT2BFFVRXWAE4HFRBPS','SKU-0012','illo velit','kg',11249,1,1,NULL,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQMT69WBT1PYNSPGRXE38','SKU-0013','sunt voluptas','pc',2273,1,1,NULL,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQMT9PC1QRAN3A9EDVE3E','SKU-0014','enim sunt','L',8715,1,1,NULL,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQMTDDC5V7N4ASHMCBEPP','SKU-0015','soluta et','L',6084,1,1,NULL,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQMTG7NR85FH40JV3P47W','SKU-0016','officia harum','kg',14210,1,1,NULL,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQMTKZ0RR2N980DBE3MG8','SKU-0017','sapiente maiores','pc',11598,1,1,NULL,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQMTPFA6ZW3B0KXQWEGDQ','SKU-0018','qui enim','pc',8699,1,1,NULL,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQMTTYWX9W7NM29RTE9FQ','SKU-0019','et voluptatem','pc',6818,0,1,NULL,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQMTXF0W5N9F59MD488AD','SKU-0020','id excepturi','pc',5472,0,1,NULL,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQMV1RCV1SQRQ49HZK8CY','SKU-0021','et distinctio','L',8780,0,1,NULL,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQMV4367TRSN828TX5NB0','SKU-0022','quae nihil','kg',981,0,1,NULL,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQMV70JVWE49MD9FHAW65','SKU-0023','omnis iste','kg',12806,0,1,NULL,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQMVAVN57GGGTK1C8HB9C','SKU-0024','itaque aut','pc',4109,0,1,NULL,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQMVDV6EEJYY6J4PBG85X','SKU-0025','illo quibusdam','pc',11159,0,1,NULL,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQMVH7FZZM9C1V7T15NBR','SKU-0026','animi id','pc',10425,0,1,NULL,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQMVMRMVW5B0EZTT5DKSY','SKU-0027','et rem','kg',2176,0,1,NULL,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQMVQAJ49T6GGRVH3KGZS','SKU-0028','et at','pc',9394,0,1,NULL,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQMVW1SVZMYHPX6YPTEZ2','SKU-0029','eaque sint','kg',9844,0,1,NULL,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQMVZ7J7HMPV396JKD1ZC','SKU-0030','fugiat aliquam','kg',13225,0,1,NULL,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL);
/*!40000 ALTER TABLE `stock_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stock_movements`
--

DROP TABLE IF EXISTS `stock_movements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `stock_movements` (
  `id` char(26) COLLATE utf8mb4_unicode_ci NOT NULL,
  `warehouse_id` char(26) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stock_item_id` char(26) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('receipt','withdrawal','adjustment','transfer_in','transfer_out') COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int NOT NULL,
  `reason` text COLLATE utf8mb4_unicode_ci,
  `ref_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ref_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `related_movement_id` char(26) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `stock_movements_warehouse_id_foreign` (`warehouse_id`),
  KEY `stock_movements_stock_item_id_foreign` (`stock_item_id`),
  KEY `stock_movements_related_movement_id_foreign` (`related_movement_id`),
  KEY `stock_movements_user_id_foreign` (`user_id`),
  KEY `stock_movements_type_index` (`type`),
  KEY `stock_movements_ref_type_ref_id_index` (`ref_type`,`ref_id`),
  KEY `stock_movements_created_at_index` (`created_at`),
  CONSTRAINT `stock_movements_related_movement_id_foreign` FOREIGN KEY (`related_movement_id`) REFERENCES `stock_movements` (`id`) ON DELETE SET NULL,
  CONSTRAINT `stock_movements_stock_item_id_foreign` FOREIGN KEY (`stock_item_id`) REFERENCES `stock_items` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `stock_movements_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `stock_movements_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock_movements`
--

LOCK TABLES `stock_movements` WRITE;
/*!40000 ALTER TABLE `stock_movements` DISABLE KEYS */;
INSERT INTO `stock_movements` VALUES ('01K40JQNBEHRF3BAKYSBCJP0FS','01K40JQMR53P3ZQWBMYJW2RGCN','01K40JQMSAE0Z3KHAVJBYT54W4','withdrawal',15,'Replenishment shipment','REPLENISHMENT_ORDER','01K40JQNAFVR08H1KADB971F1S',NULL,1,'2025-08-31 17:27:53','2025-08-31 17:27:53'),('01K40JQNBMEHCNH4FPYAQFDYD3','01K40JQMR53P3ZQWBMYJW2RGCN','01K40JQMSEP3JMZ8D1VTVVY1Q8','withdrawal',6,'Replenishment shipment','REPLENISHMENT_ORDER','01K40JQNAFVR08H1KADB971F1S',NULL,1,'2025-08-31 17:27:53','2025-08-31 17:27:53'),('01K40JQNBTT69YEXSF6H58PD7W','01K40JQMR53P3ZQWBMYJW2RGCN','01K40JQMSV8ACVYVDKDRPC18YG','withdrawal',17,'Replenishment shipment','REPLENISHMENT_ORDER','01K40JQNAFVR08H1KADB971F1S',NULL,1,'2025-08-31 17:27:53','2025-08-31 17:27:53'),('01K40JQNBYGZN5EGJF6Q6FMHA3','01K40JQMR53P3ZQWBMYJW2RGCN','01K40JQMSY930MSDZK29TWM94H','withdrawal',1,'Replenishment shipment','REPLENISHMENT_ORDER','01K40JQNAFVR08H1KADB971F1S',NULL,1,'2025-08-31 17:27:53','2025-08-31 17:27:53'),('01K40JQNC3PAA1GJXB7ZSJJVK2','01K40JQMR53P3ZQWBMYJW2RGCN','01K40JQMTPFA6ZW3B0KXQWEGDQ','withdrawal',14,'Replenishment shipment','REPLENISHMENT_ORDER','01K40JQNAFVR08H1KADB971F1S',NULL,1,'2025-08-31 17:27:53','2025-08-31 17:27:53'),('01K40JQNC76EYNWZVPJQCT4RDD','01K40JQMR53P3ZQWBMYJW2RGCN','01K40JQMVDV6EEJYY6J4PBG85X','withdrawal',7,'Replenishment shipment','REPLENISHMENT_ORDER','01K40JQNAFVR08H1KADB971F1S',NULL,1,'2025-08-31 17:27:53','2025-08-31 17:27:53'),('01K40JQNEEFCTFYYMRENBHR0H2','01K40JQMRD3D8KHGQ60TBHF9QZ','01K40JQMRQAD2R066JMJPAY6CX','withdrawal',9,'Replenishment shipment','REPLENISHMENT_ORDER','01K40JQNDAXC7J6K4DQ3SBVGDG',NULL,1,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNEK6SAFJQ4QW9S7PZFS','01K40JQMRD3D8KHGQ60TBHF9QZ','01K40JQMSAE0Z3KHAVJBYT54W4','withdrawal',13,'Replenishment shipment','REPLENISHMENT_ORDER','01K40JQNDAXC7J6K4DQ3SBVGDG',NULL,1,'2025-08-31 17:27:54','2025-08-31 17:27:54'),('01K40JQNEQK8V7WNG698MDQYGN','01K40JQMRD3D8KHGQ60TBHF9QZ','01K40JQMSQFFJE8RP0HKB1D570','withdrawal',1,'Replenishment shipment','REPLENISHMENT_ORDER','01K40JQNDAXC7J6K4DQ3SBVGDG',NULL,1,'2025-08-31 17:27:54','2025-08-31 17:27:54');
/*!40000 ALTER TABLE `stock_movements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subscription_items`
--

DROP TABLE IF EXISTS `subscription_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `subscription_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `subscription_id` bigint unsigned NOT NULL,
  `stripe_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stripe_product` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stripe_price` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `subscription_items_stripe_id_unique` (`stripe_id`),
  KEY `subscription_items_subscription_id_stripe_price_index` (`subscription_id`,`stripe_price`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subscription_items`
--

LOCK TABLES `subscription_items` WRITE;
/*!40000 ALTER TABLE `subscription_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `subscription_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subscriptions`
--

DROP TABLE IF EXISTS `subscriptions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `subscriptions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stripe_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stripe_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stripe_price` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` int DEFAULT NULL,
  `trial_ends_at` timestamp NULL DEFAULT NULL,
  `ends_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `subscriptions_stripe_id_unique` (`stripe_id`),
  KEY `subscriptions_user_id_stripe_status_index` (`user_id`,`stripe_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subscriptions`
--

LOCK TABLES `subscriptions` WRITE;
/*!40000 ALTER TABLE `subscriptions` DISABLE KEYS */;
/*!40000 ALTER TABLE `subscriptions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `truck_deployments`
--

DROP TABLE IF EXISTS `truck_deployments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `truck_deployments` (
  `id` char(26) COLLATE utf8mb4_unicode_ci NOT NULL,
  `truck_id` char(26) COLLATE utf8mb4_unicode_ci NOT NULL,
  `franchisee_id` char(26) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location_text` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `geo_lat` decimal(10,7) DEFAULT NULL,
  `geo_lng` decimal(10,7) DEFAULT NULL,
  `planned_start_at` timestamp NULL DEFAULT NULL,
  `planned_end_at` timestamp NULL DEFAULT NULL,
  `actual_start_at` timestamp NULL DEFAULT NULL,
  `actual_end_at` timestamp NULL DEFAULT NULL,
  `status` enum('planned','open','closed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `cancel_reason` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `truck_deployments_truck_id_index` (`truck_id`),
  KEY `truck_deployments_franchisee_id_index` (`franchisee_id`),
  KEY `truck_deployments_planned_start_at_index` (`planned_start_at`),
  KEY `truck_deployments_status_index` (`status`),
  CONSTRAINT `truck_deployments_franchisee_id_foreign` FOREIGN KEY (`franchisee_id`) REFERENCES `franchisees` (`id`) ON DELETE SET NULL,
  CONSTRAINT `truck_deployments_truck_id_foreign` FOREIGN KEY (`truck_id`) REFERENCES `trucks` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `truck_deployments`
--

LOCK TABLES `truck_deployments` WRITE;
/*!40000 ALTER TABLE `truck_deployments` DISABLE KEYS */;
INSERT INTO `truck_deployments` VALUES ('01K40JQN944S90RCQWZ2PBCP6W','01K40JQN74NHYMXBHQ6DTSZ02Z','01K40JQN6MTCVJC5NQZNRGETDE','88617 Wiegand Freeway Suite 402',48.9405211,2.3927165,'2025-08-01 17:27:53','2025-08-03 17:27:53','2025-08-01 17:27:53',NULL,'open',NULL,NULL,'2025-08-31 17:27:53','2025-08-31 17:27:53'),('01K40JQN98PAQV819J5WGN4EXJ','01K40JQN74NHYMXBHQ6DTSZ02Z','01K40JQN6MTCVJC5NQZNRGETDE','4891 Schroeder Parkway Suite 521',48.9243496,2.2836506,'2025-08-08 17:27:53','2025-08-10 17:27:53','2025-08-08 17:27:53','2025-08-10 17:27:53','open',NULL,NULL,'2025-08-31 17:27:53','2025-08-31 17:27:53'),('01K40JQN9DMDKH7SW3M1A476FS','01K40JQN79R4VMFMZQVFD58K6E','01K40JQN6R6SJ6724FRFA3TVS6','1078 Aleen Inlet',48.8762533,2.2793453,'2025-08-01 17:27:53','2025-08-03 17:27:53','2025-08-01 17:27:53',NULL,'open',NULL,NULL,'2025-08-31 17:27:53','2025-08-31 17:27:53'),('01K40JQN9JHNNEFMD29KYHKTKM','01K40JQN7DZ6YK0JKG5EPJV9C3','01K40JQN6W55YXQ6AKPEZBZ59V','893 Keebler Trail',48.8893745,2.3515613,'2025-08-01 17:27:53','2025-08-03 17:27:53','2025-08-01 17:27:53','2025-08-03 17:27:53','closed',NULL,NULL,'2025-08-31 17:27:53','2025-08-31 17:27:53'),('01K40JQN9QN6KCZERG3J9Z7WKT','01K40JQN7J716WX3J15R1XZDFF','01K40JQN6MTCVJC5NQZNRGETDE','559 Hermiston Crossing',48.8420141,2.3948916,'2025-08-01 17:27:53','2025-08-03 17:27:53','2025-08-01 17:27:53','2025-08-03 17:27:53','open',NULL,NULL,'2025-08-31 17:27:53','2025-08-31 17:27:53'),('01K40JQN9TY851GS44PVYA3P4X','01K40JQN7J716WX3J15R1XZDFF','01K40JQN6MTCVJC5NQZNRGETDE','428 Collier Burg',48.9248433,2.4362422,'2025-08-08 17:27:53','2025-08-10 17:27:53','2025-08-08 17:27:53',NULL,'closed',NULL,NULL,'2025-08-31 17:27:53','2025-08-31 17:27:53'),('01K40JQN9XXPZSYYS4M9YZ8J3G','01K40JQN7PR9HB0VP4DM8HAS6K','01K40JQN6R6SJ6724FRFA3TVS6','66865 Green Court',48.8474334,2.2951498,'2025-08-01 17:27:53','2025-08-03 17:27:53','2025-08-01 17:27:53',NULL,'closed',NULL,NULL,'2025-08-31 17:27:53','2025-08-31 17:27:53'),('01K40JQNA1R3DKJXX3CNP7BCYQ','01K40JQN7TCM8WRH6740FQ5NTE','01K40JQN6W55YXQ6AKPEZBZ59V','32358 Gislason Points Suite 411',48.9236799,2.2559234,'2025-08-01 17:27:53','2025-08-03 17:27:53','2025-08-01 17:27:53',NULL,'closed',NULL,NULL,'2025-08-31 17:27:53','2025-08-31 17:27:53'),('01K40JQNA53CT8MS62RDVZTTHW','01K40JQN7TCM8WRH6740FQ5NTE','01K40JQN6W55YXQ6AKPEZBZ59V','737 Layne Meadows',48.8508440,2.3009784,'2025-08-08 17:27:53','2025-08-10 17:27:53','2025-08-08 17:27:53',NULL,'closed',NULL,NULL,'2025-08-31 17:27:53','2025-08-31 17:27:53'),('01K40JQNA8RSKAFERR283ZY787','01K40JQN7TCM8WRH6740FQ5NTE','01K40JQN6W55YXQ6AKPEZBZ59V','3660 Legros Summit Apt. 485',48.8922010,2.2374793,'2025-08-15 17:27:53','2025-08-17 17:27:53','2025-08-15 17:27:53','2025-08-17 17:27:53','open',NULL,NULL,'2025-08-31 17:27:53','2025-08-31 17:27:53'),('01K40QHCR7MAPT6BF2Z9R5CWVJ','01K40JQN74NHYMXBHQ6DTSZ02Z','01K40JQN6MTCVJC5NQZNRGETDE','Paris 15eme',NULL,NULL,'2025-09-01 09:00:00','2025-09-01 18:00:00',NULL,NULL,'planned',NULL,NULL,'2025-08-31 18:51:51','2025-08-31 18:51:51');
/*!40000 ALTER TABLE `truck_deployments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `trucks`
--

DROP TABLE IF EXISTS `trucks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `trucks` (
  `id` char(26) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `plate` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `vin` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `make` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `model` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `year` smallint unsigned DEFAULT NULL,
  `status` enum('Draft','Active','InMaintenance','Retired') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Draft',
  `acquired_at` date DEFAULT NULL,
  `service_start` date DEFAULT NULL,
  `mileage_km` int unsigned DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `registration_doc_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `insurance_doc_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `franchisee_id` char(26) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `trucks_plate_unique` (`plate`),
  UNIQUE KEY `trucks_code_unique` (`code`),
  UNIQUE KEY `trucks_vin_unique` (`vin`),
  KEY `trucks_franchisee_id_foreign` (`franchisee_id`),
  KEY `trucks_status_index` (`status`),
  CONSTRAINT `trucks_franchisee_id_foreign` FOREIGN KEY (`franchisee_id`) REFERENCES `franchisees` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `trucks`
--

LOCK TABLES `trucks` WRITE;
/*!40000 ALTER TABLE `trucks` DISABLE KEYS */;
INSERT INTO `trucks` VALUES ('01K40JQN74NHYMXBHQ6DTSZ02Z','TRK-01','Truck 1','RF-233-BL','VF175005222272','Peugeot','Master',2018,'Active','2025-03-03','2024-12-01',21839,NULL,'private/docs/awJKpiwe.pdf','private/docs/9CVxUW6J.pdf','01K40JQN6MTCVJC5NQZNRGETDE','2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQN79R4VMFMZQVFD58K6E','TRK-02','Truck 2','GF-911-FD','VF128928280564','Renault','Sprinter',2023,'InMaintenance','2024-12-31','2025-05-31',147517,NULL,'private/docs/0v9QLwQW.pdf','private/docs/YttfLMmf.pdf','01K40JQN6R6SJ6724FRFA3TVS6','2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQN7DZ6YK0JKG5EPJV9C3','TRK-03','Truck 3','SW-026-IK','VF189530913670','Mercedes','Boxer',2017,'Retired','2023-12-31','2024-08-31',33980,NULL,'private/docs/PgLlYqTB.pdf','private/docs/5PfyX0zW.pdf','01K40JQN6W55YXQ6AKPEZBZ59V','2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQN7J716WX3J15R1XZDFF','TRK-04','Truck 4','MD-713-RI','VF193434358229','Citroën','Jumpy',2017,'Draft','2025-05-01','2024-08-31',127870,NULL,'private/docs/7bMbg7Xp.pdf','private/docs/7Nl9vGFe.pdf','01K40JQN6MTCVJC5NQZNRGETDE','2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQN7PR9HB0VP4DM8HAS6K','TRK-05','Truck 5','NK-790-TW','VF105735051332','Peugeot','Master',2018,'Active','2024-05-31','2024-12-31',54197,NULL,'private/docs/aYHC2BMx.pdf','private/docs/D6Dr5eup.pdf','01K40JQN6R6SJ6724FRFA3TVS6','2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQN7TCM8WRH6740FQ5NTE','TRK-06','Truck 6','BG-885-LH','VF173062938496','Citroën','Master',2023,'InMaintenance','2024-01-31','2025-03-31',80752,NULL,'private/docs/MbNf931B.pdf','private/docs/a1QUOeIw.pdf','01K40JQN6W55YXQ6AKPEZBZ59V','2025-08-31 17:27:53','2025-08-31 17:27:53',NULL);
/*!40000 ALTER TABLE `trucks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `franchisee_id` char(26) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `stripe_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pm_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pm_last_four` varchar(4) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trial_ends_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_franchisee_id_index` (`franchisee_id`),
  KEY `users_stripe_id_index` (`stripe_id`),
  CONSTRAINT `users_franchisee_id_foreign` FOREIGN KEY (`franchisee_id`) REFERENCES `franchisees` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,NULL,'Admin','admin@local.test','2025-08-31 17:27:52','$2y$12$aCtevOX7chno4GX7/Njvpe3F66YPKUi6YsBe1ZTnQsUPxTyIH57n6',NULL,'2025-08-31 17:27:52','2025-08-31 17:27:52',NULL,NULL,NULL,NULL),(2,NULL,'Warehouse','warehouse@local.test','2025-08-31 17:27:52','$2y$12$xt5vsIk6zrnHMuB3DVexcOnEaF1RM9rOCFzSUBgD18nCxlOBuONl2',NULL,'2025-08-31 17:27:52','2025-08-31 17:27:52',NULL,NULL,NULL,NULL),(3,NULL,'Fleet','fleet@local.test','2025-08-31 17:27:52','$2y$12$cHupw6h.InWv/pF8MQTHlO8buy7HE/4.tsdZh8/E5EgI2DqZD9ITu',NULL,'2025-08-31 17:27:52','2025-08-31 17:27:52',NULL,NULL,NULL,NULL),(4,NULL,'Franchisee 1','fr1@local.test','2025-08-31 17:27:53','$2y$12$4Kargdeur7lUBAr/GyX2iuRNecuYFkFGm/Ecdo9hY7O/zDLYkt1cG',NULL,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL,NULL,NULL,NULL),(5,NULL,'Franchisee 2','fr2@local.test','2025-08-31 17:27:53','$2y$12$eerC3oPuXB/U7IrMJlLuburt3Pfl1xpQAYO2U.jaZItRug2vKkKaq',NULL,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL,NULL,NULL,NULL),(6,NULL,'Franchisee 3','fr3@local.test','2025-08-31 17:27:53','$2y$12$4J9ZmxW5tRpp05V9EtKnMOX9upDSDN/PojZP6LL//W7EtZJs3i4Bm',NULL,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `warehouse_inventories`
--

DROP TABLE IF EXISTS `warehouse_inventories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `warehouse_inventories` (
  `id` char(26) COLLATE utf8mb4_unicode_ci NOT NULL,
  `warehouse_id` char(26) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stock_item_id` char(26) COLLATE utf8mb4_unicode_ci NOT NULL,
  `qty_on_hand` int NOT NULL DEFAULT '0',
  `min_qty` int DEFAULT NULL,
  `max_qty` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `warehouse_inventories_warehouse_id_stock_item_id_unique` (`warehouse_id`,`stock_item_id`),
  UNIQUE KEY `wh_inv_unique` (`warehouse_id`,`stock_item_id`),
  KEY `warehouse_inventories_stock_item_id_foreign` (`stock_item_id`),
  KEY `warehouse_inventories_qty_on_hand_index` (`qty_on_hand`),
  KEY `warehouse_inventories_min_qty_index` (`min_qty`),
  CONSTRAINT `warehouse_inventories_stock_item_id_foreign` FOREIGN KEY (`stock_item_id`) REFERENCES `stock_items` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `warehouse_inventories_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `chk_qty_on_hand_non_negative` CHECK ((`qty_on_hand` >= 0))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `warehouse_inventories`
--

LOCK TABLES `warehouse_inventories` WRITE;
/*!40000 ALTER TABLE `warehouse_inventories` DISABLE KEYS */;
INSERT INTO `warehouse_inventories` VALUES ('01K40JQMW74PEVCDTC1V02VBWC','01K40JQMRACAMEMWAC92SB2BF4','01K40JQMRQAD2R066JMJPAY6CX',29,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQMWAEF3S4C99TAEC5MBZ','01K40JQMRACAMEMWAC92SB2BF4','01K40JQMRW90SACGNZ481031VF',28,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQMWFM40C966TKAGPY5VY','01K40JQMRACAMEMWAC92SB2BF4','01K40JQMS0C7WQR3NVTN3RGQ7X',26,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQMWKQ21FXFMCFNQHYPBN','01K40JQMRACAMEMWAC92SB2BF4','01K40JQMS35DSZ5Y89XJK02YSY',30,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQMWPX34QGARFP8GR7M25','01K40JQMRACAMEMWAC92SB2BF4','01K40JQMS6XGK1X01HT0BDY76X',23,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQMWSGRNJQWMQSM43T18S','01K40JQMRACAMEMWAC92SB2BF4','01K40JQMSAE0Z3KHAVJBYT54W4',27,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQMWW4QG98720BQ7P4FKR','01K40JQMRACAMEMWAC92SB2BF4','01K40JQMSEP3JMZ8D1VTVVY1Q8',29,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQMX0H31YZFE1RRAGVH08','01K40JQMRACAMEMWAC92SB2BF4','01K40JQMSK0B7BXPK0XZTD0XZB',23,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQMX3KT98QQ00M46CCA7H','01K40JQMRACAMEMWAC92SB2BF4','01K40JQMSQFFJE8RP0HKB1D570',18,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQMX7X8A9K6WBKJZ4P2CB','01K40JQMRACAMEMWAC92SB2BF4','01K40JQMSV8ACVYVDKDRPC18YG',11,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQMXA8KJVG1YD3E6YPNKZ','01K40JQMRACAMEMWAC92SB2BF4','01K40JQMSY930MSDZK29TWM94H',15,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQMXECAAQ6QVJ8YTTHZD3','01K40JQMRACAMEMWAC92SB2BF4','01K40JQMT2BFFVRXWAE4HFRBPS',10,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQMXH3WM0DW6STQAA85WQ','01K40JQMRACAMEMWAC92SB2BF4','01K40JQMT69WBT1PYNSPGRXE38',25,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQMXP9MM4AH0FWP94R5DH','01K40JQMRACAMEMWAC92SB2BF4','01K40JQMT9PC1QRAN3A9EDVE3E',11,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQMXSM6X2QKGWGT01HX3S','01K40JQMRACAMEMWAC92SB2BF4','01K40JQMTDDC5V7N4ASHMCBEPP',12,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQMXWJ5PFJ8W3X6H8J7EB','01K40JQMRACAMEMWAC92SB2BF4','01K40JQMTG7NR85FH40JV3P47W',28,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQMY1Q4K67NNHYV0VW7YG','01K40JQMRACAMEMWAC92SB2BF4','01K40JQMTKZ0RR2N980DBE3MG8',18,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQMY5C9B1FEDGGJMG1NGM','01K40JQMRACAMEMWAC92SB2BF4','01K40JQMTPFA6ZW3B0KXQWEGDQ',24,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQMY86XQE5QKZAKA0Q5RD','01K40JQMRHACB4TN0AYNAFFVK1','01K40JQMRQAD2R066JMJPAY6CX',25,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQMYCRJJGAFHCB5DA2CTD','01K40JQMRHACB4TN0AYNAFFVK1','01K40JQMRW90SACGNZ481031VF',29,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQMYFRQJ7C0JWGGA5JFJT','01K40JQMRHACB4TN0AYNAFFVK1','01K40JQMS0C7WQR3NVTN3RGQ7X',18,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQMYKDRZ6G36G186NHAYZ','01K40JQMRHACB4TN0AYNAFFVK1','01K40JQMS35DSZ5Y89XJK02YSY',25,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQMYQM95SRHJ3CP284V62','01K40JQMRHACB4TN0AYNAFFVK1','01K40JQMS6XGK1X01HT0BDY76X',13,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQMYVFBF3RTG63V1JPWVF','01K40JQMRHACB4TN0AYNAFFVK1','01K40JQMSAE0Z3KHAVJBYT54W4',20,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQMYY3XJ9X957AVPY8W6Q','01K40JQMRHACB4TN0AYNAFFVK1','01K40JQMSEP3JMZ8D1VTVVY1Q8',25,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQMZ11CV4Z6Q0ABKAX04G','01K40JQMRHACB4TN0AYNAFFVK1','01K40JQMSK0B7BXPK0XZTD0XZB',27,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQMZ4S7VEZXGJHZJYNRBK','01K40JQMRHACB4TN0AYNAFFVK1','01K40JQMSQFFJE8RP0HKB1D570',26,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQMZ8AHN8WPG9GM1NATWD','01K40JQMRHACB4TN0AYNAFFVK1','01K40JQMSV8ACVYVDKDRPC18YG',29,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQMZB6JXFVRS2T3HKXA3C','01K40JQMRHACB4TN0AYNAFFVK1','01K40JQMSY930MSDZK29TWM94H',20,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQMZFKTS3ZS8XW251BKKQ','01K40JQMRHACB4TN0AYNAFFVK1','01K40JQMT2BFFVRXWAE4HFRBPS',17,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQMZJWKM1XHMGXKC9T18V','01K40JQMRHACB4TN0AYNAFFVK1','01K40JQMT69WBT1PYNSPGRXE38',15,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQMZPPSAXJ7STMYB0Q71H','01K40JQMRHACB4TN0AYNAFFVK1','01K40JQMT9PC1QRAN3A9EDVE3E',26,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQMZSYEG2S4VFVD2K4F4X','01K40JQMRHACB4TN0AYNAFFVK1','01K40JQMTDDC5V7N4ASHMCBEPP',23,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQMZW41EFKYD8P9QBJ9TK','01K40JQMRHACB4TN0AYNAFFVK1','01K40JQMTG7NR85FH40JV3P47W',27,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQN00N4SGHHNRC2V9F456','01K40JQMRHACB4TN0AYNAFFVK1','01K40JQMTKZ0RR2N980DBE3MG8',24,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQN031NEV96SJJ6ZZNH8A','01K40JQMRHACB4TN0AYNAFFVK1','01K40JQMTPFA6ZW3B0KXQWEGDQ',13,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQN07WQ4F9MSYQM5RZWW9','01K40JQMRHACB4TN0AYNAFFVK1','01K40JQMTTYWX9W7NM29RTE9FQ',28,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQN0C9M2TB9FKYKX2Q8MC','01K40JQMRHACB4TN0AYNAFFVK1','01K40JQMTXF0W5N9F59MD488AD',19,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQN0FV47XB72Z6C5K4Y86','01K40JQMRHACB4TN0AYNAFFVK1','01K40JQMV1RCV1SQRQ49HZK8CY',27,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQN0J695SGVJ81P5NZK6X','01K40JQMRHACB4TN0AYNAFFVK1','01K40JQMV4367TRSN828TX5NB0',21,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQN0PNSG0BW6704XA9R20','01K40JQMRHACB4TN0AYNAFFVK1','01K40JQMV70JVWE49MD9FHAW65',22,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQN0TA9VK17H701Z282ZS','01K40JQMRHACB4TN0AYNAFFVK1','01K40JQMVAVN57GGGTK1C8HB9C',18,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQN0YHP6GH5F9F7PEY0T9','01K40JQMRHACB4TN0AYNAFFVK1','01K40JQMVDV6EEJYY6J4PBG85X',26,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQN11KX42MT2AD41TMX36','01K40JQMRHACB4TN0AYNAFFVK1','01K40JQMVH7FZZM9C1V7T15NBR',11,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQN14Q97E42MKGXQ0EAKM','01K40JQMRHACB4TN0AYNAFFVK1','01K40JQMVMRMVW5B0EZTT5DKSY',27,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQN18HNYB72C9EV6H387G','01K40JQMRHACB4TN0AYNAFFVK1','01K40JQMVQAJ49T6GGRVH3KGZS',26,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQN1BMDNR03KBCZWNS6RE','01K40JQMRHACB4TN0AYNAFFVK1','01K40JQMVW1SVZMYHPX6YPTEZ2',26,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQN1FW2GEQV98N742V53G','01K40JQMRHACB4TN0AYNAFFVK1','01K40JQMVZ7J7HMPV396JKD1ZC',11,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQN1K7XVW3MWVABM0MPCE','01K40JQMR53P3ZQWBMYJW2RGCN','01K40JQMRQAD2R066JMJPAY6CX',26,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQN1P08P5WEKF5AZPJHC6','01K40JQMR53P3ZQWBMYJW2RGCN','01K40JQMRW90SACGNZ481031VF',30,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQN1SE4RVWR698AFCBTR0','01K40JQMR53P3ZQWBMYJW2RGCN','01K40JQMS0C7WQR3NVTN3RGQ7X',21,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQN1X7FJ56K3PYEMRQEMK','01K40JQMR53P3ZQWBMYJW2RGCN','01K40JQMS35DSZ5Y89XJK02YSY',13,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQN211ZS2NY97JDJXZ1TB','01K40JQMR53P3ZQWBMYJW2RGCN','01K40JQMS6XGK1X01HT0BDY76X',26,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQN24CK2EDME92F6AF9EW','01K40JQMR53P3ZQWBMYJW2RGCN','01K40JQMSAE0Z3KHAVJBYT54W4',14,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQN27BC353KWVDG97EB0W','01K40JQMR53P3ZQWBMYJW2RGCN','01K40JQMSEP3JMZ8D1VTVVY1Q8',21,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQN2AK9HAVD2G6QR9R7S3','01K40JQMR53P3ZQWBMYJW2RGCN','01K40JQMSK0B7BXPK0XZTD0XZB',25,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQN2FXEYBSGAJYFJVH3RY','01K40JQMR53P3ZQWBMYJW2RGCN','01K40JQMSQFFJE8RP0HKB1D570',10,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQN2KF35448BTFJVMV59Q','01K40JQMR53P3ZQWBMYJW2RGCN','01K40JQMSV8ACVYVDKDRPC18YG',11,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQN2PYKVMYPYS3BJF6AQP','01K40JQMR53P3ZQWBMYJW2RGCN','01K40JQMSY930MSDZK29TWM94H',14,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQN2S8MJE1EJBPREPN98P','01K40JQMR53P3ZQWBMYJW2RGCN','01K40JQMT2BFFVRXWAE4HFRBPS',15,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQN2W4W21SXK10Y91NBWR','01K40JQMR53P3ZQWBMYJW2RGCN','01K40JQMT69WBT1PYNSPGRXE38',23,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQN30A3N63B2YNP4YCXHP','01K40JQMR53P3ZQWBMYJW2RGCN','01K40JQMT9PC1QRAN3A9EDVE3E',23,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQN33VFD3YDVTJWT63D8F','01K40JQMR53P3ZQWBMYJW2RGCN','01K40JQMTDDC5V7N4ASHMCBEPP',11,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQN37G1ZMPGW4Q0N2Q7NB','01K40JQMR53P3ZQWBMYJW2RGCN','01K40JQMTG7NR85FH40JV3P47W',17,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQN3A9TSH7QSR508FR80S','01K40JQMR53P3ZQWBMYJW2RGCN','01K40JQMTKZ0RR2N980DBE3MG8',13,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQN3EYG8X1SCHRP3TFE2N','01K40JQMR53P3ZQWBMYJW2RGCN','01K40JQMTPFA6ZW3B0KXQWEGDQ',0,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQN3HE5100HVFK5VZYWE0','01K40JQMR53P3ZQWBMYJW2RGCN','01K40JQMTTYWX9W7NM29RTE9FQ',28,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQN3MVYA6Q4Q303PYXF5X','01K40JQMR53P3ZQWBMYJW2RGCN','01K40JQMTXF0W5N9F59MD488AD',27,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQN3Q8J0M8DBYK3GG452A','01K40JQMR53P3ZQWBMYJW2RGCN','01K40JQMV1RCV1SQRQ49HZK8CY',29,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQN3T1J9HMMQ540Y9F6WE','01K40JQMR53P3ZQWBMYJW2RGCN','01K40JQMV4367TRSN828TX5NB0',30,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQN3YYATAF3VAEJTM1N38','01K40JQMR53P3ZQWBMYJW2RGCN','01K40JQMV70JVWE49MD9FHAW65',18,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQN40EJ04W1H08EQNE4FE','01K40JQMR53P3ZQWBMYJW2RGCN','01K40JQMVAVN57GGGTK1C8HB9C',30,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQN4376TG3T2Q30FSY1N3','01K40JQMR53P3ZQWBMYJW2RGCN','01K40JQMVDV6EEJYY6J4PBG85X',21,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQN462SV8BKSBRP6PP295','01K40JQMR53P3ZQWBMYJW2RGCN','01K40JQMVH7FZZM9C1V7T15NBR',26,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQN4ATFFMDM82V30HWX3J','01K40JQMR53P3ZQWBMYJW2RGCN','01K40JQMVMRMVW5B0EZTT5DKSY',26,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQN4D6AG36434YG26CFJS','01K40JQMR53P3ZQWBMYJW2RGCN','01K40JQMVQAJ49T6GGRVH3KGZS',18,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQN4GRJDF2XMMZSYDPAY4','01K40JQMR53P3ZQWBMYJW2RGCN','01K40JQMVW1SVZMYHPX6YPTEZ2',22,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQN4NV28QS19RAGTHXKY6','01K40JQMR53P3ZQWBMYJW2RGCN','01K40JQMVZ7J7HMPV396JKD1ZC',17,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQN4RJM9SJSXMV46EJY10','01K40JQMRD3D8KHGQ60TBHF9QZ','01K40JQMRQAD2R066JMJPAY6CX',20,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:54',NULL),('01K40JQN4WGBN616QQCZ3440TM','01K40JQMRD3D8KHGQ60TBHF9QZ','01K40JQMRW90SACGNZ481031VF',15,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQN4ZRW26WQD1KXZ5H88X','01K40JQMRD3D8KHGQ60TBHF9QZ','01K40JQMS0C7WQR3NVTN3RGQ7X',13,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQN52VDSA3T24GNMTJJBY','01K40JQMRD3D8KHGQ60TBHF9QZ','01K40JQMS35DSZ5Y89XJK02YSY',16,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQN5514BF0STP2RS426Y9','01K40JQMRD3D8KHGQ60TBHF9QZ','01K40JQMS6XGK1X01HT0BDY76X',23,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQN5A5YSKYEQGXAAARCFB','01K40JQMRD3D8KHGQ60TBHF9QZ','01K40JQMSAE0Z3KHAVJBYT54W4',1,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:54',NULL),('01K40JQN5D7AN4C563KR20HEY9','01K40JQMRD3D8KHGQ60TBHF9QZ','01K40JQMSEP3JMZ8D1VTVVY1Q8',24,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQN5G7HHT1VM5KNK9QWAH','01K40JQMRD3D8KHGQ60TBHF9QZ','01K40JQMSK0B7BXPK0XZTD0XZB',11,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQN5KKATB8JRRXWB5SAF9','01K40JQMRD3D8KHGQ60TBHF9QZ','01K40JQMSQFFJE8RP0HKB1D570',27,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:54',NULL),('01K40JQN5P2RR0VSGMTZ85HVRA','01K40JQMRD3D8KHGQ60TBHF9QZ','01K40JQMSV8ACVYVDKDRPC18YG',11,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQN5SB13FMV7M0EM91FAJ','01K40JQMRD3D8KHGQ60TBHF9QZ','01K40JQMSY930MSDZK29TWM94H',28,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQN5W5PQJB0D24GHJWT1E','01K40JQMRD3D8KHGQ60TBHF9QZ','01K40JQMT2BFFVRXWAE4HFRBPS',16,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQN5Z93MPADKQ05S8JCAY','01K40JQMRD3D8KHGQ60TBHF9QZ','01K40JQMT69WBT1PYNSPGRXE38',29,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQN635PVYDMH0BPTXJW93','01K40JQMRD3D8KHGQ60TBHF9QZ','01K40JQMT9PC1QRAN3A9EDVE3E',17,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQN66Z4RPNJZBCG824AB9','01K40JQMRD3D8KHGQ60TBHF9QZ','01K40JQMTDDC5V7N4ASHMCBEPP',23,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQN69D3J07M0CBMNSQ3CX','01K40JQMRD3D8KHGQ60TBHF9QZ','01K40JQMTG7NR85FH40JV3P47W',26,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQN6CA851M5EWAPZ65P5N','01K40JQMRD3D8KHGQ60TBHF9QZ','01K40JQMTKZ0RR2N980DBE3MG8',23,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQN6FQTFMZ8B2XH3JCWRQ','01K40JQMRD3D8KHGQ60TBHF9QZ','01K40JQMTPFA6ZW3B0KXQWEGDQ',13,10,40,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQNEVYDECBRRFF16KDN83','01K40JQMRD3D8KHGQ60TBHF9QZ','01K40JQMTTYWX9W7NM29RTE9FQ',0,NULL,NULL,'2025-08-31 17:27:54','2025-08-31 17:27:54',NULL),('01K40JQNF0AW9RFWNQ78F3HY93','01K40JQMRD3D8KHGQ60TBHF9QZ','01K40JQMTXF0W5N9F59MD488AD',0,NULL,NULL,'2025-08-31 17:27:54','2025-08-31 17:27:54',NULL),('01K40JQNF3X97KCF9VNJNZK11W','01K40JQMRD3D8KHGQ60TBHF9QZ','01K40JQMVAVN57GGGTK1C8HB9C',0,NULL,NULL,'2025-08-31 17:27:54','2025-08-31 17:27:54',NULL),('01K40JQNF7S5PPN47B22AFHR92','01K40JQMRD3D8KHGQ60TBHF9QZ','01K40JQMVH7FZZM9C1V7T15NBR',0,NULL,NULL,'2025-08-31 17:27:54','2025-08-31 17:27:54',NULL);
/*!40000 ALTER TABLE `warehouse_inventories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `warehouses`
--

DROP TABLE IF EXISTS `warehouses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `warehouses` (
  `id` char(26) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `region` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `phone` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `warehouses_code_unique` (`code`),
  KEY `warehouses_code_is_active_index` (`code`,`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `warehouses`
--

LOCK TABLES `warehouses` WRITE;
/*!40000 ALTER TABLE `warehouses` DISABLE KEYS */;
INSERT INTO `warehouses` VALUES ('01K40JQMR53P3ZQWBMYJW2RGCN','WH-PAR','Paris Centre','Paris','IDF',NULL,NULL,NULL,1,NULL,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQMRACAMEMWAC92SB2BF4','WH-NE','Nord-Est','Saint-Denis','IDF',NULL,NULL,NULL,1,NULL,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQMRD3D8KHGQ60TBHF9QZ','WH-SUD','Sud','Vitry-sur-Seine','IDF',NULL,NULL,NULL,1,NULL,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL),('01K40JQMRHACB4TN0AYNAFFVK1','WH-OUEST','Ouest','Nanterre','IDF',NULL,NULL,NULL,1,NULL,'2025-08-31 17:27:53','2025-08-31 17:27:53',NULL);
/*!40000 ALTER TABLE `warehouses` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-08-31 19:01:34
