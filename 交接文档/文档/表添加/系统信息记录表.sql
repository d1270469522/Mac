CREATE TABLE IF NOT EXISTS `system_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sign_name` varchar(50) NOT NULL COMMENT '标记名称',
  `josn_str` text  COMMENT 'JSON log 数据',
  `remark` varchar(40) DEFAULT NULL COMMENT '简介',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态：0普通信息，1错误信息',
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='系统信息记录表';


