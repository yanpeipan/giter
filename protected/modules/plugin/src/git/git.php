<?php
/**
* 配置
* id,name,type,version 必须且不能为空
*/
return array(
//插件ID 小写字符串 不能重复
	'id' => 'git',
//插件名称 显示用
	'name' => 'Project Manager',
//插件类型 ADMIN|API
	'type' => 'ADMIN',
//开发者
	'author' => 'yanpeipan',
//邮箱
	'email' => 'yanpeipan_82@qq.com',
//版本
	'version' => '0.1',
//依赖插件
	'dependencies' => '',
//小于255个字符
	'description' => 'Project Manager',
//菜单
	'menus' => array(
//主菜单
		'MAINMENU' => array(
			'icon' => 'fa-heart',
			'cfg_value' => '/plugin/git/index',
			'cfg_pid' => 0,
			'cfg_comment' => 'Project Manager',
			'cfg_order' => 6,
//嵌套子菜单
			'SUBMENU'=>array(
				array(
					'cfg_value' => '/plugin/git/index',
					'cfg_comment' => 'Project List',
					'cfg_order' => 1,
					),
				array(
					'cfg_value' => '/plugin/git/manage',
					'cfg_comment' => 'Git Manager',
					'cfg_order' => 1,
					),
				),
			),
		),
//sql
	'execsql' => array(
		"CREATE TABLE `pre_projects` (
			`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			`name` varchar(45) NOT NULL,
			`uid` int(11) NOT NULL,
			`ctime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			`remote_url` varchar(255) NOT NULL,
			`domain` varchar(45) NOT NULL,
			`status` varchar(45) DEFAULT NULL,
			PRIMARY KEY (`id`),
			UNIQUE KEY `name_UNIQUE` (`name`),
			UNIQUE KEY `domain_UNIQUE` (`domain`)
			) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;",

"CREATE TABLE `pre_projects_members` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`pid` int(10) unsigned NOT NULL,
	`uid` int(10) unsigned NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE KEY `uniqe` (`pid`,`uid`)
	) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;",

"CREATE TABLE `pre_repositories` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`name` varchar(45) NOT NULL,
	`description` text NOT NULL,
	`root_path` varchar(3841) NOT NULL COMMENT 'The root path of repositorie',
	`apache_group_file` varchar(4096) NOT NULL,
	`apache_user_file` varchar(4096) NOT NULL,
	`ip` int(10) unsigned NOT NULL,
	`ssh_port` smallint(5) unsigned NOT NULL,
	`apache_bin` varchar(4096) NOT NULL,
	`url_port` smallint(5) unsigned NOT NULL,
	`htpasswd_bin` varchar(4096) NOT NULL,
	`git_config_path` varchar(45) NOT NULL,
	PRIMARY KEY (`id`)
	) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;",

"CREATE TABLE `pre_projects` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`name` varchar(45) NOT NULL,
	`uid` int(11) NOT NULL,
	`ctime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	`remote_url` varchar(255) NOT NULL,
	`domain` varchar(45) NOT NULL,
	`status` varchar(45) DEFAULT NULL,
	PRIMARY KEY (`id`),
	UNIQUE KEY `name_UNIQUE` (`name`),
	UNIQUE KEY `domain_UNIQUE` (`domain`)
	) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;"

)




);