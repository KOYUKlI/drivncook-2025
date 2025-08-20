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
-- Temporary view structure for view `_order_item_totals`
--

DROP TABLE IF EXISTS `_order_item_totals`;
/*!50001 DROP VIEW IF EXISTS `_order_item_totals`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `_order_item_totals` AS SELECT 
 1 AS `customer_order_id`,
 1 AS `agg_total`*/;
SET character_set_client = @saved_cs_client;

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
-- Table structure for table `commissions`
--

DROP TABLE IF EXISTS `commissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `commissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `franchisee_id` bigint unsigned NOT NULL,
  `period_year` int NOT NULL,
  `period_month` tinyint unsigned NOT NULL,
  `turnover` decimal(12,2) NOT NULL,
  `rate` decimal(5,2) NOT NULL DEFAULT '4.00',
  `amount_due` decimal(12,2) GENERATED ALWAYS AS (round(((`turnover` * `rate`) / 100),2)) STORED,
  `status` enum('pending','paid','canceled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `calculated_at` timestamp NOT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_commissions_period` (`franchisee_id`,`period_year`,`period_month`),
  CONSTRAINT `commissions_franchisee_id_foreign` FOREIGN KEY (`franchisee_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `commissions`
--

LOCK TABLES `commissions` WRITE;
/*!40000 ALTER TABLE `commissions` DISABLE KEYS */;
INSERT INTO `commissions` (`id`, `franchisee_id`, `period_year`, `period_month`, `turnover`, `rate`, `status`, `calculated_at`, `paid_at`, `created_at`, `updated_at`) VALUES (1,2,2025,7,0.00,4.00,'pending','2025-08-12 05:16:16',NULL,'2025-08-12 05:16:16','2025-08-12 05:16:16'),(2,3,2025,7,0.00,4.00,'pending','2025-08-12 05:16:16',NULL,'2025-08-12 05:16:16','2025-08-12 05:16:16'),(3,4,2025,7,0.00,4.00,'pending','2025-08-12 05:16:16',NULL,'2025-08-12 05:16:16','2025-08-12 05:16:16'),(4,5,2025,7,0.00,4.00,'pending','2025-08-12 05:16:16',NULL,'2025-08-12 05:16:16','2025-08-12 05:16:16');
/*!40000 ALTER TABLE `commissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `compliance_kpis`
--

DROP TABLE IF EXISTS `compliance_kpis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `compliance_kpis` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `franchise_id` bigint unsigned NOT NULL,
  `period_year` int NOT NULL,
  `period_month` tinyint unsigned NOT NULL,
  `external_turnover` decimal(12,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_kpi_period` (`franchise_id`,`period_year`,`period_month`),
  CONSTRAINT `compliance_kpis_franchise_id_foreign` FOREIGN KEY (`franchise_id`) REFERENCES `franchises` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `compliance_kpis`
--

LOCK TABLES `compliance_kpis` WRITE;
/*!40000 ALTER TABLE `compliance_kpis` DISABLE KEYS */;
/*!40000 ALTER TABLE `compliance_kpis` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customer_orders`
--

DROP TABLE IF EXISTS `customer_orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customer_orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `truck_id` bigint unsigned NOT NULL,
  `customer_id` bigint unsigned DEFAULT NULL,
  `location_id` bigint unsigned DEFAULT NULL,
  `loyalty_card_id` bigint unsigned DEFAULT NULL,
  `order_type` enum('online','on_site','reservation') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'online',
  `status` enum('pending','confirmed','preparing','ready','completed','canceled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `payment_status` enum('pending','paid','failed','refunded') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `reference` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pickup_at` datetime DEFAULT NULL,
  `total_price` decimal(12,2) NOT NULL,
  `ordered_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `ulid` char(26) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_orders_ref` (`reference`),
  UNIQUE KEY `customer_orders_ulid_unique` (`ulid`),
  KEY `customer_orders_loyalty_card_id_foreign` (`loyalty_card_id`),
  KEY `customer_orders_location_id_foreign` (`location_id`),
  KEY `customer_orders_truck_id_ordered_at_index` (`truck_id`,`ordered_at`),
  KEY `customer_orders_customer_id_foreign` (`customer_id`),
  CONSTRAINT `customer_orders_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `customer_orders_location_id_foreign` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE SET NULL,
  CONSTRAINT `customer_orders_loyalty_card_id_foreign` FOREIGN KEY (`loyalty_card_id`) REFERENCES `loyalty_cards` (`id`) ON DELETE SET NULL,
  CONSTRAINT `customer_orders_truck_id_foreign` FOREIGN KEY (`truck_id`) REFERENCES `trucks` (`id`) ON DELETE CASCADE,
  CONSTRAINT `chk_completed_paid` CHECK (((`status` <> _utf8mb4'completed') or (`payment_status` = _utf8mb4'paid'))),
  CONSTRAINT `chk_online_ref` CHECK ((((`order_type` = _utf8mb4'online') and (`reference` is not null)) or (`order_type` <> _utf8mb4'online'))),
  CONSTRAINT `chk_orders_reservation` CHECK ((((`order_type` = _utf8mb4'reservation') and (`pickup_at` is not null)) or (`order_type` <> _utf8mb4'reservation')))
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customer_orders`
--

LOCK TABLES `customer_orders` WRITE;
/*!40000 ALTER TABLE `customer_orders` DISABLE KEYS */;
INSERT INTO `customer_orders` VALUES (1,1,NULL,NULL,NULL,'on_site','completed','paid',NULL,NULL,11.00,'2025-08-10 05:15:17','2025-08-12 05:15:17','2025-08-12 05:15:17','01K2EB8HXHFBDZFDPR28GYEM4P'),(2,1,NULL,NULL,NULL,'on_site','completed','paid',NULL,NULL,2.50,'2025-08-09 22:15:17','2025-08-12 05:15:17','2025-08-12 05:15:17','01K2EB8HZDEH8K5A3071VC59DK'),(3,1,NULL,NULL,NULL,'on_site','completed','paid',NULL,NULL,45.60,'2025-08-11 07:15:17','2025-08-12 05:15:17','2025-08-12 05:15:17','01K2EB8J0MA4EVP0Q9AG47NATW'),(4,1,NULL,NULL,NULL,'on_site','completed','paid',NULL,NULL,43.20,'2025-08-11 19:15:17','2025-08-12 05:15:17','2025-08-12 05:15:17','01K2EB8J5Z0163D60JE0AFRHX5'),(5,2,NULL,NULL,NULL,'on_site','completed','paid',NULL,NULL,2.00,'2025-08-11 11:15:17','2025-08-12 05:15:17','2025-08-12 05:15:17','01K2EB8J8RA9R0WY9EX1YNF43P'),(6,2,NULL,NULL,NULL,'on_site','completed','paid',NULL,NULL,41.60,'2025-08-11 21:15:17','2025-08-12 05:15:17','2025-08-12 05:15:17','01K2EB8J9KXP6X989AG8M7H340'),(7,2,NULL,NULL,NULL,'on_site','completed','paid',NULL,NULL,45.20,'2025-08-10 20:15:17','2025-08-12 05:15:17','2025-08-12 05:15:17','01K2EB8JBD2QFC1FETTBV9EJS2'),(8,2,NULL,NULL,NULL,'on_site','completed','paid',NULL,NULL,55.50,'2025-08-10 08:15:17','2025-08-12 05:15:17','2025-08-12 05:15:17','01K2EB8JE1T0QNZBMFAQPGJECY'),(9,2,NULL,NULL,NULL,'on_site','completed','paid',NULL,NULL,7.00,'2025-08-10 10:15:17','2025-08-12 05:15:17','2025-08-12 05:15:17','01K2EB8JFYB3AT5SCGN6AQ5YWG'),(10,3,NULL,NULL,NULL,'on_site','completed','paid',NULL,NULL,45.20,'2025-08-09 22:15:17','2025-08-12 05:15:17','2025-08-12 05:15:18','01K2EB8JH9C93H46XDVY59N5GH'),(11,3,NULL,NULL,NULL,'on_site','completed','paid',NULL,NULL,46.20,'2025-08-10 08:15:18','2025-08-12 05:15:18','2025-08-12 05:15:18','01K2EB8JM2TF12TA3WSD5QSWKX'),(12,3,NULL,NULL,NULL,'on_site','completed','paid',NULL,NULL,40.20,'2025-08-10 19:15:18','2025-08-12 05:15:18','2025-08-12 05:15:18','01K2EB8JP0ZHBH1QWSMCBZ797E'),(13,3,NULL,NULL,NULL,'on_site','completed','paid',NULL,NULL,3.50,'2025-08-10 02:15:18','2025-08-12 05:15:18','2025-08-12 05:15:18','01K2EB8JQYNHF03RZBC9MENPGW'),(14,3,NULL,NULL,NULL,'on_site','completed','paid',NULL,NULL,43.60,'2025-08-11 21:15:18','2025-08-12 05:15:18','2025-08-12 05:15:18','01K2EB8JS8T0NFT1RWHS1DWEZ3'),(15,4,NULL,NULL,NULL,'on_site','completed','paid',NULL,NULL,7.50,'2025-08-10 20:15:18','2025-08-12 05:15:18','2025-08-12 05:15:18','01K2EB8JV5QSHRYRG65CQJ8PMJ'),(16,4,NULL,NULL,NULL,'on_site','completed','paid',NULL,NULL,41.70,'2025-08-10 01:15:18','2025-08-12 05:15:18','2025-08-12 05:15:18','01K2EB8JWEGWSCJV9MA8BYZWM8'),(17,4,NULL,NULL,NULL,'on_site','completed','paid',NULL,NULL,7.00,'2025-08-10 09:15:18','2025-08-12 05:15:18','2025-08-12 05:15:18','01K2EB8JXW75GHZ5BRCZE49TB9'),(18,4,NULL,NULL,NULL,'on_site','completed','paid',NULL,NULL,19.80,'2025-08-09 17:15:18','2025-08-12 05:15:18','2025-08-12 05:15:18','01K2EB8JZ2T4ZQ48D0GBAHNKQ7'),(19,4,NULL,NULL,NULL,'on_site','completed','paid',NULL,NULL,41.60,'2025-08-09 18:15:18','2025-08-12 05:15:18','2025-08-12 05:15:18','01K2EB8K0VHHAGP7H0CY1GHRXA'),(20,5,NULL,NULL,NULL,'on_site','completed','paid',NULL,NULL,27.40,'2025-08-09 21:15:18','2025-08-12 05:15:18','2025-08-12 05:15:18','01K2EB8K3P4XN8KMB9PNHQTFDR'),(21,5,NULL,NULL,NULL,'on_site','completed','paid',NULL,NULL,27.30,'2025-08-10 15:15:18','2025-08-12 05:15:18','2025-08-12 05:15:18','01K2EB8K6581G5J0QEQ8Z160AJ'),(22,5,NULL,NULL,NULL,'on_site','completed','paid',NULL,NULL,11.90,'2025-08-11 01:15:18','2025-08-12 05:15:18','2025-08-12 05:15:18','01K2EB8K7NA0JX0ZKG3W1D4PNA'),(23,5,NULL,NULL,NULL,'on_site','completed','paid',NULL,NULL,23.80,'2025-08-11 17:15:18','2025-08-12 05:15:18','2025-08-12 05:15:18','01K2EB8K8VNF5VAWQT6831Q2K9'),(24,5,NULL,NULL,NULL,'on_site','completed','paid',NULL,NULL,20.90,'2025-08-10 19:15:18','2025-08-12 05:15:18','2025-08-12 05:15:18','01K2EB8KA0V3JTNDGNW2DVN442'),(25,6,NULL,NULL,NULL,'on_site','completed','paid',NULL,NULL,24.90,'2025-08-11 01:15:18','2025-08-12 05:15:18','2025-08-12 05:15:18','01K2EB8KCTXC57W9J1WGZD9YVE'),(26,6,NULL,NULL,NULL,'on_site','completed','paid',NULL,NULL,43.60,'2025-08-10 06:15:18','2025-08-12 05:15:18','2025-08-12 05:15:18','01K2EB8KFD2J703E3ZY5937SK0'),(27,6,NULL,NULL,NULL,'on_site','completed','paid',NULL,NULL,19.40,'2025-08-11 16:15:18','2025-08-12 05:15:18','2025-08-12 05:15:19','01K2EB8KH9CEDMWNSASZGP52EF'),(28,6,NULL,NULL,NULL,'on_site','completed','paid',NULL,NULL,38.70,'2025-08-11 21:15:19','2025-08-12 05:15:19','2025-08-12 05:15:19','01K2EB8KM72ZA6PQ6GWGE8AVNV'),(29,6,NULL,NULL,NULL,'on_site','completed','paid',NULL,NULL,34.70,'2025-08-11 02:15:19','2025-08-12 05:15:19','2025-08-12 05:15:19','01K2EB8KPS0WEXB402BF8N1W9N');
/*!40000 ALTER TABLE `customer_orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dish_ingredients`
--

DROP TABLE IF EXISTS `dish_ingredients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dish_ingredients` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `dish_id` bigint unsigned NOT NULL,
  `supply_id` bigint unsigned NOT NULL,
  `qty_per_dish` decimal(12,3) NOT NULL,
  `unit` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `ulid` char(26) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_bom` (`dish_id`,`supply_id`),
  UNIQUE KEY `dish_ingredients_ulid_unique` (`ulid`),
  KEY `dish_ingredients_supply_id_foreign` (`supply_id`),
  CONSTRAINT `dish_ingredients_dish_id_foreign` FOREIGN KEY (`dish_id`) REFERENCES `dishes` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `dish_ingredients_supply_id_foreign` FOREIGN KEY (`supply_id`) REFERENCES `supplies` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dish_ingredients`
--

LOCK TABLES `dish_ingredients` WRITE;
/*!40000 ALTER TABLE `dish_ingredients` DISABLE KEYS */;
INSERT INTO `dish_ingredients` VALUES (1,1,4,1.000,'pc','2025-08-12 05:15:15','2025-08-12 05:15:15','01K2EB8GJYS1HJPY397GQJK47R'),(2,1,5,0.180,'kg','2025-08-12 05:15:15','2025-08-12 05:15:15','01K2EB8GKFE4RH2MRKJDRWCWX9'),(3,1,6,0.035,'kg','2025-08-12 05:15:15','2025-08-12 05:15:15','01K2EB8GM0PVGNY70MCWXGDJQE'),(4,1,7,0.020,'kg','2025-08-12 05:15:15','2025-08-12 05:15:15','01K2EB8GM6NC9KTF7BR18QKG93'),(5,1,12,0.015,'kg','2025-08-12 05:15:15','2025-08-12 05:15:15','01K2EB8GMR92SXRFZSF7PP1QTV'),(6,1,13,0.050,'kg','2025-08-12 05:15:15','2025-08-12 05:15:15','01K2EB8GMW9FYF7D028P4SJ7Y5'),(7,1,8,0.020,'kg','2025-08-12 05:15:16','2025-08-12 05:15:16','01K2EB8GND24BXY8V8R88C8MN4'),(8,2,4,1.000,'pc','2025-08-12 05:15:16','2025-08-12 05:15:16','01K2EB8GP2W6XD7BKQSFP25AZZ'),(9,2,2,0.150,'kg','2025-08-12 05:15:16','2025-08-12 05:15:16','01K2EB8GPK8XR2XREFCA55JQTK'),(10,2,3,0.030,'kg','2025-08-12 05:15:16','2025-08-12 05:15:16','01K2EB8GPQQ2PQQBE6F3ZP9R39'),(11,2,12,0.012,'kg','2025-08-12 05:15:16','2025-08-12 05:15:16','01K2EB8GQAKPDWQT5XYRJD4JMC'),(12,2,13,0.040,'kg','2025-08-12 05:15:16','2025-08-12 05:15:16','01K2EB8GQEQQ6WJA33EYTC1CD8'),(13,3,9,0.180,'kg','2025-08-12 05:15:16','2025-08-12 05:15:16','01K2EB8GRE0FXM8ZGKNZ3J8K82'),(14,4,10,1.000,'pc','2025-08-12 05:15:16','2025-08-12 05:15:16','01K2EB8GS209TYCD3SDQJ1HMQV'),(15,5,11,1.000,'pc','2025-08-12 05:15:16','2025-08-12 05:15:16','01K2EB8GSQMB3JRYFNR54SWMEF');
/*!40000 ALTER TABLE `dish_ingredients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dish_translations`
--

DROP TABLE IF EXISTS `dish_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dish_translations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `dish_id` bigint unsigned NOT NULL,
  `locale` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_dish_locale` (`dish_id`,`locale`),
  CONSTRAINT `dish_translations_dish_id_foreign` FOREIGN KEY (`dish_id`) REFERENCES `dishes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dish_translations`
--

LOCK TABLES `dish_translations` WRITE;
/*!40000 ALTER TABLE `dish_translations` DISABLE KEYS */;
/*!40000 ALTER TABLE `dish_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dishes`
--

DROP TABLE IF EXISTS `dishes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dishes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price` decimal(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `ulid` char(26) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `dishes_ulid_unique` (`ulid`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dishes`
--

LOCK TABLES `dishes` WRITE;
/*!40000 ALTER TABLE `dishes` DISABLE KEYS */;
INSERT INTO `dishes` VALUES (1,'Burger Signature','Burger Signature',11.90,'2025-08-12 05:15:15','2025-08-12 05:15:15','01K2EB8GJTGQGSDDC0DGS8X8Z6'),(2,'Burger Classique','Burger Classique',9.90,'2025-08-12 05:15:16','2025-08-12 05:15:16','01K2EB8GNHXT18KYG05BBZ9WHP'),(3,'Frites Portion','Frites Portion',3.50,'2025-08-12 05:15:16','2025-08-12 05:15:16','01K2EB8GQX9YH2E1A3B6PHM5K3'),(4,'Boisson Cola 33cl','Boisson Cola 33cl',2.50,'2025-08-12 05:15:16','2025-08-12 05:15:16','01K2EB8GRHDD28Z5WAFR91S6SF'),(5,'Eau 50cl','Eau 50cl',2.00,'2025-08-12 05:15:16','2025-08-12 05:15:16','01K2EB8GSJZ67NFH83C7BN7D67');
/*!40000 ALTER TABLE `dishes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `event_registrations`
--

DROP TABLE IF EXISTS `event_registrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `event_registrations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `event_id` bigint unsigned NOT NULL,
  `truck_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `ulid` char(26) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_event_truck` (`event_id`,`truck_id`),
  UNIQUE KEY `event_registrations_ulid_unique` (`ulid`),
  KEY `event_registrations_truck_id_foreign` (`truck_id`),
  CONSTRAINT `event_registrations_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  CONSTRAINT `event_registrations_truck_id_foreign` FOREIGN KEY (`truck_id`) REFERENCES `trucks` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `event_registrations`
--

LOCK TABLES `event_registrations` WRITE;
/*!40000 ALTER TABLE `event_registrations` DISABLE KEYS */;
/*!40000 ALTER TABLE `event_registrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `events` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date DEFAULT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `events`
--

LOCK TABLES `events` WRITE;
/*!40000 ALTER TABLE `events` DISABLE KEYS */;
/*!40000 ALTER TABLE `events` ENABLE KEYS */;
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
-- Temporary view structure for view `franchise_monthly_purchase_mix`
--

DROP TABLE IF EXISTS `franchise_monthly_purchase_mix`;
/*!50001 DROP VIEW IF EXISTS `franchise_monthly_purchase_mix`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `franchise_monthly_purchase_mix` AS SELECT 
 1 AS `franchisee_id`,
 1 AS `year`,
 1 AS `month`,
 1 AS `internal_amount`,
 1 AS `external_amount`,
 1 AS `official_pct`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `franchises`
--

DROP TABLE IF EXISTS `franchises`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `franchises` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `ulid` char(26) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_franchises_name` (`name`),
  UNIQUE KEY `franchises_ulid_unique` (`ulid`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `franchises`
--

LOCK TABLES `franchises` WRITE;
/*!40000 ALTER TABLE `franchises` DISABLE KEYS */;
INSERT INTO `franchises` VALUES (1,'Demo Franchise','2025-08-12 05:15:14','2025-08-12 05:15:14','01K2EB8FJTZ57NVTH6498D0S79'),(2,'Franchise Paris Centre','2025-08-12 05:15:15','2025-08-12 05:15:15','01K2EB8FYEMMYVNA380XWQ5Z9F'),(3,'Franchise Ouest IDF','2025-08-12 05:15:15','2025-08-12 05:15:15','01K2EB8G5DF0TKKNEB8T123Z63'),(4,'Franchise Sud IDF','2025-08-12 05:15:15','2025-08-12 05:15:15','01K2EB8GBWV15GD8X9YJ50N6KE');
/*!40000 ALTER TABLE `franchises` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventory`
--

DROP TABLE IF EXISTS `inventory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventory` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `warehouse_id` bigint unsigned NOT NULL,
  `supply_id` bigint unsigned NOT NULL,
  `on_hand` decimal(12,3) NOT NULL DEFAULT '0.000',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `ulid` char(26) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_inventory` (`warehouse_id`,`supply_id`),
  UNIQUE KEY `inventory_ulid_unique` (`ulid`),
  KEY `inventory_supply_id_foreign` (`supply_id`),
  KEY `ix_inv_wh_supply` (`warehouse_id`,`supply_id`),
  CONSTRAINT `inventory_supply_id_foreign` FOREIGN KEY (`supply_id`) REFERENCES `supplies` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `inventory_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventory`
--

LOCK TABLES `inventory` WRITE;
/*!40000 ALTER TABLE `inventory` DISABLE KEYS */;
INSERT INTO `inventory` VALUES (1,4,4,15.000,'2025-08-12 05:15:16','2025-08-12 05:15:16','01K2EB8GWVCHGMRV3EBD766CHH'),(2,4,13,33.000,'2025-08-12 05:15:16','2025-08-12 05:15:16','01K2EB8GWX6EABXVMP169D8714'),(3,4,12,34.000,'2025-08-12 05:15:16','2025-08-12 05:15:16','01K2EB8GWZ5CX69WCHCJY24NVQ'),(4,4,10,31.000,'2025-08-12 05:15:16','2025-08-12 05:15:16','01K2EB8GX1NXS7M6Z22G78XBSW'),(5,4,3,37.000,'2025-08-12 05:15:16','2025-08-12 05:15:16','01K2EB8GX3Z036RGDBJEAX99FH'),(6,4,5,33.000,'2025-08-12 05:15:16','2025-08-12 05:15:16','01K2EB8H00Q1FET3YN70YTN625'),(7,4,1,4.000,'2025-08-12 05:15:16','2025-08-12 05:15:16','01K2EB8H3JGH8H3EZXZA2EDAZK'),(8,4,2,13.000,'2025-08-12 05:15:16','2025-08-12 05:15:16','01K2EB8H6BM03X68CJ2AQBVQJV'),(9,6,12,15.000,'2025-08-12 05:15:16','2025-08-12 05:15:16','01K2EB8H93BQCS1FR6MMMGVT9A'),(10,6,2,22.000,'2025-08-12 05:15:16','2025-08-12 05:15:16','01K2EB8H94Q0RHM3CTGM66VZH8'),(11,6,5,30.000,'2025-08-12 05:15:16','2025-08-12 05:15:16','01K2EB8H96CQYTDMMDGM1RS15E'),(12,6,10,35.000,'2025-08-12 05:15:16','2025-08-12 05:15:16','01K2EB8H974NXSAPH8WXCP8RBT'),(13,6,4,34.000,'2025-08-12 05:15:16','2025-08-12 05:15:16','01K2EB8H99BY33CAAS78NQDJRS'),(14,6,1,9.000,'2025-08-12 05:15:16','2025-08-12 05:15:16','01K2EB8HBQFESGE0KSC03NGW7W'),(15,6,3,18.000,'2025-08-12 05:15:16','2025-08-12 05:15:16','01K2EB8HBWFRKKMW3RVJ8PQV23'),(16,8,12,27.000,'2025-08-12 05:15:16','2025-08-12 05:15:17','01K2EB8HKYASBR2SYHZXMWA0G9'),(17,8,4,34.000,'2025-08-12 05:15:16','2025-08-12 05:15:17','01K2EB8HM0YKCTZFSQDXTJZY8E'),(18,8,2,41.000,'2025-08-12 05:15:16','2025-08-12 05:15:17','01K2EB8HM2BTB6WGQRD7QZ3EVR'),(19,8,13,17.000,'2025-08-12 05:15:16','2025-08-12 05:15:17','01K2EB8HM35R34J8RXHJ87AD1T'),(20,8,5,13.000,'2025-08-12 05:15:16','2025-08-12 05:15:17','01K2EB8HM4NB9Q0M97FSM99M9H'),(21,8,3,26.000,'2025-08-12 05:15:17','2025-08-12 05:15:17','01K2EB8HPYP74ZTS24S3EGYM70'),(22,8,1,20.000,'2025-08-12 05:15:17','2025-08-12 05:15:17','01K2EB8HQ1Z1AGB40RWJRF63P3'),(23,8,10,12.000,'2025-08-12 05:15:17','2025-08-12 05:15:17','01K2EB8HQ3S1DD6BK2R7KCA6WZ');
/*!40000 ALTER TABLE `inventory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventory_adjustments`
--

DROP TABLE IF EXISTS `inventory_adjustments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventory_adjustments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `inventory_id` bigint unsigned NOT NULL,
  `qty_diff` decimal(12,3) NOT NULL,
  `reason` enum('waste','breakage','audit') COLLATE utf8mb4_unicode_ci NOT NULL,
  `note` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `inventory_adjustments_inventory_id_foreign` (`inventory_id`),
  CONSTRAINT `inventory_adjustments_inventory_id_foreign` FOREIGN KEY (`inventory_id`) REFERENCES `inventory` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventory_adjustments`
--

LOCK TABLES `inventory_adjustments` WRITE;
/*!40000 ALTER TABLE `inventory_adjustments` DISABLE KEYS */;
/*!40000 ALTER TABLE `inventory_adjustments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventory_lots`
--

DROP TABLE IF EXISTS `inventory_lots`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventory_lots` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `inventory_id` bigint unsigned NOT NULL,
  `lot_code` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expires_at` date DEFAULT NULL,
  `qty` decimal(12,3) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_invlot` (`inventory_id`,`lot_code`),
  CONSTRAINT `inventory_lots_inventory_id_foreign` FOREIGN KEY (`inventory_id`) REFERENCES `inventory` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventory_lots`
--

LOCK TABLES `inventory_lots` WRITE;
/*!40000 ALTER TABLE `inventory_lots` DISABLE KEYS */;
/*!40000 ALTER TABLE `inventory_lots` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventory_movements`
--

DROP TABLE IF EXISTS `inventory_movements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventory_movements` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `inventory_id` bigint unsigned NOT NULL,
  `type` enum('in','out','transfer') COLLATE utf8mb4_unicode_ci NOT NULL,
  `qty` decimal(12,3) NOT NULL,
  `reason` enum('purchase','sale','prep','waste','adjust','transfer') COLLATE utf8mb4_unicode_ci NOT NULL,
  `ref_table` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ref_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ulid` char(26) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `inventory_movements_ulid_unique` (`ulid`),
  KEY `inventory_movements_inventory_id_foreign` (`inventory_id`),
  CONSTRAINT `inventory_movements_inventory_id_foreign` FOREIGN KEY (`inventory_id`) REFERENCES `inventory` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventory_movements`
--

LOCK TABLES `inventory_movements` WRITE;
/*!40000 ALTER TABLE `inventory_movements` DISABLE KEYS */;
INSERT INTO `inventory_movements` VALUES (1,1,'in',7.000,'purchase','stock_orders',1,'2025-08-12 05:15:16','01K2EB8GWWK84J4XXMN073EX49'),(2,2,'in',14.000,'purchase','stock_orders',1,'2025-08-12 05:15:16','01K2EB8GWYY39ZMPXFFV8DBJVB'),(3,3,'in',15.000,'purchase','stock_orders',1,'2025-08-12 05:15:16','01K2EB8GX0GFBS1P53BW9MCA0F'),(4,4,'in',14.000,'purchase','stock_orders',1,'2025-08-12 05:15:16','01K2EB8GX2VV6CHV3AF2DWP89M'),(5,5,'in',9.000,'purchase','stock_orders',1,'2025-08-12 05:15:16','01K2EB8GX3Z036RGDBJEAX99FJ'),(6,3,'in',7.000,'purchase','stock_orders',2,'2025-08-12 05:15:16','01K2EB8GZZ41105ZZKZHPGPC64'),(7,6,'in',10.000,'purchase','stock_orders',2,'2025-08-12 05:15:16','01K2EB8H011ZEGTN0GJRAKWDHE'),(8,4,'in',4.000,'purchase','stock_orders',2,'2025-08-12 05:15:16','01K2EB8H02N7B89RW0PQX2KDS3'),(9,2,'in',5.000,'purchase','stock_orders',2,'2025-08-12 05:15:16','01K2EB8H03P9CJ4ETRTKE8WTF7'),(10,5,'in',13.000,'purchase','stock_orders',2,'2025-08-12 05:15:16','01K2EB8H05M2A20MRAJ6KSAM3G'),(11,7,'in',4.000,'purchase','stock_orders',3,'2025-08-12 05:15:16','01K2EB8H3MVGHYYNYBM6VNY8KK'),(12,4,'in',6.000,'purchase','stock_orders',3,'2025-08-12 05:15:16','01K2EB8H3N4ZDK11W143FDKQ2H'),(13,5,'in',15.000,'purchase','stock_orders',3,'2025-08-12 05:15:16','01K2EB8H3P7VK9KNWJBMCA8TVJ'),(14,3,'in',12.000,'purchase','stock_orders',3,'2025-08-12 05:15:16','01K2EB8H3Q24Q4YPP64A6YR965'),(15,6,'in',8.000,'purchase','stock_orders',3,'2025-08-12 05:15:16','01K2EB8H3S6AFYJXGE5C3CHWVB'),(16,6,'in',15.000,'purchase','stock_orders',4,'2025-08-12 05:15:16','01K2EB8H67GTJEADEAM5GTSCR1'),(17,2,'in',14.000,'purchase','stock_orders',4,'2025-08-12 05:15:16','01K2EB8H696J2SG81JCSSX3ZPN'),(18,4,'in',7.000,'purchase','stock_orders',4,'2025-08-12 05:15:16','01K2EB8H6ATAWE8J58A8XYP6G4'),(19,8,'in',13.000,'purchase','stock_orders',4,'2025-08-12 05:15:16','01K2EB8H6CK4DNFP37W6R2N27X'),(20,1,'in',8.000,'purchase','stock_orders',4,'2025-08-12 05:15:16','01K2EB8H6D5NA25N949DGVYKBE'),(21,9,'in',8.000,'purchase','stock_orders',5,'2025-08-12 05:15:16','01K2EB8H94Q0RHM3CTGM66VZH7'),(22,10,'in',5.000,'purchase','stock_orders',5,'2025-08-12 05:15:16','01K2EB8H952DNZ6NRV3K39RAT5'),(23,11,'in',5.000,'purchase','stock_orders',5,'2025-08-12 05:15:16','01K2EB8H974NXSAPH8WXCP8RBS'),(24,12,'in',13.000,'purchase','stock_orders',5,'2025-08-12 05:15:16','01K2EB8H99BY33CAAS78NQDJRR'),(25,13,'in',13.000,'purchase','stock_orders',5,'2025-08-12 05:15:16','01K2EB8H9A6JSENN7ZMFTR3DRH'),(26,14,'in',9.000,'purchase','stock_orders',6,'2025-08-12 05:15:16','01K2EB8HBR3YGSM2F8GB1JKZ2Z'),(27,11,'in',4.000,'purchase','stock_orders',6,'2025-08-12 05:15:16','01K2EB8HBS18VEZCKHGQNHMP12'),(28,12,'in',7.000,'purchase','stock_orders',6,'2025-08-12 05:15:16','01K2EB8HBT44SDACZKSQ2B877V'),(29,10,'in',6.000,'purchase','stock_orders',6,'2025-08-12 05:15:16','01K2EB8HBVXYZ1WTB6E7KD2SCP'),(30,15,'in',9.000,'purchase','stock_orders',6,'2025-08-12 05:15:16','01K2EB8HBXHZFM4XMPBGQTA6F9'),(31,10,'in',11.000,'purchase','stock_orders',7,'2025-08-12 05:15:16','01K2EB8HEBGK148T3V3YYYHGTN'),(32,12,'in',6.000,'purchase','stock_orders',7,'2025-08-12 05:15:16','01K2EB8HECQPTAM29SEZNHER85'),(33,13,'in',15.000,'purchase','stock_orders',7,'2025-08-12 05:15:16','01K2EB8HEDHRBGN7TVD8CKVK40'),(34,15,'in',3.000,'purchase','stock_orders',7,'2025-08-12 05:15:16','01K2EB8HEEG9YP6WDN9PNTWQTK'),(35,11,'in',15.000,'purchase','stock_orders',7,'2025-08-12 05:15:16','01K2EB8HEFN5SZVSDR1ZHK1WJ7'),(36,9,'in',7.000,'purchase','stock_orders',8,'2025-08-12 05:15:16','01K2EB8HH5XZ0P9AVC7DGAKQ8P'),(37,15,'in',6.000,'purchase','stock_orders',8,'2025-08-12 05:15:16','01K2EB8HH6Q2CK2BWP6TBHE8PF'),(38,13,'in',6.000,'purchase','stock_orders',8,'2025-08-12 05:15:16','01K2EB8HH7HHNBESEFSJDXPD27'),(39,12,'in',9.000,'purchase','stock_orders',8,'2025-08-12 05:15:16','01K2EB8HH9X6ZARKPWMQP2MB8K'),(40,11,'in',6.000,'purchase','stock_orders',8,'2025-08-12 05:15:16','01K2EB8HHBHTT802W0EVAMZKF7'),(41,16,'in',15.000,'purchase','stock_orders',9,'2025-08-12 05:15:16','01K2EB8HKZG7B2PCY08Y588J56'),(42,17,'in',14.000,'purchase','stock_orders',9,'2025-08-12 05:15:16','01K2EB8HM10GCJQZTVDD3GP8EH'),(43,18,'in',8.000,'purchase','stock_orders',9,'2025-08-12 05:15:16','01K2EB8HM2BTB6WGQRD7QZ3EVS'),(44,19,'in',10.000,'purchase','stock_orders',9,'2025-08-12 05:15:16','01K2EB8HM4NB9Q0M97FSM99M9G'),(45,20,'in',7.000,'purchase','stock_orders',9,'2025-08-12 05:15:16','01K2EB8HM557KHQQDMX8FZN1E0'),(46,21,'in',15.000,'purchase','stock_orders',10,'2025-08-12 05:15:17','01K2EB8HPZVC4X3SAF5NMBKXEE'),(47,18,'in',13.000,'purchase','stock_orders',10,'2025-08-12 05:15:17','01K2EB8HQ0DSD0G8RNYWT844VK'),(48,22,'in',10.000,'purchase','stock_orders',10,'2025-08-12 05:15:17','01K2EB8HQ2J25Y3D430WC6RS8E'),(49,23,'in',12.000,'purchase','stock_orders',10,'2025-08-12 05:15:17','01K2EB8HQ464CFB5DT1ZYAFNAR'),(50,19,'in',3.000,'purchase','stock_orders',10,'2025-08-12 05:15:17','01K2EB8HQ5D3CPP8ZYWZZVRRBN'),(51,22,'in',10.000,'purchase','stock_orders',11,'2025-08-12 05:15:17','01K2EB8HT9MA6Y4V2R2Q0FKPHM'),(52,18,'in',11.000,'purchase','stock_orders',11,'2025-08-12 05:15:17','01K2EB8HTAA0E14V4SP4Q8KACV'),(53,17,'in',11.000,'purchase','stock_orders',11,'2025-08-12 05:15:17','01K2EB8HTBMP0ARXZH61VM2S26'),(54,19,'in',4.000,'purchase','stock_orders',11,'2025-08-12 05:15:17','01K2EB8HTC6JVJG7TH8A213GNV'),(55,16,'in',3.000,'purchase','stock_orders',11,'2025-08-12 05:15:17','01K2EB8HTEZ46YP8Y04PJX1RF0'),(56,20,'in',6.000,'purchase','stock_orders',12,'2025-08-12 05:15:17','01K2EB8HWYHPZPFX2SW0B5MY0K'),(57,16,'in',9.000,'purchase','stock_orders',12,'2025-08-12 05:15:17','01K2EB8HX0KWMPHVBDQ8WPZBFC'),(58,21,'in',11.000,'purchase','stock_orders',12,'2025-08-12 05:15:17','01K2EB8HX12QCY9WJ42X6Z3K8P'),(59,18,'in',9.000,'purchase','stock_orders',12,'2025-08-12 05:15:17','01K2EB8HX2BNHCN3A62AW02C37'),(60,17,'in',9.000,'purchase','stock_orders',12,'2025-08-12 05:15:17','01K2EB8HX43945C976PR15YCXP');
/*!40000 ALTER TABLE `inventory_movements` ENABLE KEYS */;
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
-- Table structure for table `locations`
--

DROP TABLE IF EXISTS `locations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `locations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `label` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postal_code` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lat` decimal(9,6) DEFAULT NULL,
  `lng` decimal(9,6) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `ulid` char(26) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `locations_ulid_unique` (`ulid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `locations`
--

LOCK TABLES `locations` WRITE;
/*!40000 ALTER TABLE `locations` DISABLE KEYS */;
/*!40000 ALTER TABLE `locations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `loyalty_cards`
--

DROP TABLE IF EXISTS `loyalty_cards`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `loyalty_cards` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `points` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `loyalty_cards_code_unique` (`code`),
  KEY `loyalty_cards_user_id_foreign` (`user_id`),
  CONSTRAINT `loyalty_cards_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `loyalty_cards`
--

LOCK TABLES `loyalty_cards` WRITE;
/*!40000 ALTER TABLE `loyalty_cards` DISABLE KEYS */;
/*!40000 ALTER TABLE `loyalty_cards` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `loyalty_rules`
--

DROP TABLE IF EXISTS `loyalty_rules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `loyalty_rules` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `points_per_euro` decimal(6,2) NOT NULL DEFAULT '1.00',
  `redeem_rate` decimal(6,2) NOT NULL DEFAULT '100.00',
  `expires_after_months` int DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `loyalty_rules`
--

LOCK TABLES `loyalty_rules` WRITE;
/*!40000 ALTER TABLE `loyalty_rules` DISABLE KEYS */;
INSERT INTO `loyalty_rules` VALUES (1,1.00,100.00,NULL,1,'2025-08-12 05:15:15','2025-08-12 05:15:15');
/*!40000 ALTER TABLE `loyalty_rules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `loyalty_transactions`
--

DROP TABLE IF EXISTS `loyalty_transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `loyalty_transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `loyalty_card_id` bigint unsigned NOT NULL,
  `type` enum('earn','redeem','adjust') COLLATE utf8mb4_unicode_ci NOT NULL,
  `points` int NOT NULL,
  `customer_order_id` bigint unsigned DEFAULT NULL,
  `note` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `loyalty_transactions_loyalty_card_id_foreign` (`loyalty_card_id`),
  KEY `loyalty_transactions_customer_order_id_foreign` (`customer_order_id`),
  CONSTRAINT `loyalty_transactions_customer_order_id_foreign` FOREIGN KEY (`customer_order_id`) REFERENCES `customer_orders` (`id`) ON DELETE SET NULL,
  CONSTRAINT `loyalty_transactions_loyalty_card_id_foreign` FOREIGN KEY (`loyalty_card_id`) REFERENCES `loyalty_cards` (`id`) ON DELETE CASCADE,
  CONSTRAINT `chk_loy_points_pos` CHECK ((`points` > 0))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `loyalty_transactions`
--

LOCK TABLES `loyalty_transactions` WRITE;
/*!40000 ALTER TABLE `loyalty_transactions` DISABLE KEYS */;
/*!40000 ALTER TABLE `loyalty_transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `maintenance_records`
--

DROP TABLE IF EXISTS `maintenance_records`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `maintenance_records` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `truck_id` bigint unsigned NOT NULL,
  `maintenance_date` date DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `cost` decimal(8,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `ulid` char(26) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `maintenance_records_ulid_unique` (`ulid`),
  KEY `maintenance_records_truck_id_foreign` (`truck_id`),
  CONSTRAINT `maintenance_records_truck_id_foreign` FOREIGN KEY (`truck_id`) REFERENCES `trucks` (`id`) ON DELETE CASCADE,
  CONSTRAINT `chk_maint_cost` CHECK (((`cost` is null) or (`cost` >= 0)))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `maintenance_records`
--

LOCK TABLES `maintenance_records` WRITE;
/*!40000 ALTER TABLE `maintenance_records` DISABLE KEYS */;
/*!40000 ALTER TABLE `maintenance_records` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2025_08_05_162800_create_franchises_table',1),(5,'2025_08_05_162849_update_users_table',1),(6,'2025_08_05_163235_create_trucks_table',1),(7,'2025_08_05_163333_create_warehouses_table',1),(8,'2025_08_05_163408_create_supplies_table',1),(9,'2025_08_05_163443_create_stock_orders_table',1),(10,'2025_08_05_163500_create_stock_orders_items_table',1),(11,'2025_08_05_163610_create_maintenance_records_table',1),(12,'2025_08_05_163700_create_loyalty_cards_table',1),(13,'2025_08_05_163722_create_customer_orders_table',1),(14,'2025_08_05_163730_create_dishes_table',1),(15,'2025_08_05_163743_create_order_items_table',1),(16,'2025_08_05_163959_create_events_table',1),(17,'2025_08_05_164024_create_event_registrations_table',1),(18,'2025_08_09_100000_create_suppliers_table',1),(19,'2025_08_09_100100_update_stock_orders_for_suppliers_and_checks',1),(20,'2025_08_09_100150_update_stock_order_items_add_unit_price',1),(21,'2025_08_09_100200_create_commissions_table',1),(22,'2025_08_09_100300_create_locations_and_deployments',1),(23,'2025_08_09_100400_update_customer_orders_for_reservations',1),(24,'2025_08_09_100500_create_loyalty_transactions_table',1),(25,'2025_08_09_100600_create_dish_translations_table',1),(26,'2025_08_09_100700_add_indexes_and_checks_misc',1),(27,'2025_08_09_101000_alter_stock_orders_nullable_warehouse',1),(28,'2025_08_09_110000_create_inventory_table',1),(29,'2025_08_09_110100_create_dish_ingredients_table',1),(30,'2025_08_09_110200_create_inventory_traceability_tables',1),(31,'2025_08_09_110300_create_loyalty_rules_table',1),(32,'2025_08_09_110400_add_constraints_and_indexes',1),(33,'2025_08_09_120000_add_unique_franchises_name',1),(34,'2025_08_09_130000_add_public_id_to_trucks',1),(35,'2025_08_09_130000_add_ulids_to_public_resources',1),(36,'2025_08_09_131000_add_ulid_to_suppliers',1),(37,'2025_08_09_131100_add_ulid_to_stock_order_items',1),(38,'2025_08_09_140000_add_ulids_to_locations_and_deployments',1),(39,'2025_08_09_141500_create_compliance_kpis_table',1),(40,'2025_08_12_000000_add_integrity_constraints_and_purchase_mix_view',1),(41,'2025_08_12_010000_create_payments_table',1),(42,'2025_08_12_020000_delta_domain_expansion',1),(43,'2025_08_12_120000_mission1_schema_alterations',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `newsletter_sends`
--

DROP TABLE IF EXISTS `newsletter_sends`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `newsletter_sends` (
  `newsletter_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `sent_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`newsletter_id`,`user_id`),
  KEY `newsletter_sends_user_id_foreign` (`user_id`),
  CONSTRAINT `newsletter_sends_newsletter_id_foreign` FOREIGN KEY (`newsletter_id`) REFERENCES `newsletters` (`id`) ON DELETE CASCADE,
  CONSTRAINT `newsletter_sends_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `newsletter_sends`
--

LOCK TABLES `newsletter_sends` WRITE;
/*!40000 ALTER TABLE `newsletter_sends` DISABLE KEYS */;
/*!40000 ALTER TABLE `newsletter_sends` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `newsletters`
--

DROP TABLE IF EXISTS `newsletters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `newsletters` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `body` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `scheduled_at` timestamp NULL DEFAULT NULL,
  `sent_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `newsletters`
--

LOCK TABLES `newsletters` WRITE;
/*!40000 ALTER TABLE `newsletters` DISABLE KEYS */;
/*!40000 ALTER TABLE `newsletters` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `customer_order_id` bigint unsigned NOT NULL,
  `dish_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `ulid` char(26) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_items_ulid_unique` (`ulid`),
  KEY `order_items_dish_id_foreign` (`dish_id`),
  KEY `ix_items_order` (`customer_order_id`),
  CONSTRAINT `order_items_customer_order_id_foreign` FOREIGN KEY (`customer_order_id`) REFERENCES `customer_orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_dish_id_foreign` FOREIGN KEY (`dish_id`) REFERENCES `dishes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `chk_order_items_qty` CHECK ((`quantity` >= 0)),
  CONSTRAINT `chk_order_items_qty_pos` CHECK ((`quantity` >= 1))
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
INSERT INTO `order_items` VALUES (1,1,3,1,3.50,'2025-08-12 05:15:17','2025-08-12 05:15:17',NULL),(2,1,4,3,2.50,'2025-08-12 05:15:17','2025-08-12 05:15:17',NULL),(3,2,4,1,2.50,'2025-08-12 05:15:17','2025-08-12 05:15:17',NULL),(4,3,3,3,3.50,'2025-08-12 05:15:17','2025-08-12 05:15:17',NULL),(5,3,2,1,9.90,'2025-08-12 05:15:17','2025-08-12 05:15:17',NULL),(6,3,5,1,2.00,'2025-08-12 05:15:17','2025-08-12 05:15:17',NULL),(7,3,1,3,11.90,'2025-08-12 05:15:17','2025-08-12 05:15:17',NULL),(8,4,2,3,9.90,'2025-08-12 05:15:17','2025-08-12 05:15:17',NULL),(9,4,4,3,2.50,'2025-08-12 05:15:17','2025-08-12 05:15:17',NULL),(10,4,5,3,2.00,'2025-08-12 05:15:17','2025-08-12 05:15:17',NULL),(11,5,5,1,2.00,'2025-08-12 05:15:17','2025-08-12 05:15:17',NULL),(12,6,2,3,9.90,'2025-08-12 05:15:17','2025-08-12 05:15:17',NULL),(13,6,1,1,11.90,'2025-08-12 05:15:17','2025-08-12 05:15:17',NULL),(14,7,3,1,3.50,'2025-08-12 05:15:17','2025-08-12 05:15:17',NULL),(15,7,1,3,11.90,'2025-08-12 05:15:17','2025-08-12 05:15:17',NULL),(16,7,5,3,2.00,'2025-08-12 05:15:17','2025-08-12 05:15:17',NULL),(17,8,1,3,11.90,'2025-08-12 05:15:17','2025-08-12 05:15:17',NULL),(18,8,2,2,9.90,'2025-08-12 05:15:17','2025-08-12 05:15:17',NULL),(19,9,3,2,3.50,'2025-08-12 05:15:17','2025-08-12 05:15:17',NULL),(20,10,2,3,9.90,'2025-08-12 05:15:17','2025-08-12 05:15:17',NULL),(21,10,4,2,2.50,'2025-08-12 05:15:17','2025-08-12 05:15:17',NULL),(22,10,3,3,3.50,'2025-08-12 05:15:17','2025-08-12 05:15:17',NULL),(23,11,1,3,11.90,'2025-08-12 05:15:18','2025-08-12 05:15:18',NULL),(24,11,3,3,3.50,'2025-08-12 05:15:18','2025-08-12 05:15:18',NULL),(25,12,2,3,9.90,'2025-08-12 05:15:18','2025-08-12 05:15:18',NULL),(26,12,3,3,3.50,'2025-08-12 05:15:18','2025-08-12 05:15:18',NULL),(27,13,3,1,3.50,'2025-08-12 05:15:18','2025-08-12 05:15:18',NULL),(28,14,2,2,9.90,'2025-08-12 05:15:18','2025-08-12 05:15:18',NULL),(29,14,1,2,11.90,'2025-08-12 05:15:18','2025-08-12 05:15:18',NULL),(30,15,4,3,2.50,'2025-08-12 05:15:18','2025-08-12 05:15:18',NULL),(31,16,5,3,2.00,'2025-08-12 05:15:18','2025-08-12 05:15:18',NULL),(32,16,1,3,11.90,'2025-08-12 05:15:18','2025-08-12 05:15:18',NULL),(33,17,3,2,3.50,'2025-08-12 05:15:18','2025-08-12 05:15:18',NULL),(34,18,2,2,9.90,'2025-08-12 05:15:18','2025-08-12 05:15:18',NULL),(35,19,2,3,9.90,'2025-08-12 05:15:18','2025-08-12 05:15:18',NULL),(36,19,1,1,11.90,'2025-08-12 05:15:18','2025-08-12 05:15:18',NULL),(37,20,4,2,2.50,'2025-08-12 05:15:18','2025-08-12 05:15:18',NULL),(38,20,3,3,3.50,'2025-08-12 05:15:18','2025-08-12 05:15:18',NULL),(39,20,1,1,11.90,'2025-08-12 05:15:18','2025-08-12 05:15:18',NULL),(40,21,1,2,11.90,'2025-08-12 05:15:18','2025-08-12 05:15:18',NULL),(41,21,3,1,3.50,'2025-08-12 05:15:18','2025-08-12 05:15:18',NULL),(42,22,1,1,11.90,'2025-08-12 05:15:18','2025-08-12 05:15:18',NULL),(43,23,1,2,11.90,'2025-08-12 05:15:18','2025-08-12 05:15:18',NULL),(44,24,3,1,3.50,'2025-08-12 05:15:18','2025-08-12 05:15:18',NULL),(45,24,4,3,2.50,'2025-08-12 05:15:18','2025-08-12 05:15:18',NULL),(46,24,2,1,9.90,'2025-08-12 05:15:18','2025-08-12 05:15:18',NULL),(47,25,4,1,2.50,'2025-08-12 05:15:18','2025-08-12 05:15:18',NULL),(48,25,3,3,3.50,'2025-08-12 05:15:18','2025-08-12 05:15:18',NULL),(49,25,1,1,11.90,'2025-08-12 05:15:18','2025-08-12 05:15:18',NULL),(50,26,2,2,9.90,'2025-08-12 05:15:18','2025-08-12 05:15:18',NULL),(51,26,1,2,11.90,'2025-08-12 05:15:18','2025-08-12 05:15:18',NULL),(52,27,1,1,11.90,'2025-08-12 05:15:18','2025-08-12 05:15:18',NULL),(53,27,4,3,2.50,'2025-08-12 05:15:18','2025-08-12 05:15:18',NULL),(54,28,1,2,11.90,'2025-08-12 05:15:19','2025-08-12 05:15:19',NULL),(55,28,2,1,9.90,'2025-08-12 05:15:19','2025-08-12 05:15:19',NULL),(56,28,4,2,2.50,'2025-08-12 05:15:19','2025-08-12 05:15:19',NULL),(57,29,2,3,9.90,'2025-08-12 05:15:19','2025-08-12 05:15:19',NULL),(58,29,4,2,2.50,'2025-08-12 05:15:19','2025-08-12 05:15:19',NULL);
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
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
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `customer_order_id` bigint unsigned NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `method` enum('card','cash','voucher') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'card',
  `provider_ref` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pending','captured','failed','refunded') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `captured_at` timestamp NULL DEFAULT NULL,
  `refunded_at` timestamp NULL DEFAULT NULL,
  `refund_parent_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payments_customer_order_id_foreign` (`customer_order_id`),
  KEY `payments_refund_parent_id_foreign` (`refund_parent_id`),
  CONSTRAINT `payments_customer_order_id_foreign` FOREIGN KEY (`customer_order_id`) REFERENCES `customer_orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `payments_refund_parent_id_foreign` FOREIGN KEY (`refund_parent_id`) REFERENCES `payments` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payments`
--

LOCK TABLES `payments` WRITE;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
/*!40000 ALTER TABLE `payments` ENABLE KEYS */;
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
  KEY `sessions_last_activity_index` (`last_activity`),
  CONSTRAINT `fk_sessions_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stock_order_items`
--

DROP TABLE IF EXISTS `stock_order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `stock_order_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `stock_order_id` bigint unsigned NOT NULL,
  `supply_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL,
  `unit_price` decimal(12,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `ulid` char(26) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `stock_order_items_ulid_unique` (`ulid`),
  KEY `stock_order_items_stock_order_id_foreign` (`stock_order_id`),
  KEY `stock_order_items_supply_id_foreign` (`supply_id`),
  CONSTRAINT `stock_order_items_stock_order_id_foreign` FOREIGN KEY (`stock_order_id`) REFERENCES `stock_orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `stock_order_items_supply_id_foreign` FOREIGN KEY (`supply_id`) REFERENCES `supplies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `chk_stock_order_items_qty_pos` CHECK ((`quantity` >= 1))
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock_order_items`
--

LOCK TABLES `stock_order_items` WRITE;
/*!40000 ALTER TABLE `stock_order_items` DISABLE KEYS */;
INSERT INTO `stock_order_items` VALUES (1,1,4,7,NULL,'2025-08-12 05:15:16','2025-08-12 05:15:16','01K2EB8GTX17PC9RS5YKGYZYYH'),(2,1,13,14,NULL,'2025-08-12 05:15:16','2025-08-12 05:15:16','01K2EB8GV0XFE9F8SVVXV6JNCR'),(3,1,12,15,NULL,'2025-08-12 05:15:16','2025-08-12 05:15:16','01K2EB8GVJ9JKMVE4EQ35KX8CM'),(4,1,10,14,NULL,'2025-08-12 05:15:16','2025-08-12 05:15:16','01K2EB8GW3DYXE415ESHNAYSW7'),(5,1,3,9,NULL,'2025-08-12 05:15:16','2025-08-12 05:15:16','01K2EB8GW7H1GPX6FN6Z8W2XXX'),(6,2,12,7,NULL,'2025-08-12 05:15:16','2025-08-12 05:15:16','01K2EB8GY1VGVSEMPEAM06WYVE'),(7,2,5,10,NULL,'2025-08-12 05:15:16','2025-08-12 05:15:16','01K2EB8GY5D4Q9VKWRJVJJ4SFK'),(8,2,10,4,NULL,'2025-08-12 05:15:16','2025-08-12 05:15:16','01K2EB8GYQACQH2WQYQ9Q097HW'),(9,2,13,5,NULL,'2025-08-12 05:15:16','2025-08-12 05:15:16','01K2EB8GZ95H40WT6WXYPSMEP8'),(10,2,3,13,NULL,'2025-08-12 05:15:16','2025-08-12 05:15:16','01K2EB8GZD5QY1E95AT0A1ME21'),(11,3,1,4,NULL,'2025-08-12 05:15:16','2025-08-12 05:15:16','01K2EB8H15F99HQHAP8FGP2ZTA'),(12,3,10,6,NULL,'2025-08-12 05:15:16','2025-08-12 05:15:16','01K2EB8H1Q9RKTGB1B7ZQW920H'),(13,3,3,15,NULL,'2025-08-12 05:15:16','2025-08-12 05:15:16','01K2EB8H2A6PRX4SVHR1GA9QQZ'),(14,3,12,12,NULL,'2025-08-12 05:15:16','2025-08-12 05:15:16','01K2EB8H2W3162MRQR42SK4DZ7'),(15,3,5,8,NULL,'2025-08-12 05:15:16','2025-08-12 05:15:16','01K2EB8H3FJ72Q9C3GYY0JV29N'),(16,4,5,15,NULL,'2025-08-12 05:15:16','2025-08-12 05:15:16','01K2EB8H4QXBMJACYGX1XC6GTN'),(17,4,13,14,NULL,'2025-08-12 05:15:16','2025-08-12 05:15:16','01K2EB8H4VTF445HB1GAF6ARFX'),(18,4,10,7,NULL,'2025-08-12 05:15:16','2025-08-12 05:15:16','01K2EB8H5CS6YY7T3GCV8QZRGM'),(19,4,2,13,NULL,'2025-08-12 05:15:16','2025-08-12 05:15:16','01K2EB8H5G137TT3Y3JAZBWXZZ'),(20,4,4,8,NULL,'2025-08-12 05:15:16','2025-08-12 05:15:16','01K2EB8H63BFQ4YQ1F51WP0S3N'),(21,5,12,8,NULL,'2025-08-12 05:15:16','2025-08-12 05:15:16','01K2EB8H78PN1DNT1BR70GX3MS'),(22,5,2,5,NULL,'2025-08-12 05:15:16','2025-08-12 05:15:16','01K2EB8H7BYC7ZFS1RRZD9KZPM'),(23,5,5,5,NULL,'2025-08-12 05:15:16','2025-08-12 05:15:16','01K2EB8H7VSWW48SQ8CTX6MWKY'),(24,5,10,13,NULL,'2025-08-12 05:15:16','2025-08-12 05:15:16','01K2EB8H8DDB7PX10AS9E87QF9'),(25,5,4,13,NULL,'2025-08-12 05:15:16','2025-08-12 05:15:16','01K2EB8H8H6BPN798H8K51YA06'),(26,6,1,9,NULL,'2025-08-12 05:15:16','2025-08-12 05:15:16','01K2EB8HA7SEWEXY2WYC2B9CE3'),(27,6,5,4,NULL,'2025-08-12 05:15:16','2025-08-12 05:15:16','01K2EB8HACWKG5BFBNDDJEZ57W'),(28,6,10,7,NULL,'2025-08-12 05:15:16','2025-08-12 05:15:16','01K2EB8HAXKRZ8TDCYZKFX2A82'),(29,6,2,6,NULL,'2025-08-12 05:15:16','2025-08-12 05:15:16','01K2EB8HB14TXACYNV9A6MX29F'),(30,6,3,9,NULL,'2025-08-12 05:15:16','2025-08-12 05:15:16','01K2EB8HBJPWR7GCTQEP7HKAVW'),(31,7,2,11,NULL,'2025-08-12 05:15:16','2025-08-12 05:15:16','01K2EB8HCTNWCQ8NAXM6SSVB6B'),(32,7,10,6,NULL,'2025-08-12 05:15:16','2025-08-12 05:15:16','01K2EB8HCYZMFEWDPQ5QQR1FP9'),(33,7,4,15,NULL,'2025-08-12 05:15:16','2025-08-12 05:15:16','01K2EB8HDH3FMBG0Y38ERSWXRK'),(34,7,3,3,NULL,'2025-08-12 05:15:16','2025-08-12 05:15:16','01K2EB8HDN5D7MVXB3NGHS04QZ'),(35,7,5,15,NULL,'2025-08-12 05:15:16','2025-08-12 05:15:16','01K2EB8HE6HD1Q903V6DMD8BA3'),(36,8,12,7,NULL,'2025-08-12 05:15:16','2025-08-12 05:15:16','01K2EB8HFBFESMSN0DBM9NNXAZ'),(37,8,3,6,NULL,'2025-08-12 05:15:16','2025-08-12 05:15:16','01K2EB8HFE64KS83PKT6BJY412'),(38,8,4,6,NULL,'2025-08-12 05:15:16','2025-08-12 05:15:16','01K2EB8HG0W5ZN6A8PZ0ECASZM'),(39,8,10,9,NULL,'2025-08-12 05:15:16','2025-08-12 05:15:16','01K2EB8HG3F0RS9TDFGN0Q7JGJ'),(40,8,5,6,NULL,'2025-08-12 05:15:16','2025-08-12 05:15:16','01K2EB8HGK00QVAVX7D0HCT21P'),(41,9,12,15,NULL,'2025-08-12 05:15:16','2025-08-12 05:15:16','01K2EB8HJ1CHCNRZVT38PDG629'),(42,9,4,14,NULL,'2025-08-12 05:15:16','2025-08-12 05:15:16','01K2EB8HJK6ET53E8JP3S88VPN'),(43,9,2,8,NULL,'2025-08-12 05:15:16','2025-08-12 05:15:16','01K2EB8HJQNHRB9Q716R6CPHD7'),(44,9,13,10,NULL,'2025-08-12 05:15:16','2025-08-12 05:15:16','01K2EB8HK8MA85S6W9DZ1FJHPV'),(45,9,5,7,NULL,'2025-08-12 05:15:16','2025-08-12 05:15:16','01K2EB8HKSDJTKE0K3TGAHC960'),(46,10,3,15,NULL,'2025-08-12 05:15:17','2025-08-12 05:15:17','01K2EB8HN4FEBYPFHJ64MJ5W06'),(47,10,2,13,NULL,'2025-08-12 05:15:17','2025-08-12 05:15:17','01K2EB8HN8QPNK7GMHAV4DCK8N'),(48,10,1,10,NULL,'2025-08-12 05:15:17','2025-08-12 05:15:17','01K2EB8HNSFWFJJ9VNSHZ0G312'),(49,10,10,12,NULL,'2025-08-12 05:15:17','2025-08-12 05:15:17','01K2EB8HNWJPM1FXJNEV5VFHA7'),(50,10,13,3,NULL,'2025-08-12 05:15:17','2025-08-12 05:15:17','01K2EB8HPDCBXEA4S4EP92SE1S'),(51,11,1,10,NULL,'2025-08-12 05:15:17','2025-08-12 05:15:17','01K2EB8HQVYWQWY2F3Y0VSE31K'),(52,11,2,11,NULL,'2025-08-12 05:15:17','2025-08-12 05:15:17','01K2EB8HRD02DVVMVTEXGN356H'),(53,11,4,11,NULL,'2025-08-12 05:15:17','2025-08-12 05:15:17','01K2EB8HRZEJ8B4N3D9C92VAXB'),(54,11,13,4,NULL,'2025-08-12 05:15:17','2025-08-12 05:15:17','01K2EB8HS37YMNAEPQ0R8TFEWA'),(55,11,12,3,NULL,'2025-08-12 05:15:17','2025-08-12 05:15:17','01K2EB8HSPAE3YEAW88EMR3MME'),(56,12,5,6,NULL,'2025-08-12 05:15:17','2025-08-12 05:15:17','01K2EB8HV3WMNEWRQAFDWXTBE9'),(57,12,12,9,NULL,'2025-08-12 05:15:17','2025-08-12 05:15:17','01K2EB8HVKJZCD4CTK5J2WJ665'),(58,12,3,11,NULL,'2025-08-12 05:15:17','2025-08-12 05:15:17','01K2EB8HW4P4JDWKNNRJAJ6N8J'),(59,12,2,9,NULL,'2025-08-12 05:15:17','2025-08-12 05:15:17','01K2EB8HW70M7C9RZ0SS5DTDYS'),(60,12,4,9,NULL,'2025-08-12 05:15:17','2025-08-12 05:15:17','01K2EB8HWTEKYPHTW668J3AY20');
/*!40000 ALTER TABLE `stock_order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stock_orders`
--

DROP TABLE IF EXISTS `stock_orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `stock_orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `truck_id` bigint unsigned NOT NULL,
  `warehouse_id` bigint unsigned DEFAULT NULL,
  `supplier_id` bigint unsigned DEFAULT NULL,
  `status` enum('pending','approved','completed','canceled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `ordered_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `ulid` char(26) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `stock_orders_ulid_unique` (`ulid`),
  KEY `stock_orders_warehouse_id_foreign` (`warehouse_id`),
  KEY `stock_orders_supplier_id_foreign` (`supplier_id`),
  KEY `ix_so_truck_created` (`truck_id`,`created_at`),
  CONSTRAINT `stock_orders_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `stock_orders_truck_id_foreign` FOREIGN KEY (`truck_id`) REFERENCES `trucks` (`id`) ON DELETE CASCADE,
  CONSTRAINT `stock_orders_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock_orders`
--

LOCK TABLES `stock_orders` WRITE;
/*!40000 ALTER TABLE `stock_orders` DISABLE KEYS */;
INSERT INTO `stock_orders` VALUES (1,1,4,NULL,'completed','2025-08-09 05:15:16','2025-08-12 05:15:16','2025-08-12 05:15:16','01K2EB8GTB0RSGJZTJEP55HV2Y'),(2,1,4,NULL,'completed','2025-08-07 05:15:16','2025-08-12 05:15:16','2025-08-12 05:15:16','01K2EB8GXH6XCNZZKF96K0HDYQ'),(3,2,4,NULL,'completed','2025-08-06 05:15:16','2025-08-12 05:15:16','2025-08-12 05:15:16','01K2EB8H0MVXYY9GR9V3D30GX6'),(4,2,4,NULL,'completed','2025-08-05 05:15:16','2025-08-12 05:15:16','2025-08-12 05:15:16','01K2EB8H46SX3Q0SJCWCPJV9VR'),(5,3,6,NULL,'completed','2025-08-10 05:15:16','2025-08-12 05:15:16','2025-08-12 05:15:16','01K2EB8H6QWH54PWCRKYVQ0DW7'),(6,3,6,NULL,'completed','2025-08-05 05:15:16','2025-08-12 05:15:16','2025-08-12 05:15:16','01K2EB8H9N3NYDQ2Q61C954XET'),(7,4,6,NULL,'completed','2025-08-06 05:15:16','2025-08-12 05:15:16','2025-08-12 05:15:16','01K2EB8HC9YFQY992TXCM89CX9'),(8,4,6,NULL,'completed','2025-08-09 05:15:16','2025-08-12 05:15:16','2025-08-12 05:15:16','01K2EB8HET4GK0BRJDG0FZBPXC'),(9,5,8,NULL,'completed','2025-08-06 05:15:16','2025-08-12 05:15:16','2025-08-12 05:15:16','01K2EB8HHXGH2QAMCDBENHDJRF'),(10,5,8,NULL,'completed','2025-08-06 05:15:17','2025-08-12 05:15:17','2025-08-12 05:15:17','01K2EB8HMH65Z6FX8HMB9W2JQX'),(11,6,8,NULL,'completed','2025-08-06 05:15:17','2025-08-12 05:15:17','2025-08-12 05:15:17','01K2EB8HQQMT30ETHTVH3SNP8S'),(12,6,8,NULL,'completed','2025-08-08 05:15:17','2025-08-12 05:15:17','2025-08-12 05:15:17','01K2EB8HTZD5NYV56X7VE0F2SX');
/*!40000 ALTER TABLE `stock_orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `suppliers`
--

DROP TABLE IF EXISTS `suppliers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `suppliers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `siret` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_email` varchar(190) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `ulid` char(26) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `suppliers_ulid_unique` (`ulid`),
  UNIQUE KEY `uk_suppliers_siret` (`siret`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `suppliers`
--

LOCK TABLES `suppliers` WRITE;
/*!40000 ALTER TABLE `suppliers` DISABLE KEYS */;
/*!40000 ALTER TABLE `suppliers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `supplies`
--

DROP TABLE IF EXISTS `supplies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `supplies` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sku` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cost` decimal(8,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `ulid` char(26) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_supplies_sku` (`sku`),
  UNIQUE KEY `supplies_ulid_unique` (`ulid`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `supplies`
--

LOCK TABLES `supplies` WRITE;
/*!40000 ALTER TABLE `supplies` DISABLE KEYS */;
INSERT INTO `supplies` VALUES (1,'Pain burger',NULL,'pc',0.40,'2025-08-12 05:15:15','2025-08-12 05:15:15','01K2EB8FS7NCHKSBS9AJYC091C'),(2,'Steak 150g',NULL,'kg',9.50,'2025-08-12 05:15:15','2025-08-12 05:15:15','01K2EB8FSBCRQB4RW6W2Y3MRCY'),(3,'Cheddar',NULL,'kg',7.80,'2025-08-12 05:15:15','2025-08-12 05:15:15','01K2EB8FSY9EEZS91HGQXWK8A4'),(4,'Pain Burger Brioché',NULL,'pc',0.55,'2025-08-12 05:15:15','2025-08-12 05:15:15','01K2EB8FTM9FC57WK7W8PQSMN0'),(5,'Steak 180g Premium',NULL,'kg',12.40,'2025-08-12 05:15:15','2025-08-12 05:15:15','01K2EB8FV62AGA6TNGTKC5SAZM'),(6,'Cheddar Affiné',NULL,'kg',8.10,'2025-08-12 05:15:15','2025-08-12 05:15:15','01K2EB8FV9FJB8KFDG7VQCAZRG'),(7,'Oignon Rouge',NULL,'kg',2.30,'2025-08-12 05:15:15','2025-08-12 05:15:15','01K2EB8FVVYQ0GW4SVWB84H36A'),(8,'Sauce Signature',NULL,'kg',5.90,'2025-08-12 05:15:15','2025-08-12 05:15:15','01K2EB8FW0QNA5V9WACXY9VGN1'),(9,'Frites Surgelées',NULL,'kg',1.90,'2025-08-12 05:15:15','2025-08-12 05:15:15','01K2EB8FWG1PGNXH6T1M473WGB'),(10,'Boisson Cola 33cl',NULL,'pc',0.42,'2025-08-12 05:15:15','2025-08-12 05:15:15','01K2EB8FX1X3WD57ESXFWR58PK'),(11,'Boisson Eau 50cl',NULL,'pc',0.25,'2025-08-12 05:15:15','2025-08-12 05:15:15','01K2EB8FX57T5H0XA6H40R7WGE'),(12,'Salade Batavia',NULL,'kg',3.10,'2025-08-12 05:15:15','2025-08-12 05:15:15','01K2EB8FXQTHSP7S49CZPTE7YD'),(13,'Tomate Ronde',NULL,'kg',2.60,'2025-08-12 05:15:15','2025-08-12 05:15:15','01K2EB8FXTS4D36SX0TJMBJGG5');
/*!40000 ALTER TABLE `supplies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `truck_deployments`
--

DROP TABLE IF EXISTS `truck_deployments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `truck_deployments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `truck_id` bigint unsigned NOT NULL,
  `location_id` bigint unsigned NOT NULL,
  `starts_at` datetime NOT NULL,
  `ends_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `ulid` char(26) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `truck_deployments_ulid_unique` (`ulid`),
  KEY `truck_deployments_location_id_foreign` (`location_id`),
  KEY `ix_td_range` (`truck_id`,`starts_at`,`ends_at`),
  CONSTRAINT `truck_deployments_location_id_foreign` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `truck_deployments_truck_id_foreign` FOREIGN KEY (`truck_id`) REFERENCES `trucks` (`id`) ON DELETE CASCADE,
  CONSTRAINT `chk_td_range` CHECK (((`ends_at` is null) or (`ends_at` > `starts_at`))),
  CONSTRAINT `chk_td_range2` CHECK (((`ends_at` is null) or (`ends_at` > `starts_at`)))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `truck_deployments`
--

LOCK TABLES `truck_deployments` WRITE;
/*!40000 ALTER TABLE `truck_deployments` DISABLE KEYS */;
/*!40000 ALTER TABLE `truck_deployments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `trucks`
--

DROP TABLE IF EXISTS `trucks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `trucks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `ulid` char(26) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `franchise_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `license_plate` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('active','maintenance','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_trucks_plate` (`license_plate`),
  UNIQUE KEY `trucks_ulid_unique` (`ulid`),
  KEY `trucks_franchise_id_foreign` (`franchise_id`),
  CONSTRAINT `trucks_franchise_id_foreign` FOREIGN KEY (`franchise_id`) REFERENCES `franchises` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `trucks`
--

LOCK TABLES `trucks` WRITE;
/*!40000 ALTER TABLE `trucks` DISABLE KEYS */;
INSERT INTO `trucks` VALUES (1,'01K2EB8G4S1TYVCH9QHV3B5RGC',2,'Franchise Paris Centre Truck 1','VL-41V-01','active','2025-08-12 05:15:15','2025-08-12 05:15:15',NULL),(2,'01K2EB8G4X7T3KX4S3J575NDCA',2,'Franchise Paris Centre Truck 2','BP-11O-02','active','2025-08-12 05:15:15','2025-08-12 05:15:15',NULL),(3,'01K2EB8GB5DKHRFFH4SVACXAGV',3,'Franchise Ouest IDF Truck 1','NV-49L-01','active','2025-08-12 05:15:15','2025-08-12 05:15:15',NULL),(4,'01K2EB8GB8WBZRQ6ATF265ZJ3K',3,'Franchise Ouest IDF Truck 2','LI-30E-02','active','2025-08-12 05:15:15','2025-08-12 05:15:15',NULL),(5,'01K2EB8GJ5NJ3CD7KXAE4QPWBM',4,'Franchise Sud IDF Truck 1','NL-28P-01','active','2025-08-12 05:15:15','2025-08-12 05:15:15',NULL),(6,'01K2EB8GJ93215XS7YK0XCN4D7',4,'Franchise Sud IDF Truck 2','G0-86F-02','active','2025-08-12 05:15:15','2025-08-12 05:15:15',NULL);
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
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'franchise',
  `franchise_id` bigint unsigned DEFAULT NULL,
  `preferred_language` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `newsletter_opt_in` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_franchise_id_foreign` (`franchise_id`),
  CONSTRAINT `users_franchise_id_foreign` FOREIGN KEY (`franchise_id`) REFERENCES `franchises` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Admin Demo','admin@local.test',NULL,'$2y$12$k9j/GtwDjnxDyrpSJNXBU.EWiaG/WSar7IgLyZWAGoII3aEyzYm9K',NULL,'2025-08-12 05:15:14','2025-08-12 05:15:14','admin',NULL,NULL,0),(2,'Franchise Demo','franchise@local.test',NULL,'$2y$12$SFLLbpv85BExIZw3sgklguzYJEXtv9exJFQo6.Z8kND7WbNSbQiIS',NULL,'2025-08-12 05:15:15','2025-08-12 05:15:15','franchise',1,NULL,0),(3,'Franchise Manager','paris.centre@local.test',NULL,'$2y$12$GYOb8VeLJjpp9d/p9wlMTO.sarIHMSFtabg561V1CQObtwU0ARqUe',NULL,'2025-08-12 05:15:15','2025-08-12 05:15:15','franchise',2,NULL,0),(4,'Franchise Manager','ouest.idf@local.test',NULL,'$2y$12$Wy5wyZTIvy.UjiBUuCs8OO7P5SQ8yeIJk/qFskQm0imWSaBi7iMxS',NULL,'2025-08-12 05:15:15','2025-08-12 05:15:15','franchise',3,NULL,0),(5,'Franchise Manager','sud.idf@local.test',NULL,'$2y$12$sVIsgLNIOf23mX1KUZKBmOCrjsuH03IuvvK4ne5q/5bc7OQTP9U62',NULL,'2025-08-12 05:15:15','2025-08-12 05:15:15','franchise',4,NULL,0);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `warehouses`
--

DROP TABLE IF EXISTS `warehouses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `warehouses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `franchise_id` bigint unsigned NOT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `ulid` char(26) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `warehouses_ulid_unique` (`ulid`),
  KEY `warehouses_franchise_id_foreign` (`franchise_id`),
  CONSTRAINT `warehouses_franchise_id_foreign` FOREIGN KEY (`franchise_id`) REFERENCES `franchises` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `warehouses`
--

LOCK TABLES `warehouses` WRITE;
/*!40000 ALTER TABLE `warehouses` DISABLE KEYS */;
INSERT INTO `warehouses` VALUES (1,1,'Zone Est','Entrepôt Est','2025-08-12 05:15:15','2025-08-12 05:15:15','01K2EB8FRJMN0TEMV209E9Y3G7'),(2,1,'Zone Ouest','Entrepôt Ouest','2025-08-12 05:15:15','2025-08-12 05:15:15','01K2EB8FRPC8E1PVNS8XC2274N'),(3,2,'Secteur 1','Franchise Paris Centre Entrepôt 1','2025-08-12 05:15:15','2025-08-12 05:15:15','01K2EB8G432YB1FD8TNYC1ST99'),(4,2,'Secteur 2','Franchise Paris Centre Entrepôt 2','2025-08-12 05:15:15','2025-08-12 05:15:15','01K2EB8G4789XQYWTM4YC13ZQ7'),(5,3,'Secteur 1','Franchise Ouest IDF Entrepôt 1','2025-08-12 05:15:15','2025-08-12 05:15:15','01K2EB8GA5C0M83Z8CSJTYAZNS'),(6,3,'Secteur 2','Franchise Ouest IDF Entrepôt 2','2025-08-12 05:15:15','2025-08-12 05:15:15','01K2EB8GAMANWPMRJV62EW1RZY'),(7,4,'Secteur 1','Franchise Sud IDF Entrepôt 1','2025-08-12 05:15:15','2025-08-12 05:15:15','01K2EB8GHG6HFVNWDBAE4WT356'),(8,4,'Secteur 2','Franchise Sud IDF Entrepôt 2','2025-08-12 05:15:15','2025-08-12 05:15:15','01K2EB8GHKTYCMN8G40J5YY2WK');
/*!40000 ALTER TABLE `warehouses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'laravel'
--

--
-- Dumping routines for database 'laravel'
--

--
-- Final view structure for view `_order_item_totals`
--

/*!50001 DROP VIEW IF EXISTS `_order_item_totals`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`sail`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `_order_item_totals` AS select `order_items`.`customer_order_id` AS `customer_order_id`,sum((`order_items`.`quantity` * `order_items`.`price`)) AS `agg_total` from `order_items` group by `order_items`.`customer_order_id` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `franchise_monthly_purchase_mix`
--

/*!50001 DROP VIEW IF EXISTS `franchise_monthly_purchase_mix`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`sail`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `franchise_monthly_purchase_mix` AS select `f`.`id` AS `franchisee_id`,year(`so`.`created_at`) AS `year`,month(`so`.`created_at`) AS `month`,sum((case when (`so`.`warehouse_id` is not null) then (`soi`.`quantity` * coalesce(`soi`.`unit_price`,0)) else 0 end)) AS `internal_amount`,sum((case when (`so`.`supplier_id` is not null) then (`soi`.`quantity` * coalesce(`soi`.`unit_price`,0)) else 0 end)) AS `external_amount`,(case when (sum((`soi`.`quantity` * coalesce(`soi`.`unit_price`,0))) > 0) then round(((sum((case when (`so`.`warehouse_id` is not null) then (`soi`.`quantity` * coalesce(`soi`.`unit_price`,0)) else 0 end)) / sum((`soi`.`quantity` * coalesce(`soi`.`unit_price`,0)))) * 100),2) else NULL end) AS `official_pct` from (((`stock_orders` `so` join `trucks` `t` on((`t`.`id` = `so`.`truck_id`))) join `franchises` `f` on((`f`.`id` = `t`.`franchise_id`))) join `stock_order_items` `soi` on((`soi`.`stock_order_id` = `so`.`id`))) group by `f`.`id`,year(`so`.`created_at`),month(`so`.`created_at`) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-08-19 17:18:02
