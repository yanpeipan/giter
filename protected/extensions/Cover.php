<?php
class Cover
{

	
    public static function getVideoImg($pic,$size1=146,$size2=204){
       $url = Yii::app()->params['imgUrl'];
	   $url1 = Yii::app()->getBaseUrl(true);
       $filePath = self::getVideoImgUrl($pic,$size1,$size2); 
       $destPath_thumb = $url.$filePath;
       if(self::url_exists($url1.'/'.$pic)==true){
          $destPath_thumb = $url1.'/'.$pic;
       }elseif(self::url_exists($url.$pic)==true){
	   	  return $destPath_thumb;
	   }else{
	   	  $destPath_thumb = Yii::app()->params['yunimg'].$pic.'!146x204';
	   }
       return $destPath_thumb;
    }
    
    public static function getdevImg($pic,$size1=146,$size2=204){
       $url = Yii::app()->params['img_url1'];
       $filePath = self::getHeadImgUrl($pic,$size1,$size2); 
       $destPath_thumb = $url.$filePath;
       return $destPath_thumb;
    } 

    private static function getVideoImgUrl($url,$size1,$size2){
        if(!empty($url)) $url = str_replace("origin","{$size1}x{$size2}",$url);
        return $url;
    }
    
    private static function getHeadImgUrl($url,$size1,$size2){
        if(!empty($url)) $url = str_replace("originss","{$size1}x{$size2}",$url);
        return $url;
    }
	
    public function getCpImg($pic,$size1=146,$size2=204){
       $url = Yii::app()->params['yunimg'];
	   $url1 = Yii::app()->getBaseUrl(true);
	   if(Cover::url_exists($url.$pic)==true){
	   		$destPath_thumb = $url.$pic;
	   }elseif(Cover::url_exists($url1.'/'.$pic)==true){
	   		$destPath_thumb = $url1.'/'.$pic;
	   }else{
	   		$url = Yii::app()->params['imgUrl'];
	   		$filePath = self::getVideoImgUrl($pic,$size1,$size2); 
			$destPath_thumb = $url.$filePath;
	   }
       return $destPath_thumb;
    } 
	
	public function url_exists($url) {
        $head=@get_headers(urldecode($url)); 
        if(is_array($head)) {
           if(strpos($head[0],'HTTP/1.0 200')===0||strpos($head[0],'HTTP/1.1 200')===0){
            	return true; //有文件
        	}else{
            	return false;; //没有文件
        	}
        }
        return false;
    }
	
	public static function getVideoCover($pic, $location="yunimg", $s1=146, $s2=204)
	{
		if(empty($pic))
			return "";
		
		$addr = "";
		switch($location){
			case "yunimg":
				$addr = Yii::app()->params["yunimg"].$pic."!".$s1."x".$s2;
				break;
			case "topic":
				$addr = Yii::app()->params["picUrl"].$pic;
				break;
			default:
				$addr = "";
				break;
		}
		return $addr;
	}
     
}