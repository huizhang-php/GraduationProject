/*
 Navicat MySQL Data Transfer

 Source Server         : mac
 Source Server Type    : MySQL
 Source Server Version : 50717
 Source Host           : localhost
 Source Database       : graduation_project

 Target Server Type    : MySQL
 Target Server Version : 50717
 File Encoding         : utf-8

 Date: 05/25/2019 20:53:49 PM
*/

SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `admin`
-- ----------------------------
DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `admin_name` varchar(50) NOT NULL,
  `admin_password` varchar(50) NOT NULL,
  `status` tinyint(2) NOT NULL,
  `ctime` datetime NOT NULL,
  `utime` datetime NOT NULL,
  `staff` varchar(50) NOT NULL,
  `end_staff` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `role_id` int(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `every_student_topic`
-- ----------------------------
DROP TABLE IF EXISTS `every_student_topic`;
CREATE TABLE `every_student_topic` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `test_paper_content_id` int(10) NOT NULL DEFAULT '0',
  `score` int(10) DEFAULT '0',
  `test_paper_type` tinyint(1) NOT NULL DEFAULT '1',
  `student_exam_topic_id` int(10) NOT NULL DEFAULT '0',
  `answer` varchar(1000) DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `ctime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `utime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `exam_topic_id` varchar(100) NOT NULL DEFAULT '0',
  `is_deal` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=959 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `exam_paper`
-- ----------------------------
DROP TABLE IF EXISTS `exam_paper`;
CREATE TABLE `exam_paper` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `introduction` varchar(1000) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `ctime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `utime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `exam_paper_id` tinyint(10) DEFAULT '0',
  `staff` varchar(50) DEFAULT NULL,
  `end_staff` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `exam_topic`
-- ----------------------------
DROP TABLE IF EXISTS `exam_topic`;
CREATE TABLE `exam_topic` (
  `id` varchar(100) NOT NULL,
  `name` varchar(200) NOT NULL,
  `introduction` varchar(1000) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `ctime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `utime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `staff` varchar(50) NOT NULL,
  `end_staff` varchar(50) NOT NULL,
  `test_paper_status` tinyint(1) NOT NULL DEFAULT '0',
  `question_bank_config` varchar(1000) NOT NULL,
  `question_bank_id` int(10) NOT NULL DEFAULT '0',
  `test_time_length` int(4) NOT NULL,
  `test_start_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `test_paper_type` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `func`
-- ----------------------------
DROP TABLE IF EXISTS `func`;
CREATE TABLE `func` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `func_name` varchar(100) NOT NULL,
  `func_url` varchar(500) NOT NULL,
  `pid` int(10) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `ctime` datetime NOT NULL,
  `utime` datetime NOT NULL,
  `func_introduction` varchar(500) NOT NULL,
  `staff` varchar(100) NOT NULL,
  `end_staff` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `role`
-- ----------------------------
DROP TABLE IF EXISTS `role`;
CREATE TABLE `role` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `role_name` varchar(100) NOT NULL,
  `role_introduction` varchar(500) NOT NULL,
  `status` tinyint(2) NOT NULL DEFAULT '0',
  `ctime` datetime NOT NULL,
  `utime` datetime NOT NULL,
  `staff` varchar(50) NOT NULL,
  `end_staff` varchar(50) NOT NULL,
  `jurisdiction_id` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `student_exam_topic`
-- ----------------------------
DROP TABLE IF EXISTS `student_exam_topic`;
CREATE TABLE `student_exam_topic` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `student_id` varchar(100) NOT NULL,
  `exam_topic_id` varchar(100) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `ctime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `uuid` varchar(100) DEFAULT NULL,
  `login_status` tinyint(4) NOT NULL DEFAULT '0',
  `is_distribution_test` tinyint(1) NOT NULL DEFAULT '0',
  `total_score` int(4) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `students`
-- ----------------------------
DROP TABLE IF EXISTS `students`;
CREATE TABLE `students` (
  `id` varchar(100) NOT NULL,
  `name` varchar(50) NOT NULL,
  `sex` tinyint(1) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `ctime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `utime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `end_staff` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `test_paper_content`
-- ----------------------------
DROP TABLE IF EXISTS `test_paper_content`;
CREATE TABLE `test_paper_content` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) DEFAULT '',
  `type` tinyint(2) DEFAULT '-1',
  `option` varchar(500) DEFAULT '',
  `right_key` varchar(500) DEFAULT '',
  `exam_paper_id` int(10) DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `ctime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=895 DEFAULT CHARSET=utf8;

SET FOREIGN_KEY_CHECKS = 1;
