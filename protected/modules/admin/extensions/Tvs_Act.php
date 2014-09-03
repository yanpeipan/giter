<?php
class Tvs_Act{
    static $_instance = null;
    public static function getInstance(){
        if(null == self::$_instance){
            $className = get_called_class();
            self::$_instance = new $className;
        }
        return self::$_instance;
    }
    
    //管理员的操作记录
    public static function record_admin($id,$act){
       $user_id = Yii::app()->user->name;
       $sql = "INSERT INTO {{admin_act}} SET vid=:vid,action=:action,user_id=:user_id,date_time=:date_time,time=:time";
       $command = Yii::app()->db->createCommand($sql);
       $command->bindValues(array(':user_id'=>$user_id,':vid'=>$id,'time'=>time(),'date_time'=>date('Y-m-d',time()),'action'=>$act));
       $result = $command->execute(); 
       return $result;
    }
	
	public function edit_record($id,$type='ADD',$table_name='',$uname=''){
	   $date_time = date('Y-m-d',time());
       $sql = "INSERT INTO pt_cnt_edit_list SET vid=:vid,`type`=:type,table_name=:table_name,uname=:uname,date_time=:date_time";
       $command = Yii::app()->db->createCommand($sql);
       $command->bindValues(array(':vid'=>$id,':type'=>$type,':table_name'=>$table_name,':uname'=>$uname,':date_time'=>$date_time));
       $result = $command->execute(); 
       return $result;
	}
    
    
    public function _InsertTv($vid,$url,$num,$name,$source,$resolution){
        if($vid && $url){
            $model_tv = new TvNew();
            $model_tv->vid         = $vid;
            $model_tv->name        = $name;
            $model_tv->num         = $num;
            $model_tv->url         = $url;
            $model_tv->source      = $source;
            $model_tv->ctime       = time();
			$model_tv->utime       = time();
			$model_tv->resolution  = $resolution;
            if($model_tv->validate())
            {
                if($model_tv->save())
                {
                	return 1;
                }else{
                    return 0;
                    Yii::app()->end();
                }
            }else{
            	return 0;
            }
        }
    }
	public function getSource($url){
		$sources = SystemConfig::GetArrayValue('VIDEO_SOURCE',NULL,"USER");
		$sources = array_keys($sources);
		$source  = 'tuzi';
		foreach ($sources as $key => $value) {
			if(stripos($value,$url)!==FALSE){
				$source = $value;
			}
		}
		return $source;
	}
    
    //根据vid,source查询v_tv是否有记录
    public function _catTv($vid,$source){
        $sql  = "SELECT COUNT(*) AS cnt FROM {{v_tv}} WHERE vid=:vid AND source=:source";
        $cmd  = Yii::app()->db->createCommand($sql);
        $cmd->bindValue(":vid",$vid); 
        $cmd->bindValue(":source",$source); 
        $row = $cmd->queryRow();
        return $row['cnt'];
    }
    
    //查看video表的source
    public function _catVsource($id){
        $sql = "SELECT * FROM {{v_list}} WHERE id=:id";
            $cmd = Yii::app()->db->createCommand($sql);
            $cmd->bindValue(':id',$vid);
            $row = $cmd->queryRow();
            if($row)
            {
                $tv_source = $row['source'];
            }
            return $tv_source;
    }
    
    //update video source
    public function _updateSource($source,$vid){
       $sql = "UPDATE {{v_list}} SET source=:source WHERE id=:id";
       $command = Yii::app()->db->createCommand($sql);
       $command->bindValues(array(':source'=>$source,':id'=>$vid));
       $command->execute(); 
    }
    
     /**
     * admin信息
     * @param id
     */
    public function Load_user_model($id)
    {
        $model = Admin::model()->findByPk((int)$id);
        if($model==NULL)
        {
            throw new CHttpException(404, '页面不存在');
        }
        return $model;
    }

    //获取专题的列表总数
    public function _countRec($topic_id){
        $sql  = "SELECT COUNT(*) AS cnt FROM {{v_topic}} WHERE topic_id=:topic_id";
        $cmd  = Yii::app()->db->createCommand($sql);
        $cmd->bindValue(":topic_id",$topic_id); 
        $row = $cmd->queryRow();
        return $row['cnt'];
    }
    
     //查找此频道视频列表的最小order值    
    public function _countchn($ch_id){
        $sql_ch = "SELECT MIN(sort) AS minorder FROM {{v_channel}} WHERE channel=:ch_id";
        $cmd_ch = Yii::app()->db->createCommand($sql_ch);
        $cmd_ch->bindValue(':ch_id', $ch_id);
        $row_ch =$cmd_ch->queryRow();
        $min_order     = empty($row_ch['minorder'])?1:$row_ch['minorder'];
        return $min_order;
    }
	
	//查找此频道视频列表的最大order值
	public function _countchn_max($ch_id){
        $sql_ch = "SELECT MAX(orders_id) AS maxorder FROM {{v_channel}} WHERE ch_id=:ch_id";
        $cmd_ch = Yii::app()->db->createCommand($sql_ch);
        $cmd_ch->bindValue(':ch_id', $ch_id);
        $row_ch =$cmd_ch->queryRow();
        $max_order     = empty($row_ch['maxorder'])?1:$row_ch['maxorder'];
        return $max_order;
    }
    
    //查找此web专题视频列表的最小order值    
    public function _webcountchn($app_id){
        $sql_ch = "SELECT MIN(orders_id) AS minorder FROM {{web_appv}} WHERE app_id=:app_id";
        $cmd_ch = Yii::app()->db->createCommand($sql_ch);
        $cmd_ch->bindValue(':app_id', $app_id);
        $row_ch =$cmd_ch->queryRow();
        $min_order     = empty($row_ch['minorder'])?0:$row_ch['minorder'];
        return $min_order;
    }
    
    //查找频道的最小order值    
    public function _countch(){
        $sql_ch = "SELECT MIN(order_id) AS minorder FROM {{channel}}";
        $cmd_ch = Yii::app()->db->createCommand($sql_ch);
        $row_ch =$cmd_ch->queryRow();
        $min_order     = empty($row_ch['minorder'])?0:$row_ch['minorder'];
        return $min_order;
    }
    
    //修改频道列表order值
    public function _updatechn($id,$order){
        $sql_ch = "UPDATE {{v_channel}} SET sort=:order WHERE id=:id";
        $cmd_ch = Yii::app()->db->createCommand($sql_ch);
        $cmd_ch->bindValues(array(':id'=>$id,':order'=>(int)$order+1));
        $cmd_ch->execute();
    }
    
    //修改web专题视频列表order值
    public function _webupdatechn($id,$order){
        $sql_ch = "UPDATE {{web_appv}} SET orders_id=:order WHERE id=:id";
        $cmd_ch = Yii::app()->db->createCommand($sql_ch);
        $cmd_ch->bindValues(array(':id'=>$id,':order'=>(int)$order+1));
        $cmd_ch->execute();
    }
    
    //修改频道order值
    public function _updatech($id,$order){
        $sql_ch = "UPDATE {{channel}} SET order_id=:order WHERE id=:id";
        $cmd_ch = Yii::app()->db->createCommand($sql_ch);
        $cmd_ch->bindValues(array(':id'=>$id,':order'=>(int)$order+1));
        $cmd_ch->execute();
    }
    
    //获取专题列表最小的order
    public function _catRecMin($topic_id){
      $sql_recommend = "SELECT MIN(`order`) AS minorder FROM {{v_topic}} WHERE topic_id=:topic_id";
      $cmd_recommend = Yii::app()->db->createCommand($sql_recommend);
      $cmd_recommend->bindValue(':topic_id', $topic_id);
      $row_recommend =$cmd_recommend->queryRow();
      $min_order     = empty($row_recommend['minorder'])?0:$row_recommend['minorder'];
      return $min_order;
    }
    
    public static function _curl($url){
        $ch = curl_init();   
        $timeout = 5;   
        curl_setopt($ch, CURLOPT_URL, $url);   
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);   
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);   
        //在需要用户检测的网页里需要增加下面两行   
        //curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);   
        //curl_setopt($ch, CURLOPT_USERPWD, US_NAME.":".US_PWD);   
        $contents = curl_exec($ch);   
        curl_close($ch);   
        return $contents;   
    }
    //读取远程图片文件
    /**
                获取远程文件内容
     @param $url 文件http地址
    */
     function fopen_url($url)
     {
         //$url=urlencode($url);
       
         if (function_exists('file_get_contents')) {
             $file_content = @file_get_contents($url);
         } elseif (ini_get('allow_url_fopen') && ($file = @fopen($url, 'rb'))){
             $i = 0;
             while (!feof($file) && $i++ < 1000) {
                 $file_content .= strtolower(fread($file, 4096));
             }
             fclose($file);
         } elseif (function_exists('curl_init')) {
             $curl_handle = curl_init();
             curl_setopt($curl_handle, CURLOPT_URL, $url);
             curl_setopt($curl_handle, CURLOPT_HEADER, 0);
             curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER,1);
             curl_setopt($curl_handle, CURLOPT_FAILONERROR,1);
             curl_setopt($curl_handle, CURLOPT_USERAGENT, 'Trackback Spam Check');
             $file_content = curl_exec($curl_handle);
             curl_close($curl_handle);
         } else {
             $file_content = '';
         }
         return $file_content;
     }
    
    public static function _upyunimg($img){
    	if($img){
    		$cdn = SystemConfig::Get("SYSTEM_CDN",null,'USER');
			if($cdn){
				$upyun = new upyun("img1tuzi", "duanjirui", "duanjirui1");
		        $content = Tvs_Act::fopen_url($img);
		        $re = $upyun->writeFile('/'.$img,$content,true);
		        if($re==true){
		            return $img;
		        }    
			}else{
				return $img;
			}
    	}
    	
        
    }
	
	 public static function _avatar_img($img){
        $upyun = new upyun("avatar-img", "guochao", "guochaoluxtone");
        $upyun->debug = true;
        $content = Tvs_Act::fopen_url($img);
        $re = $upyun->writeFile('/'.$img,$content,true);
        if($re==true){
            return $img;
        }    
    }
	
	public static function _upyun_img($img,$upyun_url=''){
        $upyun = new upyun("img1tuzi", "duanjirui", "duanjirui1");
        $upyun->debug = true;
        $ctx = stream_context_create(array(  
            'http' => array(  
                        'timeout' =>60 //设置一个超时时间，单位为秒  
                      )  
             )  
        );
        $img_url = Yii::app()->params['imgUrl'].$img;
        $content = file_get_contents($img_url,0,$ctx);
        //$content = Yii::app()->curl->run($img);
        //$content = Tvs_Act::_curl($img);
        $re = $upyun->writeFile('/'.$upyun_url,$content,true);
        if($re==true){
            return $upyun_url;
        }    
    }
    
    //更新video表某条记录的yun_img数据
    public function updateV($id,$img){
        $sql = "UPDATE {{v_list}} SET yun_img=:yun_img WHERE id=:id";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindValues(array(':yun_img'=>$img,':id'=>$id,));
        $result = $command->execute(); 
    }
    
    //授权信息记录
    public static function setauth($id){
       $user_id = Yii::app()->user->name;
       $sql = "INSERT INTO {{auth_info}} SET user_id=:user_id,time=:time,vid=:vid,date_time=:date_time";
       $command = Yii::app()->db->createCommand($sql);
       $command->bindValues(array(':user_id'=>$user_id,':vid'=>$id,'time'=>time(),'date_time'=>date('Y-m-d',time())));
       $resu = $command->execute();
    }
    
    public static function Load_video_model($id)
    {
        $sql = "SELECT * FROM {{v_list}} WHERE id = :id";
        $model = Yii::app()->db-> createCommand($sql)->bindValue(':id',$id)->queryRow();
        if($model===null){
            throw new CHttpException(404,'页面不存在');     
        }
        return $model;
    }
    
    //查找video表的某条记录是否存在
    public static function video_count($id)
    {
        $sql = "SELECT COUNT(*) as cnt FROM {{v_list}} WHERE id = :id";
        $model = Yii::app()->db-> createCommand($sql)->bindValue(':id',$id)->queryRow();
        if($model===null){
            throw new CHttpException(404,'页面不存在');     
        }
        return $model['cnt'];
    }
    
    
    //更新video表某条记录的resolution,source_resolution数据
    public function update_v($id,$r,$sr){
        $sql = "UPDATE {{v_list}} SET resolution=:resolution,source_resolution=:source_resolution WHERE id=:id";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindValues(array(':resolution'=>$r,':source_resolution'=>$sr,':id'=>$id,));
        $result = $command->execute(); 
        return $result;
    }
    
    //解析video表某条记录里resolution_value(json)数据
    public function resolution_value($id){
        $re = Tvs_Act::Load_video_model($id);
        $data = '';
        if($re['resolution_value']!=''){
            $data = json_decode(urldecode($re['resolution_value']),true);
        }
        return $data;
    }
    
    //更新video 表某条记录的resolution_value数据
    public function update_value($id,$rv){
        $sql = "UPDATE {{v_list}} SET resolution_value=:resolution_value WHERE id=:id";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindValues(array(':resolution_value'=>$rv,':id'=>$id,));
        $result = $command->execute(); 
        return $result;
    }
    
     //查询频道视频列表中视频
    public function get_cvideo($vid,$ch_id){
        $sql = "SELECT COUNT(*) cnt FROM {{v_channel}} WHERE v_id=:v_id AND ch_id=:ch_id";
        $command = Yii::app()->db-> createCommand($sql); 
        $command->bindValues(array(':v_id'=>$vid,':ch_id'=>$ch_id));
        $re = $command->queryRow();
        return $re['cnt'];
    }
    
    //添加频道视频
    public function insert_cvideo($vid,$ch_id,$order){
         $sql = "INSERT INTO {{v_channel}} SET ch_id=:ch_id,time=:time,v_id=:v_id,orders_id=:order,user_id=:user_id";
         $command = Yii::app()->db->createCommand($sql);
         $command->bindValues(array(':ch_id'=>$ch_id,':time'=>time(),':v_id'=>$vid,':order'=>$order,':user_id'=>Yii::app()->user->name));
         $result = $command->execute();
         return $result;
    }
	
	//查看频道数据条数
	public function getzjchannelcount($ch_id){
		$sql = "SELECT COUNT(*) cnt FROM {{v_channel}} WHERE ch_id=:ch_id";
        $command = Yii::app()->db-> createCommand($sql); 
        $command->bindValues(array(':ch_id'=>$ch_id));
        $re = $command->queryRow();
        return $re['cnt'];
	}
	
	public function delchanneldata($ch_id){
		$sql = "DELETE FROM {{v_channel}} WHERE ch_id=:ch_id ORDER BY orders_id DESC LIMIT 1";
        $command = Yii::app()->db->createCommand($sql);
		$command->bindValue(':ch_id',$ch_id);
        $result = $command->execute(); 
        return $result;
	}
    
  

	
    
    //获取视频分类对应类型的最大排序
    public function get_type_order($mark){
        $sql_ch = "SELECT MAX(orders) AS maxorder FROM {{type}} WHERE mark=:mark";
        $cmd_ch = Yii::app()->db->createCommand($sql_ch);
        $cmd_ch->bindValue(':mark', $mark);
        $row_ch =$cmd_ch->queryRow();
        $max_order     = empty($row_ch['maxorder'])?0:$row_ch['maxorder'];
        return $max_order;
    }
	
	//获取视频分类对应地区的最大排序
    public function get_area_order($mark){
        $sql_ch = "SELECT MAX(orders) AS maxorder FROM {{area}} WHERE mark=:mark";
        $cmd_ch = Yii::app()->db->createCommand($sql_ch);
        $cmd_ch->bindValue(':mark', $mark);
        $row_ch =$cmd_ch->queryRow();
        $max_order     = empty($row_ch['maxorder'])?0:$row_ch['maxorder'];
        return $max_order;
    }
	
	
	/*
	 * 查询一条记录
	 * $table is table name
	 * $ids 为条件id
	 * $rows  查询字段名
	 */
	public function catRow($table,$ids=array(),$rows='*'){
		$where = ''; 
		$id = 0;
		if($ids)foreach($ids as $k=>$v){
			$where =$k.'=:'.$k;
			$id = $v;
		}
		if($where==''||$id==0){
			return false;
		}
		$sql  = "SELECT {$rows} FROM {{{$table}}} WHERE {$where}";
        $cmd  = Yii::app()->db->createCommand($sql)->bindValue(':id',$id);
        $row = $cmd->queryRow();
        return $row;
	}
	
	
	
	//修改update_url
	public function update_source_url($id,$update_url){
		$sql = "UPDATE {{v_list}} SET update_url=:update_url,is_spider=0 WHERE id=:id";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindValues(array(':update_url'=>$update_url,':id'=>$id,));
        $result = $command->execute(); 
        return $result;
	}
	
		


	//修改source_tv的分集
	public function del_source_tv($vid,$source,$tv_url){
		//检查relat_table是否已经存在vid的片子
		$status = false;
		$source1 = $this->s2t($source);
		$sql = "SELECT id,video_id vid,{$source1},{$source1}_ok FROM {{relat_table}} WHERE video_id=:vid LIMIT 1";
		$cmd = Yii::app()->db->createCommand($sql);
		$cmd->bindValue(":vid",$vid);
		$row = $cmd->queryRow();
		if($row&&$row[$source1]!=''){
			//update source_tv里相关的tv_url
			$table = 'sp_'.$source1.'_tv';
			$db_spider = Op::getInstance()->_connect();
			$sql1 = "UPDATE {$table} SET is_del=2 WHERE BINARY tv_url=:tv_url LIMIT 1";
			$cmd1 = $db_spider->createCommand($sql1)->bindValue(":tv_url",mysql_escape_string($tv_url));
		    $status = $cmd1->execute();
		}
		return $status;
	}
       
}