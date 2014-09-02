<?php
/** 
* Curl 请求
* author:shishuai <shishuai@luxtonenet.com>
* date:2013-8-28*/
Class Curl{
	/**
	 * 发送请求
	 * @param $url
	 * @param $params
	 * @param $proxy 代理设置
	 */
	public static function curlRequest($url, $params, $proxy=""){
	    $proxy=trim($proxy);
	    $user_agent ="Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)";
	    $ch = curl_init();    // 初始化CURL句柄
	    if(!empty($proxy)){
	        curl_setopt ($ch, CURLOPT_PROXY, $proxy);//设置代理服务器
	    }
	    curl_setopt($ch, CURLOPT_URL, $url); //设置请求的URL
	    //curl_setopt($ch, CURLOPT_FAILONERROR, 1); // 启用时显示HTTP状态码，默认行为是忽略编号小于等于400的HTTP信息
	    //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);//启用时会将服务器服务器返回的“Location:”放在header中递归的返回给服务器
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);// 设为TRUE把curl_exec()结果转化为字串，而不是直接输出
	    curl_setopt($ch, CURLOPT_POST, 1);//启用POST提交
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $params); //设置POST提交的字符串
	    //curl_setopt($ch, CURLOPT_PORT, 80); //设置端口
	    curl_setopt($ch, CURLOPT_TIMEOUT, 50); // 超时时间
	    curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);//HTTP请求User-Agent:头
	    //curl_setopt($ch,CURLOPT_HEADER,1);//设为TRUE在输出中包含头信息
	    //$fp = fopen("example_homepage.txt", "w");//输出文件
	    //curl_setopt($ch, CURLOPT_FILE, $fp);//设置输出文件的位置，值是一个资源类型，默认为STDOUT (浏览器)。
	    //SSL
	    /*curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); 
	    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);*/
	    
	    curl_setopt($ch,CURLOPT_HTTPHEADER,array(
	        'Accept-Language: zh-cn',
	        'Connection: Keep-Alive',
	        'Cache-Control: no-cache'
	    ));//设置HTTP头信息
	    $document = curl_exec($ch); //执行预定义的CURL
	    $info=curl_getinfo($ch); //得到返回信息的特性
	    /*
	    if($info[http_code]=="405"){
	         echo "bad proxy {$proxy}\n";  //代理出错
	        exit;
	     }*/
	    if($info['http_code']=="200"){
			$result = $document;
	    }else{
	    	$result = false;
	    }
	    curl_close($ch);
	    return $result;
	}
	
	static public function getToken(){
		$model = System::model()->findbyPk(1);
		$token = $model->token_key;
		return $token;
	}
	static public function getApiContent($method,$url,$params=array()){
		$token = Curl::getToken();
		$must_params = array(
						'method'=>$method,
						//'version'=>'4.0',
						//'soft'=>'',
						//'soft_version'=>'1.2',
						'token'=>$token,
						);
		$AllParams = array_merge($params,$must_params);
		$AllParams = http_build_query($AllParams);
		$content = Curl::curlRequest($url,$AllParams);
		return json_decode($content,TRUE);
	}
	//远程下载文件
	static public function downloadFile($url, $file="", $timeout=60){
	    $file = empty($file) ? pathinfo($url,PATHINFO_BASENAME) : $file;
	    $dir = pathinfo($file,PATHINFO_DIRNAME);
	    !is_dir($dir) && @mkdir($dir,0755,true);
	    $url = str_replace(" ","%20",$url);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $temp = curl_exec($ch);
        return $temp;
	}
}


