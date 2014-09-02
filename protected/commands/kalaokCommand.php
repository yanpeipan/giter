<?php 
	class KalaokCommand extends CConsoleCommand{
		private $_dict = array();
		private $pagesize = 200;
		public function actionCreateIndex($ktype){
			@ini_set('memory_limit', '1000M');
			$ktype = isset($ktype) ? $ktype : 1;
			$table = "";
			$pageSize = 200;
			switch ($ktype) {
				case 1:
					$this->createVideoIndex();
					break;
				case 2:
					$this->createActorIndex();
					break;
				case 3:
					$this->createUserIndex();
					break;
				case 4:
					$this->createTudanIndex();
					break;
			}
				
			Yii::app()->end();
		}
		public function GetPinyin($name){
			if(empty($this->_dict)){
				$this->initDict();
			}
			
			$pinyin = array();
			for($i=0,$len=mb_strlen($name,"UTF-8");$i<$len;$i++){
				$char = mb_substr($name,$i,1,"UTF-8");
				if(preg_match('/[\d\w]/i', $char)){
					$pinyin[$i] = strtolower($char);
				}else{
					$pinyin[$i] = ucfirst($this->GetLetter($char));
				}
			}
			$letter = $this->GetCartesianPinyin($pinyin);
			return $letter;
		}
		
		//组合拼音，笛卡尔积
		public function GetCartesianPinyin(array $array){
			$letters = array();
			foreach($array as $val){
				$letter_tmp = explode(',', $val);
				$size_letters = count($letters);
				$size_tmp     = count($letter_tmp);
				if(0 == $size_letters*$size_tmp){
					$letters = $letter_tmp;
				}else{
					$tmp_array = array();
					for($i=0;$i<$size_letters;$i++){
						for($j=0;$j<$size_tmp;$j++){
							$tmp_array[] = ucfirst($letters[$i]).ucfirst($letter_tmp[$j]);
						}
					}
					$letters = $tmp_array;
				}
			}
			//获取首字母
			$chars = array();
			foreach($letters as $val){
				if(preg_match_all('/[A-Z]/', $val,$m)){
					$chars[] = join("",$m[0]);
				}
			}
			$letters = array_merge($chars , $letters);
			return join(",",$letters);
		}
		
	    public function GetLetter($char){
			if(isset($this->_dict[$char])){
				return $this->_dict[$char];
			}
			return "";
		}
		
		//初始化字典
		public function initDict()
		{
			$filename = "GBK_Table.txt";
			$path = dirname(__FILE__)."/".$filename;
			$fp = fopen($path,"r");
			while(!feof($fp)){
				$line = fgets($fp);
				$key  = mb_substr($line,0,1,"UTF-8");
				$value= trim(mb_substr($line,1,null,"UTF-8"));
				$this->_dict[$key] = $value;
				
			}
			fclose($fp);
		}
		//无则添加，优则更新
		public function setLetter($id,$name,$ktype,$weight='',$kvalue='', $length=0,$times=0){
			if(empty($name)){
				var_dump($id);die;
			}
			if(mb_strlen($name)>150){
				$fp = @fopen("/tmp/kalaok.txt","a");
				fwrite($fp, $id."****".$ktype."####");
				return;
			}
			$letter = $this->GetPinyin($name);
			if (!empty($length)) {
				$len = $length;
			} else {
				$len = mb_strlen($name,"UTF-8");
			}
			if(empty($weight)){
				$weight = 0;
			}
			$letter = explode(",", $letter);
			$letter = array_unique($letter);
			foreach ($letter as $key => $item) {
				$bindvalues = array(
								':vid'  	=> $id,
								':name' 	=> $name,
								':letter' 	=> $item,
								':ktype'  	=> $ktype,
								':kvalue' 	=> $kvalue,
								':weight' 	=> $weight,
								':alias'	=> $len,
								':times'    => $times,
							);
				$sql_insert = "INSERT INTO {{v_kalaok}} SET
					vid=:vid,letter=:letter,weight=:weight,name=:name,ktype=:ktype,length=:alias,kvalue=:kvalue,times=:times
				";
				Yii::app()->db->createCommand($sql_insert)->bindValues($bindvalues)->execute();
			}
			
			$letter = NULL;
			$len = 0;
		}
		//生成视频索引
		public function createVideoIndex(){
			$ktype = 1;
			$lastid = 0;
			$table = "{{v_list}}";
			$sql = "SELECT count(*) cnt FROM $table WHERE id>$lastid";
			$row = Yii::app()->db->createCommand($sql)->queryRow();
			$pagesize = $this->pagesize;
			$total = $row ? $row['cnt'] : 0;
			$pages = ceil($total/$pagesize);
			$page  = 1;
			for ($page; $page <= $pages ; $page++) { 
				$start = ($page-1)*$pagesize;
				$sql  = "SELECT id vid,name,alias,category,wplays FROM {{v_list}} LIMIT $start,$pagesize";
				$rows = Yii::app()->db->createCommand($sql)->queryAll();
				if($rows)foreach($rows as $k => $val){
					$name  = $val['name'];
					$alias = $val['alias'];
					if(!empty($name) || !empty($alias)){
							$sql = "DELETE FROM {{v_kalaok}} WHERE vid=:id AND ktype=:ktype";
							Yii::app()->db->createCommand($sql)->bindValues(array(':id'=>$val['vid'],':ktype'=>$ktype))->execute();
					}
					
					if($name){
						$this->setLetter($val['vid'],$name,$ktype,$val['wplays'], $val['category']);
					}
					if($alias && $alias!=$name ){
						$len = mb_strlen($name,"UTF-8");
						$this->setLetter($val['vid'],$alias,$ktype,$val['wplays'], $val['category'], $len);
					}
					$name = null;
					$alias = null;
					echo "第".$page."页,第".($k+1)."条数据,VID:".$val['vid'].",内存占用".memory_get_usage()."\n";
				}
				$rows = NULL;
				
			}
		}
		//生成演员索引
		public function createActorIndex($page,$pageSize){
			$ktype = 2;
			$lastid = 57486;
			$table = "{{v_actor}}";
			$sql = "SELECT count(*) cnt FROM $table";
			$row = Yii::app()->db->createCommand($sql)->queryRow();
			$pagesize = $this->pagesize;
			$total = $row ? $row['cnt'] : 0;
			$pages = ceil($total/$pagesize);
			$page  = 1;
			for ($page; $page <= $pages ; $page++) { 
				$start = ($page-1)*$pagesize;
				$sql = "SELECT id AS vid,name,alias FROM $table LIMIT $start,$pagesize";
				$rows = Yii::app()->db->createCommand($sql)->queryAll();
				if($rows)foreach($rows as $k => $val){
					$name  = $val['name'];
					$alias = $val['alias'];
					if(!empty($name) || !empty($alias)){
							$sql = "DELETE FROM {{v_kalaok}} WHERE vid=:id AND ktype=:ktype";
							Yii::app()->db->createCommand($sql)->bindValues(array(':id'=>$val['vid'],':ktype'=>$ktype))->execute();
					}
					
					if($name){
						$this->setLetter($val['vid'],$name,$ktype,0, 0);
					}
					if($alias && $alias!=$name ){
						$len = mb_strlen($name,"UTF-8");
						$this->setLetter($val['vid'],$alias,$ktype,0, 0, $len);
					}
					$name = null;
					$alias = null;
					echo "第".$page."页,第".($k+1)."条数据,UID:".$val['vid'].",内存占用".memory_get_usage()."\n";
				}
				$rows = NULL;
				
			}
		}
		//生成用户索引  @@@@暂时只生成有昵称的
		public function createUserIndex(){
			$ktype = 3;
			$lastid = 128198;
			$table = "{{user}}";
			$sql = "SELECT count(*) cnt FROM $table";
			$row = Yii::app()->db->createCommand($sql)->queryRow();
			$pagesize = $this->pagesize;
			$total = $row ? $row['cnt'] : 0;
			$pages = ceil($total/$pagesize);
			$page  = 1;
			for ($page; $page <= $pages ; $page++) { 
				$start = ($page-1)*$pagesize;
				$sql = "SELECT id vid,nickname,score FROM $table LIMIT $start,$pagesize";
				$rows = Yii::app()->db->createCommand($sql)->queryAll();
				if($rows)foreach($rows as $k => $val){
					$name  = $val['nickname'];
					
					if(!empty($name)){
						$sql = "DELETE FROM {{v_kalaok}} WHERE vid=:id AND ktype=:ktype";
						Yii::app()->db->createCommand($sql)->bindValues(array(':id'=>$val['vid'],':ktype'=>$ktype))->execute();
					}
					$val['score'] = isset($val['score']) ? $val['score'] : '' ;
					if($name){
						$this->setLetter($val['vid'],$name,$ktype,$val['score'],0);
					}
					if($alias && $alias!=$name ){
						$len = mb_strlen($name,"UTF-8");
						$this->setLetter($val['vid'],$alias,$ktype,$val['score'], 0, $len);
					}
					$name = null;
					echo "第".$page."页,共".$pages."页,第".($k+1)."条数据,UID:".$val['vid'].",内存占用".memory_get_usage()."\n";
				}
				$rows = NULL;
				
			}
		}
		//生成tudan索引  @@@@暂时只生成有昵称的
		public function createTudanIndex(){
			$ktype = 4;
			$lastid = 6185;
			$table = "{{t_list}}";
			$where = " WHERE power=1 AND status=1 AND isdel=0 ";
			$sql = "SELECT count(*) cnt FROM $table $where";
			$row = Yii::app()->db->createCommand($sql)->queryRow();
			$pagesize = $this->pagesize;
			$total = $row ? $row['cnt'] : 0;
			$pages = ceil($total/$pagesize);
			$page  = 1;
			for ($page; $page <= $pages ; $page++) { 
				$start = ($page-1)*$pagesize;
				$sql = "SELECT id AS vid,name,clicks FROM $table $where LIMIT $start,$pagesize";
				$rows = Yii::app()->db->createCommand($sql)->queryAll();
				if($rows)foreach($rows as $k => $val){
					$name  = $val['name'];
					
					if(!empty($name)){
							$sql = "DELETE FROM {{v_kalaok}} WHERE vid=:id AND ktype=:ktype";
							Yii::app()->db->createCommand($sql)->bindValues(array(':id'=>$val['vid'],':ktype'=>$ktype))->execute();
					}
					
					if($name){
						$this->setLetter($val['vid'],$name,$ktype,$val['clicks'], 0);
					}
					$name = null;
					echo "第".$page."页,共".$pages."页,第".($k+1)."条数据,UID:".$val['vid'].",内存占用".memory_get_usage()."\n";
				}
				$rows = NULL;
				
			}
		}
		//字母  监控
		public function actionMonitor(){
			
			//check 
			
			$cmd = "rm /tmp/check_kalaok;ps aux|grep -i kalaok > /tmp/check_kalaok|wc -l";
			$result = exec($cmd);
			if($result>1){
				//running,write log and exit
				//file_put_contents("/tmp/kalaok_running", 1);
				exit("is running \n");			
			}
			
			//获取上次更新时间
			$sql = "SELECT cfg_value FROM {{s_config}} WHERE cfg_name='KALAOK_REFRESH_TIMESTAMP' ";
			$ts  = Yii::app()->db->createCommand($sql)->queryRow();
			$now = time();
			if($ts){
				$ts = $ts['cfg_value'];
			}else{
				$ts = "1402643047";//2014.6.12
				$sql = "INSERT INTO {{s_config}} SET cfg_name='KALAOK_REFRESH_TIMESTAMP',cfg_comment='索引更新时间戳',cfg_value=$ts,cfg_type='USER' ";
				Yii::app()->db->createCommand($sql)->execute();
			}
			
			$this->VideoMonitor($ts);
			
			$this->ActorMonitor($ts);
			
			$this->UserMonitor($ts);
			
			$this->TudanMonitor($ts);
			
			//设置本次更新时间戳
			$sql = "UPDATE {{s_config}} SET cfg_value='".$now."' WHERE cfg_name='KALAOK_REFRESH_TIMESTAMP'";
			$ts  = Yii::app()->db->createCommand($sql)->execute();
		}
		private function VideoMonitor($ts){
			$ktype = 1;
			@ini_set('memory_limit', '800M');
			echo "--------------------------检查视频索引错误开始-----------------";
			echo "\n";
			$sql_letter = "SELECT distinct vid  FROM {{v_kalaok}} WHERE ktype=$ktype ORDER BY vid";
			$letters    = Yii::app()->db->createCommand($sql_letter)->queryAll();
			foreach ($letters as $item) {
				$letter_array[] = (string)$item['vid'];
			}
			$sql_video  = "SELECT id FROM {{v_list}} WHERE status=1  ORDER by id";
			$videos     = Yii::app()->db->createCommand($sql_video)->queryAll();
			foreach ($videos as $item) {
				$video_array[] = (string)$item['id'];
			}
			echo '共有'.count($video_array).'条视频数据';
			echo "\n";
			echo "共有".count($letter_array).'个视频索引数据 ';
			echo "\n";
			$letters = NULL;
			$videos  = NULL;
			//清除 多余的 字母表数据
			$surplus = array_diff($letter_array, $video_array);
			echo '共有'.count($surplus).'条 垃圾索引';
			echo "\n";
			echo "清理中...";
			echo "\n";
			$ids     = array_chunk($surplus,100);
			foreach ($ids as $items) {
				$id_str = implode(',', $items);
				$sql = "DELETE FROM {{v_kalaok}} WHERE vid IN (".$id_str.") AND ktype=$ktype";
				Yii::app()->db->createCommand($sql)->execute();
			}
			$surplus = NULL;
			$ids     = NULL;
			//添加 未生成的 索引
			$notyet  = array_diff($video_array, $letter_array);
			echo '共有'.count($notyet).'条视频没有生成索引';
			echo "\n";
			echo "生成中...";
			echo "\n";
			$date_time = date("Y-m-d");
			$time = time();
			foreach ($notyet as $id) {
				$sql = "SELECT name,alias,wplays,category FROM {{v_list}} WHERE id=".$id;
				$val = Yii::app()->db->createCommand($sql)->queryRow();
				if($val){
					if(!empty($val['name'])){
						$this->setLetter($id,$val['name'],$ktype,$val['wplays'], $val['category']);
					}
					if(!empty($val['alias']) && $val['alias']!=$val['name'] ){
						$len = mb_strlen($val['name'],"UTF-8");
						$this->setLetter($id,$val['alias'],$ktype,$val['wplays'], $val['category'], $len);
					}
				}
				
			}
			echo "生成完毕";
			echo "\n";
			
			echo "更新索引开始。。。";
			//获取上次更新时间戳
			$sql = "SELECT id,name,alias,wplays,category FROM {{v_list}} WHERE utime>'$ts' AND status=1";
			$videos = Yii::app()->db->createCommand($sql)->queryAll();
			echo '共有'.count($videos).'需要更新';
			echo "\n";
			echo "更新中中...";
			echo "\n";
			foreach ($videos as $key => $items) {
				//删除旧索引
				$sql = "DELETE FROM {{v_kalaok}} WHERE vid=".$items['id']." AND ktype=$ktype";
				Yii::app()->db->createCommand($sql)->execute();
				//创建新索引
				if(!empty($items['name'])){
					$this->setLetter($items['id'],$items['name'],$ktype,$items['wplays'], $items['category']);
				}
				if(!empty($items['alias']) && $alias!=$items['name'] ){
					$len = mb_strlen($items['name'],"UTF-8");
					$this->setLetter($items['id'],$items['alias'],$ktype,$items['wplays'], $items['category'], $len);
				}
			}
			
			echo "更新完毕";
			echo "\n";
			echo "------------------------视频索引错误完成-----------------";
			echo "\n";
		}
		private function ActorMonitor($ts){
			$ktype = 2;
			echo "--------------------------检查演员索引错误开始-----------------";
			echo "\n";
			$sql_letter = "SELECT distinct vid  FROM {{v_kalaok}} WHERE ktype=$ktype ORDER BY vid";
			$letters    = Yii::app()->db->createCommand($sql_letter)->queryAll();
			foreach ($letters as $item) {
				$letter_array[] = (string)$item['vid'];
			}
			$sql_video  = "SELECT id FROM {{v_actor}} WHERE status=1 AND ctime > $ts ORDER by id";
			$videos     = Yii::app()->db->createCommand($sql_video)->queryAll();
			foreach ($videos as $item) {
				$video_array[] = (string)$item['id'];
			}
			echo '共有'.count($video_array).'条演员数据';
			echo "\n";
			echo "共有".count($letter_array).'个演员索引数据 ';
			echo "\n";
			$letters = NULL;
			$videos  = NULL;
			//清除 多余的 字母表数据
			$surplus = array_diff($letter_array, $video_array);
			echo '共有'.count($surplus).'条 垃圾索引';
			echo "\n";
			echo "清理中...";
			echo "\n";
			$ids     = array_chunk($surplus,100);
			foreach ($ids as $items) {
				$id_str = implode(',', $items);
				$sql = "DELETE FROM {{v_kalaok}} WHERE vid IN (".$id_str.") AND ktype=$ktype";
				Yii::app()->db->createCommand($sql)->execute();
			}
			$surplus = NULL;
			$ids     = NULL;
			//添加 未生成的 索引
			$notyet  = array_diff($video_array, $letter_array);
			echo '共有'.count($notyet).'条演员没有生成索引';
			echo "\n";
			echo "生成中...";
			echo "\n";
			$date_time = date("Y-m-d");
			$time = time();
			foreach ($notyet as $id) {
				$sql = "SELECT name,alias,wplays,category FROM {{v_actor}} WHERE id=".$id;
				$val = Yii::app()->db->createCommand($sql)->queryRow();
				if($val){
					if(!empty($val['name'])){
						$this->setLetter($id,$val['name'],$ktype,$val['wplays'], $val['category']);
					}
					if(!empty($val['alias']) && $val['alias']!=$val['name'] ){
						$len = mb_strlen($val['name'],"UTF-8");
						$this->setLetter($id,$val['alias'],$ktype,$val['wplays'], $val['category'], $len);
					}
				}
				
			}
			echo "生成完毕";
			echo "\n";
			echo "更新索引开始。。。";

		}
		private function UserMonitor($ts){
			$ktype = 3;
			echo "--------------------------检查用户索引错误开始-----------------";
			echo "\n";
			$sql_letter = "SELECT distinct vid  FROM {{v_kalaok}} WHERE ktype=$ktype ORDER BY vid";
			$letters    = Yii::app()->db->createCommand($sql_letter)->queryAll();
			foreach ($letters as $item) {
				$letter_array[] = (string)$item['vid'];
			}
			$sql_video  = "SELECT id FROM {{user}}  ORDER by id";
			$videos     = Yii::app()->db->createCommand($sql_video)->queryAll();
			foreach ($videos as $item) {
				$video_array[] = (string)$item['id'];
			}
			echo '共有'.count($video_array).'条用户数据';
			echo "\n";
			echo "共有".count($letter_array).'个用户索引数据 ';
			echo "\n";
			$letters = NULL;
			$videos  = NULL;
			//清除 多余的 字母表数据
			$surplus = array_diff($letter_array, $video_array);
			echo '共有'.count($surplus).'条 垃圾索引';
			echo "\n";
			echo "清理中...";
			echo "\n";
			$ids     = array_chunk($surplus,100);
			foreach ($ids as $items) {
				$id_str = implode(',', $items);
				$sql = "DELETE FROM {{v_kalaok}} WHERE vid IN (".$id_str.") AND ktype=$ktype";
				Yii::app()->db->createCommand($sql)->execute();
			}
			$surplus = NULL;
			$ids     = NULL;
			//添加 未生成的 索引
			$notyet  = array_diff($video_array, $letter_array);
			echo '共有'.count($notyet).'条用户没有生成索引';
			echo "\n";
			echo "生成中...";
			echo "\n";
			$date_time = date("Y-m-d");
			$time = time();
			foreach ($notyet as $id) {
				$sql = "SELECT nickname,score,lntype FROM {{user}} WHERE id=".$id;
				$val = Yii::app()->db->createCommand($sql)->queryRow();
				if($val){
					if(!empty($val['nickname'])){
						$this->setLetter($id,$val['nickname'],$ktype,$val['score'], $val['lntype']);
					}
					
				}
				
			}
			echo "生成完毕";
			echo "\n";
		}
		private function TudanMonitor($ts){
			$ktype = 4;
			echo "--------------------------检查tudan索引错误开始-----------------";
			echo "\n";
			$sql_letter = "SELECT distinct vid  FROM {{v_kalaok}} WHERE ktype=$ktype ORDER BY vid";
			$letters    = Yii::app()->db->createCommand($sql_letter)->queryAll();
			foreach ($letters as $item) {
				$letter_array[] = (string)$item['vid'];
			}
			$sql_video  = "SELECT id FROM {{t_list}} WHERE power=1 AND status=1 AND isdel=0 ORDER by id";
			$videos     = Yii::app()->db->createCommand($sql_video)->queryAll();
			foreach ($videos as $item) {
				$video_array[] = (string)$item['id'];
			}
			echo '共有'.count($video_array).'条tudan数据';
			echo "\n";
			echo "共有".count($letter_array).'个用tudan引数据 ';
			echo "\n";
			$letters = NULL;
			$videos  = NULL;
			//清除 多余的 字母表数据
			$surplus = array_diff($letter_array, $video_array);
			echo '共有'.count($surplus).'条 垃圾索引';
			echo "\n";
			echo "清理中...";
			echo "\n";
			$ids     = array_chunk($surplus,100);
			foreach ($ids as $items) {
				$id_str = implode(',', $items);
				$sql = "DELETE FROM {{v_kalaok}} WHERE vid IN (".$id_str.") AND ktype=$ktype";
				Yii::app()->db->createCommand($sql)->execute();
			}
			$surplus = NULL;
			$ids     = NULL;
			//添加 未生成的 索引
			$notyet  = array_diff($video_array, $letter_array);
			echo '共有'.count($notyet).'条tudan没有生成索引';
			echo "\n";
			echo "生成中...";
			echo "\n";
			$date_time = date("Y-m-d");
			$time = time();
			foreach ($notyet as $id) {
				$sql = "SELECT name,clicks,live FROM {{t_list}} WHERE id=".$id;
				$val = Yii::app()->db->createCommand($sql)->queryRow();
				if($val){
					if(!empty($val['name'])){
						$this->setLetter($id,$val['name'],$ktype,$val['clicks'], $val['live']);
					}
					
				}
				
			}
			echo "生成完毕";
			echo "\n";
			//获取上次更新时间戳
			$sql = "SELECT id,name,clicks,live FROM {{t_list}} WHERE utime>'$ts' AND status=1";
			$tudans = Yii::app()->db->createCommand($sql)->queryAll();
			echo '共有'.count($tudans).'需要更新';
			echo "\n";
			echo "更新中中...";
			echo "\n";
			foreach ($tudans as $key => $items) {
				//删除旧索引
				$sql = "DELETE FROM {{v_kalaok}} WHERE vid=".$items['id']." AND ktype=$ktype";
				Yii::app()->db->createCommand($sql)->execute();
				//创建新索引
				if(!empty($items['name'])){
					$this->setLetter($items['id'],$items['name'],$ktype,$items['clicks'], $items['live']);
				}
				
			}
			
			echo "更新完毕";
			echo "\n";
			echo "------------------------视频索引错误完成-----------------";
			echo "\n";
		}
		public function actionDiDo(){
			$lastid = 130299;
			$ktype  = 1;
			@ini_set('memory_limit', '800M');
			$where   = " WHERE status=1 ";
			if($lastid){
				$where .= " AND id<$lastid";
			}
			$sql_cnt = "SELECT count(*) AS cnt FROM {{v_list}} $where";
			$cnt     = Yii::app()->db->createCommand($sql_cnt)->queryRow();
			$total   = $cnt['cnt'];
			$pagesize= 1000;
			$pages   = ceil($total/$pagesize);
			$page    = 1;
			
			for ($page; $page <= $pages; $page++) {
				$limit  = ' LIMIT '.($page-1)*$pagesize.','.$pagesize;
				$sql    = "SELECT id,name,alias,wplays,category FROM {{v_list}} $where ORDER BY id DESC $limit";
				$videos = Yii::app()->db->createCommand($sql)->queryAll();
				foreach ($videos as  $val) {
					$sql    = "SELECT * FROM {{v_kalaok}} WHERE vid=:id AND ktype=:ktype";
					$kalaok = Yii::app()->db->createCommand($sql)->bindValues(array(':id'=>$val['id'],':ktype'=>$ktype))->queryRow();
					$sql    = "DELETE FROM {{v_kalaok}} WHERE vid=:id AND ktype=:ktype";
					Yii::app()->db->createCommand($sql)->bindValues(array(':id'=>$val['id'],':ktype'=>$ktype))->execute();
					echo "删除".$val['name']."的索引";
					echo "\n";
					$len = mb_strlen($val['name'],"UTF-8");
					$ktype = 1;
					echo "重新生成".$val['name']."的索引--------";
					echo "\n";
					if(!empty($val['name'])){
						$this->setLetter($val['id'],$val['name'],$ktype,$val['wplays'], $val['category'],$len,$kalaok['times']);
					}
					if(!empty($val['alias']) && $val['name']!=$val['alias'])
						$this->setLetter($val['id'],$val['alias'],$ktype,$val['wplays'], $val['category'], $len ,$kalaok['times']);
					
					echo "生成成功！ID:".$val['id'];
					echo "\n";
				}
				echo "睡眠20s！";
				echo "\n";
				sleep(5);
				
			}
		}
	}
?>