<?php
if(!file_exists(dirname(__FILE__).'../protected/config/db.php')){
	//没有配置文件则进行安装
	header('Location:index.php');
	exit();
}else{
	header("Location: ../index.php");
}
?>
