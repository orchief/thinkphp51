/*
 Navicat Premium Data Transfer

 Source Server         : thinphp51
 Source Server Type    : MySQL
 Source Server Version : 50730
 Source Schema         : thinkphp51

 Target Server Type    : MySQL
 Target Server Version : 50730
 File Encoding         : 65001

 Date: 25/03/2021 19:15:29
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for admin_group
-- ----------------------------
DROP TABLE IF EXISTS `admin_group`;
CREATE TABLE `admin_group` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `title` char(100) NOT NULL DEFAULT '' COMMENT '角色名称',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '角色状态',
  `rules` json NOT NULL COMMENT '规则列表id json array',
  `remark` varchar(255) DEFAULT NULL COMMENT '角色备注',
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of admin_group
-- ----------------------------
BEGIN;
INSERT INTO `admin_group` VALUES (1, '总管理员', 1, '[1, 2, 3, 4]', '系统管理员', 'admin');
COMMIT;

-- ----------------------------
-- Table structure for admin_rule
-- ----------------------------
DROP TABLE IF EXISTS `admin_rule`;
CREATE TABLE `admin_rule` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `pid` int(11) DEFAULT NULL COMMENT '所属父级',
  `level` int(11) DEFAULT NULL COMMENT '级别用于前端区分',
  `status` int(11) DEFAULT NULL COMMENT '规则状态',
  `api_list` json DEFAULT NULL COMMENT '可访问的接口列表',
  `extra` json DEFAULT NULL COMMENT '前端存储的额外数据',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of admin_rule
-- ----------------------------
BEGIN;
INSERT INTO `admin_rule` VALUES (1, 'admin', '系统管理', 0, 1, 1, '[\"get admin/user\", \"post admin/user\", \"get admin/rule\", \"get admin/group\", \"post admin/group\", \"delete admin/group\", \"put admin/group\"]', '{\"icon\": \"setting\", \"path\": \"/system\"}');
INSERT INTO `admin_rule` VALUES (2, 'admin_user', '管理员管理', 14, 2, 1, '[\"get admin/user\", \"post admin/user\", \"get admin/rule\", \"get admin/group\", \"post admin/group\", \"delete admin/group\", \"put admin/group\"]', '{\"icon\": \"userSwitch\", \"path\": \"/admin\"}');
INSERT INTO `admin_rule` VALUES (3, 'admin_role', '角色管理', 14, 2, 1, '[\"get admin/user\", \"post admin/user\", \"get admin/rule\", \"get admin/group\", \"post admin/group\", \"delete admin/group\", \"put admin/group\"]', '{\"icon\": \"schedule\", \"path\": \"/role\"}');
INSERT INTO `admin_rule` VALUES (4, 'admin_setting', '参数设置', 14, 2, 1, '[\"get admin/user\", \"post admin/user\", \"get admin/rule\", \"get admin/group\", \"post admin/group\", \"delete admin/group\", \"put admin/group\"]', '{\"icon\": \"laptop\", \"path\": \"/setting\"}');
COMMIT;

-- ----------------------------
-- Table structure for admin_token
-- ----------------------------
DROP TABLE IF EXISTS `admin_token`;
CREATE TABLE `admin_token` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `jwt` varchar(2000) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`,`create_time`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for admin_user
-- ----------------------------
DROP TABLE IF EXISTS `admin_user`;
CREATE TABLE `admin_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `username` varchar(100) DEFAULT NULL COMMENT '管理后台账号',
  `password` varchar(100) DEFAULT NULL COMMENT '管理后台密码',
  `phone` varchar(13) DEFAULT NULL COMMENT '手机号',
  `remark` varchar(100) DEFAULT NULL COMMENT '用户备注',
  `status` tinyint(3) DEFAULT NULL COMMENT '状态,1启用0禁用',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '注册时间',
  `super_admin` tinyint(4) NOT NULL DEFAULT '0' COMMENT '超级管理员',
  `groups` json DEFAULT NULL COMMENT '角色列表id josn array',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8 COMMENT='管理员表';

-- ----------------------------
-- Records of admin_user
-- ----------------------------
BEGIN;
INSERT INTO `admin_user` VALUES (1, 'admin', 'd93a5def7511da3d0f2d171d9c344e91', '18230373213', '默认超级管理员', 1, '2019-07-29 17:19:29', 1, '[1]');
COMMIT;

-- ----------------------------
-- Table structure for base_setting
-- ----------------------------
DROP TABLE IF EXISTS `base_setting`;
CREATE TABLE `base_setting` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '配置ID',
  `title` varchar(255) DEFAULT NULL COMMENT '后台前端使用的名称',
  `type` tinyint(3) NOT NULL DEFAULT '1' COMMENT '数据类型  1: 配置 2: 字典',
  `name` varchar(50) DEFAULT '' COMMENT '程序使用的名称 全大写 下划线隔开',
  `value` json DEFAULT NULL COMMENT '配置值 会存储为一个json格式',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '参数注释',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `参数名` (`name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=90 DEFAULT CHARSET=utf8 COMMENT='字典和配置表';

-- ----------------------------
-- Records of base_setting
-- ----------------------------
BEGIN;
INSERT INTO `base_setting` VALUES (4, '案例', 2, 'status', '[{\"name\": \"on\", \"type\": \"string\", \"title\": \"启用\", \"value\": 1}, {\"name\": \"off\", \"type\": \"string\", \"title\": \"禁用\", \"value\": 0}]', '大部分状态');
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
