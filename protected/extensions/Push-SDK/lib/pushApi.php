<?php
class pushApi extends ChannelApi 
{
		private $apiKey;
		private $secretKey;
		public  $user_id;
		public  $channel_id;
		public  $dev_id;
	
	public function __construct(){
			$this-> $apiKey = "zck1CuToR99rRNUD9eCubWUY";
			$this-> $secretKey = "crZfsEbGHejeCMI6ykg8qbO51LTIGGFL";

    }
   /**
     * 获取定制和创建的频道信息
     * @param id(终端的唯一标志，服务器端生成的，用id找到对应的UserID--百度云的)
	 * @param type(操作类型，例如:play,login....)
     */
	
	public function sendPush($id='',$type){
			$apiKey = "zck1CuToR99rRNUD9eCubWUY";
			$secretKey = "crZfsEbGHejeCMI6ykg8qbO51LTIGGFL";

			$type='play';
			$dev_id=3;
			$user_id="943470481935561058";
				//获取 $user_id
				switch($type) 
				{ 
					case "play": 
						$message['status'] = "1";
						//获取$u_id
			
							if( $dev_id==3){
								$message['push']=$this->test_pushMessage_android($user_id);
							}elseif($dev_id==4){
								$message['push']=$this->test_pushMessage_ios($user_id);
							}
							
	       					$this->_sendResponse(self::HTTP_SUCCESS,json_encode($message),'application/json');
					 break; 
				
					case "login": 
						$json_array['error'] = "800";
						return $message;
					break; 
				} 
								
		
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
	    $ret = $channel->verifyBind ( $userId, $optional ) ;
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

	//推送android设备消息
	protected function test_pushMessage_android ($user_id)
	{

	    $channel = new Channel ( $this->$apiKey,$this->$secretKey ) ;
		//推送消息到某个user，设置push_type = 1; 
		//推送消息到一个tag中的全部user，设置push_type = 2;
		//推送消息到该app中的全部user，设置push_type = 3;
		$push_type = 1; //推送单播消息
		$optional[Channel::USER_ID] = $user_id; //如果推送单播消息，需要指定user
		//optional[Channel::TAG_NAME] = "xxxx";  //如果推送tag消息，需要指定tag_name
	
		//指定发到android设备
		$optional[Channel::DEVICE_TYPE] = 3;
		//指定消息类型为通知
		$optional[Channel::MESSAGE_TYPE] = 1;
		//通知类型的内容必须按指定内容发送，示例如下：
		$message = '{ 
				"title": "test_push",
				"description": "open url",
				"notification_basic_style":7,
				"open_type":1,
				"url":"http://www.baidu.com"
				
	 		}';
		
		$message_key = "msg_key";
	    $ret = $channel->pushMessage ( $push_type, $message, $message_key, $optional ) ;
	    if ( false === $ret )
	    {
	        $this-> error_output ( 'WRONG, ' . __FUNCTION__ . ' ERROR!!!!!' ) ;
	        $this->  error_output ( 'ERROR NUMBER: ' . $channel->errno ( ) ) ;
	        $this->  error_output ( 'ERROR MESSAGE: ' . $channel->errmsg ( ) ) ;
	        $this->  error_output ( 'REQUEST ID: ' . $channel->getRequestId ( ) );
	    }
	    else
	    {
	        $this->  right_output ( 'SUCC, ' . __FUNCTION__ . ' OK!!!!!' ) ;
	        $this->  right_output ( 'result: ' . print_r ( $ret, true ) ) ;
	    }
	}

	//推送ios设备消息
	protected function test_pushMessage_ios ($user_id)
	{
	    $channel = new Channel ( $this->$apiKey, $this->$secretKey ) ;
		//注意百度push服务对ios dev版与ios release版采用不同的域名.
		//如果是dev版请修改push服务器域名"https://channel.iospush.api.duapp.com", release版则使用默认域名,无须修改。修改域名使用setHost接口
		//$channel->setHost("https://channel.iospush.api.duapp.com");
	
		$push_type = 1; //推送单播消息
		$optional[Channel::USER_ID] = $user_id; //如果推送单播消息，需要指定user
	
		//指定发到ios设备
		$optional[Channel::DEVICE_TYPE] = 4;
		//指定消息类型为通知
		$optional[Channel::MESSAGE_TYPE] = 1;
		//通知类型的内容必须按指定内容发送，示例如下：
		$message = '{ 
			"aps":{
				"alert":"msg from baidu push",
				"Sound":"",
				"Badge":0
			}
	 	}';
		
		$message_key = "msg_key";
	    $ret = $channel->pushMessage ( $push_type, $message, $message_key, $optional ) ;
	    if ( false === $ret )
	    {
	        $this->  error_output ( 'WRONG, ' . __FUNCTION__ . ' ERROR!!!!!' ) ;
	        $this->  error_output ( 'ERROR NUMBER: ' . $channel->errno ( ) ) ;
	        $this->  error_output ( 'ERROR MESSAGE: ' . $channel->errmsg ( ) ) ;
	        $this-> error_output ( 'REQUEST ID: ' . $channel->getRequestId ( ) );
	    }
	    else
	    {
	         $this-> right_output ( 'SUCC, ' . __FUNCTION__ . ' OK!!!!!' ) ;
	         $this-> right_output ( 'result: ' . print_r ( $ret, true ) ) ;
	    }
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
	/*
	 * 启动服务
	 * 获得 当前请求终端, 终端取出 要插入的数据插入数据 
	 * @param $user_id 服务器取
	 * @param $channel_id 服务器取
	 */
 	 public function push_login($user_id=0,$channel_id=0)
	 	 {
		 	 	$u_id['status'] = "0";//flag
				//1
				if($user_id==0 || $channel_id=0 ){
					return $u_id['status']='0';
				}
				//根据 获得$user_id 生成唯一的uid 验证规则 ????????
				   $uid=md5($user_id);
	
				   
				   $values=array(':user_id'=>$user_id,
				  				 ':channel_id'=>$channel_id,
				  				 ':uid'=>$uid,
								 );
				   $value1=array(':user_id'=>$user_id,
								 ':uid'=>$uid,
					 			);	
								
					$sql="select user_id from {{user_login_status}} where uid=:uid, user_id=:user_id";
					$cmd= Yii::app()->db_user->createCommand($sql);
					$cmd->bindValues($value1);
					$result= $cmd->queryRow();	
					$this->$user_id=$user_id;
					$this-> $channel_id=$channel_id;
				   $this-> $dev_id= ChannelApi::queryDeviceType($this-> $channel_id);	//1:浏览器设备;2:PC 设备;3:Android 设备;4:iOS 设备;5:Windows Phone 设备
					//有记录		 
					 if(! $result)
					 {	 
					     //没记录把uid存进db
							$values=array(':user_id'=>$user_id,'uid'=>$uid,);
					     	$sql = "INSERT INTO {{}} SET user_id=:user_id, uid=:uid";
						    $cmd = Yii::app()->db->createCommand($sql);
				            $cmd->bindValues($values);
						    $result = $cmd->execute();
							// $u_id=Yii::app()->db->getLastInsertID();
							if(!$result)
							{
								 $u_id['status']='1';
							}
					  }
				return $u_id;
		}
	const HTTP_BAD_REQUEST  = 400;
    const HTTP_UNAUTHORIZED = 401;
    const HTTP_SUCCESS      = 200;
    
    protected function _sendResponse($status = 200, $body = '', $content_type = 'text/html')
	    {
	        // set the status
	        $status_header = 'HTTP/1.1 ' . $status . ' ' . $this->_getStatusCodeMessage($status);
	        header($status_header);
	        // and the content type
	        header('Content-type: ' . $content_type);
	        
	        if($body != '')
	        {
	            // send the body
	            echo $body;
	        }
	        Yii::app()->end();
	    }
	    protected function _getStatusCodeMessage($status)
		    {
		        // these could be stored in a .ini file and loaded
		        // via parse_ini_file()... however, this will suffice
		        // for an example
		        $codes = Array(
		            200 => 'OK',
		            400 => 'Bad Request',
		            401 => 'Unauthorized',
		            402 => 'Payment Required',
		            403 => 'Forbidden',
		            404 => 'Not Found',
		            500 => 'Internal Server Error',
		            501 => 'Not Implemented',
		        );
		        return (isset($codes[$status])) ? $codes[$status] : '';
		    }
	
}