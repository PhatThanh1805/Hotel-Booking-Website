-- MySQL dump 10.13  Distrib 8.0.36, for Win64 (x86_64)
--
-- Host: localhost    Database: hotelbooking
-- ------------------------------------------------------
-- Server version	8.0.36

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
-- Table structure for table `admin_cred`
--

DROP TABLE IF EXISTS `admin_cred`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin_cred` (
  `sr_no` int NOT NULL AUTO_INCREMENT,
  `admin_name` varchar(150) NOT NULL,
  `admin_pass` varchar(150) NOT NULL,
  PRIMARY KEY (`sr_no`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_cred`
--

LOCK TABLES `admin_cred` WRITE;
/*!40000 ALTER TABLE `admin_cred` DISABLE KEYS */;
INSERT INTO `admin_cred` VALUES (1,'amey','12345'),(2,'neal','12345');
/*!40000 ALTER TABLE `admin_cred` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `booking_details`
--

DROP TABLE IF EXISTS `booking_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `booking_details` (
  `sr_no` int NOT NULL AUTO_INCREMENT,
  `booking_id` int NOT NULL,
  `room_name` varchar(100) NOT NULL,
  `price` int NOT NULL,
  `total_pay` int NOT NULL,
  `room_no` varchar(100) DEFAULT NULL,
  `user_name` varchar(100) NOT NULL,
  `phonenum` varchar(100) NOT NULL,
  `address` varchar(150) NOT NULL,
  PRIMARY KEY (`sr_no`),
  KEY `booking_id` (`booking_id`),
  CONSTRAINT `booking_details_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking_order` (`booking_id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `booking_details`
--

LOCK TABLES `booking_details` WRITE;
/*!40000 ALTER TABLE `booking_details` DISABLE KEYS */;
INSERT INTO `booking_details` VALUES (1,1,'Simple Room',300,300,NULL,'amey','123','ad'),(2,2,'Simple Room',300,600,'a2','amey','123','ad'),(3,3,'Simple Room',300,1800,NULL,'amey','123','ad'),(4,4,'Supreme deluxe room',900,4500,NULL,'amey','123','ad'),(5,5,'Supreme deluxe room',900,900,NULL,'amey','123','ad'),(6,6,'Supreme deluxe room',900,7200,'52','amey','12323432','ad2342343'),(7,7,'Supreme deluxe room',900,900,NULL,'amey','123','ad'),(8,8,'Simple Room',300,600,NULL,'amey','123','ad'),(9,9,'Luxury Room',600,3000,'159A','amey','123','ad'),(10,10,'Luxury Room',600,1800,'15S','neal','123','ad'),(11,11,'Supreme deluxe room',900,2700,'1','neal','123','ad'),(12,12,'Simple Room',300,1200,'2','neal','123','ad'),(13,13,'Deluxe Room',500,3000,'23','neal','123','ad'),(14,14,'Luxury Room',600,2400,'44','neal','123','ad'),(15,15,'Luxury Room',600,1200,NULL,'neal','123','ad'),(16,16,'Luxury Room',600,1200,'1','neal','123','ad'),(17,17,'Simple Room',300,900,'20A','neal','123','ad'),(18,18,'Luxury Room',600,1200,NULL,'amey','1234','asd'),(19,19,'Simple Room',300,300,NULL,'neal','123','ad'),(20,20,'Simple Room',300,600,NULL,'neal','123','ad');
/*!40000 ALTER TABLE `booking_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `booking_order`
--

DROP TABLE IF EXISTS `booking_order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `booking_order` (
  `booking_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `room_id` int NOT NULL,
  `check_in` date NOT NULL,
  `check_out` date NOT NULL,
  `arrival` int NOT NULL DEFAULT '0',
  `refund` int DEFAULT NULL,
  `booking_status` varchar(100) NOT NULL DEFAULT 'pending',
  `order_id` varchar(150) NOT NULL,
  `trans_id` varchar(200) DEFAULT NULL,
  `trans_amt` int NOT NULL,
  `trans_status` varchar(100) NOT NULL DEFAULT 'pending',
  `trans_resp_msg` varchar(200) DEFAULT NULL,
  `rate_review` int DEFAULT NULL,
  `datentime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`booking_id`),
  KEY `user_id` (`user_id`),
  KEY `room_id` (`room_id`),
  CONSTRAINT `booking_order_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user_cred` (`id`),
  CONSTRAINT `booking_order_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `booking_order`
--

LOCK TABLES `booking_order` WRITE;
/*!40000 ALTER TABLE `booking_order` DISABLE KEYS */;
INSERT INTO `booking_order` VALUES (1,2,3,'2022-12-15','2022-12-17',0,NULL,'pending','ORD_21055700',NULL,0,'pending',NULL,NULL,'2022-12-10 01:50:12'),(2,2,3,'2022-07-20','2022-07-22',1,NULL,'booked','ORD_24215693','20220720111212800110168128204225279',600,'TXN_SUCCESS','Txn Success',NULL,'2022-07-20 02:14:44'),(3,2,3,'2022-07-20','2022-07-26',0,1,'cancelled','ORD_26312547','20220720111212800110168165603901976',1800,'TXN_SUCCESS','Txn Success',NULL,'2022-07-20 02:19:00'),(4,2,6,'2022-07-20','2022-07-25',0,NULL,'payment failed','ORD_28394638','20220720111212800110168372503893816',4500,'TXN_FAILURE','Your payment has been declined by your bank. Please try again or use a different method to complete the payment.',NULL,'2022-07-20 02:30:52'),(5,2,6,'2022-07-20','2022-07-21',0,1,'cancelled','ORD_22877860','20220720111212800110168627705312020',900,'TXN_SUCCESS','Txn Success',NULL,'2022-07-20 02:32:09'),(6,2,6,'2022-07-20','2022-07-28',1,NULL,'booked','ORD_28689687','20220720111212800110168303704048087',7200,'TXN_SUCCESS','Txn Success',1,'2022-07-20 02:34:46'),(7,2,6,'2022-07-29','2022-07-30',0,NULL,'pending','ORD_24272313',NULL,0,'pending',NULL,NULL,'2022-07-29 01:13:42'),(8,2,3,'2022-08-14','2022-08-16',0,1,'cancelled','ORD_22541670','20220814111212800110168092803967754',600,'TXN_SUCCESS','Txn Success',NULL,'2022-08-14 01:16:05'),(9,2,5,'2022-08-15','2022-08-20',1,NULL,'booked','ORD_25267746','20220815111212800110168656003990120',3000,'TXN_SUCCESS','Txn Success',1,'2022-08-15 19:32:05'),(10,2,5,'2022-08-18','2022-08-21',1,NULL,'booked','ORD_27668816','20220815111212800110168905703947446',1800,'TXN_SUCCESS','Txn Success',1,'2022-08-15 19:32:58'),(11,2,6,'2022-08-20','2022-08-23',1,NULL,'booked','ORD_25750549','20220820111212800110168431303975409',2700,'TXN_SUCCESS','Txn Success',1,'2022-08-20 00:19:57'),(12,2,3,'2022-08-20','2022-08-24',1,NULL,'booked','ORD_2445093','20220820111212800110168173403969278',1200,'TXN_SUCCESS','Txn Success',1,'2022-08-20 00:20:23'),(13,2,4,'2022-08-20','2022-08-26',1,NULL,'booked','ORD_29233995','20220820111212800110168584503978338',3000,'TXN_SUCCESS','Txn Success',1,'2022-08-20 00:20:45'),(14,2,5,'2022-08-20','2022-08-24',1,NULL,'booked','ORD_28902800','20220820111212800110168816503988359',2400,'TXN_SUCCESS','Txn Success',1,'2022-08-20 00:21:06'),(15,2,5,'2022-08-25','2022-08-27',0,1,'cancelled','ORD_2240367','20220825111212800110168807304010818',1200,'TXN_SUCCESS','Txn Success',NULL,'2019-08-21 01:51:28'),(16,2,5,'2022-08-26','2022-08-28',1,NULL,'booked','ORD_28784829','20220825111212800110168627505415606',1200,'TXN_SUCCESS','Txn Success',1,'2022-08-25 01:52:04'),(17,2,3,'2022-09-08','2022-09-11',1,NULL,'booked','ORD_21289330','20220908111212800110168809204050263',900,'TXN_SUCCESS','Txn Success',0,'2022-09-08 01:15:30'),(18,5,5,'2022-12-14','2022-12-16',0,NULL,'pending','ORD_52387163',NULL,0,'pending',NULL,NULL,'2022-12-13 03:05:43'),(19,2,3,'2022-12-14','2022-12-15',0,NULL,'pending','ORD_28406333',NULL,0,'pending',NULL,NULL,'2022-12-13 10:01:15'),(20,2,3,'2022-12-14','2022-12-16',0,NULL,'pending','ORD_26701861',NULL,0,'pending',NULL,NULL,'2022-12-13 10:03:51');
/*!40000 ALTER TABLE `booking_order` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `carousel`
--

DROP TABLE IF EXISTS `carousel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `carousel` (
  `sr_no` int NOT NULL AUTO_INCREMENT,
  `image` varchar(150) NOT NULL,
  PRIMARY KEY (`sr_no`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `carousel`
--

LOCK TABLES `carousel` WRITE;
/*!40000 ALTER TABLE `carousel` DISABLE KEYS */;
INSERT INTO `carousel` VALUES (4,'IMG_62045.png'),(5,'IMG_93127.png'),(6,'IMG_99736.png'),(8,'IMG_40905.png'),(9,'IMG_55677.png');
/*!40000 ALTER TABLE `carousel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contact_details`
--

DROP TABLE IF EXISTS `contact_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contact_details` (
  `sr_no` int NOT NULL AUTO_INCREMENT,
  `address` varchar(50) NOT NULL,
  `gmap` varchar(100) NOT NULL,
  `pn1` bigint NOT NULL,
  `pn2` bigint NOT NULL,
  `email` varchar(100) NOT NULL,
  `fb` varchar(100) NOT NULL,
  `insta` varchar(100) NOT NULL,
  `tw` varchar(100) NOT NULL,
  `iframe` varchar(300) NOT NULL,
  PRIMARY KEY (`sr_no`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact_details`
--

LOCK TABLES `contact_details` WRITE;
/*!40000 ALTER TABLE `contact_details` DISABLE KEYS */;
INSERT INTO `contact_details` VALUES (1,'VJTI, Matunga, Mumbai, Maharashtra','https://goo.gl/maps/p9s1LYMHvWiww7pZ8',918529636985,91111222333558,'amey.neal@gmail.com','https://www.facebook.com/','https://www.facebook.com/','https://www.facebook.com/','https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3771.9433432697824!2d72.85393251443796!3d19.02221808711878!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3be7cf26f4972d21:0x2c50185364aca4c1!2sVeermata Jijabai Technological Institute!5e0!3m2!1sen!2sin!4v1670867131904!5m2!1sen!2sin');
/*!40000 ALTER TABLE `contact_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `facilities`
--

DROP TABLE IF EXISTS `facilities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `facilities` (
  `id` int NOT NULL AUTO_INCREMENT,
  `icon` varchar(100) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `facilities`
--

LOCK TABLES `facilities` WRITE;
/*!40000 ALTER TABLE `facilities` DISABLE KEYS */;
INSERT INTO `facilities` VALUES (13,'IMG_43553.svg','Wifi','Lorem ipsum dolor sit amet consectetur adipisicing elit. Temporibus incidunt odio quos dolore commodi repudiandae tenetur.'),(14,'IMG_49949.svg','Air conditioner','Lorem ipsum dolor sit amet consectetur adipisicing elit. Temporibus incidunt odio quos dolore commodi repudiandae tenetur.'),(15,'IMG_41622.svg','Television','Lorem ipsum dolor sit amet consectetur adipisicing elit. Temporibus incidunt odio quos dolore commodi repudiandae tenetur.'),(17,'IMG_47816.svg','Spa','Lorem ipsum dolor sit amet consectetur adipisicing elit. Temporibus incidunt odio quos dolore commodi repudiandae tenetur.'),(18,'IMG_96423.svg','Room Heater','Lorem ipsum dolor sit amet consectetur adipisicing elit. Temporibus incidunt odio quos dolore commodi repudiandae tenetur.'),(19,'IMG_27079.svg','Geyser','Lorem ipsum dolor sit amet consectetur adipisicing elit. Temporibus incidunt odio quos dolore commodi repudiandae tenetur.');
/*!40000 ALTER TABLE `facilities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `features`
--

DROP TABLE IF EXISTS `features`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `features` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `features`
--

LOCK TABLES `features` WRITE;
/*!40000 ALTER TABLE `features` DISABLE KEYS */;
INSERT INTO `features` VALUES (13,'bedroom'),(14,'balcony'),(15,'kitchen'),(17,'sofa');
/*!40000 ALTER TABLE `features` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rating_review`
--

DROP TABLE IF EXISTS `rating_review`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rating_review` (
  `sr_no` int NOT NULL AUTO_INCREMENT,
  `booking_id` int NOT NULL,
  `room_id` int NOT NULL,
  `user_id` int NOT NULL,
  `rating` int NOT NULL,
  `review` varchar(200) NOT NULL,
  `seen` int NOT NULL DEFAULT '0',
  `datentime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`sr_no`),
  KEY `booking_id` (`booking_id`),
  KEY `room_id` (`room_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `rating_review_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking_order` (`booking_id`),
  CONSTRAINT `rating_review_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`),
  CONSTRAINT `rating_review_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `user_cred` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rating_review`
--

LOCK TABLES `rating_review` WRITE;
/*!40000 ALTER TABLE `rating_review` DISABLE KEYS */;
INSERT INTO `rating_review` VALUES (4,14,5,2,5,'1asdlkfj Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quos voluptate vero sed tempore illo atque beatae asperiores, adipisci dicta quia nisi voluptates impedit perspiciatis, nobis libero ',1,'2022-08-20 00:22:25'),(5,13,4,5,3,'2asdlkfj Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quos voluptate vero sed tempore illo atque beatae asperiores, adipisci dicta quia nisi voluptates impedit perspiciatis, nobis libero ',1,'2022-08-20 00:22:30'),(6,12,3,6,1,'3asdlkfj Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quos voluptate vero sed tempore illo atque beatae asperiores, adipisci dicta quia nisi voluptates impedit perspiciatis, nobis libero ',1,'2022-08-20 00:22:34'),(8,14,5,7,5,'1asdlkfj Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quos voluptate vero sed tempore illo atque beatae asperiores, adipisci dicta quia nisi voluptates impedit perspiciatis, nobis libero ',1,'2022-08-20 00:22:25'),(9,12,3,8,1,'3asdlkfj Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quos voluptate vero sed tempore illo atque beatae asperiores, adipisci dicta quia nisi voluptates impedit perspiciatis, nobis libero ',1,'2022-08-20 00:22:34'),(10,12,3,2,1,'3asdlkfj Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quos voluptate vero sed tempore illo atque beatae asperiores, adipisci dicta quia nisi voluptates impedit perspiciatis, nobis libero ',1,'2022-08-20 00:22:34'),(11,16,5,5,3,'adfadfs',1,'2022-09-08 01:14:18');
/*!40000 ALTER TABLE `rating_review` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `room_facilities`
--

DROP TABLE IF EXISTS `room_facilities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `room_facilities` (
  `sr_no` int NOT NULL AUTO_INCREMENT,
  `room_id` int NOT NULL,
  `facilities_id` int NOT NULL,
  PRIMARY KEY (`sr_no`),
  KEY `facilities id` (`facilities_id`),
  KEY `room id` (`room_id`),
  CONSTRAINT `facilities id` FOREIGN KEY (`facilities_id`) REFERENCES `facilities` (`id`),
  CONSTRAINT `room id` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `room_facilities`
--

LOCK TABLES `room_facilities` WRITE;
/*!40000 ALTER TABLE `room_facilities` DISABLE KEYS */;
INSERT INTO `room_facilities` VALUES (29,4,14),(30,4,18),(31,4,19),(35,6,13),(36,6,14),(37,6,18),(38,6,19),(39,5,13),(40,5,14),(41,5,18),(42,3,14),(43,3,15),(44,3,18),(45,3,19);
/*!40000 ALTER TABLE `room_facilities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `room_features`
--

DROP TABLE IF EXISTS `room_features`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `room_features` (
  `sr_no` int NOT NULL AUTO_INCREMENT,
  `room_id` int NOT NULL,
  `features_id` int NOT NULL,
  PRIMARY KEY (`sr_no`),
  KEY `features id` (`features_id`),
  KEY `rm id` (`room_id`),
  CONSTRAINT `features id` FOREIGN KEY (`features_id`) REFERENCES `features` (`id`),
  CONSTRAINT `rm id` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `room_features`
--

LOCK TABLES `room_features` WRITE;
/*!40000 ALTER TABLE `room_features` DISABLE KEYS */;
INSERT INTO `room_features` VALUES (16,4,13),(17,4,14),(18,4,15),(22,6,13),(23,6,14),(24,6,15),(25,5,13),(26,5,14),(27,5,15),(28,3,13),(29,3,14),(30,3,17);
/*!40000 ALTER TABLE `room_features` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `room_images`
--

DROP TABLE IF EXISTS `room_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `room_images` (
  `sr_no` int NOT NULL AUTO_INCREMENT,
  `room_id` int NOT NULL,
  `image` varchar(150) NOT NULL,
  `thumb` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`sr_no`),
  KEY `room_id` (`room_id`),
  CONSTRAINT `room_images_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `room_images`
--

LOCK TABLES `room_images` WRITE;
/*!40000 ALTER TABLE `room_images` DISABLE KEYS */;
INSERT INTO `room_images` VALUES (15,3,'IMG_39782.png',0),(16,3,'IMG_65019.png',1),(17,4,'IMG_44867.png',0),(18,4,'IMG_78809.png',1),(19,4,'IMG_11892.png',0),(21,5,'IMG_17474.png',0),(22,5,'IMG_42663.png',1),(23,5,'IMG_70583.png',0),(24,6,'IMG_67761.png',0),(25,6,'IMG_69824.png',1);
/*!40000 ALTER TABLE `room_images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rooms`
--

DROP TABLE IF EXISTS `rooms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rooms` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `area` int NOT NULL,
  `price` int NOT NULL,
  `quantity` int NOT NULL,
  `adult` int NOT NULL,
  `children` int NOT NULL,
  `description` varchar(350) NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `removed` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rooms`
--

LOCK TABLES `rooms` WRITE;
/*!40000 ALTER TABLE `rooms` DISABLE KEYS */;
INSERT INTO `rooms` VALUES (1,'simple room',159,58,56,12,2,'asdf asd',1,1),(2,'simple room 2',785,159,85,452,10,'adfasdfa sd',1,1),(3,'Simple Room',250,300,10,5,3,'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quos voluptate vero sed tempore illo atque beatae asperiores, adipisci dicta quia nisi voluptates impedit perspiciatis, nobis libero culpa error officiis totam?Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quos voluptate vero sed tempore illo atque beatae asperiores, adipisci dic',1,0),(4,'Deluxe Room',300,500,10,3,2,'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quos voluptate vero sed tempore illo atque beatae asperiores, adipisci dicta quia nisi voluptates impedit perspiciatis, nobis libero culpa error officiis totam?Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quos voluptate vero sed tempore illo atque beatae asperiores, adipisci dic',1,0),(5,'Luxury Room',600,600,2,8,6,'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quos voluptate vero sed tempore illo atque beatae asperiores, adipisci dicta quia nisi voluptates impedit perspiciatis, nobis libero culpa error officiis totam?Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quos voluptate vero sed tempore illo atque beatae asperiores, adipisci dic',1,0),(6,'Supreme deluxe room',500,900,12,9,10,'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quos voluptate vero sed tempore illo atque beatae asperiores, adipisci dicta quia nisi voluptates impedit perspiciatis, nobis libero culpa error officiis totam?Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quos voluptate vero sed tempore illo atque beatae asperiores, adipisci dic',1,0);
/*!40000 ALTER TABLE `rooms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `settings` (
  `sr_no` int NOT NULL AUTO_INCREMENT,
  `site_title` varchar(50) NOT NULL,
  `site_about` varchar(250) NOT NULL,
  `shutdown` tinyint(1) NOT NULL,
  PRIMARY KEY (`sr_no`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (1,'Get Hotels','asdlkfj Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quos voluptate vero sed tempore illo atque beatae asperiores, adipisci dicta quia nisi voluptates impedit perspiciatis, nobis libero culpa error officiis totam?',0);
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `team_details`
--

DROP TABLE IF EXISTS `team_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `team_details` (
  `sr_no` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `picture` varchar(150) NOT NULL,
  PRIMARY KEY (`sr_no`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `team_details`
--

LOCK TABLES `team_details` WRITE;
/*!40000 ALTER TABLE `team_details` DISABLE KEYS */;
INSERT INTO `team_details` VALUES (16,'Amey','IMG_38410.jpg'),(17,'Neal','IMG_69823.jpg');
/*!40000 ALTER TABLE `team_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_cred`
--

DROP TABLE IF EXISTS `user_cred`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_cred` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `address` varchar(120) NOT NULL,
  `phonenum` varchar(100) NOT NULL,
  `pincode` int NOT NULL,
  `dob` date NOT NULL,
  `profile` varchar(100) NOT NULL,
  `password` varchar(200) NOT NULL,
  `is_verified` int NOT NULL DEFAULT '0',
  `token` varchar(200) DEFAULT NULL,
  `t_expire` date DEFAULT NULL,
  `status` int NOT NULL DEFAULT '1',
  `datentime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_cred`
--

LOCK TABLES `user_cred` WRITE;
/*!40000 ALTER TABLE `user_cred` DISABLE KEYS */;
INSERT INTO `user_cred` VALUES (2,'neal','neal@gmail.com','ad','123',123324,'2022-06-12','3135715.png','$2y$10$pFpfE/5mH2qjH74lCGEWrOpYBoZTF2mFgLz3w5OqcJARIooJV.7uO',1,NULL,NULL,0,'2022-06-12 16:05:59'),(5,'amey','helubeti@finews.biz','asd','1234',123,'2022-12-13','IMG_84786.jpeg','$2y$10$NtKNL5Ogn.m3NViVu/DKIevNhms7thrZP.qTnPpqooncOSygLw9hS',1,'24ffd287a4c2eda5f2b424be2824f997',NULL,1,'2022-12-13 02:37:19'),(6,'amey','xelih35531@lubde.com','asd','1123',123,'2022-12-13','3135716.png','$2y$10$aoCaCM6Ji3VuZlO0YFl.Y.O4vv2cqJr0HiT2oVH5sy3AWQJqyyQJ6',1,'ef6dc7ba39cf4bf844244d3ef927a3e7',NULL,1,'2022-12-13 02:40:42'),(7,'harry','harryd123@gmail.com','asd','12345',123,'2022-12-13','IMG_33353.jpeg','$2y$10$kiw8LOLFK9e/I4u5i3vO0.GkMpBKAbeZguOqtp1HD0mBoPyAwXFhq',0,'5c9f04397ff3e693f7cbfccea1044483',NULL,1,'2022-12-13 02:42:37'),(8,'a','cejika9124@paxven.com','a','12',1,'2022-12-13','IMG_62937.jpeg','$2y$10$0kAvtcnPie9S0W2DGjxaBuI8rvrC5Zq7BVUyNmST14J25tm2Vzdyu',0,'250dd45640f7d810313b27e758a267af',NULL,1,'2022-12-13 02:55:39'),(9,'Đào Tấn Phát','phat@gmail.com','TP. Hồ Chí Minh','0901234567',700000,'2003-01-01','3135715.png','$2y$10$pFpfE/5mH2qjH74lCGEWrOpYBoZTF2mFgLz3w5OqcJARIooJV.7uO',1,NULL,NULL,1,'2026-10-05 14:47:01');
/*!40000 ALTER TABLE `user_cred` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_queries`
--

DROP TABLE IF EXISTS `user_queries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_queries` (
  `sr_no` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `email` varchar(150) NOT NULL,
  `subject` varchar(200) NOT NULL,
  `message` varchar(500) NOT NULL,
  `datentime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `seen` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`sr_no`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_queries`
--

LOCK TABLES `user_queries` WRITE;
/*!40000 ALTER TABLE `user_queries` DISABLE KEYS */;
INSERT INTO `user_queries` VALUES (11,'Amey','amey@gmail.com','This is one subject','orem ipsum dolor sit amet, consectetur adipisicing elit. Quos voluptate vero sed tempore illo atque beatae asperiores, adipisci dicta quia nisi voluptates im','2022-03-11 00:00:00',1),(13,'neal','n@gmail.com','4a2qez','watT','2022-12-13 10:10:48',1);
/*!40000 ALTER TABLE `user_queries` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-10-05 15:26:59
