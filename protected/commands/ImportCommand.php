<?php
/**
 * 视频同步程序
 * @author xubaoguo@luxtonenet.com
 * @date 2014-06-12
 */
class ImportCommand extends CConsoleCommand{
	
	public function actionIndex() {
		// ini_set("display_errors", 1);
		// ini_set("error_reporting", E_ALL);
		ini_set('memory_limit', '1000M');
		set_time_limit(3600);
		$filename = "/tmp/import_running";
		if(file_exists($filename)){
			unlink($filename);
			exit("is running \n");			
		}
		file_put_contents($filename, 1);
		$date = date('Y-m-d H:i:s');
		$time = time();
		$sql = "INSERT {{import}} (date, time) VALUES ('{$date}', '{$time}') ";
		Yii::app()->db->createCommand($sql)->execute();
		$this->_importVideo($time);
		$this->_importTv($time);
		$this->_updateVideo($time);
		$this->_checkChanelVideo($time);
		$this->_checkTopicVideo($time);
		$this->_checkTudanVideo($time);
		$this->_importPic($time);
		$this->_syncSource($time);
		@unlink("/tmp/import_running");
		exit;
	}
	
	private function _importTv($time) {
		$this->_log('tv', "开始同步", $time);
		$db_puti = Yii::app()->db_puti;
		$vo_db_user = Yii::app()->db;
		try {
			$sql = "SELECT MAX(spiderid) AS id  FROM {{v_tv}} ";
			$new_record_id = $vo_db_user->createCommand($sql)->queryScalar();
			
			$sql = "SELECT MAX(id) AS uid  FROM {{tv_new}} AS u";
			$old_record_id = $db_puti->createCommand($sql)->queryScalar();
			
			$where = ' WHERE 1=1  AND is_del = 0 ';//GROUPY BY email HAVING cnt=1
			if (!empty($new_record_id)) {
				$where .= " AND u.id > $new_record_id ";
			}
			
			$sql = "SELECT DISTINCT u.tv_parent_id FROM {{tv_new}}  AS u $where";
			$command = $db_puti->createCommand($sql);
			$allvids = $command->queryColumn();
			$i = 0;
			if (!empty($allvids)) {
				foreach ($allvids AS $vid) {
					$sql = "SELECT isend, id, spiderid FROM {{v_list}} WHERE spiderid=:vid ";
					$row = $vo_db_user->createCommand($sql)->bindValue(':vid', $vid)->queryRow();
					if (empty($row)) {
						continue;
					}
					$sql = "SELECT count(*) AS cnt FROM {{v_tv}} WHERE vid=:vid ";
					$count1 = $vo_db_user->createCommand($sql)->bindValue(':vid', $row['id'])->queryScalar();
					
					$sql = "SELECT count(*) AS cnt FROM {{tv_new}} WHERE tv_parent_id=:vid AND is_del=0 ";
					$count2 = $db_puti->createCommand($sql)->bindValue(':vid', $row['spiderid'])->queryScalar();
					if ($count2 != $count1) {
						$this->_reimport($vid);
						$i++;
						if ($count2 > $count1 && $row['isend'] != 1) {
							$now = time();
							$sql = "UPDATE {{v_list}} SET utime={$now} WHERE id=:id ";
							$vo_db_user->createCommand($sql)->bindValue(':id', $row['id'])->execute();
						}
					}
				}
			}
			$time_file_name = "/tmp/logimport/donnot_delete.log";
			if (!file_exists($time_file_name)) {
				$this->_log('update', "不存在", $time);
			} else {
				$file = file_get_contents($time_file_name);
			
				$sql = "SELECT DISTINCT tv_parent_id FROM {{tv_new}}  WHERE update_time >= $file";
				$vids = Yii::app()->db_puti->createCommand($sql)->queryColumn();
				if (!empty($vids)) {
					foreach ($vids AS $viddd) {
						$this->_reimport($viddd);
					}
				}
				$sql = "SELECT id FROM {{new_video}}  WHERE update_time >= $file";
				$vids1 = Yii::app()->db_puti->createCommand($sql)->queryColumn();
				if (!empty($vids1)) {
					foreach ($vids1 AS $viddd) {
						$this->_reimport($viddd);
					}
				}
			}
			
			echo "同步成功，成功从老数据库同步{$i}条分集数据到新库\n";
			$this->_log('tv', "同步了分集数据", $time);
			return true;
		} catch (Exception $e) {
			print $e->getMessage();
			@unlink("/tmp/import_running");
			$this->_log('tv', "出错", $time);
			exit(); 
		}
	}
	
	private function _importVideo($time) {
		$db_puti = Yii::app()->db_puti;
		$vo_db_user = Yii::app()->db;
		try {
			$sql = "SELECT MAX(spiderid) AS id  FROM {{v_list}} ";
			$new_record_id = $vo_db_user->createCommand($sql)->queryScalar();
			
			$sql = "SELECT MAX(id) AS uid  FROM {{new_video}} AS u";
			$old_record_id = $db_puti->createCommand($sql)->queryScalar();
			
			// 判断新库数据是否少于老库的数据
			if ($new_record_id >= $old_record_id) {
				echo "无需同步\n";
				$this->_log('video', "无需同步", $time);
				return false;
			}
			$where  = ' WHERE 1=1 ';
			if (!empty($new_record_id)) {
				$where .= " AND u.id > $new_record_id ";
			}
			
			$sql = "SELECT u.* FROM {{new_video}}  AS u $where";
			$command = $db_puti->createCommand($sql);	
			$dataReader = $command->query();
			$insertsql = "INSERT INTO {{v_list}} (`name`,director,starring,actor,`desc`,cover,free,playtype,resolution,mins,area,`year`,category,type,plays,score,good,bad,source,showtime,ctime,letter,age,alias,`status`,sort,utime,dbscore,isend,tnum,unum,nutime,wplays,mplays,spiderid, playplat,issuer,producer,publisher)   VALUES ";
			$t = 0;
			$i = 1;
			$partSql = '';
			while (($row = $dataReader->read()) !== false) {
				foreach ($row AS $key => $val) {
					if (!empty($val) && is_string($val)) {
						$row[$key] = addslashes($val);
					}
				}
				if (!empty($row['play_info'])) {
				 	$ar = json_decode($row['play_info'], true);
					$tnum = !empty($ar['totalNum']) ? $ar['totalNum'] : 0;
					$unum = !empty($ar['updateNum']) ? $ar['updateNum'] : 0;
					$ntime = !empty($ar['updateTime']) ? $ar['updateTime'] : 0;
				} else {
				 	$tnum = 0;
					$unum = 0;
					$ntime = 0;
				}
				$pic = '';
				if (!empty($row['web_pic'])) {
					$pic = $row['web_pic'];
				} else {
					$pic = $row['pic'];
				}
				$isshow = $row['is_show'] == 0 ? 1 : 0;
				$dbscore = $row['score_douban'] < 0 ? 0 : $row['score_douban'];
				$playplat = '';
				$issuer = '';
				$producer = '';
				$publisher = '';
				if (!empty($row['plat'])) {
					$playplat = @mysql_escape_string($row['plat']);
				}
				if (!empty($row['issuer'])) {
					$issuer = @mysql_escape_string($row['issuer']);
				}
				if (!empty($row['producer'])) {
					$producer = @mysql_escape_string($row['producer']);
				}
				if (!empty($row['publisher'])) {
					$publisher = @mysql_escape_string($row['publisher']);
				}
				
				//					(`name`,			director,			starring,				actor,			`desc`,				cover,				free,			playtype,			resolution,			mins,							area,				`year`,		category,				type,				plays,					score,				good,			bad,					source,					showtime,						ctime,				letter,			age,			alias,			`status`,		sort,					utime,				dbscore,					isend,				tnum,	unum,		nutime,		wplays,mplays,spiderid)
				$partSql = "('{$row['name']}', '{$row['director']}', '{$row['main_actors']}', '{$row['actors']}', '{$row['desc']}','{$pic}', '{$row['free']}',  '{$row['genuine']}', '{$row['resolution']}','{$row['time_length']}',  '{$row['area']}', '{$row['year']}','{$row['category']}', '{$row['type']}',  '{$row['play_count']}', '{$row['score']}','{$row['support']}', '{$row['opposition']}',  '{$row['source']}', '{$row['tv_application_time']}','{$row['time']}','{$row['letter']}','{$row['age']}','{$row['alias']}','{$isshow}','{$row['rank_order']}', '{$row['update_time']}',  '{$dbscore}', '{$row['status']}','{$row['episode_count']}', '{$row['episode_max']}',  '{$ntime}', '0','0','{$row['id']}','{$playplat}', '{$issuer}', '{$producer}', '{$publisher}')";
	
				$bool = Yii::app()->db->createCommand($insertsql. $partSql)->execute();
				$this->_reimport($row['id']);
				unset($partSql);
				$partSql = '';
				$i++;
				unset($row);
			}//while循环结束
			echo "同步成功，成功从老数据库同步{$i}条数据到新库\n";
			$this->_log('video', "同步了{$i}条视频数据", $time);
			return true;
		} catch (Exception $e) {
			print $e->getMessage();
			$this->_log('video', "出错", $time);
			@unlink("/tmp/import_running");
			exit();   
		}
		
	}
	
	static public function getCategory($spider_id) {
		static $arr_key_category = array();
		if (isset($arr_key_category[$spider_id])) {
			return $arr_key_category[$spider_id];
		} else {
			$sql 	= "SELECT category FROM {{v_list}} WHERE spiderid=:id";
			$cate 	= Yii::app()->db->createCommand($sql)->bindValue(':id', $spider_id)->queryScalar();
			$arr_key_category[$spider_id] = $cate;
			return $arr_key_category[$spider_id];
		}
	}
	
	
	static public function getVid($spider_id) {
		static $arr_key_vid = array();
		if (isset($arr_key_vid[$spider_id])) {
			return $arr_key_vid[$spider_id];
		} else {
			$sql 	= "SELECT id FROM {{v_list}} WHERE spiderid=:id AND status != 2";
			$cate 	= Yii::app()->db->createCommand($sql)->bindValue(':id', $spider_id)->queryScalar();
			
			$arr_key_vid[$spider_id] = $cate;
			return $arr_key_vid[$spider_id];
		}
	}
	
	private function _importPic($time) {
		$db_puti = Yii::app()->db_puti;
		$vo_db_user = Yii::app()->db;
		$this->_log('pic', "开始同步", $time);
		$result = array('error' => 0, 'content' => '');
		$sql = "SELECT MAX(id) AS id  FROM {{v_pic}} ";
		$new_record_id = $vo_db_user->createCommand($sql)->queryScalar();
		
		$sql = "SELECT MAX(id) AS uid  FROM {{video_pic}}";
		$old_record_id = $db_puti->createCommand($sql)->queryScalar();
		
		// 判断新库数据是否少于老库的数据
		if ($new_record_id >= $old_record_id) {
			$result['error'] = 1;
			$result['content'] = '操作终止，新库数据比老库数据还要多，无须同步'. "\n";
			print_r($result['content']);
			$this->_log('pic', "无需同步", $time);
			return false;
		}
		
		$where = ' WHERE 1=1 ';
		if (!empty($new_record_id)) {
			$where .= " AND u.id > $new_record_id ";
		}
		
		try {
			
			$sql = "SELECT u.* FROM {{video_pic}}  AS u
					$where";
			
			$command = $db_puti->createCommand($sql);
			$dataReader = $command->query();
			$insertsql = "INSERT INTO  {{v_pic}}  VALUES ";
			$i = 1;
			$partSql = '';
			while (($row = $dataReader->read()) !== false) {
				
				foreach ($row AS $key => $val) {
					if (!empty($val) && is_string($val)) {
						$row[$key] = addslashes($val);
					}
					
				}
				if (!empty($row['vid'])) {
					$vid = self::getVid($row['vid']);
				} else {
					continue;
				}
				$partSql .= "({$row['id']}, '{$vid}', '{$row['pic']}', '{$row['time']}', '0', '{$row['cdn']}'),";
				
				$i++;
				
				if ($i % 200 == 0) {
					$bool = Yii::app()->db->createCommand($insertsql. trim($partSql, ','))->execute();
					unset($partSql);
					$partSql = '';
				}
				unset($row);
			}
			
			if (empty($partSql)) {
				$result['content'] = '同步成功!成功从老数据库同步'. $i . "条数据到新库\n";
			} else {
				$bool = Yii::app()->db->createCommand($insertsql. trim($partSql, ','))->execute();
				if ($bool) {
					$result['content'] = '同步成功!成功从老数据库同步'. $i . "条数据到新库\n";
				} else {
					$result['error'] = 1;
					$result['content'] = '同步失败';
				}
			}
			
		} catch (Exception $e) {
			$result['error'] = 1;
			$result['content'] =  $e->getMessage();
			$this->_log('pic', "出错", $time);
			@unlink("/tmp/import_running");
		}
		$this->_log('pic', "同步了{$i}条数据", $time);
		print_r($result['content']);
		return true;
	}
	
	private function _updateVideo($time) {
		$db_puti = Yii::app()->db_puti;
		$this->_log('update', "开始同步", $time);
		try {
			$now = date('Y-m-d H:i:s');
			$time_now = time();
			$time_file_name = "/tmp/logimport/donnot_delete.log";
			$file = @file_get_contents($time_file_name);
			if (empty($file)) {
				$sql = " SELECT name,id,score,age,support,opposition,tv_application_time,year,area,`type`, play_info, `status`, is_show, source, source_resolution,score_douban, main_actors, director, `desc`, pic, web_pic,alias,category,plat,issuer,producer,publisher,episode_count,episode_max FROM {{new_video}} ";
			} else {
				$sql = "SELECT name,id,score,age,support,opposition,tv_application_time,year,area,`type`, play_info, `status`, is_show, source, source_resolution,score_douban, main_actors, director, `desc`, pic, web_pic,alias,category,plat,issuer,producer,publisher,episode_count,episode_max FROM {{new_video}} WHERE update_time>$file ";
			}
			$rows = $db_puti->createCommand($sql)->queryAll();
			
			$i = 0;
			if (!empty($rows)) {
				foreach ($rows AS $key => $value) {
					$id 		= $value['id'];
					$sql = "SELECT category,name,showtime,age,score,good,bad,alias,year,area,`type`,source,tnum,unum,nutime,isend,dbscore,`status`,`desc`,cover,director,starring,playplat,issuer,producer,publisher FROM {{v_list}} WHERE spiderid=$id ";
					$vo_video = Yii::app()->db->createCommand($sql)->queryRow();
					if (empty($vo_video)) {
						continue;
					}
					$playplat = !empty($value['plat']) ? @mysql_escape_string($value['plat']) :'';
				
					$issuer =  !empty($value['issuer']) ? @mysql_escape_string($value['issuer']) :'';
			
					$producer =  !empty($value['producer']) ? @mysql_escape_string($value['producer']) :'';
			
					$publisher =  !empty($value['publisher']) ? @mysql_escape_string($value['publisher']) :'';
				
					$tnum 		= !empty($value['episode_count']) ? $value['episode_count'] : 0;
					$unum 		= !empty($value['episode_max']) ? $value['episode_max'] : 0;
					$ntime 		=  0;
					//$isshow 	= empty($value['is_show']) ? 1 : 0;
					$isend 		= $value['status'];
					$source 	= $value['source'];
					$dbscore 	= $value['score_douban'];
					$starring 	= @mysql_escape_string($value['main_actors']);
					$director 	= @mysql_escape_string($value['director']);
					$desc 		= @mysql_escape_string($value['desc']);
					$pic		= $value['pic'];
					$webpic		= $value['web_pic'];
					$type 		= $value['type'];
					$year 		= $value['year'];
					$area 		= $value['area'];
					$showtime 	= $value['tv_application_time'];
					$alias		= @mysql_escape_string($value['alias']);
					$age		= $value['age'];
					$bad		= $value['bad'];
					$good		= $value['good'];
					$score		= $value['score'];
					$name		= @mysql_escape_string($value['name']);
					$category	= $value['category'];
					
					
					//--------------------对比数据开始---------------------//
					$upsql = '';
					if (!empty($tnum) && $tnum != $vo_video['tnum']) {
						$upsql .= " tnum='{$tnum}', ";
					}
					if (!empty($unum) && $unum != $vo_video['unum']) {
						$upsql .= " unum='{$unum}', ";
					}
					if (!empty($ntime) && $ntime != $vo_video['nutime']) {
						$upsql .= " nutime='{$ntime}', ";
					}
					if (!empty($starring) && $starring != $vo_video['starring']) {
						$upsql .= " starring='{$starring}', ";
					}
					
					if (!empty($playplat) && $playplat != $vo_video['playplat']) {
						$upsql .= " playplat='{$playplat}', ";
					}
					if (!empty($issuer) && $issuer != $vo_video['issuer']) {
						$upsql .= " issuer='{$issuer}', ";
					}
					if (!empty($producer) && $producer != $vo_video['producer']) {
						$upsql .= " producer='{$producer}', ";
					}
					if (!empty($publisher) && $publisher != $vo_video['publisher']) {
						$upsql .= " publisher='{$publisher}', ";
					}
					
					if (!empty($type) && $type != $vo_video['type']) {
						$upsql .= " `type`='{$type}', ";
					}
					
					if (!empty($name) && $name != $vo_video['name']) {
						$upsql .= " `name`='{$name}', ";
					}
					
					if (!empty($category) && $category != $vo_video['category']) {
						$upsql .= " `category`='{$category}', ";
					}
					
					if (!empty($area) && $area != $vo_video['area']) {
						$upsql .= " area='{$area}', ";
					}
					
					
					if (!empty($year) && $year != $vo_video['year']) {
						$upsql .= " year='{$year}', ";
					}
					
					if (!empty($showtime) && $showtime != $vo_video['showtime']) {
						$upsql .= " showtime='{$showtime}', ";
					}
					
					if (!empty($alias) && $alias != $vo_video['alias']) {
						$upsql .= " alias='{$alias}', ";
					}
					
					if (!empty($age) && $age != $vo_video['age']) {
						$upsql .= " age='{$age}', ";
					}
					
					if (!empty($score) && $score != $vo_video['score']) {
						$upsql .= " score='{$score}', ";
					}
					
					if (!empty($good) && $good != $vo_video['good']) {
						$upsql .= " good='{$good}', ";
					}
					
					if (!empty($bad) && $bad != $vo_video['bad']) {
						$upsql .= " bad='{$bad}', ";
					}
					if (!empty($source) && $source != $vo_video['source']) {
						$upsql .= " source='{$source}', ";
					}
					
					if (!empty($dbscore) && $dbscore >= 0 && $dbscore != $vo_video['dbscore']) {
						$upsql .= " dbscore='{$dbscore}', ";
					}
					
					if (!empty($director) && $director != $vo_video['director']) {
						$director = $director;
						$upsql .= " director='{$director}', ";
					}
	
					if (!empty($desc) && $desc != $vo_video['desc']) {
						$desc = $desc;
						$upsql .= " `desc`='{$desc}', ";
					}
					// if (isset($isshow) && $isshow != $vo_video['status']) {
						// $upsql .= " `status`='{$isshow}', ";
					// }
					
					if (isset($isend) && $isend != $vo_video['isend']) {
						$upsql .= " isend='{$isend}', ";
					}
					
					if (!empty($webpic)) {
						if ($webpic != $vo_video['cover']) {
							$upsql .= " cover='{$webpic}', ";
						}
						
					} else {
						if (!empty($pic) && $pic != $vo_video['cover']) {
							$upsql .= " cover='{$pic}', ";
						}
					}
					
					$upsql = trim($upsql, ', ');
					if (empty($upsql)) {
						continue;
					}
					$sql 	= " UPDATE {{v_list}} SET  $upsql  WHERE spiderid={$id} ";
	
					$bool = Yii::app()->db->createCommand($sql)->execute();
					echo $i;
					echo "\n";
					$i++;
					//$this->_reimport($id);
				}
				$now = date('Y-m-d H:i:s');
				file_put_contents('/tmp/logimport/import_video.log', "成功同步 {$i} 条数据 	 $now\n", FILE_APPEND);
				file_put_contents($time_file_name, $time_now);
			} else {
				$now = date('Y-m-d H:i:s');
				file_put_contents('/tmp/logimport/import_video.log', "成功同步 0 条数据 	 $now\n", FILE_APPEND);
				file_put_contents($time_file_name, $time_now);
			}
			
			$now = date('Y-m-d H:i:s');
			file_put_contents('/tmp/logimport/import_video_time.log', "运行   		$now\n", FILE_APPEND);
			echo "同步了{$i}条数据!";
			$this->_log('update', "同步了{$i}条数据", $time);
			echo "\n";
			return true;
		} catch (Exception $e) {
			print $e->getMessage();
			$this->_log('update', "出错", $time);
			@unlink("/tmp/import_running");
			exit();   
		}
		
	}


	private function _reimport($old_vid) {
		try {
			$new_vid = self::getVid($old_vid);
			$db_puti = Yii::app()->db_puti;
			$alltv = array();
			if (empty($new_vid))  {
				$sql 	= "SELECT vid, source FROM {{merge_relation}} WHERE delsid=:id";
				$rowinfo	= Yii::app()->db->createCommand($sql)->bindValue(':id', $old_vid)->queryRow();
				if (!empty($rowinfo)) {
					$new_vid = $rowinfo['vid'];
					if (strpos($rowinfo['source'], ',') !== false){
						$sourceArray = explode(',', $rowinfo['source']);
						if (!empty($sourceArray)) {
								$sql = "SELECT  * FROM {{v_tv}} WHERE vid=:vid AND source IN('".join("','", $sourceArray)."') ";
								$alltv = Yii::app()->db->createCommand($sql)->bindValue(':vid',$new_vid)->queryAll();
	
						} else {
							return false;
						}
					} else {
						$source = $rowinfo['source'];
						$sql = "SELECT *  FROM {{v_tv}} WHERE vid=:vid AND source='{$source}' ";
						$alltv = Yii::app()->db->createCommand($sql)->bindValue(':vid',$new_vid)->queryAll();
					}
				} else {
					return false;
				}
			} else {
				$sql 	= "SELECT distinct source FROM {{merge_relation}} WHERE vid=:id";
				$row	= Yii::app()->db->createCommand($sql)->bindValue(':id', $new_vid)->queryColumn();
				if (empty($row)){
					$sql = "SELECT * FROM {{v_tv}} WHERE vid=:vid ";
					$alltv = Yii::app()->db->createCommand($sql)->bindValue(':vid',$new_vid)->queryAll();
				} else {
					$array = array();
					foreach ($row as $key => $value) {
						if (strpos($value, ',') !== false)  {
							$aa = explode(',', $value);
							$array = array_merge($array, $aa);
						} else {
							$array[] = $value;
						}
					}
					$sql 		= "SELECT distinct source FROM {{v_tv}} WHERE vid=:id";
					$rowinfo	= Yii::app()->db->createCommand($sql)->bindValue(':id', $new_vid)->queryColumn();
					if (!empty($rowinfo)) {
						if (!empty($rowinfo)) {
							$rowinfo = array_diff($rowinfo, $array);
							$sql = "SELECT * FROM {{v_tv}} WHERE vid=:vid AND source IN('".join("','", $rowinfo)."') ";
							$alltv = Yii::app()->db->createCommand($sql)->bindValue(':vid',$new_vid)->queryAll();
						} else {
							return false;
						}
					} else {
						return false;
					}
				}
			}
			$vo_tv_ids = array();
			$neArray = array();
			if (!empty($alltv)) {
				foreach ($alltv AS $value) {
					$vo_tv_ids[] = $value['spiderid'];
					$neArray[$value['spiderid']] = $value;
				}
			}
			$where = " WHERE tv_parent_id={$old_vid}  AND is_del = 0 ";
			$sql = "SELECT u.* FROM {{tv_new}}  AS u $where";
			$command = $db_puti->createCommand($sql);	
			$dataReader = $command->queryAll();
			$insertsql = "INSERT INTO {{v_tv}} (resolution,num, `name`,cover,vid,spiderid,url,status,plays,mins,source,ctime,utime,category) VALUES ";
			$i = 0;
			$j = 0;
			$partSql = '';
			$admin_tv_ids = array();
			foreach ($dataReader as $item) {
				$admin_tv_ids[] = $item['id'];
			}
			foreach ($dataReader AS $key => $row) {
				if (!in_array($row['id'], $vo_tv_ids)) {
					$tv_parent_id = $new_vid;
					if (empty($tv_parent_id)) {
						$j++;
						continue;
					}
					$row['tv_name'] = @mysql_escape_string($row['tv_name']);
					$category = self::getCategory($row['tv_parent_id']);
					$isshow = $row['is_del'] == 0 ? 1 : 0;
					$partSql .= "('{$row['tv_resolution']}', '{$row['tv_id']}', '{$row['tv_name']}','{$row['pic']}', '{$tv_parent_id}','{$row['id']}','{$row['tv_url']}','{$isshow}','{$row['tv_play_count']}','{$row['time_length']}','{$row['source']}','{$row['time']}','{$row['update_time']}', '{$category}')";
					$i++;
					$bool = Yii::app()->db->createCommand($insertsql.$partSql)->execute();
					unset($partSql);
					unset($row);
				} else {
					$id 			= $row['id'];
					$num 			= $row['tv_id'];
					$resolution 	= $row['resolution'];
					$name 			=  @mysql_escape_string($row['tv_name']);
					$url			= $row['tv_url'];
					$mins			= $row['time_length'];
					$upsql			= '';
					if (!empty($url) && $url != $neArray[$row['id']]['url']) {
						$upsql .= " url='{$url}', ";
					}
					
					if (!empty($num) && $num != $neArray[$row['id']]['num']) {
						$upsql .= " num='{$num}', ";
					}
					
					if (!empty($resolution) && $resolution != $neArray[$row['id']]['resolution']) {
						$upsql .= " resolution='{$resolution}', ";
					}
					
					if (!empty($name) && $name != $neArray[$row['id']]['name']) {
						$upsql .= " name='{$name}', ";
					}
					
					if (!empty($mins) && $mins != $neArray[$row['id']]['mins']) {
						$upsql .= " mins='{$mins}', ";
					}
					$upsql = trim($upsql, ', ');
					if (empty($upsql)) {
						continue;
					}
					$sql 	= " UPDATE {{v_tv}} SET  $upsql  WHERE spiderid={$id} ";
	
					$bool = Yii::app()->db->createCommand($sql)->execute();
				}
			}//while循环结束
			
			$o = 0;
			foreach ($alltv as $k => $val) {
				if (!in_array($val['spiderid'], $admin_tv_ids)) {
					$sql_delete = "DELETE FROM {{v_tv}} WHERE id=:id";
					Yii::app()->db->createCommand($sql_delete)->bindValue(':id', $val['id'])->execute();
					$o++;
				} else {
					continue;
				}
			}
			
			echo "导入失败{$j}条数据,导入成功{$i}条分集信息,删除{$o}条分集数据\n";
		} catch (Exception $e) {
			print $e->getMessage();
			unlink("/tmp/import_running");
			exit();   
		}
		
	}
	
	//检查频道视频
	private function _checkChanelVideo($time) {
		$sql = "SELECT id, vid FROM {{v_channel}} ORDER BY ctime DESC";
		$this->_log('channel', "开始同步", $time);
		$channelVideos = Yii::app()->db->createCommand($sql)->queryAll();
		$i = 0;
		if (!empty($channelVideos)) {
			
			foreach ($channelVideos AS $key => $val) {
				$sql = "SELECT status FROM {{v_list}} WHERE id=:vid ";
				$status = Yii::app()->db->createCommand($sql)->bindValue(':vid', $val['vid'])->queryScalar();
				if (!empty($status) && $status == 1) {
					continue;
				} else {
					$sql = "delete FROM {{v_channel}} WHERE id=:id";
					$bool = Yii::app()->db->createCommand($sql)->bindValue(':id', $val['id'])->execute();
					$i++;
				}
			}
		}
		$this->_log('channel', "删除{$i}条频道数据", $time);
		echo "删除{$i}条频道数据\n";
	}
	
	//检查频道视频
	private function _checkTopicVideo($time) {
		$sql = "SELECT id, vid FROM {{v_topic}} ORDER BY ctime DESC";
		$this->_log('topic', "开始同步", $time);
		$channelVideos = Yii::app()->db->createCommand($sql)->queryAll();
		$i = 0;
		if (!empty($channelVideos)) {
			foreach ($channelVideos AS $val) {
				$sql = "SELECT status FROM {{v_list}} WHERE id=:vid ";
				$status = Yii::app()->db->createCommand($sql)->bindValue(':vid', $val['vid'])->queryScalar();
				if (!empty($status) && $status == 1) {
					continue;
				} else {
					$i++;
					$sql = "DELETE FROM {{v_topic}} WHERE id=:id";
					$bool = Yii::app()->db->createCommand($sql)->bindValue(':id', $val['id'])->execute();
				}
			}
		}
		$this->_log('topic', "删除{$i}条专题数据", $time);
		echo "删除{$i}条专题数据\n";
	}
	//检查频道视频
	private function _checkTudanVideo($time) {
		$sql = "SELECT id, tid FROM {{t_type_detail}} ORDER BY ctime DESC";
		$this->_log('tudan', "开始同步", $time);
		$channelVideos = Yii::app()->db->createCommand($sql)->queryAll();
		$i = 0;
		if (!empty($channelVideos)) {
			foreach ($channelVideos AS $val) {
				$sql = "SELECT status,isdel FROM {{t_list}} WHERE id=:vid ";
				$row = Yii::app()->db->createCommand($sql)->bindValue(':vid', $val['tid'])->queryRow();
				if (!empty($row['status']) && $row['status'] == 1 && $row['isdel'] == 0) {
					continue;
				} else {
					$i++;
					$sql = "DELETE FROM {{t_type_detail}} WHERE id=:id";
					$bool = Yii::app()->db->createCommand($sql)->bindValue(':id', $val['id'])->execute();
				}
			}
		}
		$this->_log('tudan', "删除{$i}条兔单专题数据", $time);
		echo "删除{$i}条兔单专题数据\n";
	}
	
	private function _syncSource($time) {
		
		$sql = "SELECT spiderid, id FROM {{v_list}} WHERE source = '' ";
		$this->_log('source', "开始同步", $time);
		$allids = Yii::app()->db->createCommand($sql)->queryAll();
		$i = 0;
		
		foreach ($allids as $key => $value) {
			//print_r($value);
			$sql = "SELECT source FROM {{new_video}} WHERE id=:id";
			$source = Yii::app()->db_puti->createCommand($sql)->bindValue(':id', $value['spiderid'])->queryScalar();
			//var_dump($source);
			if (empty($source)) {
				continue;
			}
			$upsql = "UPDATE {{v_list}} SET source='{$source}' WHERE id=:id";
			//var_dump($upsql);
			Yii::app()->db->createCommand($upsql)->bindValue(':id', $value['id'])->execute();
			$i++;
		}
		$this->_log('source', "同步{$i}条分集", $time);
		echo "同步{$i}条分集\n";
	}


	private function _log($type, $count, $time) {
		if ($type == 'update') {
			$sql = "UPDATE {{import}} SET `update`='{$count}' WHERE time=$time ";
		} else {
			$sql = "UPDATE {{import}} SET `$type`='{$count}' WHERE time=$time ";
		}
		
		
		Yii::app()->db->createCommand($sql)->execute();
	}
}
