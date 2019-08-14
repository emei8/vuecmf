/*
Navicat MySQL Data Transfer

Source Server         : phpStudy
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : vuecmf

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2019-08-14 23:40:11
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for vc_admin
-- ----------------------------
DROP TABLE IF EXISTS `vc_admin`;
CREATE TABLE `vc_admin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态：1=开启，2=关闭',
  `username` char(16) NOT NULL DEFAULT '',
  `password` varchar(255) NOT NULL DEFAULT '' COMMENT '密码',
  `email` varchar(32) NOT NULL DEFAULT '' COMMENT '用户邮箱',
  `mobile` char(15) NOT NULL DEFAULT '' COMMENT '用户手机',
  `reg_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '注册时间',
  `reg_ip` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '注册IP',
  `last_login_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `last_login_ip` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '最后登录IP',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `token` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COMMENT='系统--模型管理';

-- ----------------------------
-- Records of vc_admin
-- ----------------------------
INSERT INTO `vc_admin` VALUES ('1', '1', 'admin', '$2y$10$wryVbKFUFLDGX6pwopfCc.izGRY2QczvB2.LueK7OafU7SKFAf4Na', '386196596@qq.com', '13988888888', '0', '0', '1565795935', '2130706433', '1555512381', '3f7291405eb28b8c35066528882df10f');

-- ----------------------------
-- Table structure for vc_auth_assignment
-- ----------------------------
DROP TABLE IF EXISTS `vc_auth_assignment`;
CREATE TABLE `vc_auth_assignment` (
  `item_name` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '角色或权限组',
  `user_id` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '用户ID',
  `created_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`item_name`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='系统--权限控制--用户分配的角色';

-- ----------------------------
-- Records of vc_auth_assignment
-- ----------------------------
INSERT INTO `vc_auth_assignment` VALUES ('超级管理员', '1', '1563545584');

-- ----------------------------
-- Table structure for vc_auth_item
-- ----------------------------
DROP TABLE IF EXISTS `vc_auth_item`;
CREATE TABLE `vc_auth_item` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `status` tinyint(4) DEFAULT '1' COMMENT '状态：1=开启，2=关闭',
  `name` varchar(64) NOT NULL DEFAULT '' COMMENT '角色或权限名称',
  `type` tinyint(4) unsigned NOT NULL DEFAULT '10' COMMENT '类型：10=角色，20=权限',
  `description` varchar(64) DEFAULT '' COMMENT '描述',
  `rule_name` varchar(64) DEFAULT '' COMMENT '规则名称',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COMMENT='系统--角色和权限模型';

-- ----------------------------
-- Records of vc_auth_item
-- ----------------------------
INSERT INTO `vc_auth_item` VALUES ('1', '1', '超级管理员', '10', '', '', '1970', '1970');
INSERT INTO `vc_auth_item` VALUES ('2', '1', '全部权限', '20', '', '', '1561910246', '1561910246');
INSERT INTO `vc_auth_item` VALUES ('3', '1', '网站编辑', '10', '', '', '1562078360', '1562078360');
INSERT INTO `vc_auth_item` VALUES ('4', '1', '网站运营员', '10', '', '', '1562078433', '1562078433');
INSERT INTO `vc_auth_item` VALUES ('5', '1', '编辑网站内容', '20', '', '', '1562253859', '1562253859');

-- ----------------------------
-- Table structure for vc_auth_item_child
-- ----------------------------
DROP TABLE IF EXISTS `vc_auth_item_child`;
CREATE TABLE `vc_auth_item_child` (
  `parent` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `child` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`),
  KEY `parent` (`parent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='系统--权限控制--角色和权限项关系表';

-- ----------------------------
-- Records of vc_auth_item_child
-- ----------------------------
INSERT INTO `vc_auth_item_child` VALUES ('超级管理员', '全部权限');
INSERT INTO `vc_auth_item_child` VALUES ('模型管理', '字段值选项列表');
INSERT INTO `vc_auth_item_child` VALUES ('模型管理', '模型列表');
INSERT INTO `vc_auth_item_child` VALUES ('模型管理', '模型字段列表');
INSERT INTO `vc_auth_item_child` VALUES ('模型管理', '模型操作项列表');
INSERT INTO `vc_auth_item_child` VALUES ('全部权限', '模型管理');
INSERT INTO `vc_auth_item_child` VALUES ('全部权限', '管理员管理');
INSERT INTO `vc_auth_item_child` VALUES ('全部权限', '菜单管理');
INSERT INTO `vc_auth_item_child` VALUES ('全部权限', '角色与权限管理');

-- ----------------------------
-- Table structure for vc_auth_item_menu
-- ----------------------------
DROP TABLE IF EXISTS `vc_auth_item_menu`;
CREATE TABLE `vc_auth_item_menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `item_name` varchar(64) NOT NULL DEFAULT '' COMMENT '权限组',
  `menu_id` int(11) NOT NULL DEFAULT '0' COMMENT '菜单ID',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态: 1=开启，2=关闭',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COMMENT='系统--权限控制--权限组与菜单关系表';

-- ----------------------------
-- Records of vc_auth_item_menu
-- ----------------------------
INSERT INTO `vc_auth_item_menu` VALUES ('24', '全部权限', '1', '1');
INSERT INTO `vc_auth_item_menu` VALUES ('25', '全部权限', '15', '1');
INSERT INTO `vc_auth_item_menu` VALUES ('26', '全部权限', '20', '1');
INSERT INTO `vc_auth_item_menu` VALUES ('27', '全部权限', '21', '1');
INSERT INTO `vc_auth_item_menu` VALUES ('28', '全部权限', '22', '1');
INSERT INTO `vc_auth_item_menu` VALUES ('29', '全部权限', '23', '1');
INSERT INTO `vc_auth_item_menu` VALUES ('30', '全部权限', '24', '1');
INSERT INTO `vc_auth_item_menu` VALUES ('31', '全部权限', '26', '1');
INSERT INTO `vc_auth_item_menu` VALUES ('32', '全部权限', '27', '1');
INSERT INTO `vc_auth_item_menu` VALUES ('33', '全部权限', '2', '1');
INSERT INTO `vc_auth_item_menu` VALUES ('34', '全部权限', '25', '1');

-- ----------------------------
-- Table structure for vc_auth_item_menu_operate
-- ----------------------------
DROP TABLE IF EXISTS `vc_auth_item_menu_operate`;
CREATE TABLE `vc_auth_item_menu_operate` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `item_menu_id` int(10) NOT NULL DEFAULT '0' COMMENT '权限组与菜单关系表ID',
  `operate_id` int(11) NOT NULL DEFAULT '0' COMMENT '操作项ID',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态: 1=开启，2=关闭',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=378 DEFAULT CHARSET=utf8mb4 COMMENT='系统--权限控制--权限组下菜单与操作项关系表';

-- ----------------------------
-- Records of vc_auth_item_menu_operate
-- ----------------------------
INSERT INTO `vc_auth_item_menu_operate` VALUES ('330', '25', '1', '1');
INSERT INTO `vc_auth_item_menu_operate` VALUES ('331', '25', '2', '1');
INSERT INTO `vc_auth_item_menu_operate` VALUES ('332', '25', '3', '1');
INSERT INTO `vc_auth_item_menu_operate` VALUES ('333', '25', '4', '1');
INSERT INTO `vc_auth_item_menu_operate` VALUES ('334', '25', '5', '1');
INSERT INTO `vc_auth_item_menu_operate` VALUES ('335', '25', '8', '1');
INSERT INTO `vc_auth_item_menu_operate` VALUES ('336', '27', '6', '1');
INSERT INTO `vc_auth_item_menu_operate` VALUES ('337', '27', '7', '1');
INSERT INTO `vc_auth_item_menu_operate` VALUES ('338', '27', '15', '1');
INSERT INTO `vc_auth_item_menu_operate` VALUES ('339', '27', '76', '1');
INSERT INTO `vc_auth_item_menu_operate` VALUES ('340', '27', '110', '1');
INSERT INTO `vc_auth_item_menu_operate` VALUES ('341', '28', '9', '1');
INSERT INTO `vc_auth_item_menu_operate` VALUES ('342', '28', '11', '1');
INSERT INTO `vc_auth_item_menu_operate` VALUES ('343', '28', '12', '1');
INSERT INTO `vc_auth_item_menu_operate` VALUES ('344', '28', '13', '1');
INSERT INTO `vc_auth_item_menu_operate` VALUES ('345', '29', '10', '1');
INSERT INTO `vc_auth_item_menu_operate` VALUES ('346', '29', '14', '1');
INSERT INTO `vc_auth_item_menu_operate` VALUES ('347', '29', '16', '1');
INSERT INTO `vc_auth_item_menu_operate` VALUES ('348', '30', '17', '1');
INSERT INTO `vc_auth_item_menu_operate` VALUES ('349', '30', '18', '1');
INSERT INTO `vc_auth_item_menu_operate` VALUES ('350', '30', '19', '1');
INSERT INTO `vc_auth_item_menu_operate` VALUES ('351', '30', '95', '1');
INSERT INTO `vc_auth_item_menu_operate` VALUES ('352', '31', '68', '1');
INSERT INTO `vc_auth_item_menu_operate` VALUES ('353', '31', '69', '1');
INSERT INTO `vc_auth_item_menu_operate` VALUES ('354', '31', '70', '1');
INSERT INTO `vc_auth_item_menu_operate` VALUES ('355', '31', '71', '1');
INSERT INTO `vc_auth_item_menu_operate` VALUES ('356', '31', '72', '1');
INSERT INTO `vc_auth_item_menu_operate` VALUES ('357', '31', '73', '1');
INSERT INTO `vc_auth_item_menu_operate` VALUES ('358', '31', '74', '1');
INSERT INTO `vc_auth_item_menu_operate` VALUES ('359', '31', '75', '1');
INSERT INTO `vc_auth_item_menu_operate` VALUES ('360', '31', '106', '1');
INSERT INTO `vc_auth_item_menu_operate` VALUES ('361', '32', '96', '1');
INSERT INTO `vc_auth_item_menu_operate` VALUES ('362', '32', '97', '1');
INSERT INTO `vc_auth_item_menu_operate` VALUES ('363', '32', '98', '1');
INSERT INTO `vc_auth_item_menu_operate` VALUES ('364', '32', '99', '1');
INSERT INTO `vc_auth_item_menu_operate` VALUES ('365', '32', '100', '1');
INSERT INTO `vc_auth_item_menu_operate` VALUES ('366', '32', '101', '1');
INSERT INTO `vc_auth_item_menu_operate` VALUES ('367', '32', '102', '1');
INSERT INTO `vc_auth_item_menu_operate` VALUES ('368', '32', '103', '1');
INSERT INTO `vc_auth_item_menu_operate` VALUES ('369', '32', '104', '1');
INSERT INTO `vc_auth_item_menu_operate` VALUES ('370', '32', '105', '1');
INSERT INTO `vc_auth_item_menu_operate` VALUES ('371', '34', '77', '1');
INSERT INTO `vc_auth_item_menu_operate` VALUES ('372', '34', '78', '1');
INSERT INTO `vc_auth_item_menu_operate` VALUES ('373', '34', '79', '1');
INSERT INTO `vc_auth_item_menu_operate` VALUES ('374', '34', '80', '1');
INSERT INTO `vc_auth_item_menu_operate` VALUES ('375', '34', '81', '1');
INSERT INTO `vc_auth_item_menu_operate` VALUES ('376', '34', '82', '1');
INSERT INTO `vc_auth_item_menu_operate` VALUES ('377', '34', '107', '1');

-- ----------------------------
-- Table structure for vc_auth_menu
-- ----------------------------
DROP TABLE IF EXISTS `vc_auth_menu`;
CREATE TABLE `vc_auth_menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '路由标题',
  `icon` varchar(20) DEFAULT '' COMMENT '菜单图标',
  `pid` int(10) NOT NULL DEFAULT '0' COMMENT '父级',
  `model_id` int(10) NOT NULL DEFAULT '0' COMMENT '模型',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态: 1=开启，2=关闭',
  PRIMARY KEY (`id`),
  KEY `model_id` (`model_id`),
  KEY `pid` (`pid`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COMMENT='系统--权限控制--菜单';

-- ----------------------------
-- Records of vc_auth_menu
-- ----------------------------
INSERT INTO `vc_auth_menu` VALUES ('1', '系统设置', '', '0', '0', '1');
INSERT INTO `vc_auth_menu` VALUES ('2', '内容管理', '', '0', '0', '1');
INSERT INTO `vc_auth_menu` VALUES ('15', '菜单管理', 'ios-home-outline', '1', '1', '1');
INSERT INTO `vc_auth_menu` VALUES ('20', '模型管理', 'ios-cube', '1', '0', '1');
INSERT INTO `vc_auth_menu` VALUES ('21', '模型列表', '', '20', '4', '1');
INSERT INTO `vc_auth_menu` VALUES ('22', '模型字段列表', '', '20', '5', '1');
INSERT INTO `vc_auth_menu` VALUES ('23', '模型操作项列表', '', '20', '6', '1');
INSERT INTO `vc_auth_menu` VALUES ('24', '字段值选项列表', '', '20', '7', '1');
INSERT INTO `vc_auth_menu` VALUES ('25', '单页管理', '', '2', '2', '1');
INSERT INTO `vc_auth_menu` VALUES ('26', '管理员管理', 'md-people', '1', '8', '1');
INSERT INTO `vc_auth_menu` VALUES ('27', '角色与权限管理', '', '1', '9', '1');
INSERT INTO `vc_auth_menu` VALUES ('28', 'TEST', '', '0', '2', '1');

-- ----------------------------
-- Table structure for vc_auth_model
-- ----------------------------
DROP TABLE IF EXISTS `vc_auth_model`;
CREATE TABLE `vc_auth_model` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `model_name` varchar(64) NOT NULL DEFAULT '' COMMENT '模型名称',
  `label` varchar(64) NOT NULL DEFAULT '' COMMENT '模型标题',
  `main_operate_id` int(11) unsigned DEFAULT '0' COMMENT '主操作项ID',
  `component` varchar(200) NOT NULL DEFAULT '' COMMENT '组件模板',
  `filter_field` varchar(80) NOT NULL DEFAULT '' COMMENT '过滤字段名',
  `type` tinyint(4) NOT NULL DEFAULT '10' COMMENT '模型类型：10=内置，20=扩展',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态：1=开启，2=关闭',
  `remark` varchar(200) NOT NULL DEFAULT '' COMMENT '表备注',
  PRIMARY KEY (`id`,`model_name`),
  UNIQUE KEY `mode_name` (`model_name`) USING BTREE,
  KEY `index` (`model_name`,`label`,`filter_field`) USING BTREE,
  KEY `main_operate_id` (`main_operate_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COMMENT='系统--模型管理';

-- ----------------------------
-- Records of vc_auth_model
-- ----------------------------
INSERT INTO `vc_auth_model` VALUES ('1', 'auth_menu_model', '菜单模型', '2', 'template/list/menu_tree', '', '10', '1', '系统--权限控制--菜单');
INSERT INTO `vc_auth_model` VALUES ('2', 'single_list_model', '单列表模型', '77', 'template/list/single_list', '', '20', '1', '单页模型');
INSERT INTO `vc_auth_model` VALUES ('4', 'auth_model_model', '模型', '6', 'template/list/model_list', '', '10', '1', '系统--模型管理');
INSERT INTO `vc_auth_model` VALUES ('5', 'model_field_model', '字段模型', '9', 'template/list/category_list', 'model_id', '10', '1', '系统--模型下字段定义');
INSERT INTO `vc_auth_model` VALUES ('6', 'model_operate_model', '操作项模型', '10', 'template/list/category_list', 'model_id', '10', '1', '系统--模型下操作项');
INSERT INTO `vc_auth_model` VALUES ('7', 'field_option_model', '字段值选项模型', '17', 'template/list/category_list', 'model_id', '10', '1', '系统--模型下字段值的选项列表');
INSERT INTO `vc_auth_model` VALUES ('8', 'admin_model', '管理员模型', '68', 'template/list/admin_list', '', '10', '1', '系统--模型管理');
INSERT INTO `vc_auth_model` VALUES ('9', 'auth_item_model', '角色和权限模型', '96', 'template/list/role_auth_tree', '', '10', '1', '系统--角色和权限模型');

-- ----------------------------
-- Table structure for vc_field_option
-- ----------------------------
DROP TABLE IF EXISTS `vc_field_option`;
CREATE TABLE `vc_field_option` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `model_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '模型ID',
  `model_field_id` int(11) unsigned DEFAULT '0' COMMENT '模型字段ID',
  `option_value` varchar(64) NOT NULL DEFAULT '' COMMENT '选项值',
  `option_label` varchar(255) NOT NULL DEFAULT '' COMMENT '选项文本',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态：1=开启，2=禁用',
  PRIMARY KEY (`id`),
  KEY `model_field_id` (`model_field_id`)
) ENGINE=InnoDB AUTO_INCREMENT=92 DEFAULT CHARSET=utf8mb4 COMMENT='系统--模型下字段的选项列表';

-- ----------------------------
-- Records of vc_field_option
-- ----------------------------
INSERT INTO `vc_field_option` VALUES ('1', '4', '7', '10', '内置', '1');
INSERT INTO `vc_field_option` VALUES ('2', '4', '7', '20', '扩展', '1');
INSERT INTO `vc_field_option` VALUES ('3', '4', '8', '1', '开启', '1');
INSERT INTO `vc_field_option` VALUES ('4', '4', '8', '2', '禁用', '1');
INSERT INTO `vc_field_option` VALUES ('5', '4', '5', 'template/list/menu_tree', '菜单树形列表', '1');
INSERT INTO `vc_field_option` VALUES ('6', '4', '5', 'template/list/single_list', '无目录列表', '1');
INSERT INTO `vc_field_option` VALUES ('7', '4', '5', 'template/list/category_list', '有目录列表', '1');
INSERT INTO `vc_field_option` VALUES ('8', '7', '53', '1', '开启', '1');
INSERT INTO `vc_field_option` VALUES ('9', '7', '53', '2', '禁用', '1');
INSERT INTO `vc_field_option` VALUES ('10', '5', '20', 'varchar', '字符型0-255字节(varchar)', '1');
INSERT INTO `vc_field_option` VALUES ('11', '5', '20', 'char', '定长字符型0-255字节(char)', '1');
INSERT INTO `vc_field_option` VALUES ('12', '5', '20', 'text', '小型文本型(text)', '1');
INSERT INTO `vc_field_option` VALUES ('13', '5', '20', 'mediumtext', '中型文本型(mediumtext)', '1');
INSERT INTO `vc_field_option` VALUES ('14', '5', '20', 'longtext', '大型文本型(longtext)', '1');
INSERT INTO `vc_field_option` VALUES ('15', '5', '20', 'tinyint', '小数值型(tinyint)', '1');
INSERT INTO `vc_field_option` VALUES ('16', '5', '20', 'smallint', '中数值型(smallint)', '1');
INSERT INTO `vc_field_option` VALUES ('17', '5', '20', 'int', '大数值型(int)', '1');
INSERT INTO `vc_field_option` VALUES ('18', '5', '20', 'bigint', '超大数值型(bigint)', '1');
INSERT INTO `vc_field_option` VALUES ('19', '5', '20', 'float', '数值浮点型(float)', '1');
INSERT INTO `vc_field_option` VALUES ('20', '5', '20', 'double', '数值双精度型(double)', '1');
INSERT INTO `vc_field_option` VALUES ('21', '5', '20', 'date', '日期型(date)', '1');
INSERT INTO `vc_field_option` VALUES ('22', '5', '20', 'datetime', '日期时间型(datetime)', '1');
INSERT INTO `vc_field_option` VALUES ('23', '5', '22', '1', '是', '1');
INSERT INTO `vc_field_option` VALUES ('24', '5', '22', '2', '否', '1');
INSERT INTO `vc_field_option` VALUES ('25', '5', '23', '1', '是', '1');
INSERT INTO `vc_field_option` VALUES ('26', '5', '23', '2', '否', '1');
INSERT INTO `vc_field_option` VALUES ('27', '5', '24', '1', '是', '1');
INSERT INTO `vc_field_option` VALUES ('28', '5', '24', '2', '否', '1');
INSERT INTO `vc_field_option` VALUES ('29', '5', '25', '1', '是', '1');
INSERT INTO `vc_field_option` VALUES ('30', '5', '25', '2', '否', '1');
INSERT INTO `vc_field_option` VALUES ('31', '5', '26', '1', '是', '1');
INSERT INTO `vc_field_option` VALUES ('32', '5', '26', '2', '否', '1');
INSERT INTO `vc_field_option` VALUES ('33', '5', '27', '1', '是', '1');
INSERT INTO `vc_field_option` VALUES ('34', '5', '27', '2', '否', '1');
INSERT INTO `vc_field_option` VALUES ('35', '5', '28', '1', '是', '1');
INSERT INTO `vc_field_option` VALUES ('36', '5', '28', '2', '否', '1');
INSERT INTO `vc_field_option` VALUES ('37', '5', '33', 'text', '单行文本框(text)', '1');
INSERT INTO `vc_field_option` VALUES ('38', '5', '33', 'password', '密码框(password)', '1');
INSERT INTO `vc_field_option` VALUES ('39', '5', '33', 'select', '下拉框(select)', '1');
INSERT INTO `vc_field_option` VALUES ('40', '5', '33', 'radio', '单选框(radio)', '1');
INSERT INTO `vc_field_option` VALUES ('41', '5', '33', 'checkbox', '复选框(checkbox)', '1');
INSERT INTO `vc_field_option` VALUES ('42', '5', '33', 'textarea', '多行文本框(textarea)', '1');
INSERT INTO `vc_field_option` VALUES ('43', '5', '33', 'file', '文件(file)', '1');
INSERT INTO `vc_field_option` VALUES ('44', '5', '33', 'video', '视频(video)', '1');
INSERT INTO `vc_field_option` VALUES ('45', '5', '33', 'editor', '编辑器(editor)', '1');
INSERT INTO `vc_field_option` VALUES ('46', '5', '33', 'img', '图片(img)', '1');
INSERT INTO `vc_field_option` VALUES ('47', '5', '33', 'flash', 'FLASH文件(flash)', '1');
INSERT INTO `vc_field_option` VALUES ('48', '5', '33', 'date', '日期(date)', '1');
INSERT INTO `vc_field_option` VALUES ('49', '5', '33', 'color', '颜色(color)', '1');
INSERT INTO `vc_field_option` VALUES ('50', '5', '36', '1', '开启', '1');
INSERT INTO `vc_field_option` VALUES ('51', '5', '36', '2', '关闭', '1');
INSERT INTO `vc_field_option` VALUES ('52', '5', '38', '1', '显示', '1');
INSERT INTO `vc_field_option` VALUES ('53', '5', '38', '2', '不显示', '1');
INSERT INTO `vc_field_option` VALUES ('54', '5', '39', '1', '固定', '1');
INSERT INTO `vc_field_option` VALUES ('55', '5', '39', '2', '不固定', '1');
INSERT INTO `vc_field_option` VALUES ('56', '5', '40', '1', '是', '1');
INSERT INTO `vc_field_option` VALUES ('57', '5', '40', '2', '否', '1');
INSERT INTO `vc_field_option` VALUES ('58', '6', '46', 'detail', '查看详情（detail）', '1');
INSERT INTO `vc_field_option` VALUES ('59', '6', '46', 'list', '获取列表（list）', '1');
INSERT INTO `vc_field_option` VALUES ('60', '6', '46', 'del', '删除记录（del）', '1');
INSERT INTO `vc_field_option` VALUES ('61', '6', '46', 'category', '获取目录（category）', '1');
INSERT INTO `vc_field_option` VALUES ('62', '6', '46', 'status', '设置状态（status）', '1');
INSERT INTO `vc_field_option` VALUES ('63', '6', '46', 'save', '保存数据（save）', '1');
INSERT INTO `vc_field_option` VALUES ('64', '6', '47', '1', '开启', '1');
INSERT INTO `vc_field_option` VALUES ('65', '6', '47', '2', '关闭', '1');
INSERT INTO `vc_field_option` VALUES ('70', '8', '91', '1', '开启', '1');
INSERT INTO `vc_field_option` VALUES ('71', '8', '91', '2', '关闭', '1');
INSERT INTO `vc_field_option` VALUES ('72', '6', '46', 'login', '登录（login）', '1');
INSERT INTO `vc_field_option` VALUES ('73', '2', '108', '1', '开启', '1');
INSERT INTO `vc_field_option` VALUES ('74', '2', '108', '2', '关闭', '1');
INSERT INTO `vc_field_option` VALUES ('79', '1', '15', '1', '开启', '1');
INSERT INTO `vc_field_option` VALUES ('80', '1', '15', '2', '关闭', '1');
INSERT INTO `vc_field_option` VALUES ('81', '6', '46', 'nav', '导航菜单（nav）', '1');
INSERT INTO `vc_field_option` VALUES ('82', '6', '46', 'tree', '格式化目录树（tree）', '1');
INSERT INTO `vc_field_option` VALUES ('83', '4', '5', 'template/list/role_auth_tree', '角色与权限列表', '1');
INSERT INTO `vc_field_option` VALUES ('87', '9', '130', '1', '开启', '1');
INSERT INTO `vc_field_option` VALUES ('88', '9', '130', '2', '关闭', '1');
INSERT INTO `vc_field_option` VALUES ('89', '9', '132', '10', '角色', '1');
INSERT INTO `vc_field_option` VALUES ('90', '9', '132', '20', '权限', '1');
INSERT INTO `vc_field_option` VALUES ('91', '6', '46', 'display', '显示按钮', '1');

-- ----------------------------
-- Table structure for vc_mediafile
-- ----------------------------
DROP TABLE IF EXISTS `vc_mediafile`;
CREATE TABLE `vc_mediafile` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '文件名',
  `type` varchar(20) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '文件类型',
  `dir` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '文件所在目录',
  `alt` varchar(50) CHARACTER SET utf8 DEFAULT '' COMMENT '文件说明',
  `size` bigint(20) NOT NULL DEFAULT '0' COMMENT '文件大小',
  `description` text CHARACTER SET utf8 COMMENT '文件描述',
  `created_at` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `use_num` int(11) DEFAULT '0' COMMENT '使用次数',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4 COMMENT='系统--图片和文件库';

-- ----------------------------
-- Records of vc_mediafile
-- ----------------------------
INSERT INTO `vc_mediafile` VALUES ('3', '1435746957734595.jpg', 'image', '20150701', '1334133930286_000.jpg', '58795', null, '1435746957', '0');
INSERT INTO `vc_mediafile` VALUES ('4', '1435746958876772.jpg', 'image', '20150701', 'b597e24e6f593569421f3376b803c12d.jpg', '205801', null, '1435746958', '3');
INSERT INTO `vc_mediafile` VALUES ('5', '1435747015136841.zip', 'file', '20150701', 'LOGO1.zip', '17633', null, '1435747015', '7');
INSERT INTO `vc_mediafile` VALUES ('6', '1435747597106232.jpg', 'image', '20150701', 'c04c631a-f4bb-45b3-8196-89d2bcf97648.jpg', '55975', null, '1435747597', '1');
INSERT INTO `vc_mediafile` VALUES ('7', '1435747598630028.jpg', 'image', '20150701', '195608b55816g1e3q4sh3z.jpg', '614789', null, '1435747598', '0');
INSERT INTO `vc_mediafile` VALUES ('8', '1435747599959350.jpg', 'image', '20150701', '201319113623662.jpg', '813113', null, '1435747599', '5');
INSERT INTO `vc_mediafile` VALUES ('9', '1435747600859602.jpg', 'image', '20150701', '112657iw056i00gm0oae0a.jpg', '216503', null, '1435747600', '9');
INSERT INTO `vc_mediafile` VALUES ('10', '1435747602663119.jpg', 'image', '20150701', 'userid219491time20101013060759.jpg', '302052', null, '1435747602', '5');
INSERT INTO `vc_mediafile` VALUES ('11', '1435747603210425.jpg', 'image', '20150701', 'd9ce9b72d8d4321e2d52a9b415086a35.jpg', '50563', null, '1435747603', '7');
INSERT INTO `vc_mediafile` VALUES ('12', '1435747603108625.jpeg', 'image', '20150701', '52b146e33dfae937bd000005.JPEG', '650024', null, '1435747603', '3');
INSERT INTO `vc_mediafile` VALUES ('13', '1435747651137275.jpg', 'image', '20150701', '20110502125941481203.jpg', '69621', null, '1435747651', '2');
INSERT INTO `vc_mediafile` VALUES ('15', '1435747653937662.jpg', 'image', '20150701', '81.jpg', '92782', null, '1435747653', '2');
INSERT INTO `vc_mediafile` VALUES ('16', '1435747744318004.jpg', 'image', '20150701', '1b0673d5615ada7ba7d511fc04012702.jpg', '136858', null, '1435747744', '3');
INSERT INTO `vc_mediafile` VALUES ('17', '1435747745502525.jpg', 'image', '20150701', '1Z0239291_0.jpg', '26681', null, '1435747745', '3');
INSERT INTO `vc_mediafile` VALUES ('19', '1435747745771473.jpg', 'image', '20150701', '5B2E42J3G8WS_DSC04023_600.jpg', '69927', null, '1435747745', '4');
INSERT INTO `vc_mediafile` VALUES ('20', '1435747786335518.jpg', 'image', '20150701', '2015070111302518.jpg', '68623', null, '1435747786', '4');
INSERT INTO `vc_mediafile` VALUES ('23', '1435747787113031.jpg', 'image', '20150701', '7916708020360149472.jpg', '84690', null, '1435747787', '1');
INSERT INTO `vc_mediafile` VALUES ('28', '1435832662115458.jpg', 'image', '20150702', '110420062448-1.jpg', '275698', null, '1435832662', '1');
INSERT INTO `vc_mediafile` VALUES ('29', '1435832775979833.jpg', 'image', '20150702', 'facdc114cb47e8fe3de1b095ad774c9b.jpg', '253491', null, '1435832775', '2');
INSERT INTO `vc_mediafile` VALUES ('30', '1435832841151730.zip', 'file', '20150702', 'yii2-cms-project-bariew.zip', '40802', null, '1435832841', '5');
INSERT INTO `vc_mediafile` VALUES ('31', '1435842442910380.zip', 'file', '20150702', 'easyii-start-master.zip', '15403', null, '1435842442', '1');
INSERT INTO `vc_mediafile` VALUES ('32', '1435855121729819.zip', 'file', '20150703', 'easyii-start-master.zip', '15403', null, '1435855121', '0');
INSERT INTO `vc_mediafile` VALUES ('33', '1437067615706797.jpg', 'image', '20150717', '0100140020.jpg', '106616', null, '1437067616', '1');
INSERT INTO `vc_mediafile` VALUES ('34', '1437067617815021.jpg', 'image', '20150717', '09550RP8-0.jpg', '101052', null, '1437067617', '1');
INSERT INTO `vc_mediafile` VALUES ('35', '1437235118127993.jpg', 'image', '20150718', '2013-06-07-11-23-4309-4263.jpg', '38446', null, '1437235118', '1');
INSERT INTO `vc_mediafile` VALUES ('36', '1437235157376958.jpg', 'image', '20150718', '4798156c52732861fb59efca27ecb61713245.jpg', '93422', null, '1437235157', '1');
INSERT INTO `vc_mediafile` VALUES ('37', '1437235158115135.jpg', 'image', '20150718', '2013-07-05-10-30-27.jpg', '140858', null, '1437235158', '1');
INSERT INTO `vc_mediafile` VALUES ('38', '1437235159594656.jpg', 'image', '20150718', '000000000120185177_1.jpg', '17883', null, '1437235159', '1');
INSERT INTO `vc_mediafile` VALUES ('39', '1437235159645820.jpg', 'image', '20150718', '7871e65dbe0d69cb00b942d80f3f_800_800.jpg', '151204', null, '1437235159', '1');
INSERT INTO `vc_mediafile` VALUES ('40', '1437235159105944.jpg', 'image', '20150718', '9773f7312581b80787c842c63a4d_737_737.jpg', '97094', null, '1437235159', '1');
INSERT INTO `vc_mediafile` VALUES ('41', '1437731476109718.jpg', 'image', '20150724', '5430.jpg', '98685', null, '1437731477', '0');
INSERT INTO `vc_mediafile` VALUES ('42', '1437732519427908.jpg', 'image', '20150724', '5430.jpg', '98685', null, '1437732519', '1');
INSERT INTO `vc_mediafile` VALUES ('43', '1442917540106615.jpg', 'image', '20150922', 'r.jpg', '112447', null, '1442917540', '0');
INSERT INTO `vc_mediafile` VALUES ('44', '1442917552107923.jpg', 'image', '20150922', 'top.jpg', '218472', null, '1442917552', '0');
INSERT INTO `vc_mediafile` VALUES ('45', '1443082403389738.jpg', 'image', '20150924', 'activity-scratch-card-start.jpg', '82542', null, '1443082403', '0');
INSERT INTO `vc_mediafile` VALUES ('46', '1443082416511088.jpg', 'image', '20150924', 'activity-scratch-card-end.jpg', '108512', null, '1443082416', '0');
INSERT INTO `vc_mediafile` VALUES ('47', '1443084686799917.jpg', 'image', '20150924', 'start.jpg', '651277', null, '1443084686', '0');
INSERT INTO `vc_mediafile` VALUES ('48', '1443084701128936.jpg', 'image', '20150924', 'end.jpg', '686820', null, '1443084701', '0');
INSERT INTO `vc_mediafile` VALUES ('49', '1443085882298898.jpg', 'image', '20150924', 'activity-coupon-start.jpg', '53654', null, '1443085882', '0');
INSERT INTO `vc_mediafile` VALUES ('50', '1443085907128660.jpg', 'image', '20150924', 'activity-coupon-end.jpg', '114926', null, '1443085907', '0');
INSERT INTO `vc_mediafile` VALUES ('51', '1443086030694231.jpg', 'image', '20150924', 'activity-coupon-winning.jpg', '54540', null, '1443086030', '0');
INSERT INTO `vc_mediafile` VALUES ('52', '1459480974815098.jpg', 'image', '20160401', 's2.jpg', '22813', null, '1459480974', '1');

-- ----------------------------
-- Table structure for vc_model_field
-- ----------------------------
DROP TABLE IF EXISTS `vc_model_field`;
CREATE TABLE `vc_model_field` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `model_id` int(11) NOT NULL DEFAULT '0' COMMENT '模型ID',
  `label` varchar(64) NOT NULL DEFAULT '' COMMENT '字段中文名称',
  `field_name` varchar(64) NOT NULL DEFAULT '' COMMENT '字段名称',
  `type` varchar(20) NOT NULL DEFAULT '' COMMENT '字段类型',
  `length` int(11) DEFAULT '0' COMMENT '字段长度',
  `is_null` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否为空，1=是，2=否',
  `is_index` tinyint(1) NOT NULL DEFAULT '2' COMMENT '是否建立索引，1= 是，2=否',
  `is_unique` tinyint(1) NOT NULL DEFAULT '2' COMMENT '是否唯一，1=是，2=否',
  `sort_num` int(11) DEFAULT '0' COMMENT '排序， 小的在前',
  `note` varchar(255) DEFAULT '' COMMENT '字段备注说明',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '字段状态，1=开启，2=关闭',
  `form_type` varchar(64) DEFAULT '' COMMENT '表单类型',
  `relation_model_id` int(11) DEFAULT NULL COMMENT '关联模型ID',
  `relation_field_id` int(11) DEFAULT NULL COMMENT '关联字段ID',
  `relation_condition` varchar(255) DEFAULT '' COMMENT '关联条件',
  `form_default` varchar(128) DEFAULT '' COMMENT '表单默认值',
  `is_label` tinyint(1) DEFAULT '2' COMMENT '是否为标题字段，1=是，2=否',
  `is_primary_key` tinyint(1) DEFAULT '2' COMMENT '是否为主键，1=是，2=否',
  `is_auto_increment` tinyint(1) DEFAULT '2' COMMENT '是否自动递增，1=是，2=否',
  `is_signed` tinyint(1) DEFAULT '1' COMMENT '是否可为负数，1=是，2=否',
  `column_width` int(10) unsigned NOT NULL DEFAULT '150' COMMENT '列宽度',
  `is_show` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否在列表中显示，1=显示，2=不显示',
  `is_fixed` tinyint(1) NOT NULL DEFAULT '2' COMMENT '是否在列表中固定， 1=固定，2=不固定',
  `is_filter` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否可筛选，1=是，2=否',
  `filter_field` varchar(64) DEFAULT '' COMMENT '筛选字段（下拉菜单联动用到）',
  `filter_form_name` varchar(64) DEFAULT '' COMMENT '筛选表单名（下拉菜单联动用到）',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=137 DEFAULT CHARSET=utf8 COMMENT='系统--模型下字段定义';

-- ----------------------------
-- Records of vc_model_field
-- ----------------------------
INSERT INTO `vc_model_field` VALUES ('1', '4', 'ID', 'id', 'int', '11', '2', '1', '1', '1', '自增ID', '1', 'hidden', '0', null, '', ' ', '2', '1', '1', '1', '150', '1', '2', '1', '', '');
INSERT INTO `vc_model_field` VALUES ('2', '4', '模型名称', 'model_name', 'varchar', '64', '2', '0', '1', '2', '模型名称', '1', 'text', '0', null, '', ' ', '2', '2', '2', '1', '150', '1', '2', '1', '', '');
INSERT INTO `vc_model_field` VALUES ('3', '4', '模型标题', 'label', 'varchar', '64', '2', '1', '2', '3', '模型标题', '1', 'text', '0', null, '', ' ', '1', '2', '2', '1', '150', '1', '2', '1', '', '');
INSERT INTO `vc_model_field` VALUES ('4', '4', '主操作项', 'main_operate_id', 'int', '11', '1', '1', '2', '5', '主操作项ID', '1', 'select', '6', '41', 'operate_type = \'list\'', ' ', '2', '2', '2', '2', '150', '1', '2', '1', '', '');
INSERT INTO `vc_model_field` VALUES ('5', '4', '组件模板', 'component', 'varchar', '200', '2', '2', '2', '4', '组件模板', '1', 'select', '0', null, '', ' ', '2', '2', '2', '1', '150', '1', '2', '1', '', '');
INSERT INTO `vc_model_field` VALUES ('6', '4', '目录过滤字段', 'filter_field', 'varchar', '80', '1', '2', '2', '6', '过滤字段名', '1', 'text', '0', null, '', ' ', '2', '2', '2', '1', '150', '1', '2', '1', '', '');
INSERT INTO `vc_model_field` VALUES ('7', '4', '模型类型', 'type', 'tinyint', '4', '1', '2', '2', '7', '模型类型：10=内置，20=扩展', '1', 'radio', '0', null, '', '20', '2', '2', '2', '1', '150', '1', '2', '1', '', '');
INSERT INTO `vc_model_field` VALUES ('8', '4', '状态', 'status', 'tinyint', '4', '1', '2', '2', '8', '状态：1=开启，2=关闭', '1', 'radio', '0', null, '', '1', '2', '2', '2', '1', '150', '1', '2', '1', '', '');
INSERT INTO `vc_model_field` VALUES ('9', '4', '备注', 'remark', 'varchar', '100', '1', '2', '2', '9', '表备注', '1', 'text', '0', null, '', ' ', '2', '2', '2', '1', '150', '1', '2', '1', '', '');
INSERT INTO `vc_model_field` VALUES ('10', '1', 'ID', 'id', 'int', '10', '2', '1', '1', '0', '自增ID', '1', 'hidden', '0', '0', '', '', '0', '1', '1', '2', '60', '1', '2', '1', '', '');
INSERT INTO `vc_model_field` VALUES ('11', '1', '路由标题', 'title', 'varchar', '64', '2', '1', '2', '0', '路由标题', '1', 'text', '0', '0', '', '', '1', '2', '2', '0', '150', '1', '2', '1', '', '');
INSERT INTO `vc_model_field` VALUES ('12', '1', '菜单图标', 'icon', 'varchar', '20', '1', '2', '2', '0', '菜单图标', '1', 'text', '0', '0', '', '', '2', '2', '2', '0', '150', '2', '2', '1', '', '');
INSERT INTO `vc_model_field` VALUES ('13', '1', '父级', 'pid', 'int', '10', '2', '1', '2', '0', '父级ID', '1', 'select', '1', '10', '', '', '2', '2', '2', '0', '150', '2', '2', '1', '', '');
INSERT INTO `vc_model_field` VALUES ('14', '1', '模型', 'model_id', 'int', '10', '2', '1', '2', '0', '模型ID', '1', 'select', '4', '1', '', '0', '2', '2', '2', '0', '150', '2', '2', '1', '', '');
INSERT INTO `vc_model_field` VALUES ('15', '1', '状态', 'status', 'tinyint', '1', '1', '2', '2', '0', '状态: 1=开启，2=关闭', '1', 'radio', '0', '0', '', '1', '2', '2', '2', '0', '150', '1', '2', '1', '', '');
INSERT INTO `vc_model_field` VALUES ('16', '5', 'ID', 'id', 'int', '11', '2', '1', '1', '1', 'ID', '1', 'hidden', '0', '0', '', '', '2', '1', '1', '2', '150', '1', '2', '1', '', '');
INSERT INTO `vc_model_field` VALUES ('17', '5', '所属模型', 'model_id', 'int', '11', '2', '1', '2', '2', '模型ID', '1', 'select', '4', '1', '', '0', '2', '2', '2', '0', '150', '1', '2', '1', '', '');
INSERT INTO `vc_model_field` VALUES ('18', '5', '字段中文名称', 'label', 'varchar', '64', '2', '1', '2', '3', '字段中文名称', '1', 'text', '0', '0', '', '', '1', '2', '2', '0', '150', '1', '2', '1', '', '');
INSERT INTO `vc_model_field` VALUES ('19', '5', '字段名称', 'field_name', 'varchar', '64', '2', '1', '2', '4', '字段中文名称', '1', 'text', '0', '0', '', '', '2', '2', '2', '0', '150', '1', '2', '1', '', '');
INSERT INTO `vc_model_field` VALUES ('20', '5', '字段类型', 'type', 'varchar', '64', '2', '1', '2', '5', '字段类型', '1', 'select', '0', '0', '', '', '2', '2', '2', '0', '150', '1', '2', '1', '', '');
INSERT INTO `vc_model_field` VALUES ('21', '5', '字段长度', 'length', 'varchar', '20', '2', '2', '2', '6', '字段长度', '1', 'text', '0', '0', '', '', '2', '2', '2', '0', '150', '1', '2', '1', '', '');
INSERT INTO `vc_model_field` VALUES ('22', '5', '是否为空', 'is_null', 'tinyint', '1', '2', '2', '2', '7', '是否为空，1=是，2=否', '1', 'radio', '0', '0', '', '2', '2', '2', '2', '0', '150', '1', '2', '1', '', '');
INSERT INTO `vc_model_field` VALUES ('23', '5', '建立索引', 'is_index', 'tinyint', '1', '2', '2', '2', '8', '是否建立索引，1= 是，2=否', '1', 'radio', '0', '0', '', '2', '2', '2', '2', '0', '150', '1', '2', '1', '', '');
INSERT INTO `vc_model_field` VALUES ('24', '5', '是否唯一', 'is_unique', 'tinyint', '1', '2', '2', '2', '9', '是否唯一，1=是，2=否', '1', 'radio', '0', '0', '', '2', '2', '2', '2', '0', '150', '1', '2', '1', '', '');
INSERT INTO `vc_model_field` VALUES ('25', '5', '标题字段', 'is_label', 'tinyint', '1', '2', '2', '2', '10', '是否为标题字段，1=是，2=否', '1', 'radio', '0', '0', '', '2', '2', '2', '2', '0', '150', '1', '2', '1', '', '');
INSERT INTO `vc_model_field` VALUES ('26', '5', '主键', 'is_primary_key', 'tinyint', '1', '2', '2', '2', '11', '是否为主键，1=是，2=否', '1', 'radio', '0', '0', '', '2', '2', '2', '2', '0', '150', '1', '2', '1', '', '');
INSERT INTO `vc_model_field` VALUES ('27', '5', '自动递增', 'is_auto_increment', 'tinyint', '1', '2', '2', '2', '12', '是否自动递增，1=是，2=否', '1', 'radio', '0', '0', '', '2', '2', '2', '2', '0', '150', '1', '2', '1', '', '');
INSERT INTO `vc_model_field` VALUES ('28', '5', '是否可为负数', 'is_signed', 'tinyint', '1', '2', '2', '2', '13', '是否可为负数，1=是，2=否', '1', 'radio', '0', '0', '', '2', '2', '2', '2', '0', '150', '1', '2', '1', '', '');
INSERT INTO `vc_model_field` VALUES ('30', '5', '字段备注', 'note', 'varchar', '255', '1', '2', '2', '19', '字段备注说明', '1', 'text', '0', '0', '', '', '2', '2', '2', '0', '150', '1', '2', '1', '', '');
INSERT INTO `vc_model_field` VALUES ('31', '5', '关联模型', 'relation_model_id', 'int', '10', '1', '2', '2', '24', '关联模型', '1', 'select', '4', '1', '', '', '2', '2', '2', '0', '150', '1', '2', '1', 'model_id', 'relation_field_id');
INSERT INTO `vc_model_field` VALUES ('32', '5', '关联字段', 'relation_field_id', 'int', '11', '1', '2', '2', '25', '关联字段', '1', 'select', '5', '16', '', '', '2', '2', '2', '0', '150', '1', '2', '1', '', '');
INSERT INTO `vc_model_field` VALUES ('33', '5', '表单类型', 'form_type', 'varchar', '64', '1', '2', '2', '20', '表单类型', '1', 'select', '0', '0', '', '', '2', '2', '2', '0', '150', '1', '2', '1', '', '');
INSERT INTO `vc_model_field` VALUES ('34', '5', '表单默认值', 'form_default', 'varchar', '128', '1', '2', '2', '21', '表单默认值', '1', 'text', '0', '0', '', '', '2', '2', '2', '0', '150', '1', '2', '1', '', '');
INSERT INTO `vc_model_field` VALUES ('35', '5', '排序', 'sort_num', 'int', '11', '1', '2', '2', '23', '排序， 小的在前', '1', 'text', '0', '0', '', '', '2', '2', '2', '0', '150', '1', '2', '1', '', '');
INSERT INTO `vc_model_field` VALUES ('36', '5', '状态', 'status', 'tinyint', '1', '2', '2', '2', '17', '字段状态，1=开启，2=关闭', '1', 'radio', '0', '0', '', '1', '2', '2', '2', '0', '150', '1', '2', '1', '', '');
INSERT INTO `vc_model_field` VALUES ('37', '5', '列宽度', 'column_width', 'int', '10', '2', '2', '2', '18', '列表的列宽度', '1', 'text', '0', '0', '', '150', '2', '2', '2', '2', '100', '1', '2', '1', '', '');
INSERT INTO `vc_model_field` VALUES ('38', '5', '列显示', 'is_show', 'tinyint', '1', '2', '2', '2', '14', '是否在列表中显示，1=显示，2=不显示', '1', 'radio', '0', '0', '', '1', '2', '2', '2', '0', '150', '1', '2', '1', '', '');
INSERT INTO `vc_model_field` VALUES ('39', '5', '固定列', 'is_fixed', 'tinyint', '1', '2', '2', '2', '15', '是否在列表中固定， 1=固定，2=不固定', '1', 'radio', '0', '0', '', '2', '2', '2', '2', '0', '150', '1', '2', '1', '', '');
INSERT INTO `vc_model_field` VALUES ('40', '5', '可筛选', 'is_filter', 'tinyint', '1', '2', '2', '2', '16', '是否可筛选，1=是，2=否', '1', 'radio', '0', '0', '', '1', '2', '2', '2', '0', '150', '1', '2', '1', '', '');
INSERT INTO `vc_model_field` VALUES ('41', '6', 'ID', 'id', 'int', '11', '2', '1', '1', '1', '自增ID', '1', 'hidden', '0', '0', '', '', '2', '1', '1', '2', '150', '1', '2', '2', '', '');
INSERT INTO `vc_model_field` VALUES ('42', '6', '操作名称', 'operate_name', 'varchar', '64', '2', '1', '1', '2', '操作名称（路由链接）', '1', 'text', '0', '0', '', '', '2', '2', '2', '0', '150', '1', '2', '1', '', '');
INSERT INTO `vc_model_field` VALUES ('43', '6', '操作标题', 'label', 'varchar', '64', '2', '1', '2', '3', '操作标题', '1', 'text', '0', '0', '', '', '1', '2', '2', '0', '150', '1', '2', '1', '', '');
INSERT INTO `vc_model_field` VALUES ('44', '6', '后端URL', 'api_path', 'varchar', '255', '2', '2', '2', '4', '后端请求地址', '1', 'text', '0', '0', '', '', '2', '2', '2', '0', '150', '1', '2', '1', '', '');
INSERT INTO `vc_model_field` VALUES ('45', '6', '所属模型', 'model_id', 'int', '11', '2', '1', '2', '5', '所属模型', '1', 'select', '4', '1', '', '', '2', '2', '2', '2', '150', '1', '2', '1', '', '');
INSERT INTO `vc_model_field` VALUES ('46', '6', '操作类型', 'operate_type', 'varchar', '64', '1', '1', '2', '6', '操作类型', '1', 'select', '0', '0', '', '', '2', '2', '2', '2', '150', '1', '2', '1', '', '');
INSERT INTO `vc_model_field` VALUES ('47', '6', '状态', 'status', 'tinyint', '1', '2', '2', '2', '0', '状态: 1=开启，2=关闭', '1', 'radio', '0', '0', '', '1', '2', '2', '2', '0', '150', '1', '2', '1', '', '');
INSERT INTO `vc_model_field` VALUES ('48', '7', 'ID', 'id', 'int', '11', '2', '1', '1', '1', '自增ID', '1', 'hidden', '0', '0', '', '', '2', '1', '1', '2', '150', '1', '2', '2', '', '');
INSERT INTO `vc_model_field` VALUES ('49', '7', '所属模型', 'model_id', 'int', '11', '1', '1', '2', '2', '模型ID', '1', 'select', '4', '1', '', '', '2', '2', '2', '2', '150', '1', '2', '1', 'model_id', 'model_field_id');
INSERT INTO `vc_model_field` VALUES ('50', '7', '模型字段', 'model_field_id', 'int', '11', '1', '1', '2', '3', '模型字段ID', '1', 'select', '5', '16', '', '', '2', '2', '2', '2', '150', '1', '2', '1', '', '');
INSERT INTO `vc_model_field` VALUES ('51', '7', '选项值', 'option_value', 'varchar', '64', '1', '1', '2', '4', '选项值', '1', 'text', '0', '0', '', '', '2', '2', '2', '0', '150', '1', '2', '1', '', '');
INSERT INTO `vc_model_field` VALUES ('52', '7', '选项文本', 'option_label', 'varchar', '255', '1', '1', '2', '5', '选项文本', '1', 'text', '0', '0', '', '', '1', '2', '2', '0', '150', '1', '2', '1', '', '');
INSERT INTO `vc_model_field` VALUES ('53', '7', '状态', 'status', 'tinyint', '1', '2', '2', '2', '6', '状态：1=开启，2=禁用', '1', 'radio', '0', '0', '', '1', '2', '2', '2', '0', '150', '1', '2', '1', '', '');
INSERT INTO `vc_model_field` VALUES ('54', '5', '筛选字段', 'filter_field', 'varchar', '64', '1', '2', '2', '26', '筛选字段（下拉菜单联动用到）', '1', 'text', null, null, '', '', '2', '2', '2', '1', '150', '1', '2', '1', '', '');
INSERT INTO `vc_model_field` VALUES ('55', '5', '筛选表单名', 'filter_form_name', 'varchar', '64', '1', '2', '2', '27', '筛选表单名（下拉菜单联动用到）', '1', 'text', null, null, '', '', '2', '2', '2', '1', '150', '1', '2', '1', '', '');
INSERT INTO `vc_model_field` VALUES ('56', '5', '关联条件', 'relation_condition', 'varchar', '255', '1', '2', '2', '22', '过滤条件 如 type = 10', '1', 'text', '0', '0', '', '', '2', '2', '2', '1', '150', '1', '2', '1', '', '');
INSERT INTO `vc_model_field` VALUES ('90', '8', 'ID', 'id', 'int', '11', '2', '1', '1', '1', '自增ID', '2', 'hidden', '0', null, '', ' ', '2', '1', '1', '1', '150', '1', '2', '1', '', '');
INSERT INTO `vc_model_field` VALUES ('91', '8', '状态', 'status', 'tinyint', '4', '1', '2', '2', '2', '状态：1=开启，2=关闭', '1', 'radio', '0', null, '', '1', '2', '2', '2', '1', '150', '1', '2', '1', '', '');
INSERT INTO `vc_model_field` VALUES ('95', '8', '用户名', 'username', 'char', '16', '2', '1', '1', '0', '', '1', 'text', '0', '0', '', '', '1', '2', '2', '2', '150', '1', '2', '1', '', '');
INSERT INTO `vc_model_field` VALUES ('96', '8', '密码', 'password', 'varchar', '255', '2', '2', '2', '0', '密码', '1', 'password', '0', '0', '', '', '2', '2', '2', '2', '150', '2', '2', '1', '', '');
INSERT INTO `vc_model_field` VALUES ('99', '8', '用户邮箱', 'email', 'varchar', '32', '2', '2', '1', '0', '用户邮箱', '1', 'text', '0', '0', '', '', '2', '2', '2', '2', '150', '1', '2', '1', '', '');
INSERT INTO `vc_model_field` VALUES ('100', '8', '用户手机', 'mobile', 'char', '15', '2', '2', '1', '0', '用户手机', '1', 'text', '0', '0', '', '', '2', '2', '2', '2', '150', '1', '2', '1', '', '');
INSERT INTO `vc_model_field` VALUES ('101', '8', '注册时间', 'reg_time', 'int', '10', '2', '2', '2', '0', '注册时间', '1', '', '0', '0', '', '', '2', '2', '2', '2', '150', '1', '2', '1', '', '');
INSERT INTO `vc_model_field` VALUES ('102', '8', '注册IP', 'reg_ip', 'bigint', '20', '2', '2', '2', '0', '注册IP', '1', '', '0', '0', '', '', '2', '2', '2', '2', '150', '1', '2', '1', '', '');
INSERT INTO `vc_model_field` VALUES ('103', '8', '最后登录时间', 'last_login_time', 'int', '10', '2', '2', '2', '0', '最后登录时间', '1', '', '0', '0', '', '', '2', '2', '2', '2', '150', '1', '2', '1', '', '');
INSERT INTO `vc_model_field` VALUES ('104', '8', '最后登录IP', 'last_login_ip', 'bigint', '20', '2', '2', '2', '0', '最后登录IP', '1', '', '0', '0', '', '', '2', '2', '2', '2', '150', '1', '2', '1', '', '');
INSERT INTO `vc_model_field` VALUES ('105', '8', '更新时间', 'update_time', 'int', '10', '2', '2', '2', '0', '更新时间', '1', '', '0', '0', '', '', '2', '2', '2', '2', '150', '1', '2', '1', '', '');
INSERT INTO `vc_model_field` VALUES ('106', '8', '登录令牌', 'token', 'varchar', '255', '1', '2', '2', '0', '', '1', '', '0', '0', '', '', '2', '2', '2', '2', '0', '2', '2', '2', '', '');
INSERT INTO `vc_model_field` VALUES ('107', '2', 'ID', 'id', 'int', '11', '2', '1', '1', '1', '自增ID', '2', 'hidden', '0', null, '', ' ', '2', '1', '1', '1', '150', '1', '2', '1', '', '');
INSERT INTO `vc_model_field` VALUES ('108', '2', '状态', 'status', 'tinyint', '4', '1', '2', '2', '2', '状态：1=开启，2=关闭', '1', 'radio', '0', null, '', '1', '2', '2', '2', '1', '150', '1', '2', '1', '', '');
INSERT INTO `vc_model_field` VALUES ('129', '9', 'ID', 'id', 'int', '11', '2', '1', '1', '1', '自增ID', '2', 'hidden', '0', null, '', ' ', '2', '1', '1', '1', '150', '1', '2', '1', '', '');
INSERT INTO `vc_model_field` VALUES ('130', '9', '状态', 'status', 'tinyint', '4', '1', '2', '2', '8', '状态：1=开启，2=关闭', '1', 'radio', '0', null, '', '1', '2', '2', '2', '1', '150', '1', '2', '1', '', '');
INSERT INTO `vc_model_field` VALUES ('131', '9', '名称', 'name', 'varchar', '64', '2', '1', '1', '2', '角色或权限名称', '1', 'text', '0', '0', '', '', '1', '2', '2', '2', '150', '1', '2', '1', '', '');
INSERT INTO `vc_model_field` VALUES ('132', '9', '类型', 'type', 'tinyint', '4', '2', '2', '2', '3', '类型：10=角色，20=权限', '1', 'radio', '0', '0', '', '10', '2', '2', '2', '2', '150', '2', '2', '1', '', '');
INSERT INTO `vc_model_field` VALUES ('133', '9', '描述', 'description', 'varchar', '64', '1', '2', '2', '4', '描述', '1', 'text', '0', '0', '', '', '2', '2', '2', '2', '150', '2', '2', '1', '', '');
INSERT INTO `vc_model_field` VALUES ('134', '9', '规则名称', 'rule_name', 'varchar', '64', '1', '2', '2', '5', '规则名称', '1', 'text', '0', '0', '', '', '2', '2', '2', '2', '150', '2', '2', '1', '', '');
INSERT INTO `vc_model_field` VALUES ('135', '9', '创建时间', 'create_time', 'int', '11', '2', '2', '2', '6', '创建时间', '1', '', '0', '0', '', '', '2', '2', '2', '2', '150', '2', '2', '1', '', '');
INSERT INTO `vc_model_field` VALUES ('136', '9', '更新时间', 'update_time', 'int', '11', '2', '2', '2', '7', '更新时间', '1', '', '0', '0', '', '', '2', '2', '2', '2', '150', '2', '2', '1', '', '');

-- ----------------------------
-- Table structure for vc_model_operate
-- ----------------------------
DROP TABLE IF EXISTS `vc_model_operate`;
CREATE TABLE `vc_model_operate` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `operate_name` varchar(64) NOT NULL DEFAULT '' COMMENT '操作名称（路由链接）',
  `label` varchar(64) NOT NULL DEFAULT '' COMMENT '操作标题',
  `api_path` varchar(200) NOT NULL DEFAULT '' COMMENT '后端请求地址',
  `model_id` int(11) NOT NULL DEFAULT '0' COMMENT '所属模型ID',
  `operate_type` varchar(64) DEFAULT '' COMMENT '操作类型',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态: 1=开启，2=关闭',
  PRIMARY KEY (`id`),
  UNIQUE KEY `index` (`operate_name`),
  KEY `operate_type` (`operate_type`)
) ENGINE=InnoDB AUTO_INCREMENT=111 DEFAULT CHARSET=utf8mb4 COMMENT='系统--模型下操作项';

-- ----------------------------
-- Records of vc_model_operate
-- ----------------------------
INSERT INTO `vc_model_operate` VALUES ('1', 'get_nav_menu', '获取导航菜单', '/menu', '1', 'nav', '1');
INSERT INTO `vc_model_operate` VALUES ('2', 'get_menu_tree', '获取菜单列表', '/menu/tree', '1', 'list', '1');
INSERT INTO `vc_model_operate` VALUES ('3', 'save_node', '保存菜单节点', '/menu/save', '1', 'save', '1');
INSERT INTO `vc_model_operate` VALUES ('4', 'del_node', '删除菜单节点', '/menu/del', '1', 'del', '1');
INSERT INTO `vc_model_operate` VALUES ('5', 'get_format_tree', '获取格式化下拉菜单', '/menu/format', '1', 'tree', '1');
INSERT INTO `vc_model_operate` VALUES ('6', 'get_model_list', '获取模型管理列表', '/auth_model', '4', 'list', '1');
INSERT INTO `vc_model_operate` VALUES ('7', 'get_model', '获取模型选择列表', '/auth_model/getModel', '4', null, '1');
INSERT INTO `vc_model_operate` VALUES ('8', 'set_menu_status', '设置菜单状态', '/menu/setStatus', '1', 'status', '1');
INSERT INTO `vc_model_operate` VALUES ('9', 'get_model_field_list', '获取模型字段列表', '/model_field', '5', 'list', '1');
INSERT INTO `vc_model_operate` VALUES ('10', 'get_model_operate_list', '获取操作项列表', '/model_operate', '6', 'list', '1');
INSERT INTO `vc_model_operate` VALUES ('11', 'get_model_field_category', '获取模型字段目录', '/auth_model/tree', '5', 'category', '1');
INSERT INTO `vc_model_operate` VALUES ('12', 'save_model_field', '保存字段', '/model_field/save', '5', 'save', '1');
INSERT INTO `vc_model_operate` VALUES ('13', 'del_model_field', '删除字段', '/model_field/del', '5', 'del', '1');
INSERT INTO `vc_model_operate` VALUES ('14', 'get_model_operate_category', '获取操作项目录', '/auth_model/tree', '6', 'category', '1');
INSERT INTO `vc_model_operate` VALUES ('15', 'save_auth_model', '保存模型', '/auth_model/save', '4', 'save', '1');
INSERT INTO `vc_model_operate` VALUES ('16', 'save_model_operate', '保存操作项', '/model_operate/save', '6', 'save', '1');
INSERT INTO `vc_model_operate` VALUES ('17', 'get_field_option_list', '获取字段值选项列表', '/field_option', '7', 'list', '1');
INSERT INTO `vc_model_operate` VALUES ('18', 'save_field_option', '保存字段值选项', '/field_option/save', '7', 'save', '1');
INSERT INTO `vc_model_operate` VALUES ('19', 'get_field_option_category', '获取字段值选项目录', '/auth_model/tree', '7', 'category', '1');
INSERT INTO `vc_model_operate` VALUES ('68', 'get_admin_list', '获取管理员模型列表', '/admin', '8', 'list', '1');
INSERT INTO `vc_model_operate` VALUES ('69', 'save_admin', '保存管理员模型', '/admin/save', '8', 'save', '1');
INSERT INTO `vc_model_operate` VALUES ('70', 'del_admin', '删除管理员模型', '/admin/del', '8', 'del', '1');
INSERT INTO `vc_model_operate` VALUES ('71', 'set_admin_status', '设置管理员模型状态', '/admin/setStatus', '8', 'status', '1');
INSERT INTO `vc_model_operate` VALUES ('72', 'get_admin_detail', '查看管理员模型详情', '/admin/getDetail', '8', 'detail', '1');
INSERT INTO `vc_model_operate` VALUES ('73', 'get_admin_category', '获取管理员模型目录', '/category/tree', '8', 'category', '1');
INSERT INTO `vc_model_operate` VALUES ('74', 'login', '登录后台', '/admin/login', '8', 'login', '1');
INSERT INTO `vc_model_operate` VALUES ('75', 'logout', '退出系统', '/admin/logout', '8', '', '1');
INSERT INTO `vc_model_operate` VALUES ('76', 'del_model', '删除模型', '/auth_model/del', '4', 'del', '1');
INSERT INTO `vc_model_operate` VALUES ('77', 'get_single_list_list', '获取单列表模型列表', '/single_list', '2', 'list', '1');
INSERT INTO `vc_model_operate` VALUES ('78', 'save_single_list', '保存单列表模型', '/single_list/save', '2', 'save', '1');
INSERT INTO `vc_model_operate` VALUES ('79', 'del_single_list', '删除单列表模型', '/single_list/del', '2', 'del', '1');
INSERT INTO `vc_model_operate` VALUES ('80', 'set_single_list_status', '设置单列表模型状态', '/single_list/setStatus', '2', 'status', '1');
INSERT INTO `vc_model_operate` VALUES ('81', 'get_single_list_detail', '查看单列表模型详情', '/single_list/getDetail', '2', 'detail', '1');
INSERT INTO `vc_model_operate` VALUES ('82', 'get_single_list_category', '获取单列表模型目录', '/category/tree', '2', 'category', '1');
INSERT INTO `vc_model_operate` VALUES ('95', 'del_field_option', '删除字段值选项', '/field_option/del', '7', 'del', '1');
INSERT INTO `vc_model_operate` VALUES ('96', 'get_auth_item_list', '获取角色和权限模型列表', '/auth_item/tree', '9', 'list', '1');
INSERT INTO `vc_model_operate` VALUES ('97', 'save_auth_item', '保存角色和权限模型', '/auth_item/save', '9', 'save', '1');
INSERT INTO `vc_model_operate` VALUES ('98', 'del_auth_item', '删除角色和权限模型', '/auth_item/del', '9', 'del', '1');
INSERT INTO `vc_model_operate` VALUES ('99', 'set_auth_item_status', '设置角色和权限模型状态', '/auth_item/setStatus', '9', 'status', '1');
INSERT INTO `vc_model_operate` VALUES ('100', 'get_auth_item_detail', '查看角色和权限模型详情', '/auth_item/getDetail', '9', 'detail', '1');
INSERT INTO `vc_model_operate` VALUES ('101', 'get_auth_item_category', '获取角色和权限模型目录', '/category/tree', '9', 'category', '1');
INSERT INTO `vc_model_operate` VALUES ('102', 'set_auth_item', '分配权限', '/auth_item/setAuth', '9', 'set_auth', '1');
INSERT INTO `vc_model_operate` VALUES ('103', 'set_menu_item', '分配菜单', '/auth_item/setMenu', '9', 'set_menu', '1');
INSERT INTO `vc_model_operate` VALUES ('104', 'get_menu_operate', '获取菜单操作项', '/auth_item/getOperate', '9', 'get_operate', '1');
INSERT INTO `vc_model_operate` VALUES ('105', 'set_menu_operate', '分配操作项', '/auth_item/setOperate', '9', 'set_operate', '1');
INSERT INTO `vc_model_operate` VALUES ('106', 'set_admin_role', '分配角色', '/admin/setRole', '8', 'set_role', '1');
INSERT INTO `vc_model_operate` VALUES ('107', 'show_add_single_btn', '显示添加按钮', '#', '2', 'display', '1');
INSERT INTO `vc_model_operate` VALUES ('110', 'show_add_model_btn', '显示添加按钮', '#', '4', 'display', '1');

-- ----------------------------
-- Table structure for vc_single_list
-- ----------------------------
DROP TABLE IF EXISTS `vc_single_list`;
CREATE TABLE `vc_single_list` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态：1=开启，2=关闭',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '标题',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='系统--单页模型';

-- ----------------------------
-- Records of vc_single_list
-- ----------------------------
