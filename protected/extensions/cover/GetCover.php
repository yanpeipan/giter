<?php
/**
 * 
 */
class GetCover   {
	private static $_instance;
	private $source;
	private $column;
	private $table;
	function __construct($source,$column) {
		$this->source = $source;
		//str 
		$this->column = $column;
		if($this->column == 'poster'){
			$this->table = 'web';
		}elseif($this->column == 'still'){
			$this->table = 'video';
		}else{ 
			return FALSE;
		}
	}
	
	public function run(){
		$data = $this->getData();
		foreach ($data as $value) {
			$picUrl = $this->relat($value,$this->column);
			$this->inStorage($picUrl,$value,$this->column);
		}
		//var_dump(($data));
	}
	
	public function runCdn(){ 
        //$path 	= '/opt/webroot/spider/www/';
		$path = Yii::app()->getBasePath().'/../';
        $result = null;
    	$db    = self::getInstancePt();
		$result = $this->getCdnEqOne();
		if(!is_array($result)) return;
        foreach ($result as  $value) {
            $pic = json_decode($value['pic'],TRUE);
             foreach ($pic as $k => $v) {
                 $filepath = $path.$v;
                 $this->upstill($filepath,$v);
             }
			$this->changCdnStatus($value['id']);
			$this->updateNewVdieo($value['vid']);
        }
	}
	
	private function getData(){
		$db  = self::getInstancePt();
		$sql = 'select id from {{v_list}} where category=2 and source like "%'.$this->source.'%"';
		$res = $db->createCommand($sql)->queryColumn();
		return $res;
	}
	/**
	 * 根据pt_relat_table找到sp_中的数据
	 */
	private function relat($value){
		$db     = self::getInstancePt();
		$db20 	= self::getInstance();
		$sql 	= 'select * from pt_relat_table where video_id=:video_id';
		$video  = $db->createCommand($sql)->bindValue(':video_id',$value)->queryRow();
		$video_name = $video[$this->source];
		if(!empty($video_name)){
		$sql    = 'select id,'.$this->column.' from sp_'.$this->source.'_video where name="'.$video_name.'"';
		$picUrl = $db20->createCommand($sql)->queryRow();
		return $picUrl;
		}
	}
	
	/**
	 * $value {{v_list}}标的id
	 */
	private function inStorage($picUrl,$value){
		$relative = array();
		if(!$this->is_e($value)){
			$arrPic   = json_decode($picUrl[$this->column],TRUE);var_dump($arrPic);
			if(empty($arrPic) || !is_array($arrPic)) return;
			foreach ($arrPic as $pic) {
				$pic = trim($pic);
				$pic = str_replace(' ', '%20', $pic);
		        $content = $this->getContent($pic);
		        $ext     = strrchr($pic, '.');
				if(strlen($ext)>8){
					$ext = '.jpg';
				}
		        $filename  = md5(uniqid()).$ext;
		        $filepath  = Yii::app()->basePath.'/../upload/origin/'.$filename[0];
		        if(!is_dir($filepath)){
		            @mkdir($filepath,0777,TRUE);
		        }
		        file_put_contents($filepath.'/'.$filename, $content);
		        $relative[] = 'upload/origin/'.$filename[0].'/'.$filename;
			}
			$json = json_encode($relative);
			//入库
			$this->insertPic($json,$value);
		}else{
			echo $value,'已经存在！',"\n";
		}
		
		//var_dump($json);
	}
    private function insertPic($json,$value){
    	$db    = self::getInstancePt();

        $sql  = "insert into  pt_".$this->table."_pic set vid=:vid,pic=:pic,cdn=1";
        $res  = $db->createCommand($sql)->bindValues(array(':vid'=>$value,':pic'=>$json))->execute();
		return $res;
    }

    private function is_e($id){
    	$db    = self::getInstancePt();
        $sql = "select count(*) cnt from pt_".$this->table."_pic where vid=:vid";
        $cnt = $db->createCommand($sql)->bindValue(':vid',$id)->queryRow();
        return $cnt['cnt'];
    }
	
	//-----------------------------------cdn-------------------------------------------
    private function getCdnEqOne(){
    	$db     = self::getInstancePt();
        $sql    = "select id,vid,pic from pt_".$this->table."_pic where cdn=1";
        $result = $db->createCommand($sql)->queryAll();
        return $result;
    }
    private function changCdnStatus($id){
    	$db     = self::getInstancePt();
        $sql = "update pt_".$this->table."_pic set cdn=0 where id=:id";
        $db->createCommand($sql)->bindValue(':id',$id)->execute();
    }
	
    private function upstill($file,$relative=''){
        $upyun = new upyun("img1tuzi", "duanjirui", "duanjirui1");
        $fh = fopen($file, 'rb');
        $re = $upyun->writeFile('/'.$relative,$fh,true);
        if($re){
            echo 'cdn成功',"\n";
        }
        fclose($fh);
        $use = $upyun->getBucketUsage();
        $use /=(1024*1024*1024);
        if($use >150){
            die;
        }
        return !!$re;
    }
	
    private function updateNewVdieo($id){
    	$db  = self::getInstancePt();
        $sql = "update {{v_list}} set is_edit=2 where id=:id";
        $db->createCommand($sql)->bindValue(':id',$id)->execute();
    }

	//-----------------------------------------------------------------------------
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
            self::$_instance = new CDbConnectionExt('mysql:host=127.0.0.1;port=3306;dbname=16tree_spider;','root','punicaG7in2012',array(array('connectionString'=>'mysql:host=127.0.0.1;dbname=16tree_spider;port=3306','username'=>'root','password'=>'punicaG7in2012')));
            self::$_instance -> active = TRUE;
            self::$_instance -> charset = 'UTF8';
            self::$_instance -> emulatePrepare = TRUE;
            self::$_instance -> tablePrefix = 'sp_';
        }
        return self::$_instance;
    } 
	
    private static $_instancePt;
    // 连接192.168.1.16
    public  static function getInstancePt() {
        if( ! (self::$_instancePt instanceof CDbConnection) ) {  // 每次都重新生成数据库连接，解决16上的链接丢失问题
            self::$_instancePt = new CDbConnectionExt('mysql:host=dbserver_puti_write;port=3306;dbname=16tree_puti;','dbuser_puti','putipunicaG7XH1842',array(array('connectionString'=>'mysql:host=dbserver_puti_write;dbname=16tree_puti;port=3306','username'=>'dbuser_puti','password'=>'putipunicaG7XH1842')));
            self::$_instancePt -> active = TRUE;
            self::$_instancePt -> charset = 'UTF8';
            self::$_instancePt -> emulatePrepare = TRUE;
            self::$_instancePt -> tablePrefix = 'pt_';
       }
        return self::$_instancePt;
    } 
}


?>