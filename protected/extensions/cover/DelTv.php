<?php
/**
 * 
 */
class DelTv  {
	public $apiAlbumItem = 'http://api.tudou.com/v3/gw?method=album.item.get&appKey=myKey&format=json&albumId=';
	public $apiItemInfo  = 'http://api.tudou.com/v3/gw?method=item.info.get&appKey=b752af45e1c35c8b&format=json&itemCodes=';
	public $apiItemState = 'http://api.tudou.com/v3/gw?method=item.state.get&appKey=b752af45e1c35c8b&format=json&itemCodes=';
	function __construct() {
		
	}
	function run(){
		$start_time = microtime(TRUE);
		$result = $this->readNewTv();
		foreach ($result as $key => $value) {
			//http://www.tudou.com/albumplay/mgf7cKA0DQc.html
			if(stripos($value['tv_url'], 'albumplay')){
				$exp1 = explode('albumplay/', $value['tv_url']);
				$exp2 = explode('.', $exp1[1]);
				$exp3 = explode('/', $exp2[0]);
				if(!isset($exp3[1])){
					var_dump($value['tv_url']);
					sleep(6);
					var_dump('sleep');
					continue;
				}
				$videoInfoen = $this->getContent($this->apiItemInfo.$exp3[1]);
				$videoInfode = json_decode($videoInfoen,TRUE);
				var_dump($videoInfode['multiResult']['results'][0]['itemCode']);var_dump($value['id']);
				if(isset($videoInfode['multiResult']['results'][0]['itemCode'])){
					
				}
				usleep(650000);
			}
			//$this->getContent();
		}
		var_dump(count($result));
		echo (microtime(TRUE) - $start_time);
	}
	function readNewTv(){
		$sql = "select id,tv_url,tv_parent_id from {{v_tv}} where source='tudou' and is_del='0' order by id limit 1000000";
		$res = Yii::app()->db_puti->createCommand($sql)->queryAll();
		return $res;
	}
	
    public function getContent($url){
        try{
            //$pattern = '/^http:\/\/(([A-Z0-9][A-Z0-9_-]*)(\.[A-Z0-9][A-Z0-9_-]*)+)/i';
            //if(!preg_match($pattern, $url)) {echo '非法URL！';retrun;}
            $curl = curl_init($url); 
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HEADER, 0); 
            curl_setopt($curl,CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($curl,CURLOPT_MAXREDIRS ,5);
            curl_setopt($curl,CURLOPT_AUTOREFERER ,1);
            curl_setopt($curl, CURLOPT_TIMEOUT, 60);
            curl_setopt($curl,CURLOPT_HTTP200ALIASES,array(200));
            curl_setopt($curl, CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 6.1; rv:6.0.2) Gecko/20100101 Firefox/6.0.2'); 
            $html = @curl_exec($curl);
            if($html == CURLE_GOT_NOTHING) return ; 
            return $html;
        }catch(Exception $e){
            var_dump($e->getMessage());
        }
    }     
        // 连接16tree_spider
    public  static function getInstance() {
        if( ! (self::$_instance instanceof CDbConnection) ) {
            self::$_instance = new CDbConnectionExt('mysql:host=192.168.1.20;port=3306;dbname=16tree_spider;','root','punica1001',array(array('connectionString'=>'mysql:host=192.168.1.20;dbname=16tree_spider;port=3306','username'=>'root','password'=>'punica1001')));
            self::$_instance -> active = TRUE;
            self::$_instance -> charset = 'UTF8';
            self::$_instance -> emulatePrepare = TRUE;
            self::$_instance -> tablePrefix = 'sp_';
        }
        return self::$_instance;
    }
}




?>