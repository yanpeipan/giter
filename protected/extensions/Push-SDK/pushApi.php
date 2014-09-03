<?php
require_once ( "../Channel.class.php" ) ;
if ( ! defined ( 'API_ROOT_PATH' ) ) 
{
	define ( 'API_ROOT_PATH', dirname( __FILE__));
}
require_once ( API_ROOT_PATH . '/ChannelApi.php' );

class pushApi extends BaseApi{
		private $apiKey;
		private $secretKey;

	
	
	public function __construct(){
			$this-> $apiKey = "zck1CuToR99rRNUD9eCubWUY";
			$this-> $secretKey = "crZfsEbGHejeCMI6ykg8qbO51LTIGGFL";

    }
   /**
     * 获取定制和创建的频道信息
     * @param id(终端的唯一标志，服务器端生成的，用id找到对应的UserID--百度云的)
	 * @param type(操作类型，例如:play,login....)
     */
	
	public function sendPush($id,$type){
		//array('id'=>312fdsf  ,'type'=>'play' option=>array('url'=>'http:xxxoo.com' title=>'' ));
		$message['status'] = "0";//flag
		//1没登陆终端开启应用
				$this-> push_login();
				//获取 $user_id
		
		//登陆
				//获取 $user_id
				switch($type) 
				{ 
					case "play": 
						$message['status'] = "1";
						//获取$u_id
							$sql="select user_id from {{}} where uid=:$id";
							$cmd= Yii::app()->db_user->createCommand($sql);
							
							$result= $cmd->queryRow();
							$user_id=$result['uid'];
							//$channel_id=$result['channel_id'];
							$message['push']=$this->test_pushMessage_android($user_id);
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
 	 public function push_login(){
		//启动服务并获得 当前请求终端  $user_id  ??????? 终端取出$user_id 我如何拿过来  如何区分是不是他的$user_id
		
		//根据 获得$user_id 生成唯一的uid 验证规则 ????????
		
		//把uid存进db
				$values=array(':user_id'=>$user_id,'uid'=>$uid,);
		     	 $sql = "INSERT INTO {{}} SET user_id=:user_id, uid=:uid";
			     $cmd = Yii::app()->db->createCommand($sql);
	             $cmd->bindValues($values);
			     $result = $cmd->execute();
				if(!$result){
					 $u_id='error';
				}
		
		 
		 
		return $u_id;
	}
	
}