SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";
DROP TABLE IF EXISTS `ap_config`;
CREATE TABLE `ap_config` (
  `id` int(11) UNSIGNED NOT NULL COMMENT 'ID',
  `name` varchar(20) NOT NULL DEFAULT '' COMMENT '名称',
  `config_key` varchar(20) NOT NULL DEFAULT '' COMMENT '键名',
  `config_value` varchar(255) NOT NULL DEFAULT '' COMMENT '键值',
  `config_type` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '类型',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '状态'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='配置表';
INSERT INTO `ap_config` (`id`, `name`, `config_key`, `config_value`, `config_type`, `status`) VALUES
(1, '网站标题', 'site_title', '一鱼留言本v3.2', 0, 1),
(2, '关键词', 'site_kw', '一鱼留言本,aphp,php框架,PHP留言本', 0, 1),
(3, '网站描述', 'site_desc', '一个用aphp框架开发的留言本', 0, 1),
(4, '首页h1', 'site_h1', '一鱼留言本', 0, 1),
(5, '首页h2', 'site_h2', '一个用aphp框架开发的留言本', 0, 1),
(6, 'logo文字', 'site_logo', 'aphp框架', 0, 1),
(7, '留言说明', 'msg_about', '文明上网，理性发言！填写QQ将自动获取QQ头像！', 0, 1),
(8, '默认主题', 'theme', 'default', 0, 1),
(9, '审核开启', 'msg_audit', '0', 0, 1),
(10, '主题列表', 'theme_list', 'default=默认|demo=演示', 0, 1);
DROP TABLE IF EXISTS `ap_guestbook`;
CREATE TABLE `ap_guestbook` (
  `id` int(11) UNSIGNED NOT NULL COMMENT 'ID',
  `uid` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '用户ID',
  `nickname` varchar(20) NOT NULL DEFAULT '' COMMENT '昵称',
  `qq` varchar(20) NOT NULL DEFAULT '' COMMENT 'QQ号',
  `ip` bigint(20) NOT NULL DEFAULT '0' COMMENT 'IP',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '标题',
  `msg` varchar(255) NOT NULL DEFAULT '' COMMENT '留言内容',
  `reply_uid` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '回复UID',
  `reply_nickname` varchar(20) NOT NULL DEFAULT '' COMMENT '回复昵称',
  `reply_msg` varchar(255) NOT NULL DEFAULT '' COMMENT '回复内容',
  `reply_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '回复时间',
  `poll_count` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '点赞数',
  `is_top` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否置顶',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '状态'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='留言表';
INSERT INTO `ap_guestbook` (`id`, `uid`, `nickname`, `qq`, `ip`, `title`, `msg`, `reply_uid`, `reply_nickname`, `reply_msg`, `reply_time`, `poll_count`, `is_top`, `create_time`, `status`) VALUES
(10, 1, '无念', '24203741', 3029187426, '一鱼留言本v3.2', '一鱼留言本v3.2发布了！ 交流QQ群：325825297', 1, '无念', '好的', 1713428498, 0, 1, 1713428418, 1),
(11, 1, '无念', '24203741', 3029187426, '更新', '不能有相同的标题', 0, '', '', 0, 0, 0, 1713428628, 1);
DROP TABLE IF EXISTS `ap_guestbook_poll`;
CREATE TABLE `ap_guestbook_poll` (
  `id` int(11) UNSIGNED NOT NULL COMMENT 'ID',
  `guestbook_id` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '留言ID',
  `poll_ip` bigint(20) NOT NULL DEFAULT '0' COMMENT '点赞IP',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '状态'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='留言点赞表';
DROP TABLE IF EXISTS `ap_single`;
CREATE TABLE `ap_single` (
  `id` int(11) UNSIGNED NOT NULL COMMENT 'ID',
  `sign` varchar(16) NOT NULL DEFAULT '' COMMENT '标识',
  `name` varchar(20) NOT NULL DEFAULT '' COMMENT '名称',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '标题',
  `summary` varchar(255) NOT NULL DEFAULT '' COMMENT '摘要',
  `thumb` varchar(255) NOT NULL DEFAULT '' COMMENT '封面图',
  `content` text NOT NULL COMMENT '内容',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '状态'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='单页表';
INSERT INTO `ap_single` (`id`, `sign`, `name`, `title`, `summary`, `thumb`, `content`, `update_time`, `status`) VALUES
(1, 'about', '关于', '关于', 'About', '', '<p>在后台=》单页管理=》关于(about)里修改</p><img src=\"/uploads/2024/0417/11861713363097.png\"><br>', 1713410340, 1),
(2, 'notice', '公告', '公告', 'Notice', '', '<b><font color=\"#337ab7\">\r\n</font><font color=\"#333333\">\r\n公告：</font><font color=\"#337ab7\">\r\n一鱼留言本v3.2发布了！ 交流QQ群：325825297 \r\n</font></b>', 1713423180, 1),
(3, 'test', '测试', '测试', 'Test', '', '测试内容', 1713422805, 1);
DROP TABLE IF EXISTS `ap_user`;
CREATE TABLE `ap_user` (
  `id` int(11) UNSIGNED NOT NULL COMMENT 'ID',
  `username` varchar(20) NOT NULL DEFAULT '' COMMENT '用户',
  `password` varchar(32) NOT NULL DEFAULT '' COMMENT '密码',
  `nickname` varchar(20) NOT NULL DEFAULT '' COMMENT '昵称',
  `email` varchar(100) NOT NULL DEFAULT '' COMMENT '邮箱',
  `mobile` char(11) NOT NULL DEFAULT '' COMMENT '手机号',
  `qq` varchar(20) NOT NULL DEFAULT '' COMMENT 'QQ号',
  `bio` varchar(120) NOT NULL DEFAULT '' COMMENT '格言',
  `avatar` varchar(100) NOT NULL DEFAULT '' COMMENT '头像',
  `level` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '等级',
  `money` decimal(10,2) UNSIGNED NOT NULL DEFAULT '0.00' COMMENT '余额',
  `score` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '积分',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '状态'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户表';
INSERT INTO `ap_user` (`id`, `username`, `password`, `nickname`, `email`, `mobile`, `qq`, `bio`, `avatar`, `level`, `money`, `score`, `create_time`, `status`) VALUES
(1, 'admin', 'a24676a269f6bfa53827470c4ea8cf24', '无念', '24203741@qq.com', '', '24203741', 'PHP是最好的语言', '', 3, '0.00', 0, 1711961328, 1);
ALTER TABLE `ap_config`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `ap_guestbook`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `ap_guestbook_poll`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `ap_single`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `ap_user`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `ap_config`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=11;
ALTER TABLE `ap_guestbook`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=12;
ALTER TABLE `ap_guestbook_poll`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=6;
ALTER TABLE `ap_single`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=4;
ALTER TABLE `ap_user`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=3;