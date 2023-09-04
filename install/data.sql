SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";
DROP TABLE IF EXISTS `wp_guestbook`;
CREATE TABLE `wp_guestbook` (
  `id` int(10) UNSIGNED NOT NULL,
  `uid` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `name` varchar(60) NOT NULL DEFAULT '',
  `qq` varchar(16) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL DEFAULT '',
  `mobile` char(11) NOT NULL DEFAULT '',
  `url` varchar(200) NOT NULL DEFAULT '',
  `ip` int(11) NOT NULL DEFAULT '0',
  `address` varchar(50) NOT NULL DEFAULT '',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '标题',
  `msg` varchar(255) NOT NULL DEFAULT '' COMMENT '内容',
  `re_uid` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `re_name` varchar(60) NOT NULL DEFAULT '',
  `re_msg` varchar(255) NOT NULL DEFAULT '',
  `re_time` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `likes` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `is_top` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='留言表';
INSERT INTO `wp_guestbook` (`id`, `uid`, `name`, `qq`, `email`, `mobile`, `url`, `ip`, `address`, `title`, `msg`, `re_uid`, `re_name`, `re_msg`, `re_time`, `likes`, `is_top`, `create_time`, `status`) VALUES
(1, 0, '无念', '24203741', '', '', '', 2130706433, '', '一鱼留言本 v3.0', '一鱼留言本v3.0发布了！ 交流QQ群：325825297 后台-测试用户：test 密码：123456', 1, '无念', '回复测试', 1693708528, 0, 0, 1693621640, 1);
DROP TABLE IF EXISTS `wp_likevote`;
CREATE TABLE `wp_likevote` (
  `id` int(11) UNSIGNED NOT NULL,
  `gid` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `ip` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='点赞表';
DROP TABLE IF EXISTS `wp_single`;
CREATE TABLE `wp_single` (
  `id` mediumint(8) UNSIGNED NOT NULL,
  `pageid` varchar(16) NOT NULL DEFAULT '',
  `name` varchar(100) NOT NULL,
  `thumb` varchar(200) NOT NULL DEFAULT '',
  `title` varchar(100) NOT NULL,
  `content` mediumtext NOT NULL,
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='单页表';
INSERT INTO `wp_single` (`id`, `pageid`, `name`, `thumb`, `title`, `content`, `status`, `update_time`) VALUES
(1, 'about', '关于', '', '关于', '<p>\r\n    <b><font color=\"#1b6d85\">\r\n一鱼留言本 v3.0</font></b>\r\n</p>\r\n<p>\r\n    <font color=\"#1b6d85\">\r\n一鱼留言本是一个用willphp框架开发的简单留言示例系统。</font>\r\n</p>\r\n<p>\r\n    <font color=\"#cc0000\">\r\n特色：多主题(自由开发)，自适应，安全过滤html\r\n</font>\r\n</p>\r\n<p>\r\n    技术构架：\r\n</p>\r\n<ul>\r\n    <li>PHP框架：WillPHP v4.5.2 (php7.4.3~8.2.x)</li>\r\n    <li>前后台UI：Bootstrap  + Jquery</li>\r\n    <li>弹窗组件：la<x>yer mobile</x></li>    \r\n    <li>富文本编辑器：HandyEditor</li>\r\n</ul>\r\n<p>\r\n    前台功能：\r\n</p>\r\n<ul>\r\n    <li>游客发布留言，点赞留言，查看留言会员信息。</li>\r\n    <li>会员注册，登录，修改资料，修改密码。发布留言，删除留言。</li>\r\n    <li>管理员回复，置顶，删除留言。</li></ul>\r\n<p>\r\n    后台功能：\r\n</p>\r\n<ul>\r\n    <li>留言管理：查看，回复，启用，停用，删除留言。</li>\r\n    <li>单页管理：设置留言公告，关于页面(HTML内容)等。</li>\r\n    <li>用户管理：启用，停用，添加，删除，修改用户。</li>\r\n    <li>网站配置：添加，修改，删除配置，生成配置文件。</li>\r\n    <li>数据管理：清空数据，清除缓存。</li></ul>\r\n', 1, 1693622760),
(2, 'notice', '公告', '', '公告', '<b><font color=\"#31859b\">一鱼留言本v3.0发布了！ 交流QQ群：325825297 后台-测试用户：test 密码：123456</font></b>                ', 1, 1693622694);
DROP TABLE IF EXISTS `wp_site`;
CREATE TABLE `wp_site` (
  `id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `name` char(15) NOT NULL,
  `value` varchar(250) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='配置表';
INSERT INTO `wp_site` (`id`, `title`, `name`, `value`) VALUES
(1, '网站标题', 'site_title', '一鱼留言本v3.0'),
(2, '关键词', 'site_kw', '一鱼留言本,willphp,php框架,PHP留言本'),
(3, '网站描述', 'site_desc', '一个用willphp框架开发的留言本'),
(5, '首页h1', 'site_h1', '一鱼留言本'),
(6, '首页h2', 'site_h2', '一个用willphp框架开发的留言本'),
(7, 'logo文字', 'site_logo', 'willphp'),
(8, '留言说明', 'gbook_about', '文明上网，理性发言！填写QQ将自动获取QQ头像！'),
(10, '默认主题', 'theme', 'default'),
(11, '主题设置', 'theme_set', 'default=默认|demo=演示');
DROP TABLE IF EXISTS `wp_user`;
CREATE TABLE `wp_user` (
  `id` int(11) UNSIGNED NOT NULL,
  `username` varchar(60) NOT NULL DEFAULT ' ',
  `password` varchar(64) NOT NULL DEFAULT ' ',
  `nickname` varchar(60) NOT NULL DEFAULT ' ',
  `about` varchar(120) NOT NULL DEFAULT '',
  `avatar` varchar(100) NOT NULL DEFAULT '',
  `mobile` char(11) NOT NULL DEFAULT '',
  `qq` varchar(20) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL DEFAULT ' ',
  `level` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `is_admin` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户表';
INSERT INTO `wp_user` (`id`, `username`, `password`, `nickname`, `about`, `avatar`, `mobile`, `qq`, `email`, `level`, `is_admin`, `status`, `create_time`) VALUES
(1, 'admin', 'c7122a1349c22cb3c009da3613d242ab', '无念', '管理员呀', '', '', '24203741', '', 3, 1, 1, 1626854240),
(2, 'test', 'b18654785eea30528da6be8a50185428', '测试', '', '', '', '', '', 3, 0, 1, 1693788770);
ALTER TABLE `wp_guestbook` ADD PRIMARY KEY (`id`);
ALTER TABLE `wp_likevote` ADD PRIMARY KEY (`id`);
ALTER TABLE `wp_single` ADD PRIMARY KEY (`id`);
ALTER TABLE `wp_site` ADD PRIMARY KEY (`id`);
ALTER TABLE `wp_user` ADD PRIMARY KEY (`id`);
ALTER TABLE `wp_guestbook` MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
ALTER TABLE `wp_likevote` MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
ALTER TABLE `wp_single` MODIFY `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
ALTER TABLE `wp_site` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
ALTER TABLE `wp_user` MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;