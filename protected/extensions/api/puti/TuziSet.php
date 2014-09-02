<?php
/**
 * 兔单类
 */
class TuziSet
{ 
	private $url;//封面图片
		
	/**
	 * 加入兔单
	 */
	public function import()
	{
		$data = isset($_POST['data']) ? $_POST['data'] : '';
		$uid = isset($_POST['uid']) ? $_POST['uid'] : '';
        $is_zb = isset($_POST['is_zb']) ? $_POST['is_zb'] : 0;
		$power = isset($_POST['public']) ? $_POST['public'] : 0;
		$desc = isset($_POST['desc']) ? $_POST['desc'] : '';
		
		$json_array['status'] = '0';
		$json_array['id'] = '';
		$not_allow_url_list = array();
		if(!empty($data)&&!empty($uid))
		{
			//解析XML
			$xml_obj = simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA );
			if($xml_obj)
			{
				$channel_title = trim($xml_obj->channel->title);
				$channel_description = trim($xml_obj->channel->description);
				$channel_link = trim($xml_obj->channel->link);

				$channel_pic = trim($xml_obj->channel->image->url);	
				
				$channel_items = $xml_obj->channel->item;
				if(!empty($channel_items))
				{
					//检查item地址是否在允许的source中
					$channel_array = @json_decode(@json_encode($xml_obj->channel), 1);
					
					//判断几维数组
					$arr_count = $this->judgeArr($channel_array['item']);
					
					$allow_items = array();
					if($arr_count == 1)
					{
						//一个
						$is_allow_url = $this->judgeSource($channel_array['item']['link']);
						if($is_allow_url||$is_zb == 1)
						{
							$allow_items[] = $channel_array['item'];
						}else{
							$not_allow_url_list[] = $channel_array['item']['link'];
						}
					}else{
						//多个
						foreach($channel_array['item'] as $item)
						{
							$is_allow_url = $this->judgeSource($item['link']);
							if($is_allow_url || $is_zb == 1)
							{
								$allow_items[] = $item;
							}else{
								$not_allow_url_list[] = $item['link'];
							}
						}
					}	
					
					if(empty($allow_items))
					{
						$json_array['error_msg'] = '7001';
						return $json_array;
					}
					
					//保存封面图片
					//$img_content = base64_encode(@file_get_contents('/var/htdocs/puti.tv/about-tuzi.png'));//$channel_pic;
					//获取图片格式
					//data:image/jpeg;base64,
					preg_match('/data\:image\/(.*)\;base64\,(.*)/', $channel_pic, $out);
					$image_type = isset($out[1]) ? $out[1] : '';
					$channel_pic = isset($out[2]) ? $out[2] : '';
					$img_content = base64_decode($channel_pic);
					if(in_array($image_type, array('jpg','png','gif','jpeg')) && !empty($img_content)){
						$re = $this->_upyunurl($img_content, $image_type);
						if(!$re){
							$json_array['error_msg'] = '7002';
						}
					}

					//保存兔单信息
					$values = array(
						':title'=>$channel_title,
						':desc'=>$desc,
						':pic'=>$this->url,
						':power'=>$power,
						':uid'=>$uid,
						':is_zb'=>$is_zb,
						':type'=>$channel_description,
					);
					$sql = "INSERT INTO {{diy_info}} SET title=:title,`desc`=:desc,pic=:pic,power=:power,uid=:uid,is_zb=:is_zb,type=:type";
					$cmd = Yii::app()->db->createCommand($sql);
					$result = $cmd->bindValues($values)->execute();
					if($result)
					{
						//diy_id
						$diy_id = Yii::app()->db->getLastInsertID();
						if($diy_id)
						{
							//记录积分
							Integral::record($uid,5);
							
							$json_array['id'] = $diy_id;
							//保存兔单下的影片信息
							$i = 0;
							foreach($allow_items as $item)
							{
								$source = $this->getUrlRoot($item['link']);
								$values = array(
									':title'=>$item['title'],
									':play_url'=>$item['link'],
									':diy_id'=>$diy_id,
									':source'=>$source,
								);
								$sql = "INSERT INTO {{diy_list}} SET title=:title,play_url=:play_url,diy_id=:diy_id,source=:source";
								$cmd = Yii::app()->db->createCommand($sql);
								$result = $cmd->bindValues($values)->execute();
								if($result)
								{
									$i++;
								}
							}
							if($i > 0) $json_array['status'] = '1';
						}
					}

				}					
			}
		}
		
		$json_array['not_allow_url_list'] = $not_allow_url_list;
		return $json_array;
	}


	/**
	 * 更新兔单
	 * @param $id
	 * @param $data
	 * @param $uid
	 */
	 public function updateTudanList()
	 {
 	 	$json_array = array('status'=>'0');
		
		$data = isset($_POST['data']) ? trim($_POST['data']) : '';
		$uid = isset($_POST['uid']) ? $_POST['uid'] : '';
		$tudan_id = isset($_POST['id']) ? intval($_POST['id']) : '';
		
		//检查是不是该用户的兔单
		$sql = "SELECT uid FROM {{diy_info}} WHERE id=:id";
		$cmd = Yii::app()->db->createCommand($sql);
		$row = $cmd->bindValue(':id', $tudan_id)->queryRow();
		$userid = isset($row['uid']) ? $row['uid'] : '';  
		if($uid != $userid){
			return $json_array;
		}
		
		//解析XML
		$xml_obj = (array)simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA );
		if($xml_obj)
		{
			//检查item地址是否在允许的source中
			$channel_array = $xml_obj['item'];
			$allow_items = array();
			if(is_array($channel_array))
			{
				//多个
				foreach($channel_array as $item)
				{
					$item = (array)$item;
					$is_allow_url = $this->judgeSource($item['link']);
					if($is_allow_url)
					{
						$allow_items[] = $item;
					}else{
						$not_allow_url_list[] = $item['link'];
					}
				}
			}else{
				//1个
				$item = (array)$channel_array;
				$is_allow_url = $this->judgeSource($item['link']);
				if($is_allow_url)
				{
					$allow_items[] = $item;
				}else{
					$not_allow_url_list[] = $item['link'];
				}
			}
			
			if(empty($allow_items))
			{
				$json_array['error_msg'] = '7001';
				return $json_array;
			}

			$json_array['id'] = $tudan_id;
			$values = "";
			for($i=0;$i<count($allow_items);$i++)
			{
				$source = $this->getUrlRoot($allow_items[$i]['link']);
				if($i != (count($allow_items)-1)){
					$values .= "('{$allow_items[$i]['title']}','{$allow_items[$i]['link']}',$tudan_id,'{$source}'),";
				}else{
					$values .= "('{$allow_items[$i]['title']}','{$allow_items[$i]['link']}',$tudan_id,'{$source}')";
				}
			}
			$sql = "INSERT INTO {{diy_list}} (title,play_url,diy_id,source) VALUES$values";
			$cmd = Yii::app()->db->createCommand($sql);
			$result = $cmd->execute();
			if($result){
				$json_array = array('status'=>'1');
			}
		}
	 	return $json_array;
	 }
	
	/**
	 * 获取兔单
	 * @param $uid
	 * @param $page
	 * @param $pagesize
	 */
	public function getTuziSetList()
	{
		$uid = isset($_POST['userid']) ? $_POST['userid'] : '';
		$page = isset($_POST['page']) ? $_POST['page'] : 1;
		$pagesize = isset($_POST['page']) ? $_POST['page'] : 10;
		
		if(empty($page))
		{
			$page = 1;
		}
		if(empty($pagesize))
		{
			$pagesize = 10;
		}
		
		$start = ($page-1)*$pagesize;
		
		$sql = "SELECT * FROM {{diy_info}} WHERE uid=:uid ORDER BY id DESC LIMIT $start, $pagesize";
		$cmd = Yii::app()->db->createCommand($sql);
		$rows = $cmd->bindValue(':uid', $uid)->queryAll();
		
		//sum
		$count = $this->getSetListCount($uid);
		$json_array = array(
			'status'=>'1',
			'count'=>$count,
			'data'=>$rows,
		);

		return $json_array;
	}
	
	/**
	 * 获取用户兔单总数
	 */
	private function getSetListCount($uid)
	{
		$sql = "SELECT id FROM {{diy_info}} WHERE uid=:uid";
		$cmd = Yii::app()->db->createCommand($sql);
		$rows = $cmd->bindValue(':uid', $uid)->queryAll();
		
		$count = $rows ? strval(count($rows)) : '0';
		return $count;
	}
	
	/**
	 * 验证接入源
	 * @param $url
	 */
	private function judgeSource($url)
	{
		$status = 0;
		
        $url = $url . "/";
        preg_match("/((\w*):\/\/)?\w*\.?([\w|-]*\.(com.cn|net.cn|gov.cn|org.cn|com|net|cn|org|asia|tel|mobi|me|tv|biz|cc|name|info))\//", $url, $ohurl);
        $root_domain = isset($ohurl[0]) ? $ohurl[0] : '';

		$allow_source = $this->getAllowTvSource();
		foreach($allow_source as $val)
		{
			if(strpos($root_domain, $val) !== false)
			{
				$status = 1;
				break;
			}
		}
		
		return $status;
	}
	
	/**
	 * 获取根域名
	 */
	function getUrlRoot($url)
	{
        #添加头部和尾巴
        $url = $url . "/";
        preg_match("/((\w*):\/\/)?\w*\.?([\w|-]*\.(com.cn|net.cn|gov.cn|org.cn|com|net|cn|org|asia|tel|mobi|me|tv|biz|cc|name|info))\//", $url, $ohurl);
        
        $root_domain = isset($ohurl[0]) ? $ohurl[0] : '';
		
		$allow_source = $this->getAllowTvSource();
		$source = 'dianlv';
		foreach($allow_source as $val)
		{
			if(strpos($root_domain, $val) !== false)
			{
				if($val == 'baidu'){
					$val = 'bdyun';
				}
				$source = $val;
				break;
			}
		}
		return $source;
	}


    /**
     * 分集播放源过滤
     * @param $uid
     */
    private function getAllowTvSource()
    {
    	$cache_id = md5('allow_source_tuziset_nn');
    	$allow_fix_source = RedisHandler::kv_get($cache_id);
		if($allow_fix_source){
			return $allow_fix_source;
		}
		
        $sql = "SELECT cfg_value FROM {{config}} WHERE cfg_name='source_all'";
        $cmd = Yii::app()->db->createCommand($sql);
        $row = $cmd->queryRow();
        
        $allow_fix_source = array();
        if($row)
        {
            $allow_fix_source = explode(',', $row['cfg_value']);
			$allow_fix_source[] = 'baidu';
			RedisHandler::kv_set_expire($cache_id, $allow_fix_source, Yii::app()->params['cache']['expire']);
        }
        return $allow_fix_source;
    }
	
    private function _upyunurl($file_content, $image_type)
    {
        Yii::import('ext.upyun.*');
		$upyun = new upyun("img1tuzi", "duanjirui", "duanjirui1");
		//$upyun->debug=true;
		$file_name = md5(md5($file_content)+time()).".".$image_type;
		$dir = substr($file_name, 0, 1);
		$this->url = '/upload/tuziset/'.$dir."/".$file_name;
        $re = $upyun->writeFile($this->url, $file_content, true);
		
		return $re;
    }


	/**
	 * 判断是不是一维数组，只对于此类
	 */
	private function judgeArr($arr)
	{
		$i = 0;
		if(is_array($arr))
		{
			$i = 1;
			foreach($arr as $val)
			{
				if(is_array($val))
				{
					$i++;
				}
				break;
			}
		}
		return $i;
	}
	
	
	/**
	 * 兔单列表for plugin
	 */
	 public function getList()
	 {
	 	$json_array = array('status'=>'0');
		$uid = isset($_POST['uid']) ? trim($_POST['uid']) : '';
		$page= isset($_POST['page']) ? trim($_POST['page']) : 1;
		$pagesize = isset($_POST['pagesize']) ? trim($_POST['pagesize']) : 10;
		
		$cache_id = md5("uid_".$uid."page_".$page."pagesize_".$pagesize);
		$data = RedisHandler::kv_get($cache_id);
		if($data){
			return $data;
		}
		
		if($uid){
			//查找兔单列表
			$page = empty($page) ? 1 : $page;
			$pagesize = empty($pagesize) ? 10 : $pagesize;
			$start = ($page-1)*$pagesize;
			$sql  = "SELECT id,title AS name FROM {{diy_info}} WHERE uid=:uid AND is_del=0 LIMIT $start,$pagesize";
			$cmd  = Yii::app()->db->createCommand($sql);
			$rows = $cmd->bindValue(':uid', $uid)->queryAll();
			if($rows){
				$count = $this->getSum($uid);
				$json_array = array(
					'status'=>'1',
					'count'=>$count,
					'data'=>$rows
				);
				RedisHandler::kv_set_expire($cache_id, $json_array, Yii::app()->params['cache']['expire']);
			}
		} 	
		
		return $json_array;
	 }

	 private function getSum($uid)
	 {
		$sql = "SELECT count(*) AS cnt FROM {{diy_info}} WHERE uid=:uid AND is_del=0";
		$cmd = Yii::app()->db->createCommand($sql);
		$row = $cmd->bindValue(':uid', $uid)->queryRow();
		$count = isset($row['cnt']) ? $row['cnt'] : '0';
	 	return $count;
	}
	 
	 /**
	  * 添加兔单内容
	  */
	  public function add()
	  {
	  	$json_array = array('status'=>'0');
		
		$uid = isset($_POST['uid']) ? trim($_POST['uid']) : '';
		$id  = isset($_POST['id']) ? trim($_POST['id']) : '';
		$title = isset($_POST['title']) ? trim($_POST['title']) : '';
		$url = isset($_POST['url']) ? trim($_POST['url']) : '';
		
		if($id){
			if($id == -1){
				//默认兔单处理
				$id = $this->getDefaultTudanId($uid);
				if(!$id){
					$json_array['error_msg'] = '7004';//默认兔单不存在或创建失败
					return $json_array;
				}
			}
			
			//接入点检查
			if(!$this->judgeSource($url)){
				$json_array['error_msg'] = '7001';
				return $json_array;
			}
			
			//$source
			$source = $this->getUrlRoot($url);
			
			//重复性检查
			$values = array(
				':diy_id'=>$id,
				':play_url'=>$url,
				':source'=>$source,
			);
			$sql = "SELECT id FROM {{diy_list}} WHERE diy_id=:diy_id AND play_url=:play_url AND source=:source";
			$cmd = Yii::app()->db->createCommand($sql);
			$row = $cmd->bindValues($values)->queryRow();
			if($row){
				$json_array['error_msg'] = '7003';
				return $json_array;
			}
			
			//insert
			$values = array(
				':diy_id'=>$id,
				':title'=>$title,
				':play_url'=>$url,
				':source'=>$source,
			);
			$sql = "INSERT INTO {{diy_list}} SET title=:title,play_url=:play_url,source=:source,diy_id=:diy_id";
			$cmd = Yii::app()->db->createCommand($sql);
			$result = $cmd->bindValues($values)->execute();
			if($result){
				//记录积分
				Integral::record($uid,5);
				
				$json_array['status'] = '1';
			}
		} 
	  	
		return $json_array;
	  }
	 
	  private function getDefaultTudanId($uid)
	  {
	  	$id = '';
	  	$values = array(
			':uid'=>$uid,
			':is_default'=>1,
			':is_del'=>0,
		);
	  	$sql = "SELECT id FROM {{diy_info}} WHERE uid=:uid AND is_default=:is_default AND is_del=:is_del";
		$cmd = Yii::app()->db->createCommand($sql);
		$row = $cmd->bindValues($values)->queryRow();
		if($row){
			$id = $row['id'];
		}else{
			//insert default tudan 
			$values = array(
				':uid'=>$uid,
				':is_default'=>1,
				':title'=>'默认兔单',
				':type'=>'',
				':desc'=>'默认兔单',
				':ts_update'=>time(),
				':power'=>0,
			);
		  	$sql = "INSERT INTO {{diy_info}}
		  		 SET 
		  		 	uid=:uid,
		  		 	is_default=:is_default,
		  		 	title=:title,
		  		 	type=:type,
		  		 	`desc`=:desc,
		  		 	ts_update=:ts_update,
		  		 	power=:power";
			$cmd = Yii::app()->db->createCommand($sql);
			$result = $cmd->bindValues($values)->execute();
			if($result){
				$id = Yii::app()->db->getLastInsertID();
			}
		}
		return $id;
	  }

	
	/**
	 * 清空兔单数据
	 */
	public function truncateTudan()
	{
		$uid = isset($_REQUEST['uid']) ? intval(trim($_REQUEST['uid'])) : '';
		$id  = isset($_REQUEST['id']) ? intval(trim($_REQUEST['id'])) : '';

		$json_array = array('status'=>'0');	  	
		
		if($uid && $id)
		{
			//
			$ids = array_filter(explode(',', $id));
			
			$sql = "SELECT id FROM {{diy_info}} WHERE id IN(".join(',', $ids).") AND uid=:uid";
			$cmd = Yii::app()->db->createCommand($sql);
			$rows = $cmd->bindValue(':uid', $uid)->queryAll();
			$user_tudan_ids = array();
			foreach($rows as $row)
			{
				$user_tudan_ids[] = $row['id'];
			}
			
			//不属于该用户的id
			$arr_diff = array_diff($ids, $user_tudan_ids);
			if($user_tudan_ids)
			{
				//执行删除
				$sql = "DELETE FROM {{diy_list}} WHERE diy_id IN(".join(',', $user_tudan_ids).")";
				$cmd = Yii::app()->db->createCommand($sql);
				$result = $cmd->execute();	
				if($result){
					$json_array = array('status'=>'1');
				}			
			}
		}
		
		return $json_array;
	}
}


?>







