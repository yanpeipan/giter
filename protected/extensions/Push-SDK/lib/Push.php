<?php 
//include_once('Channel.php');
class Push
{
	public $apiKey;
	public $secretKey;
	public $user_id;
	public $channel_id;
	public $dev_id;
	
	public function __construct()
	{
		//$this->apiKey = "zck1CuToR99rRNUD9eCubWUY"; 19891015 
		//$this->secretKey = "crZfsEbGHejeCMI6ykg8qbO51LTIGGFL";
		$this->apiKey = "qdnCheVxkY6zkgQjaH8fWeIN";
		$this->secretKey = "LMc9ukaO2GmxRVR4Nopf5obKQmpD1FKv";
 
    }
	
	private function getParams()
	{
		// $this->apiKey = "zck1CuToR99rRNUD9eCubWUY";
		// $this->secretKey = "crZfsEbGHejeCMI6ykg8qbO51LTIGGFL";
		$this->apiKey = "qdnCheVxkY6zkgQjaH8fWeIN";
		$this->secretKey = "LMc9ukaO2GmxRVR4Nopf5obKQmpD1FKv";
	}
	
   /**
     * 终端3.1
     * @param $bd_userid
	 * @param $data
     */
	public function sendPushNew($bd_userid, $channel_id, $data)
	{
		self::getParams();
		$status = false;
		if(!empty($data))
		{
			if(!empty($bd_userid) && !empty($channel_id))
			{
				//get device type
				$device_type = self::queryDeviceType($channel_id);
				//1:浏览器设备;2:PC 设备;3:Android 设备;4:iOS 设备;5:Windows 
				if( $device_type == 3)
				{
					$ret = self::pushMessage_android($bd_userid, 1, null, 1, $data);
				}elseif($device_type == 4){
					$ret = self::pushMessage_ios($bd_userid, 1,1, $data);
				}
				return $ret;
			}
		}
		
		return $status;
	}
	
	/**
     * 获取定制和创建的频道信息
     * @param id(终端的唯一标志，服务器端生成的，用id找到对应的UserID--百度云的)
	 * @param type(操作类型，例如:play,login....)
     */
	public function sendPush()
	{
		$json_array['status'] = '0';
		$uid   = isset($_REQUEST['uid']) ? trim($_REQUEST['uid']) : '';
		$data  = isset($_REQUEST['data']) ? trim($_REQUEST['data']) : '';//json
		$appid = isset($_REQUEST['appid']) ? trim($_REQUEST['appid']) : '';
		
		if(!empty($data) && $appid)
		{
			//获取绑定信息
			$values = array(
				':uid'=>$uid,
				':appid'=>$appid,
			);
			$sql = "SELECT userid,channelid FROM {{user_bdpush}} WHERE uid=:uid AND appid=:appid";
			$cmd = Yii::app()->db->createCommand($sql);
			$row = $cmd->bindValues($values)->queryRow();
			if($row)
			{
				$userid     = isset($row['userid']) ? $row['userid'] : '';
				$channel_id = isset($row['channelid']) ? $row['channelid'] : '';
				if(!empty($userid) && !empty($channel_id))
				{
					//get device type
					$device_type = $this->queryDeviceType($channel_id);
					//1:浏览器设备;2:PC 设备;3:Android 设备;4:iOS 设备;5:Windows 
					if( $device_type == 3)
					{
						$ret = $this->pushMessage_android($userid, 1, null, 1, $data);
					}elseif($device_type == 4){
						$ret = $this->pushMessage_ios($userid, 1,1, $data);
					}else{
						$ret = false;
						$json_array['error_msg'] = 'type error';
					}
		
					if($ret)
					{
						$json_array['status'] = '1';
					}else{
						$json_array['error_msg'] = 'not bind or push failed';
					}
				}else{
					//error
					$json_array['error_msg'] = 'userid or channelid empty!';
				}
			}else{
				//error
				$json_array['error_msg'] = 'uniqkey error!';
			}		
		}else{
			$json_array['error_msg'] = 'params error!';
		}
		
		return $json_array;
	}

	
    /**
     * 获取定制和创建的频道信息
     * @param id(终端的唯一标志，服务器端生成的，用id找到对应的UserID--百度云的)
	 * @param type(操作类型，例如:play,login....)
     */
	public function sendPushBak()
	{
		$json_array['status'] = '0';
		$uid   = isset($_REQUEST['uid']) ? trim($_REQUEST['uid']) : '';
		$data  = isset($_REQUEST['data']) ? trim($_REQUEST['data']) : '';//json
		$obj_json = json_decode($data);

		if(!empty($data))
		{
			//play example
			$obj_json = json_decode($data);
			$cmd = isset($obj_json->cmd) ? $obj_json->cmd : '';//operation type
			$uniqkey = isset($obj_json->uniqkey) ? $obj_json->uniqkey : '';
			$play_url =isset($obj_json ->play_url ) ? $obj_json ->play_url : '';
		    unset($obj_json->uniqkey);
		    unset($obj_json->cmd);
			if(!empty($uniqkey))
			{
				$bindInfo = $this->getUserIdByUniqkey($uniqkey);
				$userid     = isset($bindInfo['userid']) ? $bindInfo['userid'] : '';
				$channel_id = isset($bindInfo['channelid']) ? $bindInfo['channelid'] : '';
				if(!empty($userid) && !empty($channel_id))
				{
					//get device type
					$device_type = $this->queryDeviceType($channel_id);
					switch($cmd) 
					{
						// { ["uniqkey"]=> string(10) "176d8c2532" ["cmd"]=> string(4) "play" ["play_url"]=> string(21) "http://sotu.tv/1.html" }  
						case "play": 
							
							//通知类型的内容必须按指定内容发送，示例如下：
							$message_content = json_encode($obj_json);
							
							//1:浏览器设备;2:PC 设备;3:Android 设备;4:iOS 设备;5:Windows 
							if( $device_type == 3)
							{
								$ret = $this->pushMessage_android($userid, 1, null, 1, $message_content);
							}elseif($device_type == 4){
								$ret = $this->pushMessage_ios($userid, 1,1, $message_content);
							}
							break; 
						case "login": 
							$ret = false;
							break; 
					} 
					
					if($ret)
					{
						$json_array['status'] = '1';
					}else{
						$json_array['error_msg'] = 'push failed';
					}
				}else{
					//error
					$json_array['error_msg'] = 'userid or channelid empty!';
				}
			}else{
				//error
				$json_array['error_msg'] = 'uniqkey error!';
			}		
		}else{
			$json_array['error_msg'] = 'data empty!';
		}
		
		return $json_array;
	}
	
	
	protected function error_output ( $str ) 
	{
		echo "\033[1;40;31m" . $str ."\033[0m" . "\n";
	}

	protected function right_output ( $str ) 
	{
	    echo "\033[1;40;32m" . $str ."\033[0m" . "\n";
	}

	
	protected function test_queryBindList ( $userId ) 
	{

		$channel = new Channel ($this->$apiKey, $this->$secretKey) ;
		$optional [ Channel::CHANNEL_ID ] = "3915728604212165383"; 
		$ret = $channel->queryBindList ( $userId, $optional ) ;
		if ( false === $ret ) 
		{
			 $this-> error_output ( 'WRONG, ' . __FUNCTION__ . ' ERROR!!!!!' ) ;
			 $this-> error_output ( 'ERROR NUMBER: ' . $channel->errno ( ) ) ;
			 $this-> error_output ( 'ERROR MESSAGE: ' . $channel->errmsg ( ) ) ;
			 $this-> error_output ( 'REQUEST ID: ' . $channel->getRequestId ( ) );
		}
		else
		{
			 $this-> right_output ( 'SUCC, ' . __FUNCTION__ . ' OK!!!!!' ) ;
			 $this-> right_output ( 'result: ' . print_r ( $ret, true ) ) ;
		}	
	}


	protected function test_verifyBind ( $userId )
	{

	    $channel = new Channel ( $this->$apiKey, $this->$secretKey ) ;
	    //$optional [ Channel::CHANNEL_ID ] = 2484515682371722163;
	    $ret = $channel->verifyBind ( $userId, $optional );
	    if ( false === $ret )
	    {   
	         $this-> error_output ( 'WRONG, ' . __FUNCTION__ . ' ERROR!!!!!' ) ;
	         $this-> error_output ( 'ERROR NUMBER: ' . $channel->errno ( ) ) ;
	         $this->  error_output ( 'ERROR MESSAGE: ' . $channel->errmsg ( ) ) ;
	         $this-> error_output ( 'REQUEST ID: ' . $channel->getRequestId ( ) );
	    }
	    else
	    {
	         $this-> right_output ( 'SUCC, ' . __FUNCTION__ . ' OK!!!!!' ) ;
	         $this-> right_output ( 'result: ' . print_r ( $ret, true ) ) ;
	    }
	}

	/**
	 * 推送android设备消息
	 * @param $user_id
	 * @param $push_type 1推送消息到某个user,2推送消息到一个tag中的全部user,3推送消息到该app中的全部user
	 * @param $tag_name
	 * @param $message_type
	 * @param $message_content
	 */
	protected function pushMessage_android($user_id='', $push_type=1, $tag_name=null,$message_type=0, $message_content)
	{

	    $channel = new Channel ( $this->apiKey,$this->secretKey ) ;
		$optional[Channel::USER_ID] = $user_id; //如果推送单播消息，需要指定user
		
		if(!empty($tag_name))
		{
			$optional[Channel::TAG_NAME] = $tag_name;  //如果推送tag消息，需要指定tag_name
		}
	
		//指定发到android设备
		$optional[Channel::DEVICE_TYPE] = 3;
		
		//指定消息类型为通知
		$optional[Channel::MESSAGE_TYPE] =$message_type;
		$push_type=$push_type;
		$message_content=$message_content;
		$message_key = "msg_key";

	    $ret = $channel->pushMessage ( $push_type, $message_content, $message_key, $optional ) ;
		
	   	return $ret;
	}

	//推送ios设备消息
	//$userid, 1, null, 1, $message_content
	//
	protected function pushMessage_ios ($user_id='',$push_type=1,$message_type=0, $message_content)
	{
	    $channel = new Channel ( $this->$apiKey, $this->$secretKey ) ;
		//注意百度push服务对ios dev版与ios release版采用不同的域名.
		//如果是dev版请修改push服务器域名"https://channel.iospush.api.duapp.com", release版则使用默认域名,无须修改。修改域名使用setHost接口
		//$channel->setHost("https://channel.iospush.api.duapp.com");
	
		$push_type = $push_type; //推送单播消息
		$optional[Channel::USER_ID] = $user_id; //如果推送单播消息，需要指定user
	
		//指定发到ios设备
		$optional[Channel::DEVICE_TYPE] = 4;
		//指定消息类型为通知
		$optional[Channel::MESSAGE_TYPE] = 1;
		//通知类型的内容必须按指定内容发送，示例如下：
		
		$message_key = "msg_key";
	    $ret = $channel->pushMessage ( $push_type, $message_content, $message_key, $optional ) ;
	
	}

	protected function test_fetchMessageCount ( $userId  )
		{
		    $channel = new Channel ( $this->$apiKey, $this->$secretKey ) ;
		    $ret = $channel->fetchMessageCount ( $userId) ;
		    if ( false === $ret )
		    {   
		        $this->  error_output ( 'WRONG, ' . __FUNCTION__ . ' ERROR!!!!!' ) ;
		         $this-> error_output ( 'ERROR NUMBER: ' . $channel->errno ( ) ) ;
		         $this-> error_output ( 'ERROR MESSAGE: ' . $channel->errmsg ( ) ) ;
		         $this-> error_output ( 'REQUEST ID: ' . $channel->getRequestId ( ) );
		    }
		    else
		    {   
		         $this-> right_output ( 'SUCC, ' . __FUNCTION__ . ' OK!!!!!' ) ;
		         $this-> right_output ( 'result: ' . print_r ( $ret, true ) ) ;
		    }
		}

   protected function test_fetchMessage ( $userId  )
		{
		    $channel = new Channel ($this->$apiKey, $this->$secretKey) ;
		    $ret = $channel->fetchMessage ( $userId ) ;
		    if ( false === $ret )
		    {   
		        $this->  error_output ( 'WRONG, ' . __FUNCTION__ . ' ERROR!!!!!' ) ;
		        $this->  error_output ( 'ERROR NUMBER: ' . $channel->errno ( ) ) ;
		        $this->  error_output ( 'ERROR MESSAGE: ' . $channel->errmsg ( ) ) ;
		        $this->  error_output ( 'REQUEST ID: ' . $channel->getRequestId ( ) );
		    }
		    else
		    {   
		        $this->  right_output ( 'SUCC, ' . __FUNCTION__ . ' OK!!!!!' ) ;
		         $this-> right_output ( 'result: ' . print_r ( $ret, true ) ) ;
		    }
		}

	protected function test_deleteMessage ( $userId, $msgIds )
		{
		    $channel = new Channel ($this->$apiKey, $this->$secretKey ) ;
		    //$optional [ Channel::CHANNEL_ID ] = 4152049051604943232;
		    $ret = $channel->deleteMessage ( $userId, $msgIds, $optional ) ;
		    if ( false === $ret )
		    {   
		        $this->error_output ( 'WRONG, ' . __FUNCTION__ . ' ERROR!!!!!' ) ;
		        $this->error_output ( 'ERROR NUMBER: ' . $channel->errno ( ) ) ;
		        $this->error_output ( 'ERROR MESSAGE: ' . $channel->errmsg ( ) ) ;
		        $this->error_output ( 'REQUEST ID: ' . $channel->getRequestId ( ) );
		    }
		    else
		    {   
		        $this->right_output ( 'SUCC, ' . __FUNCTION__ . ' OK!!!!!' ) ;
		        $this->right_output ( 'result: ' . print_r ( $ret, true ) ) ;
		    }
		}


	protected function test_setTag($tag_name, $user_id)
		{
		    $channel = new Channel($this->$apiKey, $this->$secretKey);
		    $optional[Channel::USER_ID] = $user_id;
		    $ret = $channel->setTag($tag_name, $optional);
		    if (false === $ret) {   
		        $this->  error_output ( 'WRONG, ' . __FUNCTION__ . ' ERROR!!!!!' ) ;
		        $this->  error_output ( 'ERROR NUMBER: ' . $channel->errno ( ) ) ;
		        $this->  error_output ( 'ERROR MESSAGE: ' . $channel->errmsg ( ) ) ;
		         $this-> error_output ( 'REQUEST ID: ' . $channel->getRequestId ( ) );
		        return false;
		    } else {   
		         $this-> right_output ( 'SUCC, ' . __FUNCTION__ . ' OK!!!!!' ) ;
		         $this-> right_output ( 'result: ' . print_r ( $ret, true ) ) ;
		        return $ret['response_params']['tid'];
		    }
		}

	protected function test_fetchTag($tag_name = null)
		{
		    $channel = new Channel($this->$apiKey, $this->$secretKey);
			$optional[Channel::TAG_NAME] = $tag_name;
		    $ret = $channel->fetchTag($optional);
		    if (false === $ret) {   
		        $this->  error_output ( 'WRONG, ' . __FUNCTION__ . ' ERROR!!!!!' ) ;
		        $this->  error_output ( 'ERROR NUMBER: ' . $channel->errno ( ) ) ;
		         $this-> error_output ( 'ERROR MESSAGE: ' . $channel->errmsg ( ) ) ;
		         $this-> error_output ( 'REQUEST ID: ' . $channel->getRequestId ( ) );
		    } else {   
		         $this-> right_output ( 'SUCC, ' . __FUNCTION__ . ' OK!!!!!' ) ;
		         $this-> right_output ( 'result: ' . print_r ( $ret, true ) ) ;
		    }
		
		}


	protected function test_deleteTag($tag_name)
		{
		    $channel = new Channel($this->$apiKey, $this->$secretKey);
		    $ret = $channel->deleteTag($tag_name);
		    if (false === $ret) {   
		         $this-> error_output ( 'WRONG, ' . __FUNCTION__ . ' ERROR!!!!!' ) ;
		         $this-> error_output ( 'ERROR NUMBER: ' . $channel->errno ( ) ) ;
		        $this->  error_output ( 'ERROR MESSAGE: ' . $channel->errmsg ( ) ) ;
		        $this->  error_output ( 'REQUEST ID: ' . $channel->getRequestId ( ) );
		    } else {   
		         $this-> right_output ( 'SUCC, ' . __FUNCTION__ . ' OK!!!!!' ) ;
		        $this->  right_output ( 'result: ' . print_r ( $ret, true ) ) ;
		    }
		
		}


	protected function test_queryUserTags($user_id)
	{
		    $channel = new Channel($this->$apiKey, $this->$secretKey);
		    $ret = $channel->queryUserTags($user_id);
		    if (false === $ret) {   
		        $this->  error_output ( 'WRONG, ' . __FUNCTION__ . ' ERROR!!!!!' ) ;
		         $this-> error_output ( 'ERROR NUMBER: ' . $channel->errno ( ) ) ;
		         $this-> error_output ( 'ERROR MESSAGE: ' . $channel->errmsg ( ) ) ;
		         $this-> error_output ( 'REQUEST ID: ' . $channel->getRequestId ( ) );
		    } else {   
		         $this-> right_output ( 'SUCC, ' . __FUNCTION__ . ' OK!!!!!' ) ;
		        $this->  right_output ( 'result: ' . print_r ( $ret, true ) ) ;
		    }
		
	}

	 protected function test_initAppIoscert ( $name, $description, $release_cert, $dev_cert )
	{
	    $channel = new Channel ($this->$apiKey, $this->$secretKey) ;
		//注意百度push服务对ios dev版与ios release版采用不同的域名.
		//如果是dev版请修改push服务器域名"https://channel.iospush.api.duapp.com", release版则使用默认域名，修改域名使用setHost接口
		//$channel->setHost("https://channel.iospush.api.duapp.com");
	    
		$ret = $channel->initAppIoscert ($name, $description, $release_cert, $dev_cert) ;
	    if ( false === $ret )
	    {
	         $this-> error_output ( 'WRONG, ' . __FUNCTION__ . ' ERROR!!!!' ) ;
	         $this-> error_output ( 'ERROR NUMBER: ' . $channel->errno ( ) ) ;
	         $this-> error_output ( 'ERROR MESSAGE: ' . $channel->errmsg ( ) ) ;
	         $this-> error_output ( 'REQUEST ID: ' . $channel->getRequestId ( ) );
	    }
	    else
	    {
	         $this-> right_output ( 'SUCC, ' . __FUNCTION__ . ' OK!!!!!' ) ;
	        $this->  right_output ( 'result: ' . print_r ( $ret, true ) ) ;
	    }
	}

	 protected function test_updateAppIoscert ( $name, $description, $release_cert, $dev_cert )
		{
		    $channel = new Channel ($this->$apiKey, $this->$secretKey) ;
			//注意百度push服务对ios dev版与ios release版采用不同的域名.
			//如果是dev版请修改push服务器域名"https://channel.iospush.api.duapp.com", release版则使用默认域名，修改域名使用setHost接口
			//$channel->setHost("https://channel.iospush.api.duapp.com");
		
		    $optional[ Channel::NAME ] = $name;
		    $optional[ Channel::DESCRIPTION ] = $description;
		    $optional[ Channel::RELEASE_CERT ] = $release_cert;
		    $optional[ Channel::DEV_CERT ] = $dev_cert;
		    $ret = $channel->updateAppIoscert ($optional) ;
		    if ( false === $ret )
		    {
		        $this->  error_output ( 'WRONG, ' . __FUNCTION__ . ' ERROR!!!!' ) ;
		        $this->  error_output ( 'ERROR NUMBER: ' . $channel->errno ( ) ) ;
		        $this->  error_output ( 'ERROR MESSAGE: ' . $channel->errmsg ( ) ) ;
		        $this->  error_output ( 'REQUEST ID: ' . $channel->getRequestId ( ) );
		    }
		    else
		    {
		         $this-> right_output ( 'SUCC, ' . __FUNCTION__ . ' OK!!!!!' ) ;
		         $this-> right_output ( 'result: ' . print_r ( $ret, true ) ) ;
		    }
		}

	protected function test_queryAppIoscert ( )
		{
		    $channel = new Channel ($this->$apiKey, $this->$secretKey) ;
			//注意百度push服务对ios dev版与ios release版采用不同的域名.
			//如果是dev版请修改push服务器域名"https://channel.iospush.api.duapp.com", release版则使用默认域名，修改域名使用setHost接口
			//$channel->setHost("https://channel.iospush.api.duapp.com");
		
		    $ret = $channel->queryAppIoscert () ;
		    if ( false === $ret )
		    {
		        $this-> error_output ( 'WRONG, ' . __FUNCTION__ . ' ERROR!!!!' ) ;
		        $this-> error_output ( 'ERROR NUMBER: ' . $channel->errno ( ) ) ;
		        $this-> error_output ( 'ERROR MESSAGE: ' . $channel->errmsg ( ) ) ;
		         $this-> error_output ( 'REQUEST ID: ' . $channel->getRequestId ( ) );
		    }
		    else
		    {
		         $this-> right_output ( 'SUCC, ' . __FUNCTION__ . ' OK!!!!!' ) ;
		         $this-> right_output ( 'result: ' . print_r ( $ret, true ) ) ;
		    }
		}

	protected function test_deleteAppIoscert ( )
		{
		    $channel = new Channel ($this->$apiKey, $this->$secretKey) ;
		    $ret = $channel->deleteAppIoscert () ;
		    if ( false === $ret )
		    {
		         $this-> error_output ( 'WRONG, ' . __FUNCTION__ . ' ERROR!!!!' ) ;
		        $this-> error_output ( 'ERROR NUMBER: ' . $channel->errno ( ) ) ;
		         $this->error_output ( 'ERROR MESSAGE: ' . $channel->errmsg ( ) ) ;
		        $this->error_output ( 'REQUEST ID: ' . $channel->getRequestId ( ) );
		    }
		    else
		    {
		        $this-> right_output ( 'SUCC, ' . __FUNCTION__ . ' OK!!!!!' ) ;
		         $this-> right_output ( 'result: ' . print_r ( $ret, true ) ) ;
		    }
	}

	function queryDeviceType($channel_id)
	{
		$channel = new Channel ($this->apiKey, $this->secretKey) ;
		//array(2) { ["request_id"]=> int(2992366505) ["response_params"]=> array(1) { ["device_type"]=> int(3) } } 
		$ret = $channel->queryDeviceType($channel_id);
		
		//1:浏览器设备;2:PC 设备;3:Android 设备;4:iOS 设备;5:Windows 
		
		$device_type = isset($ret["response_params"]['device_type']) ? $ret["response_params"]['device_type'] : 0;
		return $device_type;
	}

	private function getUserIdByUniqkey($uniqkey)
	{
		$sql="SELECT userid,channelid FROM {{user_bdpush}} WHERE uniqkey=:uniqkey";
		$cmd= Yii::app()->db->createCommand($sql);
		$cmd->bindValue(':uniqkey', $uniqkey);
		$result= $cmd->queryRow();	
	
		return $result;
	}

	/*
	 * 启动服务
	 * 获得 当前请求终端, 终端取出 要插入的数据插入数据 
	 * @param $user_id 服务器取
	 * @param $channel_id 服务器取
	 */
	function unik(){
		$str=array('1','2','3','4','5','6','7','8','9','a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','0',);
		$str=array_flip($str);
		$b= implode('',array_rand($str,3));
		$str = substr(md5(uniqid()), -4).$str1;
		return substr(md5($str),-7);
	}
	function push_login_insert($values){
		$values=$values;
	    $sql = "INSERT INTO {{user_bdpush}} SET userid=:userid,uid=:uid,appid=:appid,channelid=:channelid,uniqkey=:uniqkey";
	    $cmd = Yii::app()->db->createCommand($sql);
        $cmd->bindValues($values);
	    $result = $cmd->execute();
		if($result)
		{
			$json_array = array(
				'status'=>'1',
				'uniqkey'=>$uniqkey,
			);
			return $json_array;
		}else{
			$this->push_login_insert($values);
	   }
	}
	/*
	 * 启动服务
	 * 获得 当前请求终端, 终端取出 要插入的数据插入数据 
	 * @param $user_id 服务器取
	 * @param $channel_id 服务器取
	 */
 	 public function push_login()
 	 {
 	 	$json_array['status'] = "0";//flag
 	 	$user_id    = isset($_REQUEST['userid'])? strval($_REQUEST['userid']):'';
		$channel_id = isset($_REQUEST['channelid'])?strval($_REQUEST['channelid']):'';
		$app_id     = isset($_REQUEST['appid'])?strval($_REQUEST['appid']):'';
 		$uid        = isset($_REQUEST['uid'])?$_REQUEST['uid']:'';
		if(empty($user_id) || empty($channel_id))
		{
			$json_array = array(
				'status'=>'0',
				'error_msg'=>'params error',
			);
		}else{
			//根据 获得$user_id 生成唯一的uid 验证规则
			$values = array(
				':uid'=>$uid,
				':appid'=>$app_id,
			);
			$sql="select id,userid,uniqkey from {{user_bdpush}} where uid=:uid AND appid=:appid";
			$cmd= Yii::app()->db->createCommand($sql);
			$cmd->bindValues($values);
			$result= $cmd->queryRow();
			if(!$result)
			{
				$uniqkey = substr(md5($user_id."@".$app_id."@".$uid."@".time()), 0, 9).rand(0,9);
				$values = array(
					':userid'=>$user_id,
					':channelid'=>$channel_id,
					':appid'=>$app_id,
					':uid'=>$uid,
					':uniqkey'=>$uniqkey,
				);
				
				//没记录把uid存进db
			    $sql = "INSERT INTO {{user_bdpush}} SET userid=:userid,uid=:uid,appid=:appid,channelid=:channelid,uniqkey=:uniqkey";
			    $cmd = Yii::app()->db->createCommand($sql);
		        $cmd->bindValues($values);
			    $result = $cmd->execute();
				if($result){
					$json_array = array(
						'status'=>'1',
						'uniqkey'=>$uniqkey,
					);
				}else{
					$json_array['error_msg'] = "save failed";
				}
			}else{
				//update
				if($user_id != $result['userid']){
					$uniqkey = substr(md5($user_id."@".$app_id."@".$uid."@".time()), 0, 9).rand(0,9);
					$values = array(
						':userid'=>$user_id,
						':id'=>$result['id'],
						':uniqkey'=>$uniqkey
					);
				    $sql = "UPDATE {{user_bdpush}} SET userid=:userid,uniqkey=:uniqkey WHERE id=:id";
				    $cmd = Yii::app()->db->createCommand($sql);
			        $cmd->bindValues($values);
				    $result = $cmd->execute();
					if($result){
						$json_array = array(
							'status'=>'1',
							'uniqkey'=>$uniqkey,
						);
					}else{
						$json_array = array(
							'status'=>'0',
							'error_msg'=>'update bind info error',
						);
					}
				}else{
					$json_array = array(
						'status'=>'1',
						'uniqkey'=>$result["uniqkey"],
					);
				}
			}
		}
		return $json_array;
	}
	/*
 	 public function push_login()
 	 {
 	 	
 	 	$json_array['status'] = "0";//flag
 	 	
 	 	$user_id    = isset($_REQUEST['userid'])?$_REQUEST['userid']:'';
		$channel_id = isset($_REQUEST['channelid'])?$_REQUEST['channelid']:'';
		$app_id     = isset($_REQUEST['appid'])?$_REQUEST['appid']:'';
 		$uid        = isset($_REQUEST['uid'])?$_REQUEST['uid']:'';
 	
		if(empty($user_id) || empty($channel_id))
		{
				
			return $json_array['status']='0';
		}
		
		//根据 获得$user_id 生成唯一的uid 验证规则
		$sql="select userid,uniqkey from {{user_bdpush}} where userid=:user_id";
		$cmd= Yii::app()->db->createCommand($sql);
		$cmd->bindValue(':user_id', $user_id);
		$result= $cmd->queryRow();
 	
		 if(!$result)
		 {
 		    $uniqkey = md5($user_id);
		    $values = array(
				':userid'=>$user_id,
				':channelid'=>$channel_id,
				':appid'=>$app_id,
				':uid'=>$uid,
				':uniqkey'=>$uniqkey,
		    );
			  	
		    //没记录把uid存进db
	     	$sql = "INSERT INTO {{user_bdpush}} SET userid=:userid,uid=:uid,appid=:appid,channelid=:channelid,uniqkey=:uniqkey";
		    $cmd = Yii::app()->db->createCommand($sql);
            $cmd->bindValues($values);
		    $result = $cmd->execute();
			if($result)
			{
				$json_array = array(
					'status'=>'1',
					'uniqkey'=>$uniqkey,
				);
			}
		  }else{
		  		$json_array = array(
					'status'=>'1',
					'uniqkey'=>$result["uniqkey"],
				);
		  }
		  return $json_array;
	}
*/
	/**@param 
		 * $push_type   2一群人
		 * $messages //
		 * $message_keys 'sfds'//
		 * user_id ==>fou  +$channel_id //
		 * tag_name //
		 * device_type  3:Android 设备; 4: 设备  //
		 * message_type 0:消息(透传)  1:通知  默认为 0  //
		 * message_expires Channel::MESSAGE_EXPIRES 指定消息的过期时间,  默认 86400 秒。必须和 messages 一一对应
		* $flag 0  正常，1 单个  2 多个记录  好友推送用
		 */
		function test_pushMessage ( $data, $udata='',$flag=0){
			$json_array['status'] = '0';
			$udata['user_id']=$val["userid"];
			$udata['uid']=$uid;  //推送人
		    $udata['tuid']=$fid; //被推送人
			$udata['user_id']='943470481935561058';
	 		$api_key=$this->apiKey = "zck1CuToR99rRNUD9eCubWUY";
			$secret_key=$this->secretKey = "crZfsEbGHejeCMI6ykg8qbO51LTIGGFL";
			isset($data["title"]) ? $message['title']=$data["title"] :'';
			 if(isset($data["messages "])){
				$message['messages']=$data["messages "]; 
				$message=json_encode($message);
				$message_keys=$data["message_keys"];
			 }
			
             if(isset($udata['user_id'])){
				 
             	 $user_id = $udata["user_id"];
             }
			
			$channel = new PushChannelApi ($api_key, $secret_key);
			$uid= $udata['uid'];
			$status=1;
			//var_dump($uid);die;
			//推送一条单播消息
			$push_type =$data["push_type"];
			if($data["push_type"]==1){
				 if(! isset($udata['user_id'])){
             		 return  "userid is null";
            	 }
	
				$push_type = 1;
				$optional["user_id"]     = $user_id;
				//$optional['channel_id']  = $channel_id;
				
				$ret = $channel->pushMessage($push_type,$message, $message_keys,$optional);//1
			
			
				//ChannelApi::test();
				$status= Push::checkstatus($ret);//2
				
				$json_array['status'] = $status;
				if($flag==0){
					Push::Record($uid,$user_id,$message,$status,$push_type);//3
				}
				return $json_array;
			}elseif($data["push_type"]==2){
				
				//推送到一群人,按 tag 推送,必须指定 tag_name
				$push_type = 2;
				$tag_name = $data["tag_name "];
				$optional['tag']    = $tag_name;
				
				$ret = $channel->pushMessage($push_type, $message, $message_keys,$optional);
				$status=$this->checkstatus($ret);
				$json_array['status'] = $status;
				if($flag==0){
					$json_array['Record']=$this->Record($uid,$user_id,$message,$status,$push_type);
				}
			}elseif($data["push_type"]==3){
				//推送到某个应用下的所有人,不用指定 user_id, channel_id, tag_name
				
				$push_type = 3;
				$ret = $channel->pushMessage($push_type, $message, $message_keys);
				
				$status=$this->checkstatus($ret);
				
				$json_array['status'] = $status;
				$user_id='';
				if($flag==0){
				$json_array['Record']=$this->Record($uid,$user_id,$message,$status,$push_type);
				}
			}
			
			if ( false === $ret )
			{//检查返回值
			    return  0;
			}else{
				
				return  1;
				
			}
			//return ;
		}



	//public $apiKey;
	/* 
	* @param $push_type 1推送消息到某个user,2推送消息到一个tag中的全部user,3推送消息到该app中的全部user
	* @param $tag_name
	* @param $message_type
	* user_id
	* $uniqkey
	* $data
	*/
	//$this->user_id,$uniqkey,$obj_json  
	public function  Record($uid,$user_id,$data,$status,$push_type=1,$message_type=1){
			$userid   = isset($user_id) ? $user_id:'';//	
			$uid       = isset($uid) ?  $uid:'';//
			$content      =isset($data) ? $data :'';//
			$status    = isset($status) ? $status:'';//
			isset(Yii::app()->user->uid) ? $uid=Yii::app()->user->uid : $uid=$uid ;
			if($push_type== 2){
				$userid='部分人';
			}elseif($push_type== 3){
				$userid='所有人';
			}else{
				$userid=$userid;
			}
			$ts = time();
			$values=array(
				'ts'=>$ts,
				'status'=>$status,
				'content'=>$content,
				'userid'=>$userid,
				'uid'=>$uid,
			);
			$sql="INSERT INTO  {{push_record}}  SET ts=:ts, status=:status, content=:content,userid=:userid,uid=:uid";
			$cmd = Yii::app()->db->createCommand($sql);
			$cmd->bindValues($values);
		    $result = $cmd->execute();
			if($result){
				$json_str=1;
			}else{
				$json_str=0;
			}
		
			return $json_str;
			
	
	}
	function checkstatus($ret)
	{
		//var_dump($ret);DIE;
		if ( is_array($ret) ){
			$status=1;
		}else{
			$status=0;
		}
		return $status ;
	}
	public function pushHiddenVideo( $push_type,$messages)
    {
       self::getParams();
	   $status = '0';	
	   $ret = $this->pushMessage_android($user_id='',$push_type=$push_type, $tag_name=null,$message_type=0,$messages);
	   //$ret = self::pushMessage_ios($bd_userid, 1,1, $data);
	   	if($ret)
		{
			$json_array['status'] = '1';
		}
		else
		{
			$json_array['error_msg'] = 'push failed';
		}
		return $json_array;
	   
    }
	
}





/**
 * 百度云消息通道服务 PHP SDK
 * 
 * 本文件提供百度云消息通道服务的PHP版本SDK
 * @author 百度移动.云事业部
 * @copyright Copyright (c) 2012-2020 百度在线网络技术(北京)有限公司
 * @version 2.0.0
 * @package
 */
/**
 * 
 * Channel
 * 
 * Channel类提供百度云消息通道服务的PHP版本SDK，用户首先实例化这个类，设置自己的apiKey与secretKey，即可使用百度云消息通道服务
 * 
 * @author 百度云消息通道服务@百度云架构部
 * 
 * @version 1.0.0.0
 */
require_once ( 'RequestCore.class.php' );
require_once ( 'ChannelException.class.php' );
require_once ( 'BaeBase.class.php' );
class Channel
{
	/**
	 * 可选参数的KEY
	 * 
	 * 用户关注：是
	 * 在调用Channel类的SDK方法时，根据用户的个性化需要，可能需要传入可选参数，而可选参数需要放在关联数组$optional中传入，
	 * 这里定义了$optional数组可用的KEY
	 */
	
	/**
	 * 发起请求时的时间戳
	 * 
	 * @var int TIMESTAMP
	 */
	const TIMESTAMP = 'timestamp';
	/**
	 * 请求过期的时间
	 * 
	 * 如果不填写，默认为10分钟
	 * 
	 * @var int EXPIRES
	 */
	const EXPIRES = 'expires';
	/**
	 * API版本号
	 * 
	 * 用户一般不需要关注此项
	 * 
	 * @var int VERSION
	 */
	const VERSION = 'v';
	/**
	 * 消息通道ID号
	 * 
	 * @var int CHANNEL_ID
	 */
	const CHANNEL_ID = 'channel_id';
	/**
	 * 用户ID的类型
	 * 
	 * 0：百度用户标识对称加密串；1：百度用户标识明文
	 * 
	 * @var string USER_TYPE
	 */
	const USER_TYPE = 'user_type';
	/**
	 * 设备类型
	 * 
	 * 1：浏览器设备；2：PC设备；3：andorid设备
	 * 
	 * @var int DEVICE_TYPE
	 */
	const DEVICE_TYPE = 'device_type';
	/**
	 * 第几页
	 * 
	 * 批量查询时，需要指定start，默认为第0页
	 * 
	 * @var int START
	 */
	const START = 'start';
	/**
	 * 每页多少条记录
	 * 
	 * 批量查询时，需要指定limit，默认为100条
	 * 
	 * @var int LIMIT
	 */
	const LIMIT = 'limit';
	/**
	 * 消息ID json字符串
	 * 
	 * @var string MSG_IDS
	 */
	const MSG_IDS = 'msg_ids';
	const MSG_KEYS = 'msg_keys';
	const IOS_MESSAGES = 'ios_messages';
	const WP_MESSAGES = 'wp_messages';
	/**
	 * 消息类型
	 * 
	 * 扩展类型字段，0：默认类型
	 * 
	 * @var int MESSAGE_TYPE
	 */
	const MESSAGE_TYPE = 'message_type';
	/**
	 * 消息超时时间
	 * 
	 * @var int MESSAGE_EXPIRES
	 */
	const MESSAGE_EXPIRES = 'message_expires';
    
    /**
     * 消息标签名称
     * 
     * @var string TAG_NAME
     */
    const TAG_NAME = 'tag';
    
    /**
     * 消息标签描述
     * 
     * @var stirng TAG_INFO
     */
    const TAG_INFO = 'info';
    
    /**
     * 消息标签id
     * 
     * @var int TAG_ID
     */
    const TAG_ID = 'tid';
    
    /**
     * 封禁时间
     * 
     * @var int BANNED_TIME
     */
    const BANNED_TIME = 'banned_time';
    
    /**
     * 回调域名
     * 
     * @var string CALLBACK_DOMAIN
     */
    const CALLBACK_DOMAIN = 'domain';
    
    /**
     * 回调uri
     * 
     * @var string CALLBACK_URI
     */
    const CALLBACK_URI = 'uri';

	/**
	 * Channel常量
	 * 
	 * 用户关注：否
	 */
	const APPID = 'appid';
	const ACCESS_TOKEN = 'access_token';
	const API_KEY = 'apikey';
	const SECRET_KEY = 'secret_key';
	const SIGN = 'sign';
	const METHOD = 'method';
	const HOST = 'host';
	const USER_ID = 'user_id';
	const MESSAGES = 'messages';
	const PRODUCT = 'channel';
	
	const HOST_DEFAULT = 'http://channel.api.duapp.com';
	const HOST_IOS_DEV = 'https://channel.iospush.api.duapp.com';
	const NAME = "name";
	const DESCRIPTION = "description";
	const CERT = "cert"; 
	const RELEASE_CERT = "release_cert";
	const DEV_CERT = "dev_cert";
	const PUSH_TYPE = 'push_type';
	
	/**
	 * Channel私有变量
	 * 
	 * 用户关注：否
	 */
	protected $_apiKey = NULL;
	protected $_secretKey = NULL;
	protected $_requestId = 0;
	protected $_curlOpts = array(
        CURLOPT_TIMEOUT => 30,
        CURLOPT_CONNECTTIMEOUT => 5
        );
	protected $_host = self::HOST_DEFAULT;

	const PUSH_TO_USER = 1;
	const PUSH_TO_TAG = 2;
	const PUSH_TO_ALL = 3;
	const PUSH_TO_DEVICE = 4;

	/**
	 * Channel 错误常量
	 * 
	 * 用户关注：否
	 */
	const CHANNEL_SDK_SYS = 1;
	const CHANNEL_SDK_INIT_FAIL = 2;
	const CHANNEL_SDK_PARAM = 3;
	const CHANNEL_SDK_HTTP_STATUS_ERROR_AND_RESULT_ERROR = 4;
	const CHANNEL_SDK_HTTP_STATUS_OK_BUT_RESULT_ERROR = 5;

	/**
	 * 错误常量与错误字符串的映射
	 * 
	 * 用户关注：否
	 */
	protected $_arrayErrorMap = array
		( 
		 '0' => 'php sdk error',
		 self::CHANNEL_SDK_SYS => 'php sdk error',
		 self::CHANNEL_SDK_INIT_FAIL => 'php sdk init error',
		 self::CHANNEL_SDK_PARAM => 'lack param',
		 self::CHANNEL_SDK_HTTP_STATUS_ERROR_AND_RESULT_ERROR => 'http status is error, and the body returned is not a json string',
		 self::CHANNEL_SDK_HTTP_STATUS_OK_BUT_RESULT_ERROR => 'http status is ok, but the body returned is not a json string',
		);

	/**
     * 2.0版rest API里面部分方法将channel_id放在url中，其余部分放在包体中
     * 记录需要放在包体中的方法
     *
     * 用户关注：否
     */
     protected $_method_channel_in_body = array
        (
        'push_msg',
        'set_tag',
        'fetch_tag',
        'delete_tag',
        'query_user_tags'
        );
	
	/**
	 * setApiKey
	 * 
	 * 用户关注：是
	 * 服务类方法， 设置Channel对象的apiKey属性，如果用户在创建Channel对象时已经通过参数设置了apiKey，这里的设置将会覆盖以前的设置
	 * 
	 * @access public
	 * @param string $apiKey
	 * @return 成功：true，失败：false
	 * 
	 * @version 
	 */
	public function setApiKey ( $apiKey )
	{
		$this->_resetErrorStatus (  );
		try
		{
			if ( $this->_checkString ( $apiKey, 1, 64 ) )
			{
				$this->_apiKey = $apiKey;
			}
			else 
			{
				throw new ChannelException ( "invaid apiKey ( ${apiKey} ), which must be a 1 - 64 length string", self::CHANNEL_SDK_INIT_FAIL );
			}
		}
		catch ( Exception $ex )
		{
			$this->_channelExceptionHandler ( $ex );
			return false; 
		}
		return true;
	}

	/**
	 * setSecretKey
	 * 
	 * 用户关注：是
	 * 服务类方法， 设置Channel对象的secretKey属性，如果用户在创建Channel对象时已经通过参数设置了secretKey，这里的设置将会覆盖以前的设置
	 * 
	 * @access public
	 * @param string $secretKey
	 * @return 成功：true，失败：false
	 * 
	 * @version 
	 */
	public function setSecretKey ( $secretKey )
	{
		$this->_resetErrorStatus (  );
		try
		{
			if ( $this->_checkString ( $secretKey, 1, 64 ) )
			{
				$this->_secretKey = $secretKey;
			}
			else 
			{
				throw new ChannelException ( "invaid secretKey ( ${secretKey} ), which must be a 1 - 64 length string", self::CHANNEL_SDK_INIT_FAIL );
			}
		}
		catch ( Exception $ex )
		{
			$this->_channelExceptionHandler ( $ex );
			return false; 
		}
		return true;
	}
	
	
	/**
	 * setCurlOpts
	 * 
	 * 用户关注：是
	 * 服务类方法， 设置HTTP交互的OPTION，同PHP curl库的所有opt参数
	 * 
	 * @access public
	 * @param array $arr_curlopt
	 * @return 成功：true，失败：false
	 * @throws BcmsException
	 * 
	 * @version 1.2.0
	 */
	public function setCurlOpts($arr_curlOpts)
	{
		$this->_resetErrorStatus();
		try {
			if (is_array($arr_curlOpts)) {
				$this->_curlOpts = $this->_curlOpts + $arr_curlOpts;
			}
			else  {
				throw new ChannelException( 'invalid param - arr_curlOpts is not an array ['
                        . print_r($arr_curlOpts, true) . ']',
                        self::CHANNEL_SDK_INIT_FAIL);
			}
		} catch (Exception $ex) {
			$this->_channelExceptionHandler( $ex );
			return false; 
		}
		return true;
	}

	/**
	 * setHost
	 * 
	 * 用户关注：是
	 * 服务类方法， 设置Channel对象的后端host属性，创建Channel对象时会选择默认的host，如果需要修改host，调用该方法修改。
	 * 
	 * @access public
	 * @param string $host
	 * @return 成功：true，失败：false
	 * 
	 * @version 
	 */
	public function setHost ( $host )
	{
		$this->_resetErrorStatus (  );
		try
		{
			if ( $this->_checkString ( $host, 1, 1024 ) )
			{
				$this->_host = $host;
			}
			else 
			{
				throw new ChannelException ( "invaid host ( ${host} ), which must be a 1 - 1024 length string", self::CHANNEL_SDK_INIT_FAIL );
			}
		}
		catch ( Exception $ex )
		{
			$this->_channelExceptionHandler ( $ex );
			return false; 
		}
		return true;
	}

	/**
	 * getRequestId
	 * 
	 * 用户关注：是
	 * 服务类方法，获取上次调用的request_id，如果SDK本身错误，则直接返回0
	 * 
	 * @access public
	 * @return 上次调用服务器返回的request_id
	 * 
	 * @version 1.0.0.0
	 */
	public function getRequestId (  )
	{
		return $this->_requestId;
	}
	
	/**
	 * queryBindList
	 * 
	 * 用户关注：是
	 * 
	 * 供服务器端根据userId[、channelId]查询绑定信息
	 * 
	 * @access public
	 * @param string $userId 用户ID号
	 * @param array $optional 可选参数，支持的可选参数包括：Channel::CHANNEL_ID、Channel::DEVICE_TYPE、Channel::START、Channel::LIMIT
	 * @return 成功：PHP数组；失败：false
	 * 
	 * @version 1.0.0.0
	 */
	public function queryBindList ( $userId, $optional = NULL ) 
	{
		$this->_resetErrorStatus (  );
		try 
		{
			$tmpArgs = func_get_args (  );
			$arrArgs = $this->_mergeArgs ( array ( self::USER_ID ), $tmpArgs );
			$arrArgs [ self::METHOD ] = 'query_bindlist';
			return $this->_commonProcess ( $arrArgs );
		} 
		catch ( Exception $ex ) 
		{
			$this->_channelExceptionHandler ( $ex );
			return false; 
		}
	}
	
	/**
	 * bindVerify
	 * 
	 * 用户关注：是
	 * 
	 * 校验userId[、channelId]是否已经绑定
	 * 
	 * @access public
	 * @param string $userId 用户ID号
	 * @param array $optional 可选参数，支持的可选参数包括：Channel::CHANNEL_ID、Channel::DEVICE_TYPE
	 * @return 成功：PHP数组；失败：false
	 * 
	 * @version 1.0.0.0
	 */
	public function verifyBind ( $userId, $optional = NULL ) 
	{
		$this->_resetErrorStatus (  );
		try 
		{
			$tmpArgs = func_get_args (  );
			$arrArgs = $this->_mergeArgs ( array ( self::USER_ID ), $tmpArgs );
			$arrArgs [ self::METHOD ] = 'verify_bind';
			return $this->_commonProcess ( $arrArgs );
		} 
		catch ( Exception $ex ) 
		{
			$this->_channelExceptionHandler ( $ex );
			return false; 
		}
	}
	
	/**
	 * fetchMessage
	 * 
	 * 用户关注：是
	 * 
	 * 根据userId[、channelId]查询消息
	 * 
	 * @access public
	 * @param string $userId 用户ID号
	 * @param array $optional 可选参数，支持的可选参数包括：Channel::CHANNEL_ID、Channel::START、Channel::LIMIT
	 * @return 成功：PHP数组；失败：false
	 * 
	 * @version 1.0.0.0
	 */
	public function fetchMessage ( $userId, $optional = NULL ) 
	{
		$this->_resetErrorStatus (  );
		try 
		{
			$tmpArgs = func_get_args (  );
			$arrArgs = $this->_mergeArgs ( array ( self::USER_ID ), $tmpArgs );
			$arrArgs [ self::METHOD ] = 'fetch_msg';
			return $this->_commonProcess ( $arrArgs );
		} 
		catch ( Exception $ex ) 
		{
			$this->_channelExceptionHandler ( $ex );
			return false; 
		}
	}
	
	/**
	 * fetchMessageCount
	 * 
	 * 用户关注：是
	 * 
	 * 根据userId[、channelId]查询消息的个数
	 * 
	 * @access public
	 * @param string $userId 用户ID号
	 * @param array $optional 可选参数，支持的可选参数包括：Channel::CHANNEL_ID
	 * @return 成功：PHP数组；失败：false
	 * 
	 * @version 1.0.0.0
	 */
	public function fetchMessageCount ( $userId, $optional = NULL ) 
	{
		$this->_resetErrorStatus (  );
		try 
		{
			$tmpArgs = func_get_args (  );
			$arrArgs = $this->_mergeArgs ( array ( self::USER_ID ), $tmpArgs );
			$arrArgs [ self::METHOD ] = 'fetch_msgcount';
			return $this->_commonProcess ( $arrArgs );
		} 
		catch ( Exception $ex ) 
		{
			$this->_channelExceptionHandler ( $ex );
			return false; 
		}
	}
	
	/**
	 * deleteMessage
	 * 
	 * 用户关注：是
	 * 
	 * 根据userId、msgIds[、channelId]删除消息
	 * 
	 * @access public
	 * @param string $userId 用户ID号
	 * @param string $msgIds 要删除哪些消息,如果是数组格式，则会自动做json_encode;
	 * @param array $optional 可选参数，支持的可选参数包括：Channel::CHANNEL_ID
	 * @return 成功：PHP数组；失败：false
	 * 
	 * @version 1.0.0.0
	 */
	public function deleteMessage ( $userId, $msgIds, $optional = NULL ) 
	{
		$this->_resetErrorStatus (  );
		try 
		{
			$tmpArgs = func_get_args (  );
			$arrArgs = $this->_mergeArgs ( array ( self::USER_ID, self::MSG_IDS ), $tmpArgs );
			$arrArgs [ self::METHOD ] = 'delete_msg';
			if(is_array($arrArgs [ self::MSG_IDS ])) {
				$arrArgs [ self::MSG_IDS ] = json_encode($arrArgs [ self::MSG_IDS ]);
			}
			return $this->_commonProcess ( $arrArgs );
		} 
		catch ( Exception $ex ) 
		{
			$this->_channelExceptionHandler ( $ex );
			return false; 
		}
	}


	/**
	 * pushMessage
	 * 用户关注： 是
	 * 根据pushType, messages, message_type, [optinal] 推送消息
	 * @access public
	 * @param int $pushType 推送类型 取值范围 1-3, 1:单人，2：一群人tag， 3：所有人
	 * @param string $messages 要发送的消息，如果是数组格式，则会自动做json_encode;如果是json格式给出，必须与$msgKeys对应起来;
     * @param array $optional 可选参数,如果$pushType为单人，必须指定Channel::USER_ID(例:$optional[Channel::USER_ID] = 'xxx'),
	 *		如果$pushType为tag，必须指定Channel::TAG,
	 * 		其他可选参数：Channel::MSG_KEYS 发送的消息key，如果是数组格式，则会自动做json_encode，必须与$messages对应起来;
	 *		Channel::MESSAGE_TYPE 消息类型，取值范围 0-1, 0:消息（透传），1：通知，默认为0
	 *		还可指定Channel::MESSAGE_EXPIRES, Channel::MESSAGE_EXPIRES, Channel::CHANNLE_ID等
	 *
	 * @return 成功：PHP数组；失败:false
	 * @version 2.0.0.0
	*/
	public function pushMessage($pushType, $messages, $msgKeys, $optional = NULL)
	{
	    $this->_resetErrorStatus();
		try
		{
			$tmpArgs = func_get_args();
			$arrArgs = $this->_mergeArgs (array(self::PUSH_TYPE , self::MESSAGES, self::MSG_KEYS), $tmpArgs);
			$arrArgs[self::METHOD] = 'push_msg';
			switch($pushType)
			{
				case self::PUSH_TO_USER:
					if ( !array_key_exists(self::USER_ID, $arrArgs) || empty($arrArgs[self::USER_ID])){
						throw new ChannelException("userId should be specified in optional[] when pushType is PUSH_TO_USER", self::CHANNEL_SDK_PARAM);
					}
					break;
	
				case self::PUSH_TO_TAG:
					if (!array_key_exists(self::TAG_NAME, $arrArgs) || empty($arrArgs[self::TAG_NAME])){
						throw new ChannelException("tag should be specified in optional[] when pushType is PUSH_TO_TAG", self::CHANNEL_SDK_PARAM);
					}
					break;
		
				case self::PUSH_TO_ALL:
					break;

				default:
					throw new ChannelException("pushType($pushType) must be in range[1,3]", self::CHANNEL_SDK_PARAM);
			}

			$arrArgs[self::PUSH_TYPE] = $pushType;
			
			if(is_array($arrArgs [ self::MESSAGES ])) {
                $arrArgs [ self::MESSAGES ] = json_encode($arrArgs [ self::MESSAGES ]);
            }
            if(is_array($arrArgs [ self::MSG_KEYS ])) {
                $arrArgs [ self::MSG_KEYS ] = json_encode($arrArgs [ self::MSG_KEYS ]);
            }
			//369778131
            return $this->_commonProcess ( $arrArgs );
		}
		catch (Exception $ex)
		{
			$this->_channelExceptionHandler( $ex );
			return false;
		}
	}

 
    /**
     * setTag: 创建消息标签
     * 
     * 用户关注: 是
     *
     * @access public
     * @param string $tagName 标签名称
     * @param array $optional 可选参数，支持的可选参数包括 self::USER_ID，如果指定user_id，服务器会完成与tag的绑定操作
     * @return 成功: array; 失败: false
     * 
     * @version 1.0.0.0
     */
    public function setTag($tagName, $optional = null)
    {
        $this->_resetErrorStatus();
        try {
            $tmpArgs = func_get_args();
            $arrArgs = $this->_mergeArgs(array(self::TAG_NAME), $tmpArgs);
            $arrArgs[self::METHOD] = 'set_tag';
            return $this->_commonProcess($arrArgs);
        } catch (Exception $ex) {
            $this->_channelExceptionHandler($ex);
            return false;
        }
    }
    
    /**
     * fetchTag: 查询消息标签信息
     * 
     * 用户关注: 是
     *
     * @param int $tagId 标签ID号
     * @param array $optional，可选参数，支持可选参数包括self::TAG_NAME,如果指定TAG_NAME,则获取该标签的信息，否则获取该应用的所有标签信息
     * @return 成功：PHP数组；失败：false
     */
    public function fetchTag($optional = null)
    {
        $this->_resetErrorStatus();
        try {
            $tmpArgs = func_get_args();
            $arrArgs = $this->_mergeArgs(null, $tmpArgs);
            $arrArgs[self::METHOD] = 'fetch_tag';
            return $this->_commonProcess($arrArgs);
        } catch (Exception $ex) {
            $this->_channelExceptionHandler($ex);
            return false;
        }
    }
    
    /**
     * destroyTag: 删除消息标签
     * 
     * 用户关注: 是
     *
     * @param int $tagId 消息标签ID号
     * @param array $optional
     * @return 成功：PHP数组；失败：false
     */
    public function deleteTag($tagName, $optional = null)
    {
        $this->_resetErrorStatus();
        try {
            $tmpArgs = func_get_args();
            $arrArgs = $this->_mergeArgs(array(self::TAG_NAME), $tmpArgs);
            $arrArgs[self::METHOD] = 'delete_tag';
            return $this->_commonProcess($arrArgs);
        } catch (Exception $ex) {
            $this->_channelExceptionHandler($ex);
            return false;
        }
    }
    
    /**
     * queryUserTag: 查询用户相关的标签
     * 
     * 用户关注: 是
     *
     * @param string $userId 用户ID号
     * @param array $optional
     * @return 成功：PHP数组；失败：false 
     */
    public function queryUserTags($userId, $optional = null)
    {
        $this->_resetErrorStatus();
        try {
            $tmpArgs = func_get_args();
            $arrArgs = $this->_mergeArgs(array(self::USER_ID), $tmpArgs);
            $arrArgs[self::METHOD] = 'query_user_tags';
            return $this->_commonProcess($arrArgs);
        } catch (Exception $ex) {
            $this->_channelExceptionHandler($ex);
            return false;
        }
    }
    
    

	/**
	 * initAppIoscert: 初始化应用ios证书
	 * 
	 * 用户关注: 是
	 *
	 * @param string $name 证书名称
	 * @param string description 证书描述
	 * @param string $cert 证书内容
	 * @param array $optional
	 * @return 成功：PHP数组；失败：false  
	 */
	public function initAppIoscert($name, $description, $release_cert, $dev_cert, $optional = null)
	{		
		$this->_resetErrorStatus();
		try {
			$tmpArgs = func_get_args();
			$arrArgs = $this->_mergeArgs(array(self::NAME, self::DESCRIPTION, self::RELEASE_CERT, self::DEV_CERT), $tmpArgs);
			$arrArgs[self::METHOD] = "init_app_ioscert";
			return $this->_commonProcess($arrArgs);
		} catch(Exception $ex) {
			$this->_channelExceptionHandler($ex);
			return false;
		}
	}

	/**
	 * updateAppIoscert: 修改ios证书内容
	 * 
	 * 用户关注: 是
	 *
	 * @param array $optional可选参数，支持的可选参数包括 self::NAME, self::DESCRIPTION, self::CERT
	 * @return 成功：PHP数组；失败：false   
	 */
	public function updateAppIoscert($optional = null)
	{		
		$this->_resetErrorStatus();
		try {
			$tmpArgs = func_get_args();
			$arrArgs = $this->_mergeArgs(array(), $tmpArgs);
			$arrArgs[self::METHOD] = "update_app_ioscert";
			return $this->_commonProcess($arrArgs);	
		} catch(Exception $ex) {
			$this->_channelExceptionHandler($ex);
			return false;
		}
	}

	/**
	 * queryAppIoscert: 查询ios证书内容
	 * 
	 * 用户关注: 是
	 *
	 * @param array $optional
	 * @return 成功：PHP数组；失败：false   
	 */
	public function queryAppIoscert($optional = null)
	{
		$this->_resetErrorStatus();
		try {
			$tmpArgs = func_get_args();
			$arrArgs = $this->_mergeArgs(array(), $tmpArgs);
			$arrArgs[self::METHOD] = "query_app_ioscert";	
			return $this->_commonProcess($arrArgs); 
		} catch(Exception $ex) {
			$this->_channelExceptionHandler($ex);
			return false;
		}
	}

	/**
	 * deleteAppIoscert: 删除ios证书内容
	 * 
	 * 用户关注: 是
	 *
	 * @param array $optional
	 * @return 成功：PHP数组；失败：false   
	 */
	public function deleteAppIoscert($optional = null)
	{
		$this->_resetErrorStatus();
		try {
			$tmpArgs = func_get_args();
			$arrArgs = $this->_mergeArgs(array(), $tmpArgs);
			$arrArgs[self::METHOD] = "delete_app_ioscert";
			return $this->_commonProcess($arrArgs);
		} catch(Exception $ex) {
			$this->_channelExceptionHandler($ex);
			return false;
		}
	}
	
	
	/**
	 * queryDeviceType
	 * 
	 * 用户关注：是
	 * 
	 * 根据channelId查询设备类型
	 * 
	 * @access public
	 * @param string $channelId 用户channel的ID号
	 * @return 成功：PHP数组；失败：false
	 * 
	 * @version 1.0.0.0
	 */
	public function queryDeviceType ( $channelId, $optional = NULL ) 
	{
		$this->_resetErrorStatus (  );
		try 
		{
			$tmpArgs = func_get_args (  );
			$arrArgs = $this->_mergeArgs ( array ( self::CHANNEL_ID ), $tmpArgs );
			$arrArgs [ self::METHOD ] = 'query_device_type';
			return $this->_commonProcess ( $arrArgs );
		} 
		catch ( Exception $ex ) 
		{
			$this->_channelExceptionHandler ( $ex );
			return false; 
		}
	}


    /**
     * __construct
     * 用户关注：是
     * 对象构造方法，用户传入$apiKey与$secretKey进行初始化
     * @access public
     * @param string $apiKey
     * @param string $secretKey
     * @param array $arr_curlOpts 可选参数
     * @throws ChannelException 如果出错，则抛出异常，异常号是self::CHANNEL_SDK_INIT_FAIL
    */
	public function __construct ($apiKey = NULL, $secretKey = NULL, $arr_curlOpts = array())
	{
		if($this->_checkString($apiKey, 1, 64)){
			$this->_apiKey = $apiKey;
		}
		else{
			 throw new ChannelException("invalid param - apiKey[$apiKey],"
                    . "which must be a 1 - 64 length string",
                    self::CHANNEL_SDK_INIT_FAIL );
		}

		if($this->_checkString($secretKey, 1, 64)){
			$this->_secretKey = $secretKey;
		}
		else{
			throw new ChannelException("invalid param - secretKey[$secretKey],"
                    . "which must be a 1 - 64 length string",
                    self::CHANNEL_SDK_INIT_FAIL );
		}

		if (!is_array($arr_curlOpts)) {
			throw new ChannelException('invalid param - arr_curlopt is not an array ['
                    . print_r($arr_curlOpts, true) . ']',
                    self::CHANNEL_SDK_INIT_FAIL);
		}
        $this->_curlOpts = $this->_curlOpts + $arr_curlOpts;

        $this->_resetErrorStatus();
	}


	/**
	 * _checkString
	 *  
	 * 用户关注：否
	 * 
	 * 检查参数是否是一个大于等于$min且小于等于$max的字符串
	 * 
	 * @access protected
	 * @param string $str 要检查的字符串
	 * @param int $min 字符串最小长度
	 * @param int $max 字符串最大长度
	 * @return 成功：true；失败：false
	 * 
	 * @version 1.0.0.0
	 */
	protected function _checkString($str, $min, $max)
	{
		if (is_string($str) && strlen($str) >= $min && strlen($str) <= $max) {
			return true;
		}
		return false;
	}

    /**
     * _getKey
     * 
     * 用户关注：否
     * 获取AK/SK/TOKEN/HOST的统一过程函数
     * 
     * @access protected
     * @param array $opt 参数数组
     * @param string $opt_key 参数数组的key
     * @param string $member 对象成员
     * @param string $g_key 全局变量的名字
     * @param string $env_key 环境变量的名字
     * @param int $min 字符串最短值
     * @param int $max 字符串最长值
     * @throws ChannelException 如果出错，则抛出ChannelException异常，异常类型为self::CHANNEL_SDK_PARAM
     * 
     * @version 1.0.0.0
     */
	protected function _getKey(&$opt,
            $opt_key,
            $member,
            $g_key,
            $env_key,
            $min,
            $max,
            $throw = true)
	{
        $dis = array(
            'access_token' => 'access_token',
            );
        global $$g_key;
        if (isset($opt[$opt_key])) {
            if (!$this->_checkString($opt[$opt_key], $min, $max)) {
                throw new ChannelException ( 'invalid ' . $dis[$opt_key] . ' in $optinal ('
                        . $opt[$opt_key] . '), which must be a ' . $min . '-' . $max
                        . ' length string', self::CHANNEL_SDK_PARAM );
            }
            return;
        }
        if ($this->_checkString($member, $min, $max)) {
            $opt[$opt_key] = $member;
            return;
        }
        if (isset($$g_key)) {
            if (!$this->_checkString($$g_key, $min, $max)) {
                throw new ChannelException('invalid ' . $g_key . ' in global area ('
                        . $$g_key . '), which must be a ' . $min . '-' . $max
                        . ' length string', self::CHANNEL_SDK_PARAM);
            }
            $opt[$opt_key] = $$g_key;
            return;
        }

        if (false !== getenv($env_key)) {
            if (!$this->_checkString(getenv($env_key), $min, $max)) {
                throw new ChannelException( 'invalid ' . $env_key . ' in environment variable ('
                        . getenv($env_key) . '), which must be a ' . $min . '-' . $max
                        . ' length string', self::CHANNEL_SDK_PARAM);
            }
            $opt[$opt_key] = getenv($env_key) ;
            return;
        }

        if ($opt_key === self::HOST) {
            $opt[$opt_key] = self::HOST_DEFAULT;
            return;
        }
        if ($throw) {
            throw new ChannelException('no param (' . $dis[$opt_key] . ') was found',
                    self::CHANNEL_SDK_PARAM);
        }
	}

	/**
	 * _adjustOpt
	 *   
	 * 用户关注：否
	 * 
	 * 参数调整方法
	 * 
	 * @access protected
	 * @param array $opt 参数数组
	 * @throws ChannelException 如果出错，则抛出异常，异常号为 self::CHANNEL_SDK_PARAM
	 * 
	 * @version 1.0.0.0
	 */
	protected function _adjustOpt(&$opt)
	{
		if (!isset($opt) || empty($opt) || !is_array($opt)) {
			throw new ChannelException('no params are set',self::CHANNEL_SDK_PARAM);
		}
		if (!isset($opt[self::TIMESTAMP])) {
			$opt[self::TIMESTAMP] = time();
		}
		$this->_getKey($opt, self::HOST, $this->_host, 'g_host',
                'HTTP_BAE_ENV_ADDR_CHANNEL', 1, 1024, false);

        $this->_getKey($opt, self::API_KEY, $this->_apiKey,
                'g_apiKey', 'HTTP_BAE_ENV_AK', 1, 64, false);	
        
		if (isset($opt[self::SECRET_KEY])) {
			unset($opt[self::SECRET_KEY]);
		}
	}

	/**
	 * _checkParams
	 *   
	 * 用户关注：否
	 * 
	 * 检查输入参数是否合法
	 * 
	 * @access protected
	 * @param array $params 参数数组
	 * @throws ChannelException 如果出错，则抛出异常，异常号为 self::CHANNEL_SDK_PARAM
	 * 
	 * @version 1.0.0.0
	 */
	protected function _checkParams(&$params)
	{
		if ( !is_array($params)) {
			throw new ChannelException('no params',self::CHANNEL_SDK_PARAM);
		}
		foreach($params as $key => $value) {
			switch($key)
			{
				case self::USER_ID:
					if( !is_string($value)){
						throw new ChannelException("USER_ID($value) is not string", 
							self::CHANNEL_SDK_PARAM);
					}
					break;
				case self::CHANNEL_ID:
					if( !is_numeric($value)) {
						throw new ChannelException("CHANNEL_ID($value) is not numeric", 
							self::CHANNEL_SDK_PARAM);
					}
					break;
				case self::DEVICE_TYPE:
					if( !is_numeric($value) || $value < 0 || $value > 5 ) {
						throw new ChannelException( "invalid DEVICE_TYPE($value)",
 							self::CHANNEL_SDK_PARAM);
					}
					break;
				case self::MSG_IDS:
					if( !is_numeric($value)) {
						throw new ChannelException( "MSG_IDS($value) is not numeric",
							self::CHANNEL_SDK_PARAM);
					}
					break;
				case self::TAG_NAME:
					if( !is_string($value) || strlen($value) > 128 ){
						throw new ChannelException( "TAG_NAME($value) must be a string and strlen <= 128",
							self::CHANNEL_SDK_PARAM);
					}
					break;
				case self::MESSAGE_TYPE:
					if( !is_numeric($value) || $value < 0 || $value > 1) {
						throw new ChannelException( "invalid MESSAGE_TYPE($value) must be 0 or 1",
 							self::CHANNEL_SDK_PARAM);
					}
					break;
				case self::NAME:
					if( !is_string($value) || strlen($value) > 128 ){
						throw new ChannelException( "IOS_CERT_NAME($value) must be a string and strlen <= 128",
							self::CHANNEL_SDK_PARAM);
					}
					break;
				case self::DESCRIPTION:
					if( !is_string($value) || strlen($value) > 256 ){
						throw new ChannelException( "IOS_CERT_DESCRIPTION($value) must be a string and strlen <= 256",
							self::CHANNEL_SDK_PARAM);
					}
					break;
			}
		}
	}

	/**
	 * _genSign
	 *
	 *用户关注： 否
	 *
	 * 根据method, url, 参数内容 生成签名
	*/
	protected function _genSign($method, $url, $arrContent)
	{
    	//$secret_key = $this->_secretKey;
		$opt = array();
		$this->_getKey($opt, self::SECRET_KEY, $this->_secretKey,
                'g_secretKey', 'HTTP_BAE_ENV_SK', 1, 64, false);
		$secret_key = $opt[self::SECRET_KEY];

    	$gather = $method.$url;
    	ksort($arrContent);
    	foreach($arrContent as $key => $value)
   		{
        	$gather .= $key.'='.$value;
    	}
    	$gather .= $secret_key;
    	$sign = md5(urlencode($gather));
    	return $sign;
	}

	/**
	 * _baseControl
	 *   
	 * 用户关注：否
	 * 
	 * 网络交互方法
	 * 
	 * @access protected
	 * @param array $opt 参数数组
	 * @throws ChannelException 如果出错，则抛出异常，错误号为self::CHANNEL_SDK_SYS
	 * 
	 * @version 1.0.0.0
	 */
	protected function _baseControl($opt)
	{
		$content = '';
		$resource = 'channel';
		if (isset($opt[self::CHANNEL_ID]) 
			&& !is_null($opt[self::CHANNEL_ID]) 
			&& !in_array($opt[self::METHOD], $this->_method_channel_in_body)) {
				$resource = $opt[self::CHANNEL_ID];
				unset($opt[self::CHANNEL_ID]);
		}
		$host = $opt[self::HOST];
		unset($opt[self::HOST]);
		
		$url = $host . '/rest/2.0/' . self::PRODUCT . '/';
		$url .= $resource;
		$http_method = 'POST';
		$opt[self::SIGN] = $this->_genSign($http_method, $url, $opt);
		foreach ($opt as $k => $v) {
			$k = urlencode($k);
			$v = urlencode($v);
			$content .= $k . '=' . $v . '&';
		}
		$content = substr($content, 0, strlen($content) - 1);

		$request = new RequestCore($url);
		//var_dump( $url);die('ddd');
		$headers['Content-Type'] = 'application/x-www-form-urlencoded';
		$headers['User-Agent'] = 'Baidu Channel Service Phpsdk Client';
		foreach ($headers as $headerKey => $headerValue) {
			$headerValue = str_replace(array("\r", "\n"), '', $headerValue);
			if($headerValue !== '') {
				$request->add_header($headerKey, $headerValue);
			}
		}
		$request->set_method($http_method);
		$request->set_body($content);
		if (is_array($this->_curlOpts)) {
			$request->set_curlopts($this->_curlOpts);
		}
		$request->send_request();
		
		return new ResponseCore($request->get_response_header(),
                $request->get_response_body(),
                $request->get_response_code());
	}

	/**
	 * _channelExceptionHandler
	 *   
	 * 用户关注：否
	 * 
	 * 异常处理方法
	 * 
	 * @access protected
	 * @param Excetpion $ex 异常处理函数，主要是填充Channel对象的错误状态信息
	 * 
	 * @version 1.0.0.0
	 */
	protected function _channelExceptionHandler($ex)
	{
		$tmpCode = $ex->getCode();
		if (0 === $tmpCode) {
			$tmpCode = self::CHANNEL_SDK_SYS;
		}

		$this->errcode = $tmpCode;
		if ($this->errcode >= 30000) {
			$this->errmsg = $ex->getMessage();
		} else {	
			$this->errmsg = $this->_arrayErrorMap[$this->errcode] . ',detail info['
                    . $ex->getMessage() . ',break point:' . $ex->getFile() . ':'
                    . $ex->getLine() . '].';
		}
	}

	/**
	 * _commonProcess
	 *   
	 * 用户关注：否
	 * 
	 * 所有服务类SDK方法的通用过程
	 * 
	 * @access protected
	 * @param array $paramOpt 参数数组
	 * @param array $arrNeed 必须的参数KEY
	 * @throws ChannelException 如果出错，则抛出异常
	 * 
	 * @version 1.0.0.0
	 */
	protected function _commonProcess($paramOpt = NULL)
	{
		$this->_adjustOpt($paramOpt);
		$this->_checkParams($paramOpt);
		$ret = $this->_baseControl($paramOpt);
		if (empty($ret)) {
			throw new ChannelException('base control returned empty object',
                    self::CHANNEL_SDK_SYS);
		}
		if ($ret->isOK()) {
			$result = json_decode($ret->body, true);
			if (is_null($result)) {
				throw new ChannelException($ret->body,
                        self::CHANNEL_SDK_HTTP_STATUS_OK_BUT_RESULT_ERROR);
			}
			$this->_requestId = $result['request_id'];
			
			//array(2) { ["request_id"]=> int(2862315770) ["response_params"]=> array(1) { ["device_type"]=> int(3) } } 
			//array(2) { ["request_id"]=> int(2736779669) ["response_params"]=> array(1) { ["success_amount"]=> int(1) } } 
			return $result;
		}
		
		$result = json_decode($ret->body,true);
		if (is_null($result)) {
			throw new ChannelException('ret body:' . $ret->body,
                    self::CHANNEL_SDK_HTTP_STATUS_ERROR_AND_RESULT_ERROR);
		}
		$this->_requestId = $result['request_id'];
		throw new ChannelException($result['error_msg'], $result['error_code']);
	}

	/**
	 * _mergeArgs
	 *   
	 * 用户关注：否
	 * 
	 * 合并传入的参数到一个数组中，便于后续处理
	 * 
	 * @access protected
	 * @param array $arrNeed 必须的参数KEY
	 * @param array $tmpArgs 参数数组
	 * @throws ChannelException 如果出错，则抛出异常，异常号为self::Channel_SDK_PARAM 
	 * 
	 * @version 1.0.0.0
	 */
	protected function _mergeArgs($arrNeed, $tmpArgs)
	{
		$arrArgs = array();
		if (0 == count($arrNeed) && 0 == count($tmpArgs)) {
			return $arrArgs;
		}
		if (count($tmpArgs) - 1 != count($arrNeed) && count($tmpArgs) != count($arrNeed)) {
			$keys = '(';
			foreach ($arrNeed as $key) {
                $keys .= $key .= ',';
			}
			if ($keys[strlen($keys) - 1] === '' && ',' === $keys[strlen($keys) - 2]) {
				$keys = substr($keys, 0, strlen($keys) - 2);
			}
			$keys .= ')';
			throw new Exception('invalid sdk params, params' . $keys . 'are needed',
                    self::CHANNEL_SDK_PARAM);
		}
		if( empty($tmpArgs[count($tmpArgs) - 1])){
			$tmpArgs[count($tmpArgs) - 1] = array();
		}		
		if (count($tmpArgs) - 1 == count($arrNeed) && !is_array($tmpArgs[count($tmpArgs) - 1])) {
			throw new Exception('invalid sdk params, optional param must be an array',
                    self::CHANNEL_SDK_PARAM);
		}

		$idx = 0;
		if(!is_null($arrNeed)){
			foreach ($arrNeed as $key) {
				if (!is_integer($tmpArgs[$idx]) && empty($tmpArgs[$idx])) {
					throw new Exception("lack param (${key})", self::CHANNEL_SDK_PARAM);
				}
				$arrArgs[$key] = $tmpArgs[$idx];
				$idx += 1;
			}
		}
		if (isset($tmpArgs[$idx])) {
			foreach ($tmpArgs[$idx] as $key => $value) {
				if ( !array_key_exists($key, $arrArgs) && (is_integer($value) || !empty($value))) {
					$arrArgs[$key] = $value;
				}
			}
		}
		if (isset($arrArgs[self::CHANNEL_ID])) {
			$arrArgs[self::CHANNEL_ID] = urlencode($arrArgs[self::CHANNEL_ID]);
		}
		return $arrArgs;
	}

	/**
	 * _resetErrorStatus
	 *   
	 * 用户关注：否
	 * 
	 * 恢复对象的错误状态，每次调用服务类方法时，由服务类方法自动调用该方法
	 * 
	 * @access protected
	 * 
	 * @version 1.0.0.0
	 */
	protected function _resetErrorStatus()
	{
		$this->errcode = 0;
		$this->errmsg = $this->_arrayErrorMap[$this->errcode];
		$this->_requestId = 0;
	}
	




}
