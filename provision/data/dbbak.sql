/*
SQLyog Ultimate v11.5 (64 bit)
MySQL - 5.7.17 : Database - demo_kulinich
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`demo_kulinich` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `demo_kulinich`;

/*Table structure for table `acl_group_permissions` */

DROP TABLE IF EXISTS `acl_group_permissions`;

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

/*Data for the table `acl_group_permissions` */

insert  into `acl_group_permissions`(`id`,`group_id`,`permission_id`) values (1,2,1);
insert  into `acl_group_permissions`(`id`,`group_id`,`permission_id`) values (2,3,4);
insert  into `acl_group_permissions`(`id`,`group_id`,`permission_id`) values (3,3,5);
insert  into `acl_group_permissions`(`id`,`group_id`,`permission_id`) values (4,3,6);
insert  into `acl_group_permissions`(`id`,`group_id`,`permission_id`) values (5,3,7);
insert  into `acl_group_permissions`(`id`,`group_id`,`permission_id`) values (6,3,8);
insert  into `acl_group_permissions`(`id`,`group_id`,`permission_id`) values (7,3,9);
insert  into `acl_group_permissions`(`id`,`group_id`,`permission_id`) values (8,3,10);
insert  into `acl_group_permissions`(`id`,`group_id`,`permission_id`) values (9,3,11);
insert  into `acl_group_permissions`(`id`,`group_id`,`permission_id`) values (10,3,12);
insert  into `acl_group_permissions`(`id`,`group_id`,`permission_id`) values (11,3,13);
insert  into `acl_group_permissions`(`id`,`group_id`,`permission_id`) values (12,3,14);
insert  into `acl_group_permissions`(`id`,`group_id`,`permission_id`) values (13,3,15);
insert  into `acl_group_permissions`(`id`,`group_id`,`permission_id`) values (14,4,16);

/*Table structure for table `acl_groups` */

DROP TABLE IF EXISTS `acl_groups`;

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

/*Data for the table `acl_groups` */

insert  into `acl_groups`(`group_id`,`parent_id`,`group_name`,`group_code`,`group_is_guest`) values (2,NULL,'developer-group','developer-group','no');
insert  into `acl_groups`(`group_id`,`parent_id`,`group_name`,`group_code`,`group_is_guest`) values (3,NULL,'Manager','Manager','no');
insert  into `acl_groups`(`group_id`,`parent_id`,`group_name`,`group_code`,`group_is_guest`) values (4,NULL,'visitor','visitor','yes');

/*Table structure for table `acl_permission_resources` */

DROP TABLE IF EXISTS `acl_permission_resources`;

CREATE TABLE `acl_permission_resources` (
  `id` int(255) unsigned NOT NULL AUTO_INCREMENT,
  `permission_id` int(10) unsigned DEFAULT NULL,
  `resource_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `permission_id` (`permission_id`,`resource_id`),
  KEY `resource_id` (`resource_id`),
  CONSTRAINT `acl_permission_resources_ibfk_1` FOREIGN KEY (`permission_id`) REFERENCES `acl_permissions` (`permission_id`) ON DELETE CASCADE,
  CONSTRAINT `acl_permission_resources_ibfk_2` FOREIGN KEY (`resource_id`) REFERENCES `acl_resources` (`resource_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=226 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `acl_permission_resources` */

insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (1,1,1);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (2,1,2);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (3,1,3);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (4,1,4);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (5,1,5);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (6,1,6);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (7,1,7);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (8,1,8);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (9,1,9);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (10,1,10);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (11,1,11);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (12,1,12);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (13,1,13);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (14,1,14);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (15,1,15);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (16,1,16);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (17,1,17);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (18,1,18);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (19,1,19);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (20,1,20);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (21,1,21);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (22,1,22);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (23,1,23);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (24,1,24);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (25,1,25);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (26,1,26);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (27,1,27);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (28,1,28);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (29,1,29);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (30,1,30);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (31,1,31);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (32,1,32);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (33,1,33);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (34,1,34);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (35,1,35);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (36,1,36);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (37,1,37);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (38,1,38);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (39,1,39);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (40,1,40);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (41,1,41);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (42,1,42);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (43,1,43);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (44,1,44);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (45,1,45);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (46,1,46);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (47,1,47);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (48,1,48);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (49,1,49);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (50,1,50);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (51,1,51);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (52,1,52);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (53,1,53);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (54,1,54);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (55,1,55);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (56,1,56);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (57,1,57);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (58,1,58);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (59,1,59);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (60,1,60);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (61,1,61);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (62,1,62);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (63,1,63);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (64,1,64);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (65,1,65);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (66,1,66);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (67,1,67);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (68,1,68);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (69,1,69);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (70,1,70);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (71,1,71);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (72,1,72);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (73,1,73);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (74,1,74);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (75,1,75);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (76,1,76);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (77,1,77);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (78,1,78);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (79,1,79);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (80,1,80);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (81,1,81);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (82,1,82);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (83,1,83);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (84,1,84);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (85,1,85);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (86,1,86);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (154,4,3);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (155,4,5);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (156,4,7);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (157,5,6);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (158,5,8);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (159,5,9);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (160,5,10);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (161,5,11);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (162,5,12);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (163,5,13);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (164,5,14);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (165,5,15);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (166,5,16);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (167,6,17);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (168,6,18);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (169,6,19);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (170,6,20);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (171,6,21);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (172,6,22);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (173,7,33);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (174,7,34);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (175,7,35);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (176,7,36);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (177,7,37);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (178,7,38);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (179,7,39);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (180,7,40);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (181,7,41);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (182,7,42);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (183,7,43);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (184,8,48);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (185,9,49);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (186,9,50);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (187,9,51);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (188,9,52);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (189,9,53);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (190,9,54);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (191,10,14);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (192,10,15);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (193,11,44);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (194,11,45);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (195,12,59);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (196,12,60);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (197,12,61);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (198,12,62);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (199,13,68);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (200,13,69);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (201,13,70);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (202,13,71);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (203,14,73);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (204,14,74);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (205,14,75);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (206,14,76);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (207,14,77);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (208,14,78);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (209,15,79);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (210,15,80);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (211,15,81);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (212,15,82);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (213,16,3);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (214,16,4);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (215,16,6);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (216,16,7);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (217,16,8);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (218,16,9);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (219,16,10);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (220,16,11);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (221,16,12);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (222,16,13);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (223,16,14);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (224,16,15);
insert  into `acl_permission_resources`(`id`,`permission_id`,`resource_id`) values (225,16,16);

/*Table structure for table `acl_permissions` */

DROP TABLE IF EXISTS `acl_permissions`;

CREATE TABLE `acl_permissions` (
  `permission_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_permission_id` int(10) unsigned DEFAULT NULL,
  `permission_type` enum('group','permission') NOT NULL DEFAULT 'permission',
  `permission_code` varchar(255) NOT NULL,
  PRIMARY KEY (`permission_id`),
  KEY `parent_permission_id` (`parent_permission_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

/*Data for the table `acl_permissions` */

insert  into `acl_permissions`(`permission_id`,`parent_permission_id`,`permission_type`,`permission_code`) values (1,NULL,'permission','developer-permissions');
insert  into `acl_permissions`(`permission_id`,`parent_permission_id`,`permission_type`,`permission_code`) values (3,NULL,'group','content-management');
insert  into `acl_permissions`(`permission_id`,`parent_permission_id`,`permission_type`,`permission_code`) values (4,3,'permission','auth');
insert  into `acl_permissions`(`permission_id`,`parent_permission_id`,`permission_type`,`permission_code`) values (5,3,'permission','fe-part-access');
insert  into `acl_permissions`(`permission_id`,`parent_permission_id`,`permission_type`,`permission_code`) values (6,3,'permission','about-us-access');
insert  into `acl_permissions`(`permission_id`,`parent_permission_id`,`permission_type`,`permission_code`) values (7,3,'permission','clinic-cases-access');
insert  into `acl_permissions`(`permission_id`,`parent_permission_id`,`permission_type`,`permission_code`) values (8,3,'permission','dashboard-access');
insert  into `acl_permissions`(`permission_id`,`parent_permission_id`,`permission_type`,`permission_code`) values (9,3,'permission','lab-access');
insert  into `acl_permissions`(`permission_id`,`parent_permission_id`,`permission_type`,`permission_code`) values (10,3,'permission','publications-access');
insert  into `acl_permissions`(`permission_id`,`parent_permission_id`,`permission_type`,`permission_code`) values (11,3,'permission','email-templates-access');
insert  into `acl_permissions`(`permission_id`,`parent_permission_id`,`permission_type`,`permission_code`) values (12,3,'permission','menu-names-access');
insert  into `acl_permissions`(`permission_id`,`parent_permission_id`,`permission_type`,`permission_code`) values (13,3,'permission','static-pages-access');
insert  into `acl_permissions`(`permission_id`,`parent_permission_id`,`permission_type`,`permission_code`) values (14,3,'permission','team-access');
insert  into `acl_permissions`(`permission_id`,`parent_permission_id`,`permission_type`,`permission_code`) values (15,3,'permission','system-translation-access');
insert  into `acl_permissions`(`permission_id`,`parent_permission_id`,`permission_type`,`permission_code`) values (16,NULL,'permission','visitor-permissions');

/*Table structure for table `acl_resources` */

DROP TABLE IF EXISTS `acl_resources`;

CREATE TABLE `acl_resources` (
  `resource_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `resource_type` enum('mvc','custom') NOT NULL DEFAULT 'custom',
  `resource_code` char(255) NOT NULL,
  `resource_action` varchar(255) NOT NULL,
  PRIMARY KEY (`resource_id`)
) ENGINE=InnoDB AUTO_INCREMENT=87 DEFAULT CHARSET=utf8;

/*Data for the table `acl_resources` */

insert  into `acl_resources`(`resource_id`,`resource_type`,`resource_code`,`resource_action`) values (1,'custom','system::email-templates','editVars');
insert  into `acl_resources`(`resource_id`,`resource_type`,`resource_code`,`resource_action`) values (2,'custom','developer','developer');
insert  into `acl_resources`(`resource_id`,`resource_type`,`resource_code`,`resource_action`) values (3,'mvc','auth::denied','index');
insert  into `acl_resources`(`resource_id`,`resource_type`,`resource_code`,`resource_action`) values (4,'mvc','auth::index','index');
insert  into `acl_resources`(`resource_id`,`resource_type`,`resource_code`,`resource_action`) values (5,'mvc','auth::logout','index');
insert  into `acl_resources`(`resource_id`,`resource_type`,`resource_code`,`resource_action`) values (6,'mvc','default::about','index');
insert  into `acl_resources`(`resource_id`,`resource_type`,`resource_code`,`resource_action`) values (7,'mvc','default::account','index');
insert  into `acl_resources`(`resource_id`,`resource_type`,`resource_code`,`resource_action`) values (8,'mvc','default::clinic','index');
insert  into `acl_resources`(`resource_id`,`resource_type`,`resource_code`,`resource_action`) values (9,'mvc','default::clinic','view');
insert  into `acl_resources`(`resource_id`,`resource_type`,`resource_code`,`resource_action`) values (10,'mvc','default::error','error');
insert  into `acl_resources`(`resource_id`,`resource_type`,`resource_code`,`resource_action`) values (11,'mvc','default::index','index');
insert  into `acl_resources`(`resource_id`,`resource_type`,`resource_code`,`resource_action`) values (12,'mvc','default::index','static-page');
insert  into `acl_resources`(`resource_id`,`resource_type`,`resource_code`,`resource_action`) values (13,'mvc','default::laboratory','index');
insert  into `acl_resources`(`resource_id`,`resource_type`,`resource_code`,`resource_action`) values (14,'mvc','default::publications','index');
insert  into `acl_resources`(`resource_id`,`resource_type`,`resource_code`,`resource_action`) values (15,'mvc','default::publications','view');
insert  into `acl_resources`(`resource_id`,`resource_type`,`resource_code`,`resource_action`) values (16,'mvc','default::team','index');
insert  into `acl_resources`(`resource_id`,`resource_type`,`resource_code`,`resource_action`) values (17,'mvc','system::about','index');
insert  into `acl_resources`(`resource_id`,`resource_type`,`resource_code`,`resource_action`) values (18,'mvc','system::about','create');
insert  into `acl_resources`(`resource_id`,`resource_type`,`resource_code`,`resource_action`) values (19,'mvc','system::about','handle-upload');
insert  into `acl_resources`(`resource_id`,`resource_type`,`resource_code`,`resource_action`) values (20,'mvc','system::about','reorder-items');
insert  into `acl_resources`(`resource_id`,`resource_type`,`resource_code`,`resource_action`) values (21,'mvc','system::about','edit');
insert  into `acl_resources`(`resource_id`,`resource_type`,`resource_code`,`resource_action`) values (22,'mvc','system::about','delete');
insert  into `acl_resources`(`resource_id`,`resource_type`,`resource_code`,`resource_action`) values (23,'mvc','system::acl-groups','index');
insert  into `acl_resources`(`resource_id`,`resource_type`,`resource_code`,`resource_action`) values (24,'mvc','system::acl-groups','create');
insert  into `acl_resources`(`resource_id`,`resource_type`,`resource_code`,`resource_action`) values (25,'mvc','system::acl-groups','edit');
insert  into `acl_resources`(`resource_id`,`resource_type`,`resource_code`,`resource_action`) values (26,'mvc','system::acl-groups','delete');
insert  into `acl_resources`(`resource_id`,`resource_type`,`resource_code`,`resource_action`) values (27,'mvc','system::acl-permissions','create');
insert  into `acl_resources`(`resource_id`,`resource_type`,`resource_code`,`resource_action`) values (28,'mvc','system::acl-permissions','index');
insert  into `acl_resources`(`resource_id`,`resource_type`,`resource_code`,`resource_action`) values (29,'mvc','system::acl-permissions','set-parent');
insert  into `acl_resources`(`resource_id`,`resource_type`,`resource_code`,`resource_action`) values (30,'mvc','system::acl-permissions','delete');
insert  into `acl_resources`(`resource_id`,`resource_type`,`resource_code`,`resource_action`) values (31,'mvc','system::acl-permissions','edit');
insert  into `acl_resources`(`resource_id`,`resource_type`,`resource_code`,`resource_action`) values (32,'mvc','system::acl-resources','index');
insert  into `acl_resources`(`resource_id`,`resource_type`,`resource_code`,`resource_action`) values (33,'mvc','system::clinic-cases','index');
insert  into `acl_resources`(`resource_id`,`resource_type`,`resource_code`,`resource_action`) values (34,'mvc','system::clinic-cases','create');
insert  into `acl_resources`(`resource_id`,`resource_type`,`resource_code`,`resource_action`) values (35,'mvc','system::clinic-cases','reorder-items');
insert  into `acl_resources`(`resource_id`,`resource_type`,`resource_code`,`resource_action`) values (36,'mvc','system::clinic-cases','edit');
insert  into `acl_resources`(`resource_id`,`resource_type`,`resource_code`,`resource_action`) values (37,'mvc','system::clinic-cases','delete');
insert  into `acl_resources`(`resource_id`,`resource_type`,`resource_code`,`resource_action`) values (38,'mvc','system::clinic-cases-gallery','index');
insert  into `acl_resources`(`resource_id`,`resource_type`,`resource_code`,`resource_action`) values (39,'mvc','system::clinic-cases-gallery','create');
insert  into `acl_resources`(`resource_id`,`resource_type`,`resource_code`,`resource_action`) values (40,'mvc','system::clinic-cases-gallery','handle-upload');
insert  into `acl_resources`(`resource_id`,`resource_type`,`resource_code`,`resource_action`) values (41,'mvc','system::clinic-cases-gallery','reorder-items');
insert  into `acl_resources`(`resource_id`,`resource_type`,`resource_code`,`resource_action`) values (42,'mvc','system::clinic-cases-gallery','delete');
insert  into `acl_resources`(`resource_id`,`resource_type`,`resource_code`,`resource_action`) values (43,'mvc','system::clinic-cases-gallery','edit');
insert  into `acl_resources`(`resource_id`,`resource_type`,`resource_code`,`resource_action`) values (44,'mvc','system::email-templates','index');
insert  into `acl_resources`(`resource_id`,`resource_type`,`resource_code`,`resource_action`) values (45,'mvc','system::email-templates','edit');
insert  into `acl_resources`(`resource_id`,`resource_type`,`resource_code`,`resource_action`) values (46,'mvc','system::email-templates','create');
insert  into `acl_resources`(`resource_id`,`resource_type`,`resource_code`,`resource_action`) values (47,'mvc','system::email-templates','delete');
insert  into `acl_resources`(`resource_id`,`resource_type`,`resource_code`,`resource_action`) values (48,'mvc','system::index','index');
insert  into `acl_resources`(`resource_id`,`resource_type`,`resource_code`,`resource_action`) values (49,'mvc','system::laboratory','index');
insert  into `acl_resources`(`resource_id`,`resource_type`,`resource_code`,`resource_action`) values (50,'mvc','system::laboratory','create');
insert  into `acl_resources`(`resource_id`,`resource_type`,`resource_code`,`resource_action`) values (51,'mvc','system::laboratory','handle-upload');
insert  into `acl_resources`(`resource_id`,`resource_type`,`resource_code`,`resource_action`) values (52,'mvc','system::laboratory','reorder-items');
insert  into `acl_resources`(`resource_id`,`resource_type`,`resource_code`,`resource_action`) values (53,'mvc','system::laboratory','edit');
insert  into `acl_resources`(`resource_id`,`resource_type`,`resource_code`,`resource_action`) values (54,'mvc','system::laboratory','delete');
insert  into `acl_resources`(`resource_id`,`resource_type`,`resource_code`,`resource_action`) values (55,'mvc','system::languages','create');
insert  into `acl_resources`(`resource_id`,`resource_type`,`resource_code`,`resource_action`) values (56,'mvc','system::languages','edit');
insert  into `acl_resources`(`resource_id`,`resource_type`,`resource_code`,`resource_action`) values (57,'mvc','system::languages','index');
insert  into `acl_resources`(`resource_id`,`resource_type`,`resource_code`,`resource_action`) values (58,'mvc','system::languages','delete');
insert  into `acl_resources`(`resource_id`,`resource_type`,`resource_code`,`resource_action`) values (59,'mvc','system::menu-names','index');
insert  into `acl_resources`(`resource_id`,`resource_type`,`resource_code`,`resource_action`) values (60,'mvc','system::menu-names','create');
insert  into `acl_resources`(`resource_id`,`resource_type`,`resource_code`,`resource_action`) values (61,'mvc','system::menu-names','edit');
insert  into `acl_resources`(`resource_id`,`resource_type`,`resource_code`,`resource_action`) values (62,'mvc','system::menu-names','delete');
insert  into `acl_resources`(`resource_id`,`resource_type`,`resource_code`,`resource_action`) values (63,'mvc','system::publications','index');
insert  into `acl_resources`(`resource_id`,`resource_type`,`resource_code`,`resource_action`) values (64,'mvc','system::publications','handle-upload');
insert  into `acl_resources`(`resource_id`,`resource_type`,`resource_code`,`resource_action`) values (65,'mvc','system::publications','create');
insert  into `acl_resources`(`resource_id`,`resource_type`,`resource_code`,`resource_action`) values (66,'mvc','system::publications','edit');
insert  into `acl_resources`(`resource_id`,`resource_type`,`resource_code`,`resource_action`) values (67,'mvc','system::publications','delete');
insert  into `acl_resources`(`resource_id`,`resource_type`,`resource_code`,`resource_action`) values (68,'mvc','system::static-pages','index');
insert  into `acl_resources`(`resource_id`,`resource_type`,`resource_code`,`resource_action`) values (69,'mvc','system::static-pages','handle-upload');
insert  into `acl_resources`(`resource_id`,`resource_type`,`resource_code`,`resource_action`) values (70,'mvc','system::static-pages','create');
insert  into `acl_resources`(`resource_id`,`resource_type`,`resource_code`,`resource_action`) values (71,'mvc','system::static-pages','edit');
insert  into `acl_resources`(`resource_id`,`resource_type`,`resource_code`,`resource_action`) values (72,'mvc','system::static-pages','delete');
insert  into `acl_resources`(`resource_id`,`resource_type`,`resource_code`,`resource_action`) values (73,'mvc','system::team','index');
insert  into `acl_resources`(`resource_id`,`resource_type`,`resource_code`,`resource_action`) values (74,'mvc','system::team','create');
insert  into `acl_resources`(`resource_id`,`resource_type`,`resource_code`,`resource_action`) values (75,'mvc','system::team','handle-upload');
insert  into `acl_resources`(`resource_id`,`resource_type`,`resource_code`,`resource_action`) values (76,'mvc','system::team','reorder-items');
insert  into `acl_resources`(`resource_id`,`resource_type`,`resource_code`,`resource_action`) values (77,'mvc','system::team','edit');
insert  into `acl_resources`(`resource_id`,`resource_type`,`resource_code`,`resource_action`) values (78,'mvc','system::team','delete');
insert  into `acl_resources`(`resource_id`,`resource_type`,`resource_code`,`resource_action`) values (79,'mvc','system::translations','create');
insert  into `acl_resources`(`resource_id`,`resource_type`,`resource_code`,`resource_action`) values (80,'mvc','system::translations','edit');
insert  into `acl_resources`(`resource_id`,`resource_type`,`resource_code`,`resource_action`) values (81,'mvc','system::translations','delete');
insert  into `acl_resources`(`resource_id`,`resource_type`,`resource_code`,`resource_action`) values (82,'mvc','system::translations','index');
insert  into `acl_resources`(`resource_id`,`resource_type`,`resource_code`,`resource_action`) values (83,'mvc','system::users','create');
insert  into `acl_resources`(`resource_id`,`resource_type`,`resource_code`,`resource_action`) values (84,'mvc','system::users','edit');
insert  into `acl_resources`(`resource_id`,`resource_type`,`resource_code`,`resource_action`) values (85,'mvc','system::users','index');
insert  into `acl_resources`(`resource_id`,`resource_type`,`resource_code`,`resource_action`) values (86,'mvc','system::users','delete');

/*Table structure for table `clinic_case_photos` */

DROP TABLE IF EXISTS `clinic_case_photos`;

CREATE TABLE `clinic_case_photos` (
  `photo_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `case_id` int(10) unsigned NOT NULL,
  `photo_description` text,
  `photo_image` varchar(255) DEFAULT NULL,
  `photo_rank` int(10) DEFAULT NULL,
  `photo_is_cover` enum('yes','no') DEFAULT NULL,
  PRIMARY KEY (`photo_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Data for the table `clinic_case_photos` */

/*Table structure for table `clinic_cases` */

DROP TABLE IF EXISTS `clinic_cases`;

CREATE TABLE `clinic_cases` (
  `case_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `case_code` varchar(255) NOT NULL,
  `case_title` varchar(255) DEFAULT NULL,
  `is_enabled` enum('yes','no') NOT NULL DEFAULT 'no',
  `case_rank` int(10) DEFAULT NULL,
  PRIMARY KEY (`case_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `clinic_cases` */

insert  into `clinic_cases`(`case_id`,`case_code`,`case_title`,`is_enabled`,`case_rank`) values (2,'werwe','sdfsd s df','yes',1);

/*Table structure for table `languages` */

DROP TABLE IF EXISTS `languages`;

CREATE TABLE `languages` (
  `language_id` char(2) NOT NULL,
  `language_name` varchar(255) NOT NULL,
  `language_is_avaliable` enum('yes','no') NOT NULL DEFAULT 'no',
  `language_locale` char(5) NOT NULL,
  PRIMARY KEY (`language_id`),
  KEY `language_locale` (`language_locale`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `languages` */

insert  into `languages`(`language_id`,`language_name`,`language_is_avaliable`,`language_locale`) values ('ru','Русский','yes','ru_RU');

/*Table structure for table `photos` */

DROP TABLE IF EXISTS `photos`;

CREATE TABLE `photos` (
  `photo_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `photo_description` text,
  `photo_title` varchar(255) DEFAULT NULL,
  `photo_image` varchar(255) DEFAULT NULL,
  `photo_rank` int(10) DEFAULT NULL,
  `photo_gallery` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`photo_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `photos` */

/*Table structure for table `publications` */

DROP TABLE IF EXISTS `publications`;

CREATE TABLE `publications` (
  `publication_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `publication_title` varchar(255) DEFAULT NULL,
  `publication_code` varchar(255) NOT NULL,
  `publication_content` text,
  `publication_image` varchar(255) DEFAULT NULL,
  `publication_is_enabled` enum('yes','no') NOT NULL DEFAULT 'no',
  PRIMARY KEY (`publication_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `publications` */

/*Table structure for table `sessions` */

DROP TABLE IF EXISTS `sessions`;

CREATE TABLE `sessions` (
  `session_id` char(32) NOT NULL,
  `session_modified` int(11) DEFAULT NULL,
  `session_lifetime` int(11) DEFAULT NULL,
  `session_data` text,
  PRIMARY KEY (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `sessions` */

/*Table structure for table `static_page_locales` */

DROP TABLE IF EXISTS `static_page_locales`;

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

/*Data for the table `static_page_locales` */

insert  into `static_page_locales`(`locale_id`,`page_id`,`language_id`,`html_title`,`html_keywords`,`locale_content`) values (6,3,'ru','Философия','Философия','Философия ');
insert  into `static_page_locales`(`locale_id`,`page_id`,`language_id`,`html_title`,`html_keywords`,`locale_content`) values (7,4,'ru','Контакты','Контакты','Контакты');

/*Table structure for table `static_pages` */

DROP TABLE IF EXISTS `static_pages`;

CREATE TABLE `static_pages` (
  `page_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `page_code` varchar(200) NOT NULL,
  `page_background` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`page_id`),
  UNIQUE KEY `page_code` (`page_code`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Data for the table `static_pages` */

insert  into `static_pages`(`page_id`,`page_code`,`page_background`) values (3,'philosophy',NULL);
insert  into `static_pages`(`page_id`,`page_code`,`page_background`) values (4,'contacts',NULL);

/*Table structure for table `system_email_locales` */

DROP TABLE IF EXISTS `system_email_locales`;

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

/*Data for the table `system_email_locales` */

insert  into `system_email_locales`(`locale_id`,`email_id`,`language_id`,`locale_from_name`,`locale_subject`,`locale_text_body`,`locale_html_body`) values (1,1,'en','Kulinich Management Console','Your new account','Hello {%firstName%} {%lastName%},\r\n\r\nYou are registered on the «Votertrove Managememnt Console» site now .\r\nThis letter contains the login and password to access the site http://manage.votertrove.com/\r\n\r\nLogin: {%email%}\r\nPassword: {%password%}\r\n\r\n\r\nPlease do not reply to this email. It was automatically generated to inform you.\r\n\r\nBest regards. Sandbox Team.','<span lang=\"en\" id=\"result_box\">Hello {%firstName%} {%lastName%},<br />\r\n	<br />\r\n	You are registered on the «Votertrove Managememnt Console» <span lang=\"en\" id=\"result_box\">site </span>.<br />\r\n	<br />\r\n	This letter contains the login and password to access the site <a href=\"http://manage.votertrove.com\">http://manage.votertrove.com/</a><br />\r\n	<br />\r\n	Login: {%email%}<br />\r\n	Password: {%password%}<br />\r\n	<br />\r\n	<br />\r\n	Please do not reply to this email. It was automatically generated solely for the purpose of informing you.</span>Please do not reply to this email. It was automatically generated to inform you.<br />\r\n<br />\r\n\r\n<div align=\"right\">Best regards. Votertrove.com.<br />\r\n	</div>');
insert  into `system_email_locales`(`locale_id`,`email_id`,`language_id`,`locale_from_name`,`locale_subject`,`locale_text_body`,`locale_html_body`) values (2,1,'ru','Kulinich Management Console','Добро пожаловать в комманду разработчиков Sandbox','Здравствуйте {%firstName%} {%lastName%},\r\n\r\nВы зарегистрированы в системе «Votertrove».\r\n\r\nЭто письмо содержит логин и пароль для доступа к сайту https://manage.votertrove.com\r\n\r\nЛогин: {%email%}\r\nПароль: {%password%}\r\n\r\nПожалуйста, не отвечайте на это письмо. Оно было сгенерировано автоматически, исключительно с целью проинформировать вас.\r\n\r\nС уважением, команда разработчиков Sandbox','\r\n<div>Здравствуйте <span style=\"font-weight: bold;\">{%firstName%} {%lastName%}</span>,</div><br />\r\nВы зарегистрированы на сайте.<br />\r\n<br />\r\nЭто письмо содержит логин и пароль для доступа к сайту <a href=\"manage.votertrove.com\">http://manage.votertrove.com/</a><br />\r\n<br />\r\nЛогин: {%email%}<br />\r\nПароль: {%password%}<br />\r\n<br />\r\n<br />\r\nПожалуйста, не отвечайте на это письмо. Оно было сгенерировано автоматически, исключительно с целью проинформировать вас.<br />\r\n<br />\r\n\r\n<div align=\"right\">С уважением, команда разработчиков votertrove.com<br />\r\n	</div>');
insert  into `system_email_locales`(`locale_id`,`email_id`,`language_id`,`locale_from_name`,`locale_subject`,`locale_text_body`,`locale_html_body`) values (3,2,'en','Kulinich Management Console','Your Password Changed','Hello {%firstName%} {%lastName%},\r\n\r\nSystem Administrator changed your password on Votertrove Management Console.\r\nThis letter contains the login and password to access the site http://manage.votertrove.com/\r\n\r\nLogin: {%email%}\r\nPassword: {%password%}\r\n\r\nPlease do not reply to this email. It was automatically generated solely for the purpose of informing you.','Hello {%firstName%} {%lastName%},<br />\r\n<br />\r\nSystem Administrator changed your password on Sandbox SiteVotertrove Management Console.<br />\r\nThis letter contains the login and password to access the site http://manage.votertrove.com/<br />\r\n<br />\r\nLogin: {%email%}<br />\r\nPassword: {%password%}<br />\r\n<br />\r\nPlease do not reply to this email. It was automatically generated solely for the purpose of informing you.');
insert  into `system_email_locales`(`locale_id`,`email_id`,`language_id`,`locale_from_name`,`locale_subject`,`locale_text_body`,`locale_html_body`) values (4,2,'ru','Kulinich Management Console','Смена пароля к вашей учетной записи','Здравствуйте {%firstName%} {%lastName%},\r\n\r\nСистемный администратор изменил пароль вашей учетной записи.\r\nЭто письмо содержит логин и пароль для доступа к сайту http://sandbox.in.ua/\r\n\r\nЛогин: {%email%}\r\nПароль: {%password%}\r\n\r\n\r\nПожалуйста, не отвечайте на это письмо. Оно было сгенерировано автоматически, исключительно с целью проинформировать вас.','Здравствуйте {%firstName%} {%lastName%},<br />\r\n<br />\r\nСистемный администратор изменил пароль вашей учетной записи.<br />\r\nЭто письмо содержит логин и пароль для доступа к сайту http://manage.votertrove.com/<br />\r\n<br />\r\nЛогин: {%email%}<br />\r\nПароль: {%password%}<br />\r\n<br />\r\n<br />\r\nПожалуйста, не отвечайте на это письмо. Оно было сгенерировано автоматически, исключительно с целью проинформировать вас.');

/*Table structure for table `system_emails` */

DROP TABLE IF EXISTS `system_emails`;

CREATE TABLE `system_emails` (
  `email_id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `email_code` varchar(100) NOT NULL,
  `email_from` varchar(255) DEFAULT NULL,
  `email_variables` text,
  `email_content_type` enum('text','html') NOT NULL DEFAULT 'text',
  PRIMARY KEY (`email_id`),
  KEY `email_code` (`email_code`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `system_emails` */

insert  into `system_emails`(`email_id`,`email_code`,`email_from`,`email_variables`,`email_content_type`) values (1,'account-created','no-reply@kulinich.com','firstName,lastName,email,password,language','html');
insert  into `system_emails`(`email_id`,`email_code`,`email_from`,`email_variables`,`email_content_type`) values (2,'account-password-changed-by-admin','no-reply@kulinich.com','firstName,lastName,email,password','html');

/*Table structure for table `team` */

DROP TABLE IF EXISTS `team`;

CREATE TABLE `team` (
  `team_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `team_description` text,
  `team_title` varchar(255) DEFAULT NULL,
  `team_image` varchar(255) DEFAULT NULL,
  `team_rank` int(10) DEFAULT NULL,
  `team_specification` varchar(255) DEFAULT NULL,
  `team_use_big_photo` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`team_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `team` */

/*Table structure for table `translations` */

DROP TABLE IF EXISTS `translations`;

CREATE TABLE `translations` (
  `translation_id` int(100) unsigned NOT NULL AUTO_INCREMENT,
  `translation_module` varchar(100) NOT NULL,
  `translation_key` varchar(255) NOT NULL,
  `translation_locale` char(2) NOT NULL,
  `translation_value` text NOT NULL,
  PRIMARY KEY (`translation_id`),
  KEY `translation_locale` (`translation_locale`),
  CONSTRAINT `translations_ibfk_1` FOREIGN KEY (`translation_locale`) REFERENCES `languages` (`language_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=388 DEFAULT CHARSET=utf8;

/*Data for the table `translations` */

insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (2,'system-users','id','ru','№');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (4,'system-users','name','ru','Имя пользователя');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (6,'system-users','last_login','ru','Последний Вход');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (8,'system-users','last_activity','ru','Был Активен');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (10,'system-users','last_ip','ru','Последний IP');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (12,'system-users','action_edit','ru','Редактировать пользователя');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (14,'system-user','action_delete','ru','Удалить пользователя');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (16,'system-users','delete_confirmation','ru','Вы Уверены что следует удалить этого пользователя?');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (18,'system-translations','section','ru','Секция');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (20,'system-translations','key','ru','Ключ');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (22,'system-translations','language_keys','ru','Переведено На');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (24,'system-translations','action_edit','ru','Редактировать');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (26,'system-translations','action_delete','ru','Удалить');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (28,'system-translations','delete_confirmation','ru','Удалить выбранный ряд?');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (30,'system-languages','id','ru','Код');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (32,'system-languages','name','ru','Название');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (34,'system-languages','locale','ru','Локаль');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (36,'system-languages','is_available','ru','Доступен');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (38,'system-languages','action_edit','ru','Редактировать');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (40,'system-languages','action_delete','ru','Удалить Язык');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (42,'system-languages','delete_confirmation','ru','Удалить выбранный язык?');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (44,'system-acl-resources','id','ru','№');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (46,'system-acl-resources','type','ru','Тип');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (48,'system-acl-resources','code','ru','Ресурс');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (50,'system-acl-resources','action','ru','Привилегия');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (52,'system-acl-resources','action_edit','ru','Редактировать');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (54,'system-acl-resources','action_delete','ru','Удалить');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (56,'system-acl-resources','delete_confirmation','ru','Действительно удалить ресурс?');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (58,'system-acl-permissions','id','ru','№');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (60,'system-acl-permissions','code','ru','Код');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (62,'system-acl-permissions','action_edit','ru','Редактировать');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (64,'system-acl-permissions','action_delete','ru','Удалить');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (66,'system-acl-permissions','delete_confirmation','ru','Точно удалить выбранную строку?');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (68,'system-acl-groups','id','ru','№');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (70,'system-acl-groups','name','ru','Название');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (72,'system-acl-groups','code','ru','Код');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (74,'system-acl-groups','action_edit','ru','Редактировать группу');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (76,'system-acl-groups','action_delete','ru','Удалить группу');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (78,'system-acl-groups','delete_confirmation','ru','Вы уверены, что следует удалить эту группу');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (80,'system-users','action_save','ru','Сохранить');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (82,'system-users','user_groups','ru','Группы Пользователя');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (84,'system-users','password','ru','Пароль');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (86,'system-users','email','ru','E-Mail');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (88,'system-users','last_name','ru','Фамилия');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (90,'system-users','first_name','ru','Имя');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (92,'system-languages','action_save','ru','Сохранить');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (94,'system-translations','value','ru','Текст');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (96,'system-translations','action_save','ru','Сохранить');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (98,'system-acl-groups','group_permissions','ru','Привелегии');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (100,'system-acl-groups','action_save','ru','Сохранить');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (102,'system-acl-permissions','permission_resources','ru','Ресурсы');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (104,'system-acl-permissions','action_save','ru','Сохранить');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (106,'navigation','system-users','ru','Пользователи');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (108,'navigation','system-users-create','ru','Создать Учетку');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (110,'navigation','system-users-edit','ru','Редактировать');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (112,'navigation','system-languages','ru','Доступные Локали');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (114,'navigation','system-languages-create','ru','Добавить Локаль');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (116,'navigation','system-languages-edit','ru','Редактировать Локаль');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (118,'navigation','system-translations','ru','Локализация');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (120,'navigation','system-translations-create','ru','Добавить перевод');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (122,'navigation','system-translations-edit','ru','Опции перевода');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (124,'navigation','system-acl-groups','ru','Системные группы');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (126,'navigation','system-acl-groups-create','ru','Добавить группу');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (128,'navigation','system-acl-groups-edit','ru','Опции группы');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (130,'navigation','system-acl-permissions','ru','Правила доступа');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (132,'navigation','system-acl-permissions-create','ru','Добавить правило');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (134,'navigation','system-acl-permissions-edit','ru','Опции правила');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (136,'navigation','system-acl-resources','ru','Ресурсы доступа');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (138,'pagination','next','ru','&gt;');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (140,'pagination','previous','ru','&lt;');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (142,'pagination','page','ru','Страница');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (144,'pagination','page_of','ru','всего');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (146,'pagination','records','ru','Записи c');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (148,'pagination','records_to','ru','по');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (150,'system-users','receive_notifications','ru','Уведомления');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (152,'system-users','notifications_language','ru','Язык уведомлений');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (154,'navigation','sign-in','ru','Авторизация');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (156,'auth','sign-in-action','ru','Войти');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (158,'auth','logout','ru','Выйти');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (160,'navigation','system','ru','Параметры');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (162,'system-emails','code','ru','Код');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (164,'system-emails','variables','ru','Переменные');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (166,'system-emails','from','ru','Адрес отправителя');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (168,'system-emails','content_type','ru','Тип Контента');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (170,'system-emails','from_name','ru','Имя отправителя');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (172,'system-emails','subject','ru','Тема');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (174,'system-emails','text_body','ru','Текстовая часть');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (176,'system-emails','html_body','ru','HTML Часть');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (178,'navigation','system-emails','ru','E-Mail Шаблоны');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (180,'navigation','system-emails-create','ru','Создать шаблон');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (182,'navigation','system-emails-edit','ru','Параметры Сообщения');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (184,'system-emails','id','ru','№');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (186,'system-emails','no-records-message','ru','нет созданных E-Mail Шаблонов');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (188,'system-emails','action_save','ru','Сохранить');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (190,'grid-filter','action_search','ru','Поиск');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (192,'grid-filter','search_placeholder','ru','Нужно что нибудь найти?');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (194,'grid-filter','action_show_filter','ru','Расширенный');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (196,'grid-filter','condition_starts_with','ru','Начинается с');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (198,'grid-filter','condition_ends_with','ru','Оканчивается на');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (200,'grid-filter','condition_contains','ru','Содержит');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (202,'grid-filter','condition_equals','ru','Равно');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (204,'grid-filter','action_clear_filter','ru','Сброс');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (206,'system-users','new_password','ru','Новый пароль');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (208,'system-users','password_confirm','ru','Подтверждение');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (210,'auth','edit-account','ru','Править учетку');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (212,'navigation','static-pages','ru','Статические страници');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (214,'static-pages','id','ru','№');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (216,'static-pages','code','ru','Код Url');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (218,'static-pages','title','ru','Название');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (220,'static-pages','action_edit','ru','Править страницу');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (222,'static-pages','action_delete','ru','Удалить Страницу');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (224,'static-pages','delete_confirmation','ru','Удалить выбранную страницу?');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (226,'navigation','static-pages-create','ru','Создать страницу');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (228,'navigation','static-pages-edit','ru','Опции страници');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (230,'static-pages','keywords','ru','Ключевые слова');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (232,'static-pages','content','ru','Содежимое');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (234,'static-pages','action_save','ru','Сохранить');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (236,'system-acl-groups','is_guest','ru','Гостевая группа');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (238,'system-acl-permissions','group','ru','Группа');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (240,'system-acl-permissions','type','ru','Тип');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (242,'navigation','projects','ru','Проекты');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (244,'pagination','last','ru','Последняя');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (246,'navigation','repositories','ru','Репозитории');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (300,'profile','provide-status-message','ru','Установить статус');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (302,'navigation','my-svn-repos','ru','Мой Svn');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (304,'navigation','system-management','ru','Система');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (318,'action','upload','ru','Загрузить');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (322,'action','remember','ru','Запомнить меня');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (324,'action','sign-in','ru','Войти');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (326,'action','save','ru','Сохранить');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (328,'action','cancel','ru','Отменить');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (330,'navigation','accounts','ru','Сервисы и учетки');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (331,'navigation','site-contents','ru','Контент');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (332,'navigation-contents','philosofy','ru','Философия');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (333,'navigation-contents','contacts','ru','Контакты');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (334,'navigation-contents','publications','ru','Публикации');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (335,'navigation-contents','clinic-cases','ru','Клинические случаи');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (336,'navigation-contents','team','ru','Команда');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (337,'navigation-contents','lab','ru','Лаборатория');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (338,'navigation-contents','about','ru','О нас');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (339,'navigation','menu-names','ru','Локализация меню');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (340,'navigation','about-add-photo','ru','Добавить фото');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (341,'photos','id','ru','#');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (342,'photos','title','ru','Название');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (343,'photos','image','ru','Изображение');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (344,'photos','description','ru','Описание');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (345,'photos','action_edit','ru','Редактировать');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (346,'photos','action_delete','ru','Удалить');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (347,'photos','delete_confirmation','ru','Точно удалить?');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (348,'photos','no-records-message','ru','Ничего нет.');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (349,'navigation','lab-add-photo','ru','Добавить фото');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (350,'navigation','team-add-photo','ru','Добавить');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (351,'team','id','ru','#');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (352,'team','name','ru','Имя');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (353,'team','specification','ru','Специализация');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (354,'team','use_big_photo','ru','Большое фото');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (355,'team','image','ru','Картинка');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (356,'team','no-records-message','ru','Ничего нет');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (357,'team','description','ru','Описание');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (358,'team','action_edit','ru','Редактировать');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (359,'team','action_delete','ru','Удалить');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (360,'team','delete_confirmation','ru','Точно удалить?');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (361,'clinic-photos','id','ru','#');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (362,'clinic-photos','title','ru','Название');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (363,'clinic-photos','is_enabled','ru','Активен');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (364,'clinic-photos','no-records-message','ru','Ничего нет');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (365,'navigation','clinic-cases-create','ru','Добавить');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (366,'clinic-photos','code','ru','Код');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (367,'clinic-photos','action_gallery','ru','Фотограффии');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (368,'clinic-photos','action_edit','ru','Редактировать');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (369,'clinic-photos','delete_confirmation','ru','Точно удалить?');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (370,'clinic-photos','action_delete','ru','Удалить');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (371,'clinic-photos','description','ru','Описание');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (372,'clinic-photos','is_cover','ru','Обложка');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (373,'clinic-photos','image','ru','Изображение');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (374,'navigation','clinic-photos-create','ru','Добавить фото');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (375,'publications','id','ru','#');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (376,'publications','title','ru','Название');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (377,'publications','is_enabled','ru','Активно');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (378,'publications','no-records-message','ru','Ничего нет');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (379,'navigation','publications-create','ru','Добавить публикацию');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (380,'publications','code','ru','Код');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (381,'publications','content','ru','Текст публикации');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (382,'publications','image','ru','Изображение');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (383,'static-pages','background','ru','Фон');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (384,'default','view_next','ru','Следующий');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (385,'default','more_details','ru','Подробнее');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (386,'default','back_to_clinic_cases','ru','Вернуться');
insert  into `translations`(`translation_id`,`translation_module`,`translation_key`,`translation_locale`,`translation_value`) values (387,'default','site_title','ru','Клиника Доктора Кулинича');

/*Table structure for table `user_acl_groups` */

DROP TABLE IF EXISTS `user_acl_groups`;

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

/*Data for the table `user_acl_groups` */

insert  into `user_acl_groups`(`id`,`user_id`,`group_id`) values (2,2,2);
insert  into `user_acl_groups`(`id`,`user_id`,`group_id`) values (3,3,3);

/*Table structure for table `user_photos` */

DROP TABLE IF EXISTS `user_photos`;

CREATE TABLE `user_photos` (
  `photo_id` int(100) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `photo_file` varchar(255) DEFAULT NULL,
  `photo_is_main` enum('yes','no') NOT NULL DEFAULT 'no',
  `photo_added_at` datetime NOT NULL,
  PRIMARY KEY (`photo_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `user_photos` */

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

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

/*Data for the table `users` */

insert  into `users`(`user_id`,`user_first_name`,`user_last_name`,`user_gender`,`user_email`,`user_nickname`,`user_birthdate`,`user_password`,`user_last_login_time`,`user_last_activity_time`,`user_last_ip`,`user_enable_notifications`,`user_system_language`,`user_status_message`) values (2,'p','m',NULL,'pavel@sandbox.com.ua',NULL,NULL,'355f42038ea0f3da33319407c515e197b9a4e952','2017-01-29 14:14:28','2017-01-29 14:38:13',3232250113,'no','ru',NULL);
insert  into `users`(`user_id`,`user_first_name`,`user_last_name`,`user_gender`,`user_email`,`user_nickname`,`user_birthdate`,`user_password`,`user_last_login_time`,`user_last_activity_time`,`user_last_ip`,`user_enable_notifications`,`user_system_language`,`user_status_message`) values (3,'Content','Manager',NULL,'manager@kulinich.com',NULL,NULL,'7f57205ac51bd9ee4217cc327395c2d1c96dc21d','2017-01-29 14:35:55','2017-01-29 14:38:11',3232250113,'yes','ru',NULL);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
