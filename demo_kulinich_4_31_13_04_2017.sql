-- MySQL dump 10.13  Distrib 5.5.54, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: demo_kulinich
-- ------------------------------------------------------
-- Server version	5.5.54-0ubuntu0.14.04.1

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
-- Table structure for table `acl_group_permissions`
--

DROP TABLE IF EXISTS `acl_group_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acl_group_permissions` (
  `id` int(255) unsigned NOT NULL AUTO_INCREMENT,
  `group_id` int(5) unsigned NOT NULL,
  `permission_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `group_id` (`group_id`,`permission_id`),
  KEY `permission_id` (`permission_id`),
  CONSTRAINT `acl_group_permissions_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `acl_groups` (`group_id`) ON DELETE CASCADE,
  CONSTRAINT `acl_group_permissions_ibfk_2` FOREIGN KEY (`permission_id`) REFERENCES `acl_permissions` (`permission_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acl_group_permissions`
--

LOCK TABLES `acl_group_permissions` WRITE;
/*!40000 ALTER TABLE `acl_group_permissions` DISABLE KEYS */;
INSERT INTO `acl_group_permissions` VALUES (1,2,1),(2,3,4),(3,3,5),(4,3,6),(5,3,7),(6,3,8),(7,3,9),(8,3,10),(9,3,11),(10,3,12),(11,3,13),(12,3,14),(13,3,15),(14,4,16);
/*!40000 ALTER TABLE `acl_group_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acl_groups`
--

DROP TABLE IF EXISTS `acl_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acl_groups` (
  `group_id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(5) DEFAULT NULL,
  `group_name` varchar(255) NOT NULL,
  `group_code` char(50) NOT NULL,
  `group_is_guest` enum('yes','no') NOT NULL DEFAULT 'no',
  PRIMARY KEY (`group_id`),
  UNIQUE KEY `group_code_2` (`group_code`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acl_groups`
--

LOCK TABLES `acl_groups` WRITE;
/*!40000 ALTER TABLE `acl_groups` DISABLE KEYS */;
INSERT INTO `acl_groups` VALUES (2,NULL,'developer-group','developer-group','no'),(3,NULL,'Manager','Manager','no'),(4,NULL,'visitor','visitor','yes');
/*!40000 ALTER TABLE `acl_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acl_permission_resources`
--

DROP TABLE IF EXISTS `acl_permission_resources`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acl_permission_resources` (
  `id` int(255) unsigned NOT NULL AUTO_INCREMENT,
  `permission_id` int(10) unsigned DEFAULT NULL,
  `resource_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `permission_id` (`permission_id`,`resource_id`),
  KEY `resource_id` (`resource_id`),
  CONSTRAINT `acl_permission_resources_ibfk_1` FOREIGN KEY (`permission_id`) REFERENCES `acl_permissions` (`permission_id`) ON DELETE CASCADE,
  CONSTRAINT `acl_permission_resources_ibfk_2` FOREIGN KEY (`resource_id`) REFERENCES `acl_resources` (`resource_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=233 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acl_permission_resources`
--

LOCK TABLES `acl_permission_resources` WRITE;
/*!40000 ALTER TABLE `acl_permission_resources` DISABLE KEYS */;
INSERT INTO `acl_permission_resources` VALUES (1,1,1),(2,1,2),(3,1,3),(4,1,4),(5,1,5),(6,1,6),(7,1,7),(8,1,8),(9,1,9),(10,1,10),(11,1,11),(12,1,12),(13,1,13),(14,1,14),(15,1,15),(16,1,16),(17,1,17),(18,1,18),(19,1,19),(20,1,20),(21,1,21),(22,1,22),(23,1,23),(24,1,24),(25,1,25),(26,1,26),(27,1,27),(28,1,28),(29,1,29),(30,1,30),(31,1,31),(32,1,32),(33,1,33),(34,1,34),(35,1,35),(36,1,36),(37,1,37),(38,1,38),(39,1,39),(40,1,40),(41,1,41),(42,1,42),(43,1,43),(44,1,44),(45,1,45),(46,1,46),(47,1,47),(48,1,48),(49,1,49),(50,1,50),(51,1,51),(52,1,52),(53,1,53),(54,1,54),(55,1,55),(56,1,56),(57,1,57),(58,1,58),(59,1,59),(60,1,60),(61,1,61),(62,1,62),(63,1,63),(64,1,64),(65,1,65),(66,1,66),(67,1,67),(68,1,68),(69,1,69),(70,1,70),(71,1,71),(72,1,72),(73,1,73),(74,1,74),(75,1,75),(76,1,76),(77,1,77),(78,1,78),(79,1,79),(80,1,80),(81,1,81),(82,1,82),(83,1,83),(84,1,84),(85,1,85),(86,1,86),(232,1,87),(154,4,3),(155,4,5),(156,4,7),(157,5,6),(158,5,8),(159,5,9),(160,5,10),(161,5,11),(162,5,12),(163,5,13),(164,5,14),(165,5,15),(166,5,16),(167,6,17),(168,6,18),(169,6,19),(170,6,20),(171,6,21),(172,6,22),(173,7,33),(174,7,34),(175,7,35),(176,7,36),(177,7,37),(178,7,38),(179,7,39),(180,7,40),(181,7,41),(182,7,42),(183,7,43),(184,8,48),(185,9,49),(186,9,50),(187,9,51),(188,9,52),(189,9,53),(190,9,54),(191,10,14),(192,10,15),(226,10,63),(227,10,64),(228,10,65),(229,10,66),(230,10,67),(231,10,87),(193,11,44),(194,11,45),(195,12,59),(196,12,60),(197,12,61),(198,12,62),(199,13,68),(200,13,69),(201,13,70),(202,13,71),(203,14,73),(204,14,74),(205,14,75),(206,14,76),(207,14,77),(208,14,78),(209,15,79),(210,15,80),(211,15,81),(212,15,82),(213,16,3),(214,16,4),(215,16,6),(216,16,7),(217,16,8),(218,16,9),(219,16,10),(220,16,11),(221,16,12),(222,16,13),(223,16,14),(224,16,15),(225,16,16);
/*!40000 ALTER TABLE `acl_permission_resources` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acl_permissions`
--

DROP TABLE IF EXISTS `acl_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acl_permissions` (
  `permission_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_permission_id` int(10) unsigned DEFAULT NULL,
  `permission_type` enum('group','permission') NOT NULL DEFAULT 'permission',
  `permission_code` varchar(255) NOT NULL,
  PRIMARY KEY (`permission_id`),
  KEY `parent_permission_id` (`parent_permission_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acl_permissions`
--

LOCK TABLES `acl_permissions` WRITE;
/*!40000 ALTER TABLE `acl_permissions` DISABLE KEYS */;
INSERT INTO `acl_permissions` VALUES (1,NULL,'permission','developer-permissions'),(3,NULL,'group','content-management'),(4,3,'permission','auth'),(5,3,'permission','fe-part-access'),(6,3,'permission','about-us-access'),(7,3,'permission','clinic-cases-access'),(8,3,'permission','dashboard-access'),(9,3,'permission','lab-access'),(10,3,'permission','publications-access'),(11,3,'permission','email-templates-access'),(12,3,'permission','menu-names-access'),(13,3,'permission','static-pages-access'),(14,3,'permission','team-access'),(15,3,'permission','system-translation-access'),(16,NULL,'permission','visitor-permissions');
/*!40000 ALTER TABLE `acl_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acl_resources`
--

DROP TABLE IF EXISTS `acl_resources`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acl_resources` (
  `resource_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `resource_type` enum('mvc','custom') NOT NULL DEFAULT 'custom',
  `resource_code` char(255) NOT NULL,
  `resource_action` varchar(255) NOT NULL,
  PRIMARY KEY (`resource_id`)
) ENGINE=InnoDB AUTO_INCREMENT=88 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acl_resources`
--

LOCK TABLES `acl_resources` WRITE;
/*!40000 ALTER TABLE `acl_resources` DISABLE KEYS */;
INSERT INTO `acl_resources` VALUES (1,'custom','system::email-templates','editVars'),(2,'custom','developer','developer'),(3,'mvc','auth::denied','index'),(4,'mvc','auth::index','index'),(5,'mvc','auth::logout','index'),(6,'mvc','default::about','index'),(7,'mvc','default::account','index'),(8,'mvc','default::clinic','index'),(9,'mvc','default::clinic','view'),(10,'mvc','default::error','error'),(11,'mvc','default::index','index'),(12,'mvc','default::index','static-page'),(13,'mvc','default::laboratory','index'),(14,'mvc','default::publications','index'),(15,'mvc','default::publications','view'),(16,'mvc','default::team','index'),(17,'mvc','system::about','index'),(18,'mvc','system::about','create'),(19,'mvc','system::about','handle-upload'),(20,'mvc','system::about','reorder-items'),(21,'mvc','system::about','edit'),(22,'mvc','system::about','delete'),(23,'mvc','system::acl-groups','index'),(24,'mvc','system::acl-groups','create'),(25,'mvc','system::acl-groups','edit'),(26,'mvc','system::acl-groups','delete'),(27,'mvc','system::acl-permissions','create'),(28,'mvc','system::acl-permissions','index'),(29,'mvc','system::acl-permissions','set-parent'),(30,'mvc','system::acl-permissions','delete'),(31,'mvc','system::acl-permissions','edit'),(32,'mvc','system::acl-resources','index'),(33,'mvc','system::clinic-cases','index'),(34,'mvc','system::clinic-cases','create'),(35,'mvc','system::clinic-cases','reorder-items'),(36,'mvc','system::clinic-cases','edit'),(37,'mvc','system::clinic-cases','delete'),(38,'mvc','system::clinic-cases-gallery','index'),(39,'mvc','system::clinic-cases-gallery','create'),(40,'mvc','system::clinic-cases-gallery','handle-upload'),(41,'mvc','system::clinic-cases-gallery','reorder-items'),(42,'mvc','system::clinic-cases-gallery','delete'),(43,'mvc','system::clinic-cases-gallery','edit'),(44,'mvc','system::email-templates','index'),(45,'mvc','system::email-templates','edit'),(46,'mvc','system::email-templates','create'),(47,'mvc','system::email-templates','delete'),(48,'mvc','system::index','index'),(49,'mvc','system::laboratory','index'),(50,'mvc','system::laboratory','create'),(51,'mvc','system::laboratory','handle-upload'),(52,'mvc','system::laboratory','reorder-items'),(53,'mvc','system::laboratory','edit'),(54,'mvc','system::laboratory','delete'),(55,'mvc','system::languages','create'),(56,'mvc','system::languages','edit'),(57,'mvc','system::languages','index'),(58,'mvc','system::languages','delete'),(59,'mvc','system::menu-names','index'),(60,'mvc','system::menu-names','create'),(61,'mvc','system::menu-names','edit'),(62,'mvc','system::menu-names','delete'),(63,'mvc','system::publications','index'),(64,'mvc','system::publications','handle-upload'),(65,'mvc','system::publications','create'),(66,'mvc','system::publications','edit'),(67,'mvc','system::publications','delete'),(68,'mvc','system::static-pages','index'),(69,'mvc','system::static-pages','handle-upload'),(70,'mvc','system::static-pages','create'),(71,'mvc','system::static-pages','edit'),(72,'mvc','system::static-pages','delete'),(73,'mvc','system::team','index'),(74,'mvc','system::team','create'),(75,'mvc','system::team','handle-upload'),(76,'mvc','system::team','reorder-items'),(77,'mvc','system::team','edit'),(78,'mvc','system::team','delete'),(79,'mvc','system::translations','create'),(80,'mvc','system::translations','edit'),(81,'mvc','system::translations','delete'),(82,'mvc','system::translations','index'),(83,'mvc','system::users','create'),(84,'mvc','system::users','edit'),(85,'mvc','system::users','index'),(86,'mvc','system::users','delete'),(87,'mvc','system::publications','reorder-items');
/*!40000 ALTER TABLE `acl_resources` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clinic_case_photos`
--

DROP TABLE IF EXISTS `clinic_case_photos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `clinic_case_photos` (
  `photo_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `case_id` int(10) unsigned NOT NULL,
  `photo_description` text,
  `photo_image` varchar(255) DEFAULT NULL,
  `photo_rank` int(10) DEFAULT NULL,
  `photo_is_cover` enum('yes','no') DEFAULT NULL,
  PRIMARY KEY (`photo_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clinic_case_photos`
--

LOCK TABLES `clinic_case_photos` WRITE;
/*!40000 ALTER TABLE `clinic_case_photos` DISABLE KEYS */;
INSERT INTO `clinic_case_photos` VALUES (5,2,'ээээээ','/assets/clinic/image_58dbb055cfa0a.png',1,'no'),(6,3,'Picture 1','/assets/clinic/image_58dbcf4911663.png',2,'no'),(7,3,'oicywyf idc sidwio eciowe ioewd ioeduweio dioweud wioedu iowe\r\nfsd\r\nfs\r\nfsdf\r\nLorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.\r\ne\r\nfe','/assets/clinic/image_58dbcf813aeda.png',1,'no'),(8,3,'rtytr','/assets/clinic/image_58dbcfe071f94.png',0,'yes'),(11,2,'Не следует, однако забывать, что реализация намеченных плановых заданий требуют от нас анализа дальнейших направлений развития. Задача организации, в особенности же постоянное информационно-пропагандистское обеспечение нашей деятельности влечет за собой процесс внедрения и модернизации соответствующий условий активизации. Равным образом реализация намеченных плановых заданий способствует подготовки и реализации систем массового участия. Товарищи! постоянное информационно-пропагандистское обеспечение нашей деятельности позволяет оценить значение направлений прогрессивного развития. Идейные соображения высшего порядка, а также реализация намеченных плановых заданий играет важную роль в формировании форм развития.','/assets/clinic/image_58dc9bfdda945.png',0,'yes'),(12,2,'Разнообразный и богатый опыт укрепление и развитие структуры позволяет выполнять важные задания по разработке направлений прогрессивного развития. Задача организации, в особенности же начало повседневной работы по формированию позиции требуют определения и уточнения дальнейших направлений развития.','/assets/clinic/image_58eecda0d33a3.png',2,'no'),(13,2,'Разнообразный и богатый опыт укрепление и развитие структуры позволяет выполнять важные задания по разработке направлений прогрессивного развития. Задача организации, в особенности же начало повседневной работы по формированию позиции требуют определения и уточнения дальнейших направлений развития.','/assets/clinic/image_58eecdb63012f.png',3,'no'),(14,2,'Разнообразный и богатый опыт укрепление и развитие структуры позволяет выполнять важные задания по разработке направлений прогрессивного развития. Задача организации, в особенности же начало повседневной работы по формированию позиции требуют определения и уточнения дальнейших направлений развития.','/assets/clinic/image_58eecde19f2c5.png',4,'no'),(15,2,'Разнообразный и богатый опыт укрепление и развитие структуры позволяет выполнять важные задания по разработке направлений прогрессивного развития. Задача организации, в особенности же начало повседневной работы по формированию позиции требуют определения и уточнения дальнейших направлений развития.','/assets/clinic/image_58eecdf1829ab.png',5,'no');
/*!40000 ALTER TABLE `clinic_case_photos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clinic_cases`
--

DROP TABLE IF EXISTS `clinic_cases`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `clinic_cases` (
  `case_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `case_code` varchar(255) NOT NULL,
  `case_title` varchar(255) DEFAULT NULL,
  `is_enabled` enum('yes','no') NOT NULL DEFAULT 'no',
  `case_rank` int(10) DEFAULT NULL,
  PRIMARY KEY (`case_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clinic_cases`
--

LOCK TABLES `clinic_cases` WRITE;
/*!40000 ALTER TABLE `clinic_cases` DISABLE KEYS */;
INSERT INTO `clinic_cases` VALUES (2,'only_latin_characters_without_whitespases','Кариес кашмариес','yes',1),(3,'case1','Clinical case','yes',0);
/*!40000 ALTER TABLE `clinic_cases` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `languages`
--

DROP TABLE IF EXISTS `languages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `languages` (
  `language_id` char(2) NOT NULL,
  `language_name` varchar(255) NOT NULL,
  `language_is_avaliable` enum('yes','no') NOT NULL DEFAULT 'no',
  `language_locale` char(5) NOT NULL,
  PRIMARY KEY (`language_id`),
  KEY `language_locale` (`language_locale`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `languages`
--

LOCK TABLES `languages` WRITE;
/*!40000 ALTER TABLE `languages` DISABLE KEYS */;
INSERT INTO `languages` VALUES ('ru','Русский','yes','ru_RU');
/*!40000 ALTER TABLE `languages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `photos`
--

DROP TABLE IF EXISTS `photos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `photos` (
  `photo_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `photo_description` text,
  `photo_title` varchar(255) DEFAULT NULL,
  `photo_image` varchar(255) DEFAULT NULL,
  `photo_rank` int(10) DEFAULT NULL,
  `photo_gallery` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`photo_id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `photos`
--

LOCK TABLES `photos` WRITE;
/*!40000 ALTER TABLE `photos` DISABLE KEYS */;
INSERT INTO `photos` VALUES (3,'Идейные соображения высшего порядка, а также сложившаяся структура организации играет важную роль в формировании форм развития. Идейные соображения высшего порядка, а также начало повседневной работы по формированию позиции играет важную роль в формировании системы обучения кадров, соответствует насущным потребностям. С другой стороны постоянное информационно-пропагандистское обеспечение нашей деятельности играет важную роль в формировании направлений прогрессивного развития. Товарищи! новая модель организационной деятельности в значительной степени обуславливает создание направлений прогрессивного развития.','О нас ещё что-то','/assets/photos/image_58dbaf5eda18d.png',0,'about'),(4,'Не следует, однако забывать, что реализация намеченных плановых заданий требуют от нас анализа дальнейших направлений развития. Задача организации, в особенности же постоянное информационно-пропагандистское обеспечение нашей деятельности влечет за собой процесс внедрения и модернизации соответствующий условий активизации. Равным образом реализация намеченных плановых заданий способствует подготовки и реализации систем массового участия. Товарищи! постоянное информационно-пропагандистское обеспечение нашей деятельности позволяет оценить значение направлений прогрессивного развития. Идейные соображения высшего порядка, а также реализация намеченных плановых заданий играет важную роль в формировании форм развития.','Наша лаборатория','/assets/photos/image_58dbb017b9ddd.png',4,'lab'),(5,'С другой стороны дальнейшее развитие различных форм деятельности обеспечивает широкому кругу (специалистов) участие в формировании существенных финансовых и административных условий. Значимость этих проблем настолько очевидна, что укрепление и развитие структуры играет важную роль в формировании позиций, занимаемых участниками в отношении поставленных задач. Равным образом новая модель организационной деятельности в значительной степени обуславливает создание системы обучения кадров, соответствует насущным потребностям. С другой стороны рамки и место обучения кадров в значительной степени обуславливает создание форм развития. Разнообразный и богатый опыт постоянный количественный рост и сфера нашей активности обеспечивает широкому кругу (специалистов) участие в формировании позиций, занимаемых участниками в отношении поставленных задач. Не следует, однако забывать, что консультация с широким активом в значительной степени обуславливает создание дальнейших направлений развития.\r\n\r\nС другой стороны дальнейшее развитие различных форм деятельности обеспечивает широкому кругу (специалистов) участие в формировании существенных финансовых и административных условий. Значимость этих проблем настолько очевидна, что укрепление и развитие структуры играет важную роль в формировании позиций, занимаемых участниками в отношении поставленных задач. Равным образом новая модель организационной деятельности в значительной степени обуславливает создание системы обучения кадров, соответствует насущным потребностям. С другой стороны рамки и место обучения кадров в значительной степени обуславливает создание форм развития. Разнообразный и богатый опыт постоянный количественный рост и сфера нашей активности обеспечивает широкому кругу (специалистов) участие в формировании позиций, занимаемых участниками в отношении поставленных задач. Не следует, однако забывать, что консультация с широким активом в значительной степени обуславливает создание дальнейших направлений развития.\r\n\r\nС другой стороны дальнейшее развитие различных форм деятельности обеспечивает широкому кругу (специалистов) участие в формировании существенных финансовых и административных условий. Значимость этих проблем настолько очевидна, что укрепление и развитие структуры играет важную роль в формировании позиций, занимаемых участниками в отношении поставленных задач. Равным образом новая модель организационной деятельности в значительной степени обуславливает создание системы обучения кадров, соответствует насущным потребностям. С другой стороны рамки и место обучения кадров в значительной степени обуславливает создание форм развития. Разнообразный и богатый опыт постоянный количественный рост и сфера нашей активности обеспечивает широкому кругу (специалистов) участие в формировании позиций, занимаемых участниками в отношении поставленных задач. Не следует, однако забывать, что консультация с широким активом в значительной степени обуславливает создание дальнейших направлений развития.','О нас','/assets/photos/image_58dc541bb9526.png',1,'about'),(6,'Равным образом дальнейшее развитие различных форм деятельности играет важную роль в формировании существенных финансовых и административных условий. Задача организации, в особенности же постоянный количественный рост и сфера нашей активности обеспечивает широкому кругу (специалистов) участие в формировании новых предложений.\r\n\r\n','Просто текст','/assets/photos/image_58dc54bb2d9aa.png',2,'about'),(7,'Не следует, однако забывать, что реализация намеченных плановых заданий требуют от нас анализа дальнейших направлений развития. Задача организации, в особенности же постоянное информационно-пропагандистское обеспечение нашей деятельности влечет за собой процесс внедрения и модернизации соответствующий условий активизации. Равным образом реализация намеченных плановых заданий способствует подготовки и реализации систем массового участия. Товарищи! постоянное информационно-пропагандистское обеспечение нашей деятельности позволяет оценить значение направлений прогрессивного развития. Идейные соображения высшего порядка, а также реализация намеченных плановых заданий играет важную роль в формировании форм развития.','Может картинку поменять?','/assets/photos/image_58dc52db9289d.png',3,'about'),(8,'Не следует, однако забывать, что реализация намеченных плановых заданий требуют от нас анализа дальнейших направлений развития. Задача организации, в особенности же постоянное информационно-пропагандистское обеспечение нашей деятельности влечет за собой процесс внедрения и модернизации соответствующий условий активизации. Равным образом реализация намеченных плановых заданий способствует подготовки и реализации систем массового участия. Товарищи! постоянное информационно-пропагандистское обеспечение нашей деятельности позволяет оценить значение направлений прогрессивного развития. Идейные соображения высшего порядка, а также реализация намеченных плановых заданий играет важную роль в формировании форм развития.','Что-то произошло','/assets/photos/image_58dc5535f1269.png',4,'about'),(9,'Не следует, однако забывать, что реализация намеченных плановых заданий требуют от нас анализа дальнейших направлений развития. Задача организации, в особенности же постоянное информационно-пропагандистское обеспечение нашей деятельности влечет за собой процесс внедрения и модернизации соответствующий условий активизации. Равным образом реализация намеченных плановых заданий способствует подготовки и реализации систем массового участия. Товарищи! постоянное информационно-пропагандистское обеспечение нашей деятельности позволяет оценить значение направлений прогрессивного развития. Идейные соображения высшего порядка, а также реализация намеченных плановых заданий играет важную роль в формировании форм развития.','Ещё одна фотография','/assets/photos/image_58dc557133cf1.png',5,'about'),(10,'Не следует, однако забывать, что реализация намеченных плановых заданий требуют от нас анализа дальнейших направлений развития. Задача организации, в особенности же постоянное информационно-пропагандистское обеспечение нашей деятельности влечет за собой процесс внедрения и модернизации соответствующий условий активизации. Равным образом реализация намеченных плановых заданий способствует подготовки и реализации систем массового участия. Товарищи! постоянное информационно-пропагандистское обеспечение нашей деятельности позволяет оценить значение направлений прогрессивного развития. Идейные соображения высшего порядка, а также реализация намеченных плановых заданий играет важную роль в формировании форм развития.','Опять фото','/assets/photos/image_58dc55b795745.png',6,'about'),(11,'wer','wre','/assets/photos/image_58dc55b795745.png',7,'about'),(12,'wer','wre','/assets/photos/image_58dc580ae1bfd.png',8,'about'),(13,'wer','wre','/assets/photos/image_58dc562bcc471.png',9,'about'),(14,'wer','wre','/assets/photos/image_58dc563c26659.png',10,'about'),(15,'wer','wre','/assets/photos/image_58dc5669a94fe.png',11,'about'),(16,'Не следует, однако забывать, что реализация намеченных плановых заданий требуют от нас анализа дальнейших направлений развития. Задача организации, в особенности же постоянное информационно-пропагандистское обеспечение нашей деятельности влечет за собой процесс внедрения и модернизации соответствующий условий активизации. Равным образом реализация намеченных плановых заданий способствует подготовки и реализации систем массового участия. Товарищи! постоянное информационно-пропагандистское обеспечение нашей деятельности позволяет оценить значение направлений прогрессивного развития. Идейные соображения высшего порядка, а также реализация намеченных плановых заданий играет важную роль в формировании форм развития.','Я добавил фото','/assets/photos/image_58dc55eaef509.png',12,'about'),(17,'Не следует, однако забывать, что реализация намеченных плановых заданий требуют от нас анализа дальнейших направлений развития. Задача организации, в особенности же постоянное информационно-пропагандистское обеспечение нашей деятельности влечет за собой процесс внедрения и модернизации соответствующий условий активизации. Равным образом реализация намеченных плановых заданий способствует подготовки и реализации систем массового участия. Товарищи! постоянное информационно-пропагандистское обеспечение нашей деятельности позволяет оценить значение направлений прогрессивного развития. Идейные соображения высшего порядка, а также реализация намеченных плановых заданий играет важную роль в формировании форм развития.','Опять наша лаба','/assets/photos/image_58dc58ec00fa7.png',17,'lab');
/*!40000 ALTER TABLE `photos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `publications`
--

DROP TABLE IF EXISTS `publications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `publications` (
  `publication_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `publication_rank` int(10) unsigned DEFAULT NULL,
  `publication_title` varchar(255) DEFAULT NULL,
  `publication_code` varchar(255) NOT NULL,
  `publication_content` text,
  `publication_image` varchar(255) DEFAULT NULL,
  `publication_is_enabled` enum('yes','no') NOT NULL DEFAULT 'no',
  PRIMARY KEY (`publication_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `publications`
--

LOCK TABLES `publications` WRITE;
/*!40000 ALTER TABLE `publications` DISABLE KEYS */;
INSERT INTO `publications` VALUES (1,1,'Научная статья','article','\r\n<p>С другой стороны сложившаяся структура организации требуют от нас анализа системы обучения кадров, соответствует насущным потребностям. Товарищи! постоянный количественный рост и сфера нашей активности требуют от нас анализа соответствующий условий активизации. Идейные соображения высшего порядка, а также сложившаяся структура организации позволяет выполнять важные задания по разработке существенных финансовых и административных условий. Не следует, однако забывать, что постоянный количественный рост и сфера нашей активности позволяет выполнять важные задания по разработке соответствующий условий активизации. Задача организации, в особенности же дальнейшее развитие различных форм деятельности требуют определения и уточнения систем массового участия.</p>\r\n<p>С другой стороны новая модель организационной деятельности представляет собой интересный эксперимент проверки соответствующий условий активизации. Идейные соображения высшего порядка, а также новая модель организационной деятельности обеспечивает широкому кругу (специалистов) участие в формировании позиций, занимаемых участниками в отношении поставленных задач. Равным образом постоянное информационно-пропагандистское обеспечение нашей деятельности играет важную роль в формировании соответствующий условий активизации.</p><br />\r\n\r\n<div>&nbsp;</div> ','/assets/publication/image_58dbd1f1179aa.png','yes'),(2,0,'другая статья','12312','\r\n<div>\r\n	<p>С другой стороны сложившаяся структура организации требуют от нас анализа системы обучения кадров, соответствует насущным потребностям. Товарищи! постоянный количественный рост и сфера нашей активности требуют от нас анализа соответствующий условий активизации. Идейные соображения высшего порядка, а также сложившаяся структура организации позволяет выполнять важные задания по разработке существенных финансовых и административных условий. Не следует, однако забывать, что постоянный количественный рост и сфера нашей активности позволяет выполнять важные задания по разработке соответствующий условий активизации. Задача организации, в особенности же дальнейшее развитие различных форм деятельности требуют определения и уточнения систем массового участия.</p>\r\n	<p>С другой стороны новая модель организационной деятельности представляет собой интересный эксперимент проверки соответствующий условий активизации. Идейные соображения высшего порядка, а также новая модель организационной деятельности обеспечивает широкому кругу (специалистов) участие в формировании позиций, занимаемых участниками в отношении поставленных задач. Равным образом постоянное информационно-пропагандистское обеспечение нашей деятельности играет важную роль в формировании соответствующий условий активизации.</p><br />\r\n	</div>  ',NULL,'yes');
/*!40000 ALTER TABLE `publications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `session_id` char(32) NOT NULL,
  `session_modified` int(11) DEFAULT NULL,
  `session_lifetime` int(11) DEFAULT NULL,
  `session_data` text,
  PRIMARY KEY (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `static_page_locales`
--

DROP TABLE IF EXISTS `static_page_locales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `static_page_locales` (
  `locale_id` int(100) unsigned NOT NULL AUTO_INCREMENT,
  `page_id` int(10) unsigned NOT NULL,
  `language_id` char(2) NOT NULL,
  `html_title` varchar(255) DEFAULT NULL,
  `html_keywords` varchar(255) DEFAULT NULL,
  `locale_content` text NOT NULL,
  PRIMARY KEY (`locale_id`),
  KEY `FK_static_page_locales` (`page_id`),
  CONSTRAINT `FK_static_page_locales` FOREIGN KEY (`page_id`) REFERENCES `static_pages` (`page_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `static_page_locales`
--

LOCK TABLES `static_page_locales` WRITE;
/*!40000 ALTER TABLE `static_page_locales` DISABLE KEYS */;
INSERT INTO `static_page_locales` VALUES (6,3,'ru','Философия','Философия','\r\n<div class=\"main-text\">\r\n	<h3>Заголовок</h3>\r\n	<p>\r\n		<p>Задача организации, в особенности же дальнейшее развитие различных форм деятельности влечет за собой процесс внедрения и модернизации позиций, занимаемых участниками в отношении поставленных задач. С другой стороны постоянное информационно-пропагандистское обеспечение нашей деятельности способствует подготовки и реализации новых предложений. Равным образом сложившаяся структура организации требуют от нас анализа соответствующий условий активизации. Задача организации, в особенности же дальнейшее развитие различных форм деятельности в значительной степени обуславливает создание дальнейших направлений развития.</p>\r\n		<p>Равным образом консультация с широким активом позволяет выполнять важные задания по разработке систем массового участия. Товарищи! консультация с широким активом в значительной степени обуславливает создание новых предложений. Товарищи! новая модель организационной деятельности влечет за собой процесс внедрения и модернизации соответствующий условий активизации. Разнообразный и богатый опыт консультация с широким активом влечет за собой процесс внедрения и модернизации соответствующий условий активизации. Задача организации, в особенности же рамки и место обучения кадров обеспечивает широкому кругу (специалистов) участие в формировании позиций, занимаемых участниками в отношении поставленных задач. Разнообразный и богатый опыт новая модель организационной деятельности в значительной степени обуславливает создание дальнейших направлений развития.</p>\r\n		<p>Разнообразный и богатый опыт дальнейшее развитие различных форм деятельности в значительной степени обуславливает создание новых предложений. Значимость этих проблем настолько очевидна, что реализация намеченных плановых заданий способствует подготовки и реализации позиций, занимаемых участниками в отношении поставленных задач.</p>\r\n		<p>Равным образом реализация намеченных плановых заданий позволяет оценить значение системы обучения кадров, соответствует насущным потребностям. Товарищи! рамки и место обучения кадров влечет за собой процесс внедрения и модернизации системы обучения кадров, соответствует насущным потребностям. Разнообразный и богатый опыт укрепление и развитие структуры влечет за собой процесс внедрения и модернизации форм развития. Идейные соображения высшего порядка, а также сложившаяся структура организации позволяет выполнять важные задания по разработке системы обучения кадров, соответствует насущным потребностям.</p>\r\n		<p>С другой стороны новая модель организационной деятельности играет важную роль в формировании модели развития. С другой стороны начало повседневной работы по формированию позиции позволяет оценить значение новых предложений.</p><br />\r\n		</p></div>   '),(7,4,'ru','Контакты','Контакты','\r\n<div class=\"contact\">\r\n	<ul>\r\n		<li class=\"cont-01\">г. Харьков, ул. Петровского, 12<br />\r\n			Украина, 61002</li>\r\n		<li class=\"cont-02\">\r\n			<p>+38 (057) 714-15-15</p>\r\n			<p>+38 (057) 714-15-16</p>\r\n			<p>+38 (057) 700-76-61</p></li>\r\n		<li class=\"cont-03\">+38 (057) 719-44-00</li>\r\n		<li class=\"cont-04\"><a href=\"#\">Мы в фейсбуке</a></li>\r\n	</ul>\r\n	<div class=\"map\"><img src=\"/res/images/map.jpg\" alt=\"\" /></div></div> ');
/*!40000 ALTER TABLE `static_page_locales` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `static_pages`
--

DROP TABLE IF EXISTS `static_pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `static_pages` (
  `page_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `page_code` varchar(200) NOT NULL,
  `page_background` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`page_id`),
  UNIQUE KEY `page_code` (`page_code`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `static_pages`
--

LOCK TABLES `static_pages` WRITE;
/*!40000 ALTER TABLE `static_pages` DISABLE KEYS */;
INSERT INTO `static_pages` VALUES (3,'philosophy','/assets/static/image_58dbccca852f9.jpg'),(4,'contacts','/assets/static/image_58dcf8541ad43.jpg');
/*!40000 ALTER TABLE `static_pages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `system_email_locales`
--

DROP TABLE IF EXISTS `system_email_locales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `system_email_locales` (
  `locale_id` int(30) unsigned NOT NULL AUTO_INCREMENT,
  `email_id` int(5) unsigned NOT NULL,
  `language_id` char(2) CHARACTER SET utf8 NOT NULL DEFAULT 'en',
  `locale_from_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `locale_subject` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `locale_text_body` text COLLATE utf8_unicode_ci,
  `locale_html_body` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`locale_id`),
  KEY `email_id` (`email_id`),
  CONSTRAINT `system_email_locales_ibfk_1` FOREIGN KEY (`email_id`) REFERENCES `system_emails` (`email_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system_email_locales`
--

LOCK TABLES `system_email_locales` WRITE;
/*!40000 ALTER TABLE `system_email_locales` DISABLE KEYS */;
INSERT INTO `system_email_locales` VALUES (1,1,'en','Kulinich Management Console','Your new account','Hello {%firstName%} {%lastName%},\r\n\r\nYou are registered on the «Votertrove Managememnt Console» site now .\r\nThis letter contains the login and password to access the site http://manage.votertrove.com/\r\n\r\nLogin: {%email%}\r\nPassword: {%password%}\r\n\r\n\r\nPlease do not reply to this email. It was automatically generated to inform you.\r\n\r\nBest regards. Sandbox Team.','<span lang=\"en\" id=\"result_box\">Hello {%firstName%} {%lastName%},<br />\r\n	<br />\r\n	You are registered on the «Votertrove Managememnt Console» <span lang=\"en\" id=\"result_box\">site </span>.<br />\r\n	<br />\r\n	This letter contains the login and password to access the site <a href=\"http://manage.votertrove.com\">http://manage.votertrove.com/</a><br />\r\n	<br />\r\n	Login: {%email%}<br />\r\n	Password: {%password%}<br />\r\n	<br />\r\n	<br />\r\n	Please do not reply to this email. It was automatically generated solely for the purpose of informing you.</span>Please do not reply to this email. It was automatically generated to inform you.<br />\r\n<br />\r\n\r\n<div align=\"right\">Best regards. Votertrove.com.<br />\r\n	</div>'),(2,1,'ru','Kulinich Management Console','Добро пожаловать в комманду разработчиков Sandbox','Здравствуйте {%firstName%} {%lastName%},\r\n\r\nВы зарегистрированы в системе «Votertrove».\r\n\r\nЭто письмо содержит логин и пароль для доступа к сайту https://manage.votertrove.com\r\n\r\nЛогин: {%email%}\r\nПароль: {%password%}\r\n\r\nПожалуйста, не отвечайте на это письмо. Оно было сгенерировано автоматически, исключительно с целью проинформировать вас.\r\n\r\nС уважением, команда разработчиков Sandbox','\r\n<div>Здравствуйте <span style=\"font-weight: bold;\">{%firstName%} {%lastName%}</span>,</div><br />\r\nВы зарегистрированы на сайте.<br />\r\n<br />\r\nЭто письмо содержит логин и пароль для доступа к сайту <a href=\"manage.votertrove.com\">http://manage.votertrove.com/</a><br />\r\n<br />\r\nЛогин: {%email%}<br />\r\nПароль: {%password%}<br />\r\n<br />\r\n<br />\r\nПожалуйста, не отвечайте на это письмо. Оно было сгенерировано автоматически, исключительно с целью проинформировать вас.<br />\r\n<br />\r\n\r\n<div align=\"right\">С уважением, команда разработчиков votertrove.com<br />\r\n	</div>'),(3,2,'en','Kulinich Management Console','Your Password Changed','Hello {%firstName%} {%lastName%},\r\n\r\nSystem Administrator changed your password on Votertrove Management Console.\r\nThis letter contains the login and password to access the site http://manage.votertrove.com/\r\n\r\nLogin: {%email%}\r\nPassword: {%password%}\r\n\r\nPlease do not reply to this email. It was automatically generated solely for the purpose of informing you.','Hello {%firstName%} {%lastName%},<br />\r\n<br />\r\nSystem Administrator changed your password on Sandbox SiteVotertrove Management Console.<br />\r\nThis letter contains the login and password to access the site http://manage.votertrove.com/<br />\r\n<br />\r\nLogin: {%email%}<br />\r\nPassword: {%password%}<br />\r\n<br />\r\nPlease do not reply to this email. It was automatically generated solely for the purpose of informing you.'),(4,2,'ru','Kulinich Management Console','Смена пароля к вашей учетной записи','Здравствуйте {%firstName%} {%lastName%},\r\n\r\nСистемный администратор изменил пароль вашей учетной записи.\r\nЭто письмо содержит логин и пароль для доступа к сайту http://sandbox.in.ua/\r\n\r\nЛогин: {%email%}\r\nПароль: {%password%}\r\n\r\n\r\nПожалуйста, не отвечайте на это письмо. Оно было сгенерировано автоматически, исключительно с целью проинформировать вас.','Здравствуйте {%firstName%} {%lastName%},<br />\r\n<br />\r\nСистемный администратор изменил пароль вашей учетной записи.<br />\r\nЭто письмо содержит логин и пароль для доступа к сайту http://manage.votertrove.com/<br />\r\n<br />\r\nЛогин: {%email%}<br />\r\nПароль: {%password%}<br />\r\n<br />\r\n<br />\r\nПожалуйста, не отвечайте на это письмо. Оно было сгенерировано автоматически, исключительно с целью проинформировать вас.');
/*!40000 ALTER TABLE `system_email_locales` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `system_emails`
--

DROP TABLE IF EXISTS `system_emails`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `system_emails` (
  `email_id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `email_code` varchar(100) NOT NULL,
  `email_from` varchar(255) DEFAULT NULL,
  `email_variables` text,
  `email_content_type` enum('text','html') NOT NULL DEFAULT 'text',
  PRIMARY KEY (`email_id`),
  KEY `email_code` (`email_code`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system_emails`
--

LOCK TABLES `system_emails` WRITE;
/*!40000 ALTER TABLE `system_emails` DISABLE KEYS */;
INSERT INTO `system_emails` VALUES (1,'account-created','no-reply@kulinich.com','firstName,lastName,email,password,language','html'),(2,'account-password-changed-by-admin','no-reply@kulinich.com','firstName,lastName,email,password','html');
/*!40000 ALTER TABLE `system_emails` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `team`
--

DROP TABLE IF EXISTS `team`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `team` (
  `team_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `team_description` text,
  `team_title` varchar(255) DEFAULT NULL,
  `team_image` varchar(255) DEFAULT NULL,
  `team_rank` int(10) DEFAULT NULL,
  `team_specification` varchar(255) DEFAULT NULL,
  `team_use_big_photo` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`team_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `team`
--

LOCK TABLES `team` WRITE;
/*!40000 ALTER TABLE `team` DISABLE KEYS */;
INSERT INTO `team` VALUES (3,'dsfg','sdgf','/assets/team/image_58dbb95e7a8df.png',0,'dfg','no'),(4,'С другой стороны дальнейшее развитие различных форм деятельности обеспечивает широкому кругу (специалистов) участие в формировании существенных финансовых и административных условий. Значимость этих проблем настолько очевидна, что укрепление и развитие структуры играет важную роль в формировании позиций, занимаемых участниками в отношении поставленных задач. Равным образом новая модель организационной деятельности в значительной степени обуславливает создание системы обучения кадров, соответствует насущным потребностям. С другой стороны рамки и место обучения кадров в значительной степени обуславливает создание форм развития. Разнообразный и богатый опыт постоянный количественный рост и сфера нашей активности обеспечивает широкому кругу (специалистов) участие в формировании позиций, занимаемых участниками в отношении поставленных задач. Не следует, однако забывать, что консультация с широким активом в значительной степени обуславливает создание дальнейших направлений развития.','Doctor','/assets/team/image_58dbce3c12d50.png',1,'Boss','yes'),(5,'Description','Doctor 1','/assets/team/image_58dc47a4cf66b.png',2,'Specialisation 1','no'),(6,'Description','Doctor 2','/assets/team/image_58dc484ade824.png',3,'Specialisation 1','yes'),(7,'Значимость этих проблем настолько очевидна, что дальнейшее развитие различных форм деятельности представляет собой интересный эксперимент проверки модели развития. Товарищи! постоянный количественный рост и сфера нашей активности позволяет оценить значение систем массового участия. Равным образом реализация намеченных плановых заданий влечет за собой процесс внедрения и модернизации позиций, занимаемых участниками в отношении поставленных задач. Равным образом постоянный количественный рост и сфера нашей активности способствует подготовки и реализации систем массового участия.','Doctor 3','/assets/team/image_58dc4ade38c45.png',4,'Specialisation 2','no');
/*!40000 ALTER TABLE `team` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `translations`
--

DROP TABLE IF EXISTS `translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `translations` (
  `translation_id` int(100) unsigned NOT NULL AUTO_INCREMENT,
  `translation_module` varchar(100) NOT NULL,
  `translation_key` varchar(255) NOT NULL,
  `translation_locale` char(2) NOT NULL,
  `translation_value` text NOT NULL,
  PRIMARY KEY (`translation_id`),
  KEY `translation_locale` (`translation_locale`),
  CONSTRAINT `translations_ibfk_1` FOREIGN KEY (`translation_locale`) REFERENCES `languages` (`language_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=396 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `translations`
--

LOCK TABLES `translations` WRITE;
/*!40000 ALTER TABLE `translations` DISABLE KEYS */;
INSERT INTO `translations` VALUES (2,'system-users','id','ru','№'),(4,'system-users','name','ru','Имя пользователя'),(6,'system-users','last_login','ru','Последний Вход'),(8,'system-users','last_activity','ru','Был Активен'),(10,'system-users','last_ip','ru','Последний IP'),(12,'system-users','action_edit','ru','Редактировать пользователя'),(14,'system-user','action_delete','ru','Удалить пользователя'),(16,'system-users','delete_confirmation','ru','Вы Уверены что следует удалить этого пользователя?'),(18,'system-translations','section','ru','Секция'),(20,'system-translations','key','ru','Ключ'),(22,'system-translations','language_keys','ru','Переведено На'),(24,'system-translations','action_edit','ru','Редактировать'),(26,'system-translations','action_delete','ru','Удалить'),(28,'system-translations','delete_confirmation','ru','Удалить выбранный ряд?'),(30,'system-languages','id','ru','Код'),(32,'system-languages','name','ru','Название'),(34,'system-languages','locale','ru','Локаль'),(36,'system-languages','is_available','ru','Доступен'),(38,'system-languages','action_edit','ru','Редактировать'),(40,'system-languages','action_delete','ru','Удалить Язык'),(42,'system-languages','delete_confirmation','ru','Удалить выбранный язык?'),(44,'system-acl-resources','id','ru','№'),(46,'system-acl-resources','type','ru','Тип'),(48,'system-acl-resources','code','ru','Ресурс'),(50,'system-acl-resources','action','ru','Привилегия'),(52,'system-acl-resources','action_edit','ru','Редактировать'),(54,'system-acl-resources','action_delete','ru','Удалить'),(56,'system-acl-resources','delete_confirmation','ru','Действительно удалить ресурс?'),(58,'system-acl-permissions','id','ru','№'),(60,'system-acl-permissions','code','ru','Код'),(62,'system-acl-permissions','action_edit','ru','Редактировать'),(64,'system-acl-permissions','action_delete','ru','Удалить'),(66,'system-acl-permissions','delete_confirmation','ru','Точно удалить выбранную строку?'),(68,'system-acl-groups','id','ru','№'),(70,'system-acl-groups','name','ru','Название'),(72,'system-acl-groups','code','ru','Код'),(74,'system-acl-groups','action_edit','ru','Редактировать группу'),(76,'system-acl-groups','action_delete','ru','Удалить группу'),(78,'system-acl-groups','delete_confirmation','ru','Вы уверены, что следует удалить эту группу'),(80,'system-users','action_save','ru','Сохранить'),(82,'system-users','user_groups','ru','Группы Пользователя'),(84,'system-users','password','ru','Пароль'),(86,'system-users','email','ru','E-Mail'),(88,'system-users','last_name','ru','Фамилия'),(90,'system-users','first_name','ru','Имя'),(92,'system-languages','action_save','ru','Сохранить'),(94,'system-translations','value','ru','Текст'),(96,'system-translations','action_save','ru','Сохранить'),(98,'system-acl-groups','group_permissions','ru','Привелегии'),(100,'system-acl-groups','action_save','ru','Сохранить'),(102,'system-acl-permissions','permission_resources','ru','Ресурсы'),(104,'system-acl-permissions','action_save','ru','Сохранить'),(106,'navigation','system-users','ru','Пользователи'),(108,'navigation','system-users-create','ru','Создать Учетку'),(110,'navigation','system-users-edit','ru','Редактировать'),(112,'navigation','system-languages','ru','Доступные Локали'),(114,'navigation','system-languages-create','ru','Добавить Локаль'),(116,'navigation','system-languages-edit','ru','Редактировать Локаль'),(118,'navigation','system-translations','ru','Локализация'),(120,'navigation','system-translations-create','ru','Добавить перевод'),(122,'navigation','system-translations-edit','ru','Опции перевода'),(124,'navigation','system-acl-groups','ru','Системные группы'),(126,'navigation','system-acl-groups-create','ru','Добавить группу'),(128,'navigation','system-acl-groups-edit','ru','Опции группы'),(130,'navigation','system-acl-permissions','ru','Правила доступа'),(132,'navigation','system-acl-permissions-create','ru','Добавить правило'),(134,'navigation','system-acl-permissions-edit','ru','Опции правила'),(136,'navigation','system-acl-resources','ru','Ресурсы доступа'),(138,'pagination','next','ru','&gt;'),(140,'pagination','previous','ru','&lt;'),(142,'pagination','page','ru','Страница'),(144,'pagination','page_of','ru','всего'),(146,'pagination','records','ru','Записи c'),(148,'pagination','records_to','ru','по'),(150,'system-users','receive_notifications','ru','Уведомления'),(152,'system-users','notifications_language','ru','Язык уведомлений'),(154,'navigation','sign-in','ru','Авторизация'),(156,'auth','sign-in-action','ru','Войти'),(158,'auth','logout','ru','Выйти'),(160,'navigation','system','ru','Параметры'),(162,'system-emails','code','ru','Код'),(164,'system-emails','variables','ru','Переменные'),(166,'system-emails','from','ru','Адрес отправителя'),(168,'system-emails','content_type','ru','Тип Контента'),(170,'system-emails','from_name','ru','Имя отправителя'),(172,'system-emails','subject','ru','Тема'),(174,'system-emails','text_body','ru','Текстовая часть'),(176,'system-emails','html_body','ru','HTML Часть'),(178,'navigation','system-emails','ru','E-Mail Шаблоны'),(180,'navigation','system-emails-create','ru','Создать шаблон'),(182,'navigation','system-emails-edit','ru','Параметры Сообщения'),(184,'system-emails','id','ru','№'),(186,'system-emails','no-records-message','ru','нет созданных E-Mail Шаблонов'),(188,'system-emails','action_save','ru','Сохранить'),(190,'grid-filter','action_search','ru','Поиск'),(192,'grid-filter','search_placeholder','ru','Нужно что нибудь найти?'),(194,'grid-filter','action_show_filter','ru','Расширенный'),(196,'grid-filter','condition_starts_with','ru','Начинается с'),(198,'grid-filter','condition_ends_with','ru','Оканчивается на'),(200,'grid-filter','condition_contains','ru','Содержит'),(202,'grid-filter','condition_equals','ru','Равно'),(204,'grid-filter','action_clear_filter','ru','Сброс'),(206,'system-users','new_password','ru','Новый пароль'),(208,'system-users','password_confirm','ru','Подтверждение'),(210,'auth','edit-account','ru','Править учетку'),(212,'navigation','static-pages','ru','Статические страници'),(214,'static-pages','id','ru','№'),(216,'static-pages','code','ru','Код Url'),(218,'static-pages','title','ru','Название'),(220,'static-pages','action_edit','ru','Править страницу'),(222,'static-pages','action_delete','ru','Удалить Страницу'),(224,'static-pages','delete_confirmation','ru','Удалить выбранную страницу?'),(226,'navigation','static-pages-create','ru','Создать страницу'),(228,'navigation','static-pages-edit','ru','Опции страници'),(230,'static-pages','keywords','ru','Ключевые слова'),(232,'static-pages','content','ru','Содежимое'),(234,'static-pages','action_save','ru','Сохранить'),(236,'system-acl-groups','is_guest','ru','Гостевая группа'),(238,'system-acl-permissions','group','ru','Группа'),(240,'system-acl-permissions','type','ru','Тип'),(242,'navigation','projects','ru','Проекты'),(244,'pagination','last','ru','Последняя'),(246,'navigation','repositories','ru','Репозитории'),(300,'profile','provide-status-message','ru','Установить статус'),(302,'navigation','my-svn-repos','ru','Мой Svn'),(304,'navigation','system-management','ru','Система'),(318,'action','upload','ru','Загрузить'),(322,'action','remember','ru','Запомнить меня'),(324,'action','sign-in','ru','Войти'),(326,'action','save','ru','Сохранить'),(328,'action','cancel','ru','Отменить'),(330,'navigation','accounts','ru','Сервисы и учетки'),(331,'navigation','site-contents','ru','Контент'),(332,'navigation-contents','philosofy','ru','Философия'),(333,'navigation-contents','contacts','ru','Контакты'),(334,'navigation-contents','publications','ru','Публикации'),(335,'navigation-contents','clinic-cases','ru','Клинические случаи'),(336,'navigation-contents','team','ru','Команда'),(337,'navigation-contents','lab','ru','Лаборатория'),(339,'navigation','menu-names','ru','Локализация меню'),(340,'navigation','about-add-photo','ru','Добавить фото'),(341,'photos','id','ru','#'),(342,'photos','title','ru','Название'),(343,'photos','image','ru','Изображение'),(344,'photos','description','ru','Описание'),(345,'photos','action_edit','ru','Редактировать'),(346,'photos','action_delete','ru','Удалить'),(347,'photos','delete_confirmation','ru','Точно удалить?'),(348,'photos','no-records-message','ru','Ничего нет.'),(349,'navigation','lab-add-photo','ru','Добавить фото'),(350,'navigation','team-add-photo','ru','Добавить'),(351,'team','id','ru','#'),(352,'team','name','ru','Имя'),(353,'team','specification','ru','Специализация'),(354,'team','use_big_photo','ru','Большое фото'),(355,'team','image','ru','Картинка'),(356,'team','no-records-message','ru','Ничего нет'),(357,'team','description','ru','Описание'),(358,'team','action_edit','ru','Редактировать'),(359,'team','action_delete','ru','Удалить'),(360,'team','delete_confirmation','ru','Точно удалить?'),(361,'clinic-photos','id','ru','#'),(362,'clinic-photos','title','ru','Название'),(363,'clinic-photos','is_enabled','ru','Активен'),(364,'clinic-photos','no-records-message','ru','Ничего нет'),(365,'navigation','clinic-cases-create','ru','Добавить'),(366,'clinic-photos','code','ru','URL'),(367,'clinic-photos','action_gallery','ru','Фотограффии'),(368,'clinic-photos','action_edit','ru','Редактировать'),(369,'clinic-photos','delete_confirmation','ru','Точно удалить?'),(370,'clinic-photos','action_delete','ru','Удалить'),(371,'clinic-photos','description','ru','Описание'),(372,'clinic-photos','is_cover','ru','Обложка'),(373,'clinic-photos','image','ru','Изображение'),(374,'navigation','clinic-photos-create','ru','Добавить фото'),(375,'publications','id','ru','#'),(376,'publications','title','ru','Название'),(377,'publications','is_enabled','ru','Активно'),(378,'publications','no-records-message','ru','Ничего нет'),(379,'navigation','publications-create','ru','Добавить публикацию'),(380,'publications','code','ru','Код'),(381,'publications','content','ru','Текст публикации'),(382,'publications','image','ru','Изображение'),(383,'static-pages','background','ru','Фон'),(384,'default','view_next','ru','Следующий'),(385,'default','more_details','ru','Подробнее'),(386,'default','back_to_clinic_cases','ru','Вернуться'),(387,'default','site_title','ru','Клиника Доктора Кулинича'),(388,'navigation-contents','about','ru','О нас'),(389,'navigation','about-edit-photo','ru','Редактировать фото'),(390,'navigation','team-edit-photo','ru','Редактировать фото'),(391,'navigation','clinic-photos-edit','ru','Редактировать фото'),(392,'navigation','clinic-cases-edit','ru','Редактирование клинического случая'),(393,'navigation','publications-edit','ru','Редактирование публикации'),(394,'navigation','menu-names-create','ru','Добавить перевод'),(395,'navigation','menu-names-edit','ru','Перевод');
/*!40000 ALTER TABLE `translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_acl_groups`
--

DROP TABLE IF EXISTS `user_acl_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_acl_groups` (
  `id` int(50) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `group_id` int(5) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`,`group_id`),
  KEY `group_id` (`group_id`),
  CONSTRAINT `user_acl_groups_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `acl_groups` (`group_id`) ON DELETE CASCADE,
  CONSTRAINT `user_acl_groups_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_acl_groups`
--

LOCK TABLES `user_acl_groups` WRITE;
/*!40000 ALTER TABLE `user_acl_groups` DISABLE KEYS */;
INSERT INTO `user_acl_groups` VALUES (2,2,2),(3,3,3);
/*!40000 ALTER TABLE `user_acl_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_photos`
--

DROP TABLE IF EXISTS `user_photos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_photos` (
  `photo_id` int(100) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `photo_file` varchar(255) DEFAULT NULL,
  `photo_is_main` enum('yes','no') NOT NULL DEFAULT 'no',
  `photo_added_at` datetime NOT NULL,
  PRIMARY KEY (`photo_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_photos`
--

LOCK TABLES `user_photos` WRITE;
/*!40000 ALTER TABLE `user_photos` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_photos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_first_name` varchar(255) DEFAULT NULL,
  `user_last_name` varchar(255) DEFAULT NULL,
  `user_gender` enum('M','F') DEFAULT NULL,
  `user_email` varchar(255) NOT NULL,
  `user_nickname` varchar(255) DEFAULT NULL,
  `user_birthdate` date DEFAULT NULL,
  `user_password` char(60) NOT NULL,
  `user_last_login_time` datetime DEFAULT NULL,
  `user_last_activity_time` datetime DEFAULT NULL,
  `user_last_ip` bigint(50) DEFAULT NULL,
  `user_enable_notifications` enum('yes','no') NOT NULL DEFAULT 'yes',
  `user_system_language` char(2) NOT NULL DEFAULT 'en',
  `user_status_message` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_email` (`user_email`),
  UNIQUE KEY `user_nickname` (`user_nickname`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (2,'p','m',NULL,'pavel@sandbox.com.ua',NULL,NULL,'355f42038ea0f3da33319407c515e197b9a4e952','2017-04-12 21:36:27','2017-04-13 01:28:20',3232250113,'no','ru',NULL),(3,'Content','Manager',NULL,'manager@kulinich.com',NULL,NULL,'7f57205ac51bd9ee4217cc327395c2d1c96dc21d','2017-04-12 21:19:13','2017-04-12 21:36:02',3232250113,'yes','ru',NULL);
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

-- Dump completed on 2017-04-13  1:31:47
