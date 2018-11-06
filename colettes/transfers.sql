/*
Navicat MySQL Data Transfer

Source Server         : local
Source Server Version : 50616
Source Host           : 127.0.0.1:3306
Source Database       : colletes

Target Server Type    : MYSQL
Target Server Version : 50616
File Encoding         : 65001

Date: 2018-10-04 20:32:47
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for transfers
-- ----------------------------
DROP TABLE IF EXISTS `transfers`;
CREATE TABLE `transfers` (
  `transfer_id` int(11) NOT NULL AUTO_INCREMENT,
  `branch_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `dttm` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `qty` decimal(10,0) NOT NULL,
  `state` int(1) NOT NULL DEFAULT '0',
  `rcv_dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `to_branch` int(11) NOT NULL,
  PRIMARY KEY (`transfer_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
