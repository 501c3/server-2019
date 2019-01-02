CREATE DATABASE  IF NOT EXISTS `setup` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */;
USE `setup`;
-- MySQL dump 10.13  Distrib 8.0.13, for Linux (x86_64)
--
-- Host: localhost    Database: setup
-- ------------------------------------------------------
-- Server version	8.0.13

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
 SET NAMES utf8 ;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `age_person`
--

DROP TABLE IF EXISTS `age_person`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `age_person` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `describe` json NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=369 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `age_person_has_prf_person`
--

DROP TABLE IF EXISTS `age_person_has_prf_person`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `age_person_has_prf_person` (
  `age_person_id` smallint(6) NOT NULL,
  `prf_person_id` smallint(6) NOT NULL,
  PRIMARY KEY (`age_person_id`,`prf_person_id`),
  KEY `fk_age_person_has_prf_person_prf_person1_idx` (`prf_person_id`),
  KEY `fk_age_person_has_prf_person_age_person1_idx` (`age_person_id`),
  CONSTRAINT `fk_age_person_has_prf_person_age_person1` FOREIGN KEY (`age_person_id`) REFERENCES `age_person` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_age_person_has_prf_person_prf_person1` FOREIGN KEY (`prf_person_id`) REFERENCES `prf_person` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `age_person_has_value`
--

DROP TABLE IF EXISTS `age_person_has_value`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `age_person_has_value` (
  `age_person_id` smallint(6) NOT NULL,
  `value_id` smallint(6) NOT NULL,
  PRIMARY KEY (`age_person_id`,`value_id`),
  KEY `fk_age_person_has_value_value1_idx` (`value_id`),
  KEY `fk_age_person_has_value_age_person1_idx` (`age_person_id`),
  CONSTRAINT `fk_age_person_has_value_age_person1` FOREIGN KEY (`age_person_id`) REFERENCES `age_person` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_age_person_has_value_value1` FOREIGN KEY (`value_id`) REFERENCES `value` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `age_team`
--

DROP TABLE IF EXISTS `age_team`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `age_team` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `age_team_class_id` smallint(6) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_age_team_age_team_class1_idx` (`age_team_class_id`),
  CONSTRAINT `fk_age_team_age_team_class1` FOREIGN KEY (`age_team_class_id`) REFERENCES `age_team_class` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7311 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `age_team_class`
--

DROP TABLE IF EXISTS `age_team_class`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `age_team_class` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `describe` json NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=83 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `age_team_class_has_prf_team_class`
--

DROP TABLE IF EXISTS `age_team_class_has_prf_team_class`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `age_team_class_has_prf_team_class` (
  `age_team_class_id` smallint(6) NOT NULL,
  `prf_team_class_id` smallint(6) NOT NULL,
  PRIMARY KEY (`age_team_class_id`,`prf_team_class_id`),
  KEY `fk_age_team_class_has_prf_team_class_prf_team_class1_idx` (`prf_team_class_id`),
  KEY `fk_age_team_class_has_prf_team_class_age_team_class1_idx` (`age_team_class_id`),
  CONSTRAINT `fk_age_team_class_has_prf_team_class_age_team_class1` FOREIGN KEY (`age_team_class_id`) REFERENCES `age_team_class` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_age_team_class_has_prf_team_class_prf_team_class1` FOREIGN KEY (`prf_team_class_id`) REFERENCES `prf_team_class` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `age_team_class_has_value`
--

DROP TABLE IF EXISTS `age_team_class_has_value`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `age_team_class_has_value` (
  `age_team_class_id` smallint(6) NOT NULL,
  `value_id` smallint(6) NOT NULL,
  PRIMARY KEY (`age_team_class_id`,`value_id`),
  KEY `fk_age_team_class_has_value_value1_idx` (`value_id`),
  KEY `fk_age_team_class_has_value_age_team_class1_idx` (`age_team_class_id`),
  CONSTRAINT `fk_age_team_class_has_value_age_team_class1` FOREIGN KEY (`age_team_class_id`) REFERENCES `age_team_class` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_age_team_class_has_value_value1` FOREIGN KEY (`value_id`) REFERENCES `value` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `age_team_has_age_person`
--

DROP TABLE IF EXISTS `age_team_has_age_person`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `age_team_has_age_person` (
  `age_team_id` int(11) NOT NULL,
  `age_person_id` smallint(6) NOT NULL,
  PRIMARY KEY (`age_team_id`,`age_person_id`),
  KEY `fk_age_team_has_age_person_age_person1_idx` (`age_person_id`),
  KEY `fk_age_team_has_age_person_age_team1_idx` (`age_team_id`),
  CONSTRAINT `fk_age_team_has_age_person_age_person1` FOREIGN KEY (`age_person_id`) REFERENCES `age_person` (`id`),
  CONSTRAINT `fk_age_team_has_age_person_age_team1` FOREIGN KEY (`age_team_id`) REFERENCES `age_team` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `age_team_has_prf_team`
--

DROP TABLE IF EXISTS `age_team_has_prf_team`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `age_team_has_prf_team` (
  `age_team_id` int(11) NOT NULL,
  `prf_team_id` int(11) NOT NULL,
  PRIMARY KEY (`age_team_id`,`prf_team_id`),
  KEY `fk_age_team_has_prf_team_prf_team1_idx` (`prf_team_id`),
  KEY `fk_age_team_has_prf_team_age_team1_idx` (`age_team_id`),
  CONSTRAINT `fk_age_team_has_prf_team_age_team1` FOREIGN KEY (`age_team_id`) REFERENCES `age_team` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_age_team_has_prf_team_prf_team1` FOREIGN KEY (`prf_team_id`) REFERENCES `prf_team` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `domain`
--

DROP TABLE IF EXISTS `domain`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `domain` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `event`
--

DROP TABLE IF EXISTS `event`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `event` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `model_id` smallint(6) NOT NULL,
  `describe` json NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_event_model1_idx` (`model_id`),
  CONSTRAINT `fk_event_model1` FOREIGN KEY (`model_id`) REFERENCES `model` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2277 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `event_has_team_class`
--

DROP TABLE IF EXISTS `event_has_team_class`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `event_has_team_class` (
  `event_id` int(11) NOT NULL,
  `team_class_id` smallint(6) NOT NULL,
  PRIMARY KEY (`event_id`,`team_class_id`),
  KEY `fk_event_has_team_class_team_class1_idx` (`team_class_id`),
  KEY `fk_event_has_team_class_event1_idx` (`event_id`),
  CONSTRAINT `fk_event_has_team_class_event1` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`),
  CONSTRAINT `fk_event_has_team_class_team_class1` FOREIGN KEY (`team_class_id`) REFERENCES `team_class` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `event_has_value`
--

DROP TABLE IF EXISTS `event_has_value`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `event_has_value` (
  `event_id` int(11) NOT NULL,
  `value_id` smallint(6) NOT NULL,
  PRIMARY KEY (`event_id`,`value_id`),
  KEY `fk_event_has_value_value1_idx` (`value_id`),
  KEY `fk_event_has_value_event1_idx` (`event_id`),
  CONSTRAINT `fk_event_has_value_event1` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_event_has_value_value1` FOREIGN KEY (`value_id`) REFERENCES `value` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `model`
--

DROP TABLE IF EXISTS `model`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `model` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `model_has_value`
--

DROP TABLE IF EXISTS `model_has_value`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `model_has_value` (
  `model_id` smallint(6) NOT NULL,
  `value_id` smallint(6) NOT NULL,
  PRIMARY KEY (`model_id`,`value_id`),
  KEY `fk_model_has_value_value1_idx` (`value_id`),
  KEY `fk_model_has_value_model1_idx` (`model_id`),
  CONSTRAINT `fk_model_has_value_model1` FOREIGN KEY (`model_id`) REFERENCES `model` (`id`),
  CONSTRAINT `fk_model_has_value_value1` FOREIGN KEY (`value_id`) REFERENCES `value` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `person`
--

DROP TABLE IF EXISTS `person`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `person` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `age_person_id` smallint(6) NOT NULL,
  `prf_person_id` smallint(6) NOT NULL,
  `describe` json NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_person_age_person1_idx` (`age_person_id`),
  KEY `fk_person_prf_person1_idx` (`prf_person_id`),
  CONSTRAINT `fk_person_age_person1` FOREIGN KEY (`age_person_id`) REFERENCES `age_person` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_person_prf_person1` FOREIGN KEY (`prf_person_id`) REFERENCES `prf_person` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `prf_person`
--

DROP TABLE IF EXISTS `prf_person`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `prf_person` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `describe` json NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=137 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `prf_person_has_value`
--

DROP TABLE IF EXISTS `prf_person_has_value`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `prf_person_has_value` (
  `prf_person_id` smallint(6) NOT NULL,
  `value_id` smallint(6) NOT NULL,
  PRIMARY KEY (`prf_person_id`,`value_id`),
  KEY `fk_prf_person_has_value_value1_idx` (`value_id`),
  KEY `fk_prf_person_has_value_prf_person1_idx` (`prf_person_id`),
  CONSTRAINT `fk_prf_person_has_value_prf_person1` FOREIGN KEY (`prf_person_id`) REFERENCES `prf_person` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_prf_person_has_value_value1` FOREIGN KEY (`value_id`) REFERENCES `value` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `prf_team`
--

DROP TABLE IF EXISTS `prf_team`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `prf_team` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prf_team_class_id` smallint(6) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_prf_team_prf_team_class1_idx` (`prf_team_class_id`),
  CONSTRAINT `fk_prf_team_prf_team_class1` FOREIGN KEY (`prf_team_class_id`) REFERENCES `prf_team_class` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1014 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `prf_team_class`
--

DROP TABLE IF EXISTS `prf_team_class`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `prf_team_class` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `describe` json NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=220 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `prf_team_class_has_value`
--

DROP TABLE IF EXISTS `prf_team_class_has_value`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `prf_team_class_has_value` (
  `prf_team_class_id` smallint(6) NOT NULL,
  `value_id` smallint(6) NOT NULL,
  PRIMARY KEY (`prf_team_class_id`,`value_id`),
  KEY `fk_prf_team_class_has_value_value1_idx` (`value_id`),
  KEY `fk_prf_team_class_has_value_prf_team_class1_idx` (`prf_team_class_id`),
  CONSTRAINT `fk_prf_team_class_has_value_prf_team_class1` FOREIGN KEY (`prf_team_class_id`) REFERENCES `prf_team_class` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_prf_team_class_has_value_value1` FOREIGN KEY (`value_id`) REFERENCES `value` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `prf_team_has_prf_person`
--

DROP TABLE IF EXISTS `prf_team_has_prf_person`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `prf_team_has_prf_person` (
  `prf_team_id` int(11) NOT NULL,
  `prf_person_id` smallint(6) NOT NULL,
  PRIMARY KEY (`prf_team_id`,`prf_person_id`),
  KEY `fk_prf_team_has_prf_person_prf_person1_idx` (`prf_person_id`),
  KEY `fk_prf_team_has_prf_person_prf_team1_idx` (`prf_team_id`),
  CONSTRAINT `fk_prf_team_has_prf_person_prf_person1` FOREIGN KEY (`prf_person_id`) REFERENCES `prf_person` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_prf_team_has_prf_person_prf_team1` FOREIGN KEY (`prf_team_id`) REFERENCES `prf_team` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `team`
--

DROP TABLE IF EXISTS `team`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `team` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `team_class_id` smallint(6) NOT NULL,
  `age_team_id` int(11) NOT NULL,
  `prf_team_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_team_team_class1_idx` (`team_class_id`),
  KEY `fk_team_age_team1_idx` (`age_team_id`),
  KEY `fk_team_prf_team1_idx` (`prf_team_id`),
  CONSTRAINT `fk_team_age_team1` FOREIGN KEY (`age_team_id`) REFERENCES `age_team` (`id`),
  CONSTRAINT `fk_team_prf_team1` FOREIGN KEY (`prf_team_id`) REFERENCES `prf_team` (`id`),
  CONSTRAINT `fk_team_team_class1` FOREIGN KEY (`team_class_id`) REFERENCES `team_class` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `team_class`
--

DROP TABLE IF EXISTS `team_class`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `team_class` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `age_team_class_id` smallint(6) NOT NULL,
  `prf_team_class_id` smallint(6) NOT NULL,
  `describe` json NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_team_class_age_team_class1_idx` (`age_team_class_id`),
  KEY `fk_team_class_prf_team_class1_idx` (`prf_team_class_id`),
  CONSTRAINT `fk_team_class_age_team_class1` FOREIGN KEY (`age_team_class_id`) REFERENCES `age_team_class` (`id`),
  CONSTRAINT `fk_team_class_prf_team_class1` FOREIGN KEY (`prf_team_class_id`) REFERENCES `prf_team_class` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `team_has_person`
--

DROP TABLE IF EXISTS `team_has_person`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `team_has_person` (
  `team_id` int(11) NOT NULL,
  `person_id` int(11) NOT NULL,
  PRIMARY KEY (`team_id`,`person_id`),
  KEY `fk_team_has_person_person1_idx` (`person_id`),
  KEY `fk_team_has_person_team1_idx` (`team_id`),
  CONSTRAINT `fk_team_has_person_person1` FOREIGN KEY (`person_id`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_team_has_person_team1` FOREIGN KEY (`team_id`) REFERENCES `team` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `value`
--

DROP TABLE IF EXISTS `value`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `value` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `domain_id` smallint(6) NOT NULL,
  `name` varchar(45) NOT NULL,
  `abbr` varchar(6) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_value_domain1_idx` (`domain_id`),
  CONSTRAINT `fk_value_domain1` FOREIGN KEY (`domain_id`) REFERENCES `domain` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=178 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Temporary view structure for view `view_person`
--

DROP TABLE IF EXISTS `view_person`;
/*!50001 DROP VIEW IF EXISTS `view_person`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8mb4;
/*!50001 CREATE VIEW `view_person` AS SELECT 
 1 AS `age_person_id`,
 1 AS `prf_person_id`,
 1 AS `describe`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `view_team`
--

DROP TABLE IF EXISTS `view_team`;
/*!50001 DROP VIEW IF EXISTS `view_team`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8mb4;
/*!50001 CREATE VIEW `view_team` AS SELECT 
 1 AS `team_class_id`,
 1 AS `age_team_id`,
 1 AS `prf_team_id`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `view_team_aux`
--

DROP TABLE IF EXISTS `view_team_aux`;
/*!50001 DROP VIEW IF EXISTS `view_team_aux`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8mb4;
/*!50001 CREATE VIEW `view_team_aux` AS SELECT 
 1 AS `age_team_id`,
 1 AS `prf_team_id`,
 1 AS `JSON_MERGE_PATCH(atc.describe,ptc.describe)`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `view_team_class`
--

DROP TABLE IF EXISTS `view_team_class`;
/*!50001 DROP VIEW IF EXISTS `view_team_class`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8mb4;
/*!50001 CREATE VIEW `view_team_class` AS SELECT 
 1 AS `age_team_class_id`,
 1 AS `prf_team_class_id`,
 1 AS `describe`*/;
SET character_set_client = @saved_cs_client;

--
-- Dumping events for database 'setup'
--

--
-- Dumping routines for database 'setup'
--
/*!50003 DROP PROCEDURE IF EXISTS `build_setup` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `build_setup`()
BEGIN
  INSERT INTO person(age_person_id,prf_person_id, `describe`)
  SELECT age_person_id, prf_person_id, `describe`
  FROM view_person;
  
  INSERT INTO team_class(age_team_class_id, prf_team_class_id, `describe`)
  SELECT age_team_class_id, prf_team_class_id, `describe`
  FROM view_team_class;
  
  INSERT INTO team(team_class_id, age_team_id, prf_team_id)
  SELECT team_class_id, age_team_id, prf_team_id
  FROM view_team;
  
  INSERT INTO team_has_person(team_id,person_id)
  SELECT team_id,person_id
  FROM view_team_person;
  
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Final view structure for view `view_person`
--

/*!50001 DROP VIEW IF EXISTS `view_person`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_person` AS select `ap`.`id` AS `age_person_id`,`pp`.`id` AS `prf_person_id`,json_merge_patch(`pp`.`describe`,`ap`.`describe`) AS `describe` from ((`age_person_has_prf_person` `appp` join `prf_person` `pp` on((`appp`.`prf_person_id` = `pp`.`id`))) join `age_person` `ap` on((`appp`.`age_person_id` = `ap`.`id`))) where ((json_extract(`pp`.`describe`,'$.type') = json_extract(`ap`.`describe`,'$.type')) and (json_extract(`pp`.`describe`,'$.status') = json_extract(`ap`.`describe`,'$.status')) and (json_extract(`pp`.`describe`,'$.designate') = json_extract(`ap`.`describe`,'$.designate'))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_team`
--

/*!50001 DROP VIEW IF EXISTS `view_team`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_team` AS select `tc`.`id` AS `team_class_id`,`at`.`id` AS `age_team_id`,`pt`.`id` AS `prf_team_id` from ((`team_class` `tc` join `age_team` `at` on((`at`.`age_team_class_id` = `tc`.`age_team_class_id`))) join `prf_team` `pt` on((`pt`.`prf_team_class_id` = `tc`.`prf_team_class_id`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_team_aux`
--

/*!50001 DROP VIEW IF EXISTS `view_team_aux`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_team_aux` AS select distinct `at`.`id` AS `age_team_id`,`pt`.`id` AS `prf_team_id`,json_merge_patch(`atc`.`describe`,`ptc`.`describe`) AS `JSON_MERGE_PATCH(atc.describe,ptc.describe)` from ((((`age_team_class_has_prf_team_class` `atcptc` join `age_team_class` `atc` on((`atcptc`.`age_team_class_id` = `atc`.`id`))) join `prf_team_class` `ptc` on((`atcptc`.`prf_team_class_id` = `ptc`.`id`))) join `age_team` `at` on((`atc`.`id` = `at`.`age_team_class_id`))) join `prf_team` `pt` on((`ptc`.`id` = `pt`.`prf_team_class_id`))) where ((json_extract(`atc`.`describe`,'$.type') = json_extract(`ptc`.`describe`,'$.type')) and (json_extract(`atc`.`describe`,'$.status') = json_extract(`ptc`.`describe`,'$.status'))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_team_class`
--

/*!50001 DROP VIEW IF EXISTS `view_team_class`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_team_class` AS select `atcptc`.`age_team_class_id` AS `age_team_class_id`,`atcptc`.`prf_team_class_id` AS `prf_team_class_id`,json_merge_patch(`atc`.`describe`,`ptc`.`describe`) AS `describe` from ((`age_team_class_has_prf_team_class` `atcptc` join `age_team_class` `atc` on((`atcptc`.`age_team_class_id` = `atc`.`id`))) join `prf_team_class` `ptc` on((`atcptc`.`prf_team_class_id` = `ptc`.`id`))) */;
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

-- Dump completed on 2018-12-31 10:36:36
