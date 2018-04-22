/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : lvcheng

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2018-04-22 10:50:04
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `article`
-- ----------------------------
DROP TABLE IF EXISTS `article`;
CREATE TABLE `article` (
  `vist_author_id` int(11) NOT NULL,
  `article_id` int(11) NOT NULL AUTO_INCREMENT,
  `article_author` varchar(50) NOT NULL,
  `article_title` varchar(50) NOT NULL,
  `article_create_time` int(11) DEFAULT '0',
  `article_update_time` int(11) DEFAULT '0',
  `article_content` text NOT NULL,
  `look_count` int(11) NOT NULL DEFAULT '0',
  `comment_count` int(11) NOT NULL DEFAULT '0',
  `article_on_top` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`article_id`)
) ENGINE=MyISAM AUTO_INCREMENT=75 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of article
-- ----------------------------
INSERT INTO `article` VALUES ('1', '74', 'admin', '望周知', '1520913189', '1520913189', '<p>&nbsp; 希望大家文明交流，约团出行已经上线。希望大家旅行多多注意安全。</p>', '3', '0', '1');
INSERT INTO `article` VALUES ('8', '62', '嘉磊', '我觉得', '1520502056', '1520502056', '<p>我觉的还是可以&nbsp;</p>', '15', '0', '1');
INSERT INTO `article` VALUES ('4', '66', 'JimLee', '旅行于我', '1520663119', '1520663119', '<p style=\"margin-top: 0px; margin-bottom: 25px; padding: 0px; line-height: 39.2px; text-indent: 28px; color: rgb(102, 102, 102); font-family: Arial; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\">每个人都有一个远方的梦，于是旅行势在必行。</p><p style=\"margin-top: 0px; margin-bottom: 25px; padding: 0px; line-height: 39.2px; text-indent: 28px; color: rgb(102, 102, 102); font-family: Arial; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\">我不是用的旅游这个词，因为我们并不是去玩。我们是在走，是一段历程，是一场心灵的放开，是一种生命的聆听。</p><p style=\"margin-top: 0px; margin-bottom: 25px; padding: 0px; line-height: 39.2px; text-indent: 28px; color: rgb(102, 102, 102); font-family: Arial; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\">我喜欢简单而充实的旅途，火车或者旧的巴士，可以穿过拥挤的车站，可以在站台和人们一样拖着行李箱向前张望，可以用很长的时间去看窗外的景物，可以用整个晚上去听各地的乡音。可以去徒步，可以去奔跑。可以去探索黑夜，可以去追寻黎明。</p><p style=\"margin-top: 0px; margin-bottom: 25px; padding: 0px; line-height: 39.2px; text-indent: 28px; color: rgb(102, 102, 102); font-family: Arial; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\">我不是以穷人的状态去说旅行，因为我不是穷人。可以走过一路的缭乱，但太多的享受就会失去很多旅行的意义。你去了好多地方，买了多少东西，享受了多美的服务，这些都不重要，一车一旅行背包，不管沿途在精致的餐厅还是简陋的饭馆，我们要的只是渴了喝水，饿了吃饭，风情不需要太多的突出和修饰。睡在哪，有一扇窗，就可把风景收进心底。</p><p style=\"margin-top: 0px; margin-bottom: 25px; padding: 0px; line-height: 39.2px; text-indent: 28px; color: rgb(102, 102, 102); font-family: Arial; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\">不管是用<a href=\"https://www.duanwenxue.com/duanxin/\" style=\"margin: 0px; padding: 0px; color: rgb(102, 102, 102); outline: none; text-decoration: none;\">手机</a>或者相机，我都会用心的拍照，我拍的不只是风景，不只是我和你，不只是那随处可拾的美，更不只是证明我来过这里。我只是从我<a href=\"https://www.duanwenxue.com/jingdian/shenghuo/\" style=\"margin: 0px; padding: 0px; color: rgb(102, 102, 102); outline: none; text-decoration: none;\">生活</a>的视角，去看一场这个世界的寂静与喧嚣，优雅和繁华。路很远，世界很大，任何一次旅途，都不要在归程里印上短暂，大雨滂沱的时候，我们也可以扔掉伞，用力的跑进生命的笑声和灿烂。</p><p style=\"margin-top: 0px; margin-bottom: 25px; padding: 0px; line-height: 39.2px; text-indent: 28px; color: rgb(102, 102, 102); font-family: Arial; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\">你去了，又回来了。你不一定要把所有东西都记下来，但总有一些东西会融入了血液。有一张照片，有一段文字，或者是有一句朋友耳口相传的描述，都似乎可以闻到你身上淡淡的草原香气，或看到你眼睛里满装的大海的蔚蓝。</p><p style=\"margin-top: 0px; margin-bottom: 25px; padding: 0px; line-height: 39.2px; text-indent: 28px; color: rgb(102, 102, 102); font-family: Arial; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\">于是青春不息，到我们垂垂老矣，我们行走的那么用心，而美好随意。此刻的我正在通往湘西的车厢，而，你在哪里。</p><p><br/></p>', '24', '0', '1');
INSERT INTO `article` VALUES ('1', '73', 'admin', '公告', '1520913116', '1520913116', '<p style=\"text-align: center;\">不可以骂脏话！！！<br/></p>', '0', '0', '1');

-- ----------------------------
-- Table structure for `category`
-- ----------------------------
DROP TABLE IF EXISTS `category`;
CREATE TABLE `category` (
  `id` int(2) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(2) NOT NULL,
  `cate_title` varchar(50) NOT NULL,
  `cate_url` varchar(255) DEFAULT NULL,
  `cate_icon` varchar(255) DEFAULT '&#xe658;',
  `cate_role` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2001 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of category
-- ----------------------------
INSERT INTO `category` VALUES ('1', '0', '文章管理', '', '&#xe616;', '1');
INSERT INTO `category` VALUES ('2', '0', '图片管理', '', '&#xe613;', '1');
INSERT INTO `category` VALUES ('3', '0', '评论管理', null, '&#xe622;', '1');
INSERT INTO `category` VALUES ('4', '0', '用户管理', null, '&#xe60d;', '1');
INSERT INTO `category` VALUES ('5', '0', '管理员管理', null, '&#xe62d;', '1');
INSERT INTO `category` VALUES ('6', '0', '旅游团', null, '&#xe658', '1');
INSERT INTO `category` VALUES ('7', '1', '文章列表', '../article/articleList.html', null, '1');
INSERT INTO `category` VALUES ('8', '2', '图片列表', '../photo/photoList.html', null, '1');
INSERT INTO `category` VALUES ('9', '3', '评论列表', '../comment/commentList.html', null, '1');
INSERT INTO `category` VALUES ('10', '3', '意见反馈', '../comment/suggestionList.html', null, '1');
INSERT INTO `category` VALUES ('11', '4', '用户列表', '../admin/adminManageVist.html', null, '1');
INSERT INTO `category` VALUES ('12', '4', '用户权限管理', '../admin/adminChangeVist.html', null, '1');
INSERT INTO `category` VALUES ('13', '5', '管理员列表', '../admin/adminList.html', null, '1');
INSERT INTO `category` VALUES ('14', '5', '权限管理', '../admin/adminRoleChange.html', null, '1');
INSERT INTO `category` VALUES ('15', '16', '系统栏目管理', '../category/systemList.html', null, '1');
INSERT INTO `category` VALUES ('16', '0', '系统设置', '', '&#xe62e;', '1');
INSERT INTO `category` VALUES ('1003', '6', '旅游组团管理', '../travel/travelList.html', null, '1');

-- ----------------------------
-- Table structure for `comment`
-- ----------------------------
DROP TABLE IF EXISTS `comment`;
CREATE TABLE `comment` (
  `comment_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `vist_id` int(11) NOT NULL,
  `article_id` int(11) NOT NULL,
  `vist_name` varchar(50) NOT NULL,
  `comment_create_time` int(11) NOT NULL,
  `comment_des` varchar(100) NOT NULL,
  PRIMARY KEY (`comment_id`)
) ENGINE=MyISAM AUTO_INCREMENT=61 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of comment
-- ----------------------------

-- ----------------------------
-- Table structure for `photo`
-- ----------------------------
DROP TABLE IF EXISTS `photo`;
CREATE TABLE `photo` (
  `photo_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `photo_name` varchar(50) NOT NULL,
  `photo_title` varchar(50) NOT NULL,
  `photo_src` varchar(255) NOT NULL,
  `photo_er` varchar(50) NOT NULL DEFAULT 'admin',
  `photo_upload_time` int(11) DEFAULT NULL,
  `photo_look_count` int(11) DEFAULT '0',
  PRIMARY KEY (`photo_id`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of photo
-- ----------------------------
INSERT INTO `photo` VALUES ('1', 'a1', '伊利奥斯', 'uploads/sight/a1.jpg', 'admin', null, '10');
INSERT INTO `photo` VALUES ('2', 'a2', '峡谷', 'uploads/sight/a2.jpg', 'admin', null, '11');
INSERT INTO `photo` VALUES ('3', 'a3', '雪山夕阳', 'uploads/sight/a3.jpg', 'admin', null, '12');
INSERT INTO `photo` VALUES ('4', 'a4', '海峡', 'uploads/sight/a4.jpg', 'admin', null, '13');
INSERT INTO `photo` VALUES ('5', 'a5', '晚霞城市', 'uploads/sight/a5.jpg', 'admin', null, '14');
INSERT INTO `photo` VALUES ('12', '02b55b85c7872e194c53cb4755e28d64.jpg', '街景', 'uploads/sight/20180110\\02b55b85c7872e194c53cb4755e28d64.jpg', 'Lee', '1515586612', '16');
INSERT INTO `photo` VALUES ('19', 'a9dc87a1c35d35e8462d36f35df5bf1a.jpg', '城市之巅', 'uploads/sight/20180210\\a9dc87a1c35d35e8462d36f35df5bf1a.jpg', 'Lee', '1518232437', '25');
INSERT INTO `photo` VALUES ('20', '1fc09ae5635fa7148a497065ca340966.jpg', '123123123', 'uploads/sight/20180311\\1fc09ae5635fa7148a497065ca340966.jpg', '嘉磊', '1520776342', '7');
INSERT INTO `photo` VALUES ('22', '4bb958387264b128c2bcaa6c782f63c8.jpg', '13123123', 'uploads/sight/20180312\\4bb958387264b128c2bcaa6c782f63c8.jpg', '123', '1520784926', '3');

-- ----------------------------
-- Table structure for `suggestion`
-- ----------------------------
DROP TABLE IF EXISTS `suggestion`;
CREATE TABLE `suggestion` (
  `suggestion_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `vist_id` int(11) NOT NULL,
  `vist_name` varchar(255) NOT NULL,
  `suggestion_create_time` int(11) NOT NULL,
  `suggestion_title` varchar(50) NOT NULL,
  `suggestion_content` varchar(255) NOT NULL,
  PRIMARY KEY (`suggestion_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of suggestion
-- ----------------------------

-- ----------------------------
-- Table structure for `travel`
-- ----------------------------
DROP TABLE IF EXISTS `travel`;
CREATE TABLE `travel` (
  `travel_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `sponsor_id` int(11) NOT NULL,
  `sponsor_name` varchar(255) NOT NULL,
  `sponsor_tel` varchar(12) NOT NULL,
  `sponsor_starttime` int(11) NOT NULL,
  `sponsor_endtime` int(11) NOT NULL,
  `travel_place` varchar(255) NOT NULL,
  `travel_img_url` varchar(255) NOT NULL,
  `travel_des` longtext NOT NULL,
  `status` int(2) NOT NULL DEFAULT '0',
  `creatime` int(11) NOT NULL,
  `participant_count` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`travel_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of travel
-- ----------------------------
INSERT INTO `travel` VALUES ('4', '1', 'admin', '17748135274', '1522684800', '1522857600', '成都', 'uploads/travel/20180401\\677b75f6cc4d5a0d782519747a38f8d1.jpg', '<p>水电费</p>', '1', '1522585886', '2');
INSERT INTO `travel` VALUES ('7', '1', 'admin', '15208319838', '1523462400', '1525276800', '水电费', 'uploads/travel/20180401\\b66cd15870338445efc5a820ee35adba.jpg', '<p>说的f</p>', '1', '1522585886', '1');
INSERT INTO `travel` VALUES ('8', '8', '嘉磊', '15208319838', '1525276800', '1527177600', '九寨沟', 'uploads/travel/20180403\\aec254e366c3be71dbbbd3779ed705b9.jpg', '', '1', '1522770463', '3');
INSERT INTO `travel` VALUES ('9', '8', '嘉磊', '15208319838', '1523980800', '1524585600', '东莞', 'uploads/travel/20180403\\bb303bc6f7984e05c1d12b74780bf504.jpg', '<p>水电费<br/></p>', '1', '1524289237', '4');

-- ----------------------------
-- Table structure for `user`
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `password` varchar(32) NOT NULL,
  `email` varchar(255) NOT NULL,
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  `role` tinyint(2) unsigned DEFAULT NULL,
  `status` int(2) unsigned DEFAULT NULL,
  `login_time` int(11) unsigned DEFAULT NULL,
  `login_count` int(11) unsigned DEFAULT NULL,
  `des` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES ('1', 'admin', '21232f297a57a5a743894a0e4a801fc3', 'admin@admin.com', '1514383215', '1517891753', '1', '1', '1524288870', '125', 'admin');
INSERT INTO `user` VALUES ('2', 'lee', 'b0f8b49f22c718e9924f5b1165111a67', 'lee@admin.com', '1514441519', '1514960626', '1', '1', '1515299986', '11', null);
INSERT INTO `user` VALUES ('13', 'lsy', 'b3bf78bda035fb8803f101a1eb19026a', 'lsy@admin.com', '1515853044', '1515853420', '1', '1', '1519549514', '3', 'lsy');
INSERT INTO `user` VALUES ('15', '李嘉磊', '1c083f3db92b4ab77c96a897d0d6afb3', 'lijialei@admin.com', '1520660260', '1520661195', '1', '0', null, null, 'lijialei');

-- ----------------------------
-- Table structure for `user_role`
-- ----------------------------
DROP TABLE IF EXISTS `user_role`;
CREATE TABLE `user_role` (
  `delArticle` int(2) DEFAULT '1',
  `delComment` int(2) DEFAULT '1',
  `delPhoto` int(2) DEFAULT NULL,
  `admin_id` int(11) NOT NULL,
  `admin_name` varchar(50) NOT NULL DEFAULT '1',
  `admin_role` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`admin_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user_role
-- ----------------------------
INSERT INTO `user_role` VALUES ('1', '1', '1', '1', 'admin', '1');
INSERT INTO `user_role` VALUES ('1', '1', '1', '2', 'lee', '1');
INSERT INTO `user_role` VALUES ('1', '1', '1', '15', '李嘉磊', '1');
INSERT INTO `user_role` VALUES ('1', '1', '1', '13', 'lsy', '1');

-- ----------------------------
-- Table structure for `vist`
-- ----------------------------
DROP TABLE IF EXISTS `vist`;
CREATE TABLE `vist` (
  `vist_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `vist_name` varchar(50) NOT NULL,
  `vist_email` varchar(255) NOT NULL,
  `vist_password` varchar(32) NOT NULL,
  `vist_creat_time` int(11) DEFAULT NULL,
  `vist_role` int(2) NOT NULL DEFAULT '0',
  `vist_status` int(2) DEFAULT '1',
  `vist_login_time` int(11) DEFAULT NULL,
  `vist_login_count` int(11) DEFAULT '0',
  `vist_des` varchar(100) DEFAULT NULL,
  `vist_age` int(11) DEFAULT '0',
  PRIMARY KEY (`vist_id`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of vist
-- ----------------------------
INSERT INTO `vist` VALUES ('8', '嘉磊', '123@qq.com', '202cb962ac59075b964b07152d234b70', '1515394652', '1', '1', '1524288835', '41', '123', '23');
INSERT INTO `vist` VALUES ('9', 'LEE', 'LEE@LEE.com', 'a14a336dba542a604ab19aa9c04585c9', '1515468834', '1', '1', '1522918406', '5', null, '0');
INSERT INTO `vist` VALUES ('11', 'lsy', 'lsy@lsy.com', '82c7a14cfb399e107bb47078f5c91fb0', '1518143977', '1', '1', '1518143990', '1', '我是lsy', '21');
INSERT INTO `vist` VALUES ('12', '管理员', 'admin@qq.com', '21232f297a57a5a743894a0e4a801fc3', '1519720308', '1', '1', '1519720796', '2', null, '0');
INSERT INTO `vist` VALUES ('20', 'Lee', 'Lee@123.com', '7b34fdbd72fdecd596f0c583dd483a0f', '1520942379', '1', '1', '1523015177', '3', 'Lee', '1');
INSERT INTO `vist` VALUES ('17', 'Cobra', 'cobra@qq.com', 'b2afdf253f6c1391022115bbca0cd8b0', '1520913570', '0', '1', '1520913581', '1', null, '0');
INSERT INTO `vist` VALUES ('21', 'zyh', 'zyh@qq.com', '9e67a24cd2b010ec0d1ca08a5723cc23', '1524291740', '1', '1', '1524291846', '1', null, '0');

-- ----------------------------
-- Table structure for `vist_role`
-- ----------------------------
DROP TABLE IF EXISTS `vist_role`;
CREATE TABLE `vist_role` (
  `vist_id` int(11) NOT NULL,
  `vist_name` varchar(50) NOT NULL,
  `vist_role` int(2) NOT NULL DEFAULT '0',
  `commentArticle` int(2) DEFAULT '1',
  `commentDel` int(2) DEFAULT '0',
  `writeArticlt` int(2) DEFAULT '1',
  `uploadPhoto` int(2) DEFAULT '1',
  PRIMARY KEY (`vist_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of vist_role
-- ----------------------------
INSERT INTO `vist_role` VALUES ('8', '嘉磊', '1', '1', '1', '1', '1');
INSERT INTO `vist_role` VALUES ('4', 'JimLee', '1', '1', '1', '1', '1');
INSERT INTO `vist_role` VALUES ('9', 'LEE', '1', '1', '1', '1', '1');
INSERT INTO `vist_role` VALUES ('12', '管理员', '1', '1', '1', '1', '1');
INSERT INTO `vist_role` VALUES ('11', 'lsy', '1', '1', '1', '1', '1');
INSERT INTO `vist_role` VALUES ('21', 'zyh', '1', '1', '1', '1', '1');
INSERT INTO `vist_role` VALUES ('20', 'Lee', '1', '1', '1', '1', '1');
INSERT INTO `vist_role` VALUES ('17', 'Cobra', '0', '1', '1', '1', '1');

-- ----------------------------
-- Table structure for `vist_travel`
-- ----------------------------
DROP TABLE IF EXISTS `vist_travel`;
CREATE TABLE `vist_travel` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `travel_id` int(11) NOT NULL,
  `vist_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of vist_travel
-- ----------------------------
INSERT INTO `vist_travel` VALUES ('1', '9', '20');
INSERT INTO `vist_travel` VALUES ('2', '9', '9');
INSERT INTO `vist_travel` VALUES ('4', '8', '9');
INSERT INTO `vist_travel` VALUES ('5', '8', '20');
INSERT INTO `vist_travel` VALUES ('6', '9', '0');
INSERT INTO `vist_travel` VALUES ('7', '8', '0');
INSERT INTO `vist_travel` VALUES ('8', '4', '0');
INSERT INTO `vist_travel` VALUES ('9', '7', '0');
INSERT INTO `vist_travel` VALUES ('10', '4', '8');
INSERT INTO `vist_travel` VALUES ('11', '9', '21');
