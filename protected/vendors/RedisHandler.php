<?php
class RedisHandler
{
    const LIST_PUSH = "list_push";
    const LIST_PUSH_THIRD = "list_push_new";
	const LIST_PUSH_AD = "list_push_ad";
    
    const KEY_LIST = "Rediska_Key_List";
	
    const KEY_HASH = "Rediska_Key_Hash";
    const KEY_STRING = "Rediska_Key"; 
    
    
    const EXPIRE = 3600;//300 seconds
    static private $_redisServer = null;
    static private $_redisStore   = array();
	static private $redis_status = 'redis_status';//redis状态 1（ok），（ 0） down
	
	static public function getInstance(){
		try{
			$obj->auth('jlvnw23dzn4656towjffj5839e2ndnf');
    	}catch(exception $e){
			//返回文件缓存对象
			$obj = new FileCacheHandler();
    	}
		return $obj;
    }
    
    /**
     * key-value  get
     */
    static public function kv_get($name){
    	try{
    		$result = @json_decode(Yii::app()->memcache->get($name), true);
    		//$result = @json_decode(self::getInstance()->get($name), true);
	        return $result;
    	}catch(Exception $e){
    	}
    }
    
    /**
     * key-value  set
     */
    static public function kv_set($name,$value,$expire=self::EXPIRE){
    	try{
			$value = @json_encode($value);
			return Yii::app()->memcache->set($name, $value, $expire);
	        //return self::getInstance()->setex($name, $expire, $value);
    	}catch(Exception $e){
    	}
    }
    
    /**
     * list append
     */
    static public function list_append($data){
    	try{
			$data = @json_encode($data);
	        return self::getInstance()->rPush(self::LIST_PUSH, $data);
		}catch(Exception $e){
		}
    }
    
    /**
     * list shift
     */
    static public function list_shift(){
    	try{
    		$result = self::getInstance()->lPop(self::LIST_PUSH);
			$result = @json_decode($result, true);
        	return $result;
		}catch(Exception $e){
		}
    }
	
		
	/**
     * list append
     */
    static public function list_append_ad($data){
    	try{
			$data = @json_encode($data);
	        return self::getInstance()->rPush(self::LIST_PUSH_AD, $data);
		}catch(Exception $e){
		}
    }
    
    /**
     * list shift
     */
    static public function list_shift_ad(){
    	try{
    		$result = self::getInstance()->lPop(self::LIST_PUSH_AD);
			$result = @json_decode($result, true);
        	return $result;
		}catch(Exception $e){
		}
    }
    
    /**
     * hash set
     */
    static public function hash_set($name,array $data){
    	try{
			$data = @json_encode($data);
        	return self::getInstance()->hSet(self::KEY_HASH,$name,$data);
		}catch(Exception $e){
		}
    }
    
    /**
     * hash get
     */
    static public function hash_get($name){
    	try{
    		$result = self::getInstance()->hget(self::KEY_HASH,$name);
			$result = @json_decode($result, true);
        	return $result;
		}catch(Exception $e){
		}
    }
    
    static public function kv_set_expire($key, $value, $expire)
    {
    	try{
			$value = @json_encode($value);
			return Yii::app()->memcache->set($key, $value, $expire);
			//return self::getInstance()->setex($key, $expire, $value);
    	}catch(Exception $e){
		}
    }

}
