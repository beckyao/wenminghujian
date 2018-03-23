-- MySQL dump 10.13  Distrib 5.5.38, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: wenming
-- ------------------------------------------------------
-- Server version	5.5.38-0ubuntu0.14.04.1

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
-- Table structure for table `banner`
--

DROP TABLE IF EXISTS `banner`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `banner` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL COMMENT 'banner名称',
  `picurl` varchar(300) DEFAULT NULL COMMENT 'banner图片存放地址',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='banner表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `place`
--

DROP TABLE IF EXISTS `place`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `place` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` varchar(50) DEFAULT NULL COMMENT '景区id，国家号+地区号+5位字符',
  `icon` varchar(500) DEFAULT NULL COMMENT '景区图标存放地址',
  `title` varchar(100) DEFAULT NULL COMMENT '景区名称',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='景区表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `placepoint`
--

DROP TABLE IF EXISTS `placepoint`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `placepoint` (
  `cid` int(11) NOT NULL COMMENT '景点id',
  `pid` varchar(50) NOT NULL COMMENT '景区id',
  `placename` varchar(50) NOT NULL COMMENT '景点名称',
  `placeindex` int(4) DEFAULT NULL COMMENT '景点排序标识',
  `placeaddress` varchar(400) DEFAULT NULL COMMENT '景点地址，可用于腾讯地图反向查找的地址',
  `placelatitude` decimal(16,6) DEFAULT NULL COMMENT '景点精度，用于腾讯地图',
  `placelongitude` decimal(16,6) DEFAULT NULL COMMENT '景点维度，用于腾讯地图',
  `subject` varchar(100) DEFAULT NULL COMMENT '景点简介，用于腾讯地图上mark点上的label',
  `price` decimal(12,2) DEFAULT NULL COMMENT '景点价格',
  `radius` int(6) DEFAULT NULL COMMENT '景点识别半径，单位米，用于判断用户是否进入景点周边',
  PRIMARY KEY (`cid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='景点表，一个景区对应多个景点';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `placepoint_resource`
--

DROP TABLE IF EXISTS `placepoint_resource`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `placepoint_resource` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` varchar(50) NOT NULL DEFAULT '0' COMMENT '景区id',
  `cid` int(11) NOT NULL DEFAULT '0' COMMENT '景点id',
  `res_type` int(2) NOT NULL DEFAULT '1' COMMENT '资源类型：4-video,3-sound,2-text,1-photo',
  `res_index` int(4) NOT NULL DEFAULT '1' COMMENT '资源排序表示，结果集按照res_type,res_index，正序',
  `res_name` varchar(100) DEFAULT NULL COMMENT '资源标题',
  `res_subject` varchar(400) DEFAULT NULL COMMENT '资源简介',
  `res_photo` varchar(200) DEFAULT '0' COMMENT '资源的小图片存放地址',
  `res_coverphoto` varchar(200) DEFAULT '0' COMMENT '用于视频、音频的封面图，尤其是视频资源，便于节省流量',
  `res_url` varchar(200) DEFAULT '0' COMMENT '资源存放地址',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=69 DEFAULT CHARSET=utf8 COMMENT='景点对应资源表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `route`
--

DROP TABLE IF EXISTS `route`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `route` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '路线id，景区包含多线路线',
  `pid` varchar(50) NOT NULL COMMENT '景区id',
  `route_index` int(4) NOT NULL COMMENT '路线排序，查询结果集按此列正序',
  `content` varchar(500) NOT NULL COMMENT '路线简介',
  `message` varchar(2000) DEFAULT NULL COMMENT '路线内容',
  `coverphoto` varchar(500) DEFAULT NULL COMMENT '路线图片存放地址',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='景区路线，景区包含多条游览路线，路线包含多个景点';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `route_placepoint`
--

DROP TABLE IF EXISTS `route_placepoint`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `route_placepoint` (
  `id` int(11) NOT NULL COMMENT '路线id',
  `pid` varchar(50) NOT NULL COMMENT '景区id',
  `cid` int(11) NOT NULL COMMENT '景点id',
  `cid_index` int(4) NOT NULL COMMENT '排序表示，结果集按照此列正序',
  PRIMARY KEY (`id`,`pid`,`cid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='景区路线所包含的景点列表';
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-03-23 18:31:28
