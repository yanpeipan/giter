<?php

define('LUXTONE_DBHOST_WRITE', '127.0.0.1');	//  数据库主机 主库
define('LUXTONE_DBHOST_READ', '127.0.0.1');	//  数据库主机 从库
define('LUXTONE_DBUSER', 'root');			//  数据库用户名
define('LUXTONE_DBPW', '');		//  数据库密码
define('LUXTONE_DBNAME', 'vo');		    // 数据库名称


define('LUXTONE_PUTI_DBHOST', 'dbserver_puti_write');	//puti 数据库host
define('LUXTONE_PUTI_DBNAME', '16tree_puti');		    // 数据库名称
define('LUXTONE_PUTI_DBUSER', 'dbuser_puti');			//  数据库用户名
define('LUXTONE_PUTI_DBPW', 'putipunicaG7XH1842');		//  数据库密码


define('LUXTONE_USER_DBHOST', 'dbserver_user_write');	//puti 数据库host
define('LUXTONE_USER_DBNAME', '16tree_user');		    // 数据库名称
define('LUXTONE_USER_DBUSER', 'dbuser_user');			//  数据库用户名
define('LUXTONE_USER_DBPW', 'userpunicaG7XH1842');		//  数据库密码

define('LUXTONE_DBCHARSET', 'utf8');				//数据库字符集
define('LUXTONE_DBTABLEPRE', 'pre_');			    //数据库表前缀

//通信相关
define('LUXTONE_KEY', '123456789');				// 与  的通信密钥, 要与 UCenter 保持一致
define('LUXTONE_API', 'http://i.tuziv.tv/_u');	//  的 URL 地址, 在调用头像时依赖此常量
define('LUXTONE_CHARSET', 'utf8');				//  的字符集
define('LUXTONE_APPID', 1);					// 当前应用的 ID
define('LUXTONE_BOSSURL', 'boss.tuziv.com');		    //数据库表前缀

define('UC_DBCONNECT', 0);

//同步登录 Cookie 设置
$cookiedomain = '.tuziv.tv'; 			// cookie 作用域
$cookiepath = '/';			// cookie 作用路径
$cookiepre = '';
$timestamp = time();



?>
