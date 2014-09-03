<?php
/**
 * 配置
 * id,name,type,version 必须且不能为空
 */
return array(
//插件ID 小写字符串 不能重复
'id' => 'user',
//插件名称 显示用
'name' => ' 用户管理',
//插件类型 ADMIN|API
'type' => 'ADMIN',
//开发者
'author' => '徐宝国',
//邮箱
'email'  => 'xubaoguo@luxtonenet.com',
//版本
'version' => '0.1',
//依赖插件
'dependencies' => '',
//小于255个字符
'description' => '用户管理',
//菜单
'menus' => array(
	//主菜单
	'MAINMENU' => array(
	        'icon' => 'fa-eye',
			'cfg_value' => '/plugin/user/index',
			'cfg_pid'   => 0,
			'cfg_comment' => '用户管理',
			'cfg_order' => 6,
			//嵌套子菜单
			'SUBMENU'=>array(
				array(
					'cfg_value' => '/plugin/user/index',
					'cfg_comment' => '用户列表',
					'cfg_order' => 1,
				),
				array(
					'cfg_value' => '/plugin/user/hot',
					'cfg_comment' => '活跃用户',
					'cfg_order' => 2,
				),
				array(
					'cfg_value' => '/plugin/user/avatar',
					'cfg_comment' => '用户头像',
					'cfg_order' => 3,
				),
				
			),
		),
),
//sql
'execsql' => array(
		"DROP TABLE IF EXISTS `pt_user_avatar`;
		CREATE TABLE `pt_user_avatar` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `url` varchar(255) DEFAULT NULL   COMMENT '图片url',
		  `imgId` int(11) DEFAULT NULL COMMENT '图片id',
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户头像表';",
		"DROP TABLE IF EXISTS `pt_user_hot`;
		CREATE TABLE `pt_user_hot` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `uid` int(11) DEFAULT NULL COMMENT '用户uid',
		  `orderid` int(11) DEFAULT '500' COMMENT '顺序',
		  `ctime` int(10) DEFAULT NULL COMMENT '创建时间',
		  `status` tinyint(1) DEFAULT '1' NULL COMMENT 'status',
		  PRIMARY KEY (`id`),
		  KEY `idx_uid` (`uid`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='活跃用户表';"
	)
);
