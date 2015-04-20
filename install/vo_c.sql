
--
-- 表的结构 `pt_action`
--

DROP TABLE IF EXISTS `pt_action`;
CREATE TABLE IF NOT EXISTS `pt_action` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `action` bigint(3) DEFAULT NULL COMMENT '1:add 2:update 3:del',
  `table_name` varchar(50) DEFAULT NULL,
  `vid` int(11) DEFAULT NULL,
  `is_del` tinyint(1) DEFAULT '0' COMMENT '0:否  1：删除',
  `user_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ac_vid` (`vid`),
  KEY `ac_action` (`action`),
  KEY `ac_table_name` (`table_name`),
  KEY `idx_is_del` (`is_del`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=841255 ;



-- --------------------------------------------------------

--
-- 表的结构 `pt_admin`
--

DROP TABLE IF EXISTS `pt_admin`;
CREATE TABLE IF NOT EXISTS `pt_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL COMMENT '用户名',
  `password` varchar(255) NOT NULL COMMENT '密码',
  `is_super_admin` int(1) NOT NULL DEFAULT '0' COMMENT '是否为超级管理员',
  `encrypt` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `username` (`username`),
  KEY `password` (`password`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=69 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_admin_act`
--

DROP TABLE IF EXISTS `pt_admin_act`;
CREATE TABLE IF NOT EXISTS `pt_admin_act` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `vid` int(11) unsigned NOT NULL,
  `action` smallint(1) unsigned NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `date_time` varchar(255) NOT NULL,
  `time` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=647335 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_admin_profiles`
--

DROP TABLE IF EXISTS `pt_admin_profiles`;
CREATE TABLE IF NOT EXISTS `pt_admin_profiles` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `real_name` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=37 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_admin_profiles_fields`
--

DROP TABLE IF EXISTS `pt_admin_profiles_fields`;
CREATE TABLE IF NOT EXISTS `pt_admin_profiles_fields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `varname` varchar(50) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '',
  `field_type` varchar(50) NOT NULL DEFAULT '',
  `field_size` int(3) NOT NULL DEFAULT '0',
  `field_size_min` int(3) NOT NULL DEFAULT '0',
  `required` int(1) NOT NULL DEFAULT '0',
  `match` varchar(255) NOT NULL DEFAULT '',
  `range` varchar(255) NOT NULL DEFAULT '',
  `error_message` varchar(255) NOT NULL DEFAULT '',
  `other_validator` text,
  `default` varchar(255) NOT NULL DEFAULT '',
  `widget` varchar(255) NOT NULL DEFAULT '',
  `widgetparams` text,
  `position` int(3) NOT NULL DEFAULT '0',
  `visible` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_admin_time`
--

DROP TABLE IF EXISTS `pt_admin_time`;
CREATE TABLE IF NOT EXISTS `pt_admin_time` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL COMMENT '用户id',
  `user_name` varchar(255) NOT NULL COMMENT '用户名',
  `utime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '登录时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4705 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_admin_users`
--

DROP TABLE IF EXISTS `pt_admin_users`;
CREATE TABLE IF NOT EXISTS `pt_admin_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL DEFAULT '',
  `password` varchar(128) NOT NULL DEFAULT '',
  `email` varchar(128) NOT NULL DEFAULT '',
  `activkey` varchar(128) NOT NULL DEFAULT '',
  `superuser` int(1) NOT NULL DEFAULT '0',
  `status` int(1) NOT NULL DEFAULT '0',
  `create_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastvisit_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_username` (`username`),
  UNIQUE KEY `user_email` (`email`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=37 ;



-- --------------------------------------------------------

--
-- 表的结构 `pt_s_config`
--

DROP TABLE IF EXISTS `pt_s_config`;
CREATE TABLE IF NOT EXISTS `pt_s_config` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `cfg_name` varchar(128) NOT NULL DEFAULT '' COMMENT '配置名称',
  `cfg_value` varchar(255) NOT NULL DEFAULT '' COMMENT '配置值',
  `cfg_order` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `cfg_pid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '父id',
  `ctime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `cfg_type` set('SYSTEM','USER') NOT NULL DEFAULT 'USER' COMMENT 'SYSTEM:系统配置,USER:用户配置',
  `cfg_comment` varchar(255) DEFAULT NULL COMMENT '配置说明',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=544 ;


--
-- 表的结构 `tbl_migration`
--

DROP TABLE IF EXISTS `tbl_migration`;
CREATE TABLE IF NOT EXISTS `tbl_migration` (
  `version` varchar(255) NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------
--
-- 表的结构 `AuthItem`
--
CREATE TABLE IF NOT EXISTS `AuthItem` (
  `name` varchar(64) NOT NULL,
  `type` int(11) NOT NULL,
  `description` text,
  `bizrule` text,
  `data` text,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------
--
-- 表的结构 `AuthAssignment`
--
CREATE TABLE IF NOT EXISTS `AuthAssignment` (
  `itemname` varchar(64) NOT NULL,
  `userid` varchar(64) NOT NULL,
  `bizrule` text,
  `data` text,
  PRIMARY KEY (`itemname`,`userid`),
  CONSTRAINT `AuthAssignment_ibfk_1` FOREIGN KEY (`itemname`) REFERENCES `AuthItem` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------
--
-- 表的结构 `AuthItemChild`
--
CREATE TABLE IF NOT EXISTS `AuthItemChild` (
  `parent` varchar(64) NOT NULL,
  `child` varchar(64) NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`),
  CONSTRAINT `AuthItemChild_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `AuthItem` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `AuthItemChild_ibfk_2` FOREIGN KEY (`child`) REFERENCES `AuthItem` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
-- --------------------------------------------------------

--
-- 表的结构 `pre_projects_members`
--
CREATE TABLE `pre_projects_members` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniqe` (`pid`,`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=83 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `pre_repositories`
--
CREATE TABLE `pre_repositories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `description` text NOT NULL,
  `root_path` varchar(3841) NOT NULL COMMENT 'The root path of repositorie',
  `apache_group_file` varchar(4096) NOT NULL,
  `apache_user_file` varchar(4096) NOT NULL,
  `ip` varchar(255) NOT NULL,
  `ssh_port` smallint(5) unsigned NOT NULL,
  `apache_bin` varchar(4096) NOT NULL,
  `url_port` smallint(5) unsigned NOT NULL,
  `url_schema` varchar(45) NOT NULL,
  `htpasswd_bin` varchar(4096) NOT NULL,
  `git_config_path` varchar(45) NOT NULL,
  `url_host` varchar(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `pre_projects`
--
CREATE TABLE `pre_projects` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `description` text NOT NULL,
  `uid` int(11) NOT NULL,
  `ctime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `remote_url` varchar(255) NOT NULL,
  `domain` varchar(45) NOT NULL,
  `status` varchar(45) DEFAULT NULL,
  `type` varchar(45) NOT NULL,
  `root` varchar(4096) NOT NULL,
  `index` varchar(255) NOT NULL DEFAULT 'index.php',
  `repository` varchar(45) NOT NULL DEFAULT 'local' COMMENT '版本库类型:local, github',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=213 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------
--
-- 表的结构 `pre_configure`
--
CREATE TABLE `pre_configure` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `value` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------
