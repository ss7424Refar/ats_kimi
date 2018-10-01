-- MySQL dump 10.13  Distrib 5.7.19, for Linux (x86_64)
--
-- Host: localhost    Database: tpms
-- ------------------------------------------------------
-- Server version	5.7.19-0ubuntu1

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
-- Table structure for table `ats_task_panel`
--

DROP TABLE IF EXISTS `ats_task_panel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ats_tool_element` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tool_name` varchar(30) DEFAULT NULL,
  `panel_class` varchar(45) DEFAULT NULL,
  `html_type` varchar(10) DEFAULT NULL,
  `html_class` varchar(100) DEFAULT NULL,
  `html_name` varchar(40) DEFAULT NULL,
  `html_url` varchar(100) DEFAULT NULL,
  `html_value` varchar(250) DEFAULT NULL COMMENT '	',
  `html_size` int(4) DEFAULT NULL,
  `html_default` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ats_task_panel`
--

LOCK TABLES `ats_task_panel` WRITE;
/*!40000 ALTER TABLE `ats_task_panel` DISABLE KEYS */;
INSERT INTO `ats_task_panel` VALUES (1,'JumpStart','btn btn-info btn-block','select2','form-control select2','TestImage','AddTool/getTestImage','',100,NULL),(2,'JumpStart','btn btn-info btn-block','select','form-control select2','Execute Job','','Fast Startup,Standby,Microsoft Edge_Fast Startup_BatteryLife_Fast Startup,Standby,Microsoft Edge,BatteryLife,DataGrab',NULL,NULL),(3,'JumpStart','btn btn-info btn-block','radio','iradio_minimal-blue','OS Activation',NULL,'YES_NO',NULL,'YES'),(4,'Image Recovery','btn btn-info btn-block','select2','form-control select2','TestImage','AddTool/getTestImage',NULL,100,''),(5,'Image Recovery','btn btn-info btn-block','radio','iradio_minimal-blue','OS Activation',NULL,'YES_NO',NULL,'NO');
/*!40000 ALTER TABLE `ats_task_panel` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-09-20 16:18:06
