<?php

	header("Content-type: text/html; charset=utf-8"); 
	error_reporting(0);

	 
	@set_time_limit(0);

	require('Base.php');
	$base=new Base();
	
	if(isset($_GET['action'])){
		
		$action=$_GET['action'];
		switch($action){
			case 'test_db':
				$base->test_db();
			break;
			case 'install':
				$base->install();
			break;
			case 'install_process';
				$base->install_process();
			break;
		}
		die();
	}else if(isset($_GET['step'])){
		$step=$_GET['step'];
	}

	if(empty($step))
	{
	    $step = 1;
	}
	/*------------------------
	使用协议书
	------------------------*/
	if($step==1){
	   	include('./tpl/head.html');
		include('./install-1.html');
		//include('./tpl/foot.html');
		exit();
	}
	/*------------------------
	环境测试
	------------------------*/
	else if($step==2){
	
		include('./tpl/head.html');
		//include('./tpl/check.html');
		//include('./tpl/foot.html');
		include('./install-2.html');
		exit();
	}
	/*------------------------
	设置参数
	------------------------*/
	else if($step==3){
		include('./tpl/head.html');
		//require('./tpl/setting.html');
		//include('./tpl/foot.html');
		include('./install-3.html');
	
	}
	/************************
	安装
	************************/
	else if($step==4){
		include('./tpl/head.html');
		//include('./tpl/install.html');
		//include('./tpl/foot.html');
		include('./install-4.html');
	}else if($step==5){
		header("Location: ../index.php");
	}	
?>
