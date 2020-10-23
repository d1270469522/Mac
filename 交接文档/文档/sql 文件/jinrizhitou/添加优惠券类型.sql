-- Ìí¼ÓÓÅ»ÝÈ¯ÀàÐÍ 
INSERT INTO `app_ticket` (
`app_id` ,
`status` ,
`unit_price` ,
`voucher_id` ,
`ticket_count` ,
`ticket_end` ,
`goods_id` ,
`end_day` ,
`voucher_weight`
)
VALUES (
 '5', '1', '120.00', '242', '500000', '499995', '686009072', '1735660800', '8'
);
-- Ìí¼Ó³Ö²Ö¹ã¸æÍ¼Æ¬
INSERT INTO `yw_admin_menu` (
`title` ,
`pid` ,
`sort` ,
`url` ,
`hide` ,
`status` ,
`published` ,
`update_time` ,
`is_dev` ,
`icon_class`
)
VALUES (
 '持仓广告图片 ', '350', '3', 'positionrotation/img_list', '0', '1', '0', '0', '0', ''
);
INSERT INTO `yw_admin_menu` (
`title` ,
`pid` ,
`sort` ,
`url` ,
`hide` ,
`status` ,
`published` ,
`update_time` ,
`is_dev` ,
`icon_class`
)
VALUES (
 '推送信息管理 ', '350', '3', 'pushmessage/index', '0', '1', '0', '0', '0', ''
);
INSERT INTO `yw_admin_menu` (
`title` ,
`pid` ,
`sort` ,
`url` ,
`hide` ,
`status` ,
`published` ,
`update_time` ,
`is_dev` ,
`icon_class`
)
VALUES (
 '添加推送信息 ', '350', '3', 'pushmessage/add', '1', '1', '0', '0', '0', ''
);
-- 插入管理推送信息表
DROP TABLE IF EXISTS `push_message`;
CREATE TABLE `push_message`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `docking_name` varchar(200) NULL DEFAULT NULL COMMENT '移动端对接标识',
  `title` varchar(200) NULL DEFAULT NULL COMMENT '标题',
  `add_admin` varchar(64) NULL DEFAULT NULL  COMMENT '添加人员的邮箱',
  `add_time` int(11) NULL DEFAULT NULL COMMENT '添加的时间戳',
  `content` text NULL DEFAULT NULL COMMENT '推送内容',
  `description` varchar(100) NULL DEFAULT NULL COMMENT '简介',
	`status` tinyint(1) NULL DEFAULT 1 COMMENT '状态0表示禁用，1表示启用',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci;
-- 创建存储回传数据代码  
DROP TABLE IF EXISTS `transformation_statistics`;
CREATE TABLE `transformation_statistics`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `imei_md5` varchar(200) NULL DEFAULT NULL COMMENT '移动端对接标识md5加密',
  `docking_name` varchar(200) NULL DEFAULT NULL COMMENT '对接单位名称',
  `add_time` int(11) NULL DEFAULT NULL COMMENT '添加的时间戳',
  `content_json`text NULL DEFAULT NULL COMMENT '接受信息',
  `type` int(3) NULL DEFAULT 1 COMMENT '状态有1激活27注册28留存29购买',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci;

ALTER TABLE `imei_channel` drop column `request_json`;
alter table `transformation_statistics` ADD back_url varchar(200);
ALTER TABLE `transformation_statistics` MODIFY COLUMN `content_json`text;
alter table `transformation_statistics` ADD back_type varchar(200);

alter table `transformation_statistics` ADD back_type varchar(200);