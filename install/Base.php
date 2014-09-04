<?php
$filename = dirname(dirname(__FILE__)) . '/protected/components/Mcrypter.php';
if (is_file ($filename)) {
	require_once $filename;
}
Class Base{
	private $config=array(
		'iniget'=>array(
				//键名随意 方便调用为上
			'mysql'=>array(
					'item'=>'数据库支持情况',		//要检验的项
					'keywords'=>'mysql_connect',		//要拿到ini_get去检验的相关函数名
					'deny'=>'0',				//规定不能通过的结果检验结果  如果不设置 deny项那么就直接返回值
					'message'=>'不支持数据库联结',		//不能通过的前提下的提示信息
					),
			'safe_mode'=>array(
				'item'=>'安全模式检验',
				'keywords'=>'safe_mode',
				'deny'=>'1',
				'message'=>'本程序不支持在安全模式下运行',
				),
			'allow_url_fopen'=>array(
				'item'=>'allow_url_fopen',
				'keywords'=>'allow_url_fopen',
				'deny'=>'0',
				'message'=>'不支持远程获取文件',
				),
			'max_execution_time'=>array(
				'item'=>'最大执行时间',
				'keywords'=>'max_execution_time',
				),
			'test'=>array(
				'item'=>'测试',
				'keywords'=>'test',
				'deny'=>'0',
				'message'=>'此函数应该是不存在的',
				),


			),
		'dir'=>array(
			'','install','/protected/config','/protected/runtime','/assets'

			),

		);
		//获取php版本
	public function getPhpVer(){
		return phpversion();		
	}
	public function getOsInfo(){
		return PHP_OS;
	}


		//获取服务器运行环境 编译引擎
	public function getSever(){
		return $_SERVER['SERVER_SOFTWARE'];
	}
		//获取域名
	public function getDomain(){
		return $_SERVER['SERVER_NAME'];
	}

		//获取gd库版本  不知道要不要用,通常是需要的
	public function getGDVer(){
			//没启用php.ini函数的情况下如果有GD默认视作2.0以上版本
		if(!function_exists('phpinfo')){
			if(function_exists('imagecreate')) return '2.0';
			else return 0;
		}
		else{
			ob_start();
			phpinfo(8);
			$module_info = ob_get_contents();
			ob_end_clean();
			if(preg_match("/\bgd\s+version\b[^\d\n\r]+?([\d\.]+)/i", $module_info,$matches)){
				$gdversion_h = $matches[1];  
			}else{
				$gdversion_h = 0; 
			}
			return $gdversion_h;
		}
	}



		//获取整体检测情况  
	public function getAllInfo(){			
		$iniget=array();
		foreach($this->config['iniget'] as $key=>$val){				
			$tmp=array(
				'item'=>$val['item'],
				'status'=>1,
				);	
			if(isset($val['deny']) && !$val['deny']){
				$tmp['allow']='开启';
			}else if(isset($val['deny']) && $val['deny']){
				$tmp['allow']='关闭';
			}else{
				$tmp['allow']='';
			}		
			$result_iniset=ini_get($val['keywords']);

				//返回结果非布尔类型 
			if(!$result_iniset && isset($val['deny'])){
				$result_iniset=function_exists($val['keywords']);
			}	
			$tmp['result']='关闭';
			if($result_iniset){
				$tmp['result']='开启';
			}		
			if(isset($val['deny']) && $result_iniset!=$val['deny']){
				$tmp['message']='支持';
			}else if(isset($val['deny']) && $result_iniset==$val['deny']){
				$tmp['message']='<font color="#F00">'.$val['message'].'</font>';
				$tmp['status']=0;
			}else{
				$tmp['message']=$result_iniset;
				unset($tmp['result']);
			}
			$iniget[$key]=$tmp;
		}


		return $iniget;

	}
		//获取全部路径权限
	public function getAllDir(){
		$dir=array();
		if(!is_dir($this->getRootDir() . '/assets')) {
			mkdir($this->getRootDir() . '/assets');
		}
		if(!is_dir($this->getRootDir() . 'protected/runtime')) {
			mkdir($this->getRootDir() . '/assets');
		}
		foreach($this->config['dir'] as $val){			

			$dir[$this->getRootDir().$val]=$this->getRW($this->getRootDir().$val);
		}
		return $dir;
	}
	public function createConfig(){
		$config_name='../yjl/test/config5.php';
		$config_content='array("host"=>"192.168.1.211")';
			//file_put_contents($config_name,$config_content);
		mkdir('../yjl/test/');
			//chmod('../yjl/test/',0777);
		$file = fopen($config_name,"w");
		fwrite($file,$config_content);
		fclose($file);
	}

	public function getRootDir(){
			//反加上级目录的绝对路径
		return str_replace('install','',dirname(__FILE__));
	}
	public function getDirMod(){
		$prem=fileperms($this->getDirname());
		$prem=substr(sprintf('%o', $prem), -4);
		return $prem;
	}
		//获取得读写权限
	public function getRW($file){
		$wx=array(
			'w'=>false,
			'r'=>false,
			);
		if(is_writeable($file)){
			$wx['w']=true;
		}
		if(is_readable($file)){
			$wx['r']=true;
		}
		return $wx;
	}

	public function test_db(){
		$dbhost=$_GET['dbhost'];			
		$dbuser=$_GET['dbuser'];
		$dbpsw=$_GET['dbpsw'];

		$ajax=array(
			'status'=>0,
			'info'=>'连接数据库失败',
			);
		$conn = @mysql_connect($dbhost,$dbuser,$dbpsw) or false;
		if($conn){
			$ajax['status']=1;
			$ajax['info']='连接成功';
		}
		$db=array(
			);
		echo json_encode($ajax);
		die;
	}
	public function getConfig(){
		$config=include $this->getRootDir().'config.php';
		return $config;
	}
	public function getConnect($config=null){
		if($config===null){
			$config=$this->getConfig();
		}
		$dbhost=$config['dbhost'];			
		$dbuser=$config['dbuser'];
		$dbpsw=$config['dbpsw'];
		$dbname=$config['dbname'];
		$charset=$config['charset'];
		$conn = @mysql_connect($dbhost,$dbuser,$dbpsw) or false;

		$sql='CREATE DATABASE IF NOT EXISTS `'.$dbname.'` DEFAULT CHARACTER SET '.$charset;
		mysql_query($sql);
		$SqlCharset='SET NAMES '.$charset;
		mysql_query($SqlCharset);
		mysql_select_db($dbname,$conn);
		return $conn;

	}
	public function install(){
		$value=$_GET['value'];			
		$value=json_decode($value,true);

		$config=array();

		foreach($value as $cfg){
			$config[$cfg['key']]=$cfg['val'];
		}
		$config_must=array('dbhost','dbuser','dbpsw','dbprefix','dbname','charset','adminuser','adminpsw','webname','adminmail','baseurl');
			//检查必设值 
		foreach($config_must as $key){
			if(!isset($config[$key])){
				$config[$key]='';
			}
		}	

		$inputString="<?php\nreturn ".var_export($config,true)."?>";

		$isSetYiiDbSuccess=$this->setYiiDb($config);

		$ajax=array(
			'status'=>0,
			'info'=>'写入失败',
			);
		$file=$this->getRootDir().'config.php';
		$conn = @file_put_contents($file,$inputString);
		if($conn && $isSetYiiDbSuccess){
			$ajax['status']=1;
			$ajax['info']=$file.';'.$this->getRootDir().'/protected/config/db.php'.'写入成功';
		}
		$db=array(
			);
		echo json_encode($ajax);
		die;
	}
		//将sql数据导入数据库
	public function insertDataToDb(){
	}
		//
	public function install_process(){
		
		$config=$this->getConfig();
			//var_dump($config);
			//前缀
		$preFix='';
		if(isset($config['dbprefix']) && $config['dbprefix']!='pt_'){
			$preFix=$config['dbprefix'];
		}
		$connect=$this->getConnect();

		$charset=$config['charset'];
			//表结构
		$sql=file_get_contents('./vo_c.sql');
		$sql=str_replace('CHARSET=utf8','CHARSET='.$charset,$sql);
		$pattern='/-- 表的结构(.*)?-- --------------------------------------------------------/isU';
		preg_match_all($pattern,$sql,$ar);


		foreach($ar[0] as $tableString){
			$pattern2='/-- 表的结构 `(.*)?`/isU';
			preg_match_all($pattern2,$tableString,$ar2);
			$tableName=$ar2[1][0];
			$preTableName=$tableName;
			if($preFix){
				$preTableName=preg_replace('/^pt_/isU',$preFix,$tableName);
				$tableString=str_replace($tableName,$preTableName,$tableString);
					//$tableName=$preTableName;
			}
				//drop sql
			$sqlDrop="DROP TABLE IF EXISTS `$preTableName`";
			$r=mysql_query($sqlDrop,$connect) ;

				//创建sql
			$pattern3='/CREATE(.*)?;/isU';
			preg_match_all($pattern3,$tableString,$ar3);
			$sqlCreate=$ar3[0][0];



			$pattern3='/CREATE(.*)?;/isU';
			$r=mysql_query($sqlCreate,$connect) ;
			$info='成功';
			if($r){

			}else{
					//var_dump(mysql_error($connect));
				$info='失败';
			}
			set_time_limit(0);
			ob_end_flush();
				//echo '创建'.$tableName.' '.$info."\n";
			echo '<script> new_info("'.$preTableName.' '.$info.'"); </script>';
			flush();
			ob_flush();
			ob_clean();

		}

			//表插入部分

		$sql=file_get_contents('./vo_d.sql');
		$sql=str_replace('CHARSET=utf8','CHARSET='.$charset,$sql);
		$ar1=explode('转存表中的数据',$sql);
			//var_dump($ar1);
		foreach($ar1 as $str1){
			$str1=str_replace('--','',$str1);
			$ar2=explode('INSERT',$str1);
			$tableName=trim(str_replace('`','',$ar2[0]));
			if($tableName){
				$preTableName=$tableName;
				if($preFix){
					$preTableName=preg_replace('/^pt_/isU',$preFix,$tableName);
					$tableString=str_replace($tableName,$preTableName,$tableString);
						//$tableName=$preTableName;
				}
				
				$r=mysql_query('turncate table '.$preTableName,$connect) ;
				$sqlInsert='INSERT '.str_replace($tableName,$preTableName,$ar2[1]);
				$r=mysql_query($sqlInsert,$connect) ;


				$info='初始化成功';
				if($r){

				}else{
						//var_dump(mysql_error($connect));
					$info='初始化失败************************************';
				}

				set_time_limit(0);
				ob_end_flush();
					//echo '初始化'.$tableName.' '.$info."\n";
				echo '<script> new_info("'.$preTableName.' '.$info.'"); </script>';
				flush();
				ob_flush();
				ob_clean();

			}
		}


			//基本设置初始化  管理员等设置 
		$tableName='pt_admin';

		$preTableName=$tableName;

		if(class_exists('Mcrypter')) {
			$encrypt = Mcrypter::encrypt($config['adminpsw']);
		} else {
			$encrypt = $config['adminpsw'];
		}

		$tableString="INSERT INTO `pt_admin` (`username`, `password`, `is_super_admin`, `encrypt`) VALUES('".$config['adminuser']."', '".substr(md5($config['adminpsw']),8,16)."', 3, '{$encrypt}');";

		if($preFix){
			$preTableName=preg_replace('/^pt_/isU',$preFix,$tableName);
			$tableString=str_replace($tableName,$preTableName,$tableString);
		}
		$r=mysql_query('turncate table '.$preTableName,$connect) ;
		$sqlInsert=str_replace($tableName,$preTableName,$tableString);
		$r=mysql_query($sqlInsert,$connect) ;	



			//更改官网一些设置
		$tableName='pt_s_config';
		$preTableName=$tableName; 
		if($preFix){
			$preTableName=preg_replace('/^pt_/isU',$preFix,$tableName);
			$tableString=str_replace($tableName,$preTableName,$tableString);
		}

		$sqlSystem1="update `$preTableName` set `cfg_value`='".$config['baseurl']."' where `cfg_name`='SYSTEM_DOMAIN'";			
		$sqlSystem2="update `$preTableName` set `cfg_value`='".$config['webname']."' where `cfg_name`='SYSTEM_WEBSITE_NAME'";
		$r1=mysql_query($sqlSystem1,$connect) ;
		$r2=mysql_query($sqlSystem2,$connect) ;	


	}

		//设置 Yii  db config
	public function setYiiDb($config){
		$yiiPath=$this->getRootDir().'/protected/config/db.php';
		$yiiDbConfig=array(
			'class'=>'CDbConnectionExt',
			'connectionString' => 'mysql:host='.$config['dbhost'].';port=3306;dbname='.$config['dbname'],
			'emulatePrepare' => true,
			'username' => $config['dbuser'],
			'password' => $config['dbpsw'],
			'charset' => $config['charset'],
			'tablePrefix'=>$config['dbprefix'],
			);


		$inputString=
		'<?php
		return '.var_export($yiiDbConfig,true).' 
		?>';

		$is_success=0;
		$conn = file_put_contents($yiiPath,$inputString);
		if($conn){
			$is_success=1;
		}
		$db=array(
			);
		return $is_success;
	}
}

$b=new Base();
$info1=$b->getAllInfo();
$info2=$b->getAllDir();
	/**
	$filename='../yjl/test';
	$prem=fileperms($filename);
	$prem=substr(sprintf('%o', $prem), -4);
	var_dump($prem);
	
	$array_dir=scandir($filename);
	var_dump($array_dir);
	var_dump(dirname(__FILE__));
	**/
	
	?>
