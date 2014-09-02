<?php

class ApiboosController extends ApiBaseNewController
{ 
    /**
     * 请求的api类型
     * @var string
     */
    private $_method;
    /**
     * api版本号
     */
    private $_version;
    
    /**
     * 根据{@see $_method}返回api的参数
     * @var array/boolean
     */
    private $_apiParams;
    
    /**
     * 请求地址
     */
    private $_host;
    
    /**
     * api的数据
     * @var mixed
     */
    private $_apiData;
	/*
	protected function validateCompany(){
    	$companyName     = isset($_POST['companyName']) ? $_POST['companyName'] : '';
		$companyPassword = isset($_POST['companyPassword']) ? $_POST['companyPassword'] : '';
		if(!empty($companyName) && isset(Yii::app()->params['sotu_api_company'][$companyName])){
			if($companyPassword != Yii::app()->params['sotu_api_company'][$companyName]){
				$this->_sendResponse(200,json_encode(array('error_msg'=>'invalid company')),'application/json');
			}
		}else{
			$this->_sendResponse(200,json_encode(array('error_msg'=>'invalid company')),'application/json');
		}
	}
    */
    /**
     * 对所有请求进行access token验证
     */
    public function init(){
    		
    	//强制转换版本
    	$_POST['version'] = "boos";
		
    	//企业认证
    	//$this->validateCompany();
		
        //Yii::log(var_export($_SERVER,true).var_export($_POST,true),CLogger::LEVEL_INFO);
        if(in_array($_SERVER['REMOTE_ADDR'],array('123.127.244.22','123.127.244.23','123.127.244.24'))){
            //内部服务器 不需要oauth认证
           
        }else{
         //   $data = Yii::app()->oauth2->verifyAccessToken();
        //    $_POST['uid']    = $data['uid'];//把授权用户的uid写入post方便后面提取
        //    $_POST['app_id'] = $data['client_id'];
		//	$_POST['ip'] = $_SERVER['REMOTE_ADDR'];
        }
	
        //request method must be 'post'
        $request_method = $_SERVER['REQUEST_METHOD'];
        //for lua
        // if("lua"==$_GET['_ifrom']){
        	// $_POST = $_GET;
        	// $request_method = "post";
        // }
        if('post'==strtolower($request_method)){
            $this->validateMethods();
            $this->validateVersion();
            $res = $this->getApiParamsByMethod();
        }else{
            echo "*_*";
            Yii::app()->end();
        }
    }
    
    public function actionIndex()
    {
        //var_dump($this->_method,$this->_apiParams);
        $this->runApi();
    }
	
    /**
     * 设置request method
     */
    public function validateMethods(){
        $this->_method = isset($_POST['method']) ? $_POST['method'] : '';
        if(empty($this->_method)){
            $this->_sendResponse(400,json_encode(array('error_msg'=>'invalid method')),'application/json');
        }
    }
    
    /**
     * 设置api version
     */
    public function validateVersion(){
        $this->_version = isset($_POST['version']) ? $_POST['version'] : '';
        if(empty($this->_version)){
            $this->_sendResponse(400,json_encode(array('error_msg'=>'invalid version')),'application/json');
        }elseif(floatval($this->_version) >= 3.1){
        	if(!isset($_POST['soft']) && !isset($_POST['soft_version'])){
        		$this->_sendResponse(400,json_encode(array('error_msg'=>'soft and soft_version error')),'application/json');
        	}
        }
    }
    
    /**
     * 返回api params
     */
	public function getApiParamsByMethod(){
		$apiParams = array(
            'api.boos.appcheck'=>array( 
                'url'=>'/VoStartCheck/checkstart',
                'field'=>'token',
                'request_method'=>'GET',
            ), 
            'api.boos.checkCom'=>array( 
                'url'=>'/VoStartCheck/getcom',
                'field'=>'token',
                'request_method'=>'GET',
            ), 
            'api.boos.getpluginlist'=>array( 
                'url'=>'/VoStartCheck/getpluginlists',
                'field'=>'token',
                'request_method'=>'GET',
            ), 
            'api.boos.search'=>array( 
                'url'=>'/VoStartCheck/searchname',
                'field'=>'condition,page,pagesize',
                'request_method'=>'GET',
            ), 
		);
        $this->_apiParams = isset($apiParams[$this->_method]) ? $apiParams[$this->_method] : false;
}
    
    /**
     * 从$_POST里面提取需要的参数，作为api请求参数
     */
    public function prepareApiParams(){
        $field = $this->_apiParams['field'];
        if(!empty($field)){
            $fields = array();
            $fields = explode(',', $field);
            foreach ($fields as &$value) {
                //默认参数  type=SONG
                $tmp = explode('=', $value);
                if(count($tmp)==2){
                	$_GET[$tmp[0]] = $tmp[1];
                    $this->_apiParams['params'][$tmp[0]] = $tmp[1];
                }else{
                	if(isset($this->_apiParams['request_method']))
					{
	                	if(strtolower($this->_apiParams['request_method']) == 'get')
	                	{
	                		$_GET[$value] = isset($_POST[$value]) ? $_POST[$value] : NULL;
	                	}
					}
                    $this->_apiParams['params'][$value] = isset($_POST[$value]) ? $_POST[$value] : NULL;
			    }
            }
            unset($field,$fields);
        }else{
            $this->_apiParams['params'] = array();//没有请求参数的时候，置空数组
        }
		
		$_REQUEST = $_GET = $_POST = array_merge($_POST, $this->_apiParams['params']);
    }
    
    /**
     * 执行api获取数据
     */
    public function runApi(){
        //准备api参数
        $this->prepareApiParams();
        
		if(Yii::app()->params['log_switch'])
		{
	        if(1 || isset($_POST['log'])){
	            $dir = "/tmp/api/".date("Ymd");
	            $file = "/".date('YmdH');
	            if(!is_dir($dir)){
	                @mkdir($dir,0777,true);
	            }
	            $fp = @fopen($dir.$file,'a+');
	            $str = "[".date('Y-m-d H:i:s')."]api:".$_POST['method'].",params:".var_export($this->_apiParams,true);
	            @fputs($fp, $str."\n");
	        }
		}
        //调用本地api
        if(!empty($this->_apiParams['class'])){
            $this->runExtApi();
        }
        
        //执行 调用远程api
        if(!empty($this->_apiParams['url'])){
            $this->runModuleApi();
        }
    }
    
	/**
	 * 调用extensions下面的API
	 */
    public function runExtApi(){
        $class = $this->_apiParams['class'];
        $method = $this->_apiParams['method'];
        foreach ($this->_apiParams['import'] as $ext) {
            Yii::import($ext);
        }
        
        if( class_exists($class) ){
            $obj = new $class;
            if( method_exists($obj, $method) ){
                $http_code = 200;
                $body = $obj->$method($this->_apiParams['params']);
                if(!$body){
                    $http_code = 400;
                    $body = array('error_msg'=>'Bad request 101');
                }
            }else{
                $http_code = 400;
                $body = array('error_msg'=>'Bad request 102');
            }
        }else{
            $http_code = 400;
            $body = array('error_msg'=>'Bad request 103');
        }
        $this->_sendResponse($http_code,json_encode($body),'application/json');
    }

	/**
	 * 调用module下面的API
	 */
    public function runModuleApi(){
       	$status = 0;
		
		//加载module
		$module = ucfirst($this->_version)."Module";
		Yii::import('application.modules.'.$this->_version.'.'.$module);
		
		//加载对应的模块下面的controllers信息
    	if(YII_DEBUG)error_reporting(0);
		$m = new $module;
        $m->init();
		$m->setImport(array(
            "$this->_version.controllers.*",
        ));

		//处理类和方法
		$arr = explode('/', $this->_apiParams['url']);
		$class = isset($arr[1]) ? $arr[1]."Controller" : '';
		$method = isset($arr[2]) ? "action".$arr[2] : '';
		
        if( class_exists($class) ){
            $obj = new $class;
            if( method_exists($obj, $method) ){
            	if(YII_DEBUG)error_reporting(3);
                $http_code = 200;
                //init
                $obj->init();
                $body = $obj->$method();
                if(!$body){
                    $http_code = 400;
                    $body = array('error_msg'=>'Bad request 101');
                }else{
                	$status = 1;
                }
            }else{
                $http_code = 400;
                $body = array('error_msg'=>'Bad request 102');
            }
        }else{
            $http_code = 400;
            $body = array('error_msg'=>'Bad request 103');
        }

		if(!$status){
	        $this->_sendResponse($http_code,json_encode($body),'application/json');
		}
    }
}
