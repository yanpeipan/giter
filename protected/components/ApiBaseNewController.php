<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class ApiBaseNewController extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/column1';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();
    
    /**
     * 给用户分配uuid
     */
    protected function getUserId4Username(){
        $sql = "SELECT id FROM {{userid}} WHERE id>100000 AND is_use=0 ORDER BY sort DESC";
        $command = Yii::app()->db_user->createCommand($sql);
        $row = $command->queryRow();
        $id = $row['id'];
        $sql_update = "UPDATE {{userid}} SET is_use=1 WHERE id={$id}";
        $command_update = Yii::app()->db_user->createCommand($sql_update);
        $command_update->execute();
        return $id;
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
        //$this->_appendRedis(); //write redis
        //self::_appendAd(); //重磅广告
        Yii::app()->end();
    }
	
    protected function _appendRedis(){
        $info = array();
        $info['type'] = 1;
        @$info['uid'] = $_POST['uid'];
        @$info['ip'] = $_SERVER['REMOTE_ADDR'];
        @$info['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
        @$info['method'] = $_REQUEST['method'];
        @$info['tid'] = $_REQUEST['tid'];
        @$info['time']   =time();
		@$info['soft'] = self::getSoft();
		@$info['api_version'] = isset($_POST['version']) ? $_POST['version'] : ''; 
		//@$info['soft'] = isset($_POST['soft']) ? trim($_POST['soft']) : '';
		@$info['soft_version'] = isset($_POST['soft_version']) ? trim($_POST['soft_version']) : '';
        RedisHandler::list_append($info);
        //$info = RedisHandler::list_shift();
        //var_dump($info);
    }
	
	static private function _appendAd()
	{
		if(isset($_REQUEST['method']) && isset($_REQUEST['ch_id']) && isset($_REQUEST['ch_type']) && isset($_REQUEST['order_id']))
		{
			//重磅广告以及记录频道被点击次数
			if($_REQUEST['method'] == 'api.puti.channelvideos')
			{
				if($_REQUEST['ch_id'] == '233' && $_REQUEST['ch_type'] == '1' && $_REQUEST['order_id'] == '0')
				{
					//重磅内容广告
					$is_topic = 1;
				}else{
					//频道点击
					$is_topic = 0;
				}
				
				$info = array(
					'method'=>$_REQUEST['method'],
					'ip'=>$_SERVER['REMOTE_ADDR'],
					'time'=>time(),
					'api_version'=>isset($_POST['version']) ? $_POST['version'] : '',
					'soft'=>self::getSoft(),
					'is_topic'=>$is_topic,
					'ch_id'=>intval($_REQUEST['ch_id'])
				);
				RedisHandler::list_append_ad($info);
				//$data = RedisHandler::list_shift_ad();
				//var_dump($data);exit;
			}
		}
	}
	
	private function getSoft()
	{
		$soft = '';
		$version = isset($_POST['version']) ? $_POST['version'] : '';
		if(!empty($version))
		{
			/**
            '兔子视频3.0'=>'tuzi3',
            '兔子视频HD2.0(MIX)'=>'TuziHD2.0',
            '兔子视频HD'=>'TuziHD',
            '兔子视频手机版'=>'TuziTV-iphone',
            '兔子服务'=>'tuziService',
            'Launcher'=>'launcher',
            '兔子电视助手'=>'tuziHelper',
            '播放器'=>'player',
            'rom平台测试数据'=>'rom-test-dev',
            '四川联通产品'=>'scunicom',    
			*/
			$arr_mix= array('2.0','2.1','2.2','2.3','2.4','2.5','2.6');
			$arr_hd = array('1.0','1.1','1.3');
			$arr_tuzi3 = array('3.1');
			$arr_android = array('1.5');
			$arr_ios = array('1.4','1.6');
			$arr_ios_android = array('1.2');
			if(in_array($version, $arr_mix))
			{
				$soft = "TuziHD2.0";
			}elseif(in_array($version, $arr_hd)){
				$soft = "TuziHD";
			}elseif(in_array($version, $arr_tuzi3)){
				$soft = "tuzi3";
			}elseif(in_array($version, $arr_android)){
				$soft = "tuzi-phone-android";
			}elseif(in_array($version, $arr_ios)){
				$soft = "tuzi-phone-ios";
			}elseif(in_array($version, $arr_ios_android)){
				$soft = "tuzi-phone-android-ios";
			}else{
				$soft = isset($_POST['soft']) ? trim($_POST['soft']) : '';
			}
		}
	 	return $soft;
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