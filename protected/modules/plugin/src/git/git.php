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

	)




	);