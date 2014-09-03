<?php
/**
 * 分析日志文件记录到数据库
 * @author xubaoguo@luxtonenet.com
 * @date 2014-06-09
 */
class StatisticCommand extends CConsoleCommand{
	
	private $_files_prefix    = 'log.aituzi.com-access.log-';
	private $_www			  = 'http://log.aituzi.com/logs/';
	private $_file_path		  = '/tmp/report/';
	private $_arraymd5 		  = array();
	
	public function actionIndex($ktype) {
		@ini_set('memory_limit', '1000M');

		$i = isset($ktype) ? $ktype : 1;
		$date = date('Ymd', strtotime('-' . $i . ' days'));
		$file = $this->_www . $date . '.tar.gz';
		$bool = $this->_check($date);
		if (!$bool) {
			$dir  = $this->_file_path;
			$filename = $dir . $date . '.tar.gz';
			if (!file_exists($filename)) {
				$cmd  = "wget $file -P $dir";
				$result = exec($cmd);
				$time = date('Y-m-d H:i:s');
				if ($result > 1) {
					echo "下载失败!\n";
					file_put_contents($this->_file_path . 'error.log', "下载失败!	$time\n");
				}
			}
			
			$tar_filename = $this->_file_path . $this->_files_prefix . $date;
			if (!file_exists($tar_filename)) {
				$cmd = "tar zxvf $filename -C $dir";
				$time = date('Y-m-d H:i:s');
				$result = exec($cmd);
				if ($result > 1) {
					echo "解压失败!\n";
					file_put_contents($this->_file_path . 'error.log', "解压失败!	$time\n");
				}
			}
		} else {
			echo "已经统计过\n";
			exit;
		}
		//$this->tudanViews();
		$this->delRepeat($date);
		$this->tjcontent($date);
		$this->tjvideo($date);
		$this->tjsource($date);
		$this->tjsinglevideo($date);
		//$this->tjsinglesohu($date);
		//$this->writeDB($date);
		@unlink($filename);
		$filename1 = $this->_file_path . $this->_files_prefix . $date;//
		@unlink($filename1);
		unlink($filename1.".temp");
		exit;
	}
	
	private function _check($date) {
		$time = strtotime($date);
		$sql = "SELECT count(*) AS cnt FROM {{tj_video}} WHERE ttime=$time ";
		$count1 = Yii::app()->db->createCommand($sql)->queryScalar();
		
		$sql = "SELECT count(*) AS cnt FROM {{tj_source}} WHERE ttime=$time ";
		$count2 = Yii::app()->db->createCommand($sql)->queryScalar();
		
		$sql = "SELECT count(*) AS cnt FROM {{tj_singlevideo}} WHERE ttime=$time ";
		$count3 = Yii::app()->db->createCommand($sql)->queryScalar();
		
		$sql = "SELECT count(*) FROM {{tj_content}} WHERE ttime=:time";
		$count4 = Yii::app()->db->createCommand($sql)->bindValue(':time', $time)->queryScalar();
		
		if ($count1 >0 && $count2 >0 && $count3 > 0 && $count4 > 0) {
			return true;
		} else {
			return false;
		}
		
	}
	public function delRepeat($date) {
		$filename = $this->_file_path . $this->_files_prefix . $date;//
		if (!file_exists($filename)) {
			echo "{$filename} 文件不存在!\n";
			return false;
		}

		if (!file_exists($filename . '.temp')) {
			$dir = dirname(__FILE__);
			$cmd = "sh /$dir/delrepeat.sh {$filename}";
			$result = exec($cmd);
			if ($result > 1) {
				echo "替换失败!\n";
				file_put_contents($this->_file_path . 'error.log', "替换失败!	$time\n");
			}
			unlink($filename);
		}
		echo "去重复完成!\n";
		return true;
	}
	/**
	 * 统计视频播放
	 */
	public function tjvideo($date) {
		$filename = $this->_file_path . $this->_files_prefix . $date . '.temp';//
		if (!file_exists($filename)) {
			echo "{$filename} 文件不存在!\n";
			return false;
		}
		$time = strtotime($date);
		$sql = "SELECT count(*) AS cnt FROM {{tj_video}} WHERE ttime=$time ";
		$count1 = Yii::app()->db->createCommand($sql)->queryScalar();
		if ($count1 > 0) {
			return false;
		}
		$handle = fopen($filename, "r");
		$arraymd5 = array();
		if ($handle) {
			$category_array = array(
				1	=> '其他',
				2 	=> '电影',
				3	=> '电视剧',
				4	=> '动漫',
				5	=> '纪录片',
				6	=> '综艺',
				8	=> '公开课',
				9	=> '音乐',
				10	=> '微电影',
				11	=> '片花',
				13	=> '兔单',
			);
			$array = array();
		    while (!feof($handle)) {
		       $buffer = fgets($handle, 4096);
		       $data = explode(' ', $buffer);
			   parse_str($data[3], $da);
			   
			   if (!empty($da['action']) && $da['action'] == 'playstart') {
			   	  if (!empty($da['vid'])) {
			   	  	if (strpos($da['vid'], 't_') !== false) {
			   	  		$array[13] += 1;
			   	  	} else {
			   	  		$category = self::getCategoryByVid($da['vid']);
						if (!empty($category)) {
							$array[$category] += 1;
						} else {
							continue;
						}
			   	  	}
			   	  } else {
			   	  	  continue;
			   	  }
			   } else {
			   	  continue;
			   }
		    }
		    fclose($handle);
		}

		if (!empty($array)) {
			foreach ($array AS $key => $value) {
				$value11 = array(
					':ttime'		=> strtotime($date),
					':plays'		=> $value,
				);
				
				$time = strtotime($date);
				$vcate  = $key;
				$sqlc = "SELECT COUNT(*) AS cnt FROM {{tj_video}} WHERE ttime={$time} AND vcate='{$vcate}' ";
				$cmd  = Yii::app()->db->createCommand($sqlc);
				$count = $cmd->queryScalar();
				if ($count > 0) {
					continue;
				}
				$sql  = "INSERT INTO {{tj_video}} SET ttime=:ttime,vcate='{$vcate}',plays=:plays ";
				$cmd  = Yii::app()->db->createCommand($sql);
				$bool = $cmd->bindvalues($value11)->execute();
			}
		}
		
		echo "统计{$date}的视频播放数据完成 \n";
		$time = date('Y-m-d H:i:s');
		file_put_contents($this->_file_path . 'success.log', "统计{$date}的视频播放数据完成 !	$time\n", FILE_APPEND);
		return true;
	}
	
	
	static public function getCategoryByVid($spider_id) {
		static $arr_key_category = array();
		if (isset($arr_key_category[$spider_id])) {
			return $arr_key_category[$spider_id];
		} else {
			$sql 	= "SELECT category FROM {{v_list}} WHERE id=:id";
			$cate 	= Yii::app()->db->createCommand($sql)->bindValue(':id', $spider_id)->queryScalar();
			$arr_key_category[$spider_id] = $cate;
			return $arr_key_category[$spider_id];
		}
	}
	
	/**
	 * 统计接入源播放
	 */
	public function tjsource($date) {
		$filename = $this->_file_path . $this->_files_prefix . $date . '.temp';//
		if (!file_exists($filename)) {
			echo "{$filename} 文件不存在!\n";
			return false;
		}
		$time = strtotime($date);
		$sql = "SELECT count(*) AS cnt FROM {{tj_source}} WHERE ttime=$time ";
		$count1 = Yii::app()->db->createCommand($sql)->queryScalar();
		if ($count1 > 0) {
			return false;
		}
		$handle = fopen($filename, "r");
		if ($handle) {
			$array = array();
		    while (!feof($handle)) {
		       $buffer = fgets($handle, 4096);
		       $data = explode(' ', $buffer);
			   parse_str($data[3], $da);

			   if (!empty($da['action']) && $da['action'] == 'playstart') {
			   	  if (!empty($da['source'])) {
			   	  	$source = $da['source'];
					$array[$source] += 1;
			   	  } else {
			   	  	  continue;
			   	  }
			   } else {
			   	  continue;
			   }
		    }
		    fclose($handle);
		}
		
		if (!empty($array)) {
			foreach ($array AS $key => $value) {
				$value11 = array(
					':ttime'		=> strtotime($date),
					':plays'		=> $value,
				);
				
				$time = strtotime($date);
				
				$sqlc = "SELECT COUNT(*) AS cnt FROM {{tj_source}} WHERE ttime={$time} AND vcate='{$key}' ";
				$cmd  = Yii::app()->db->createCommand($sqlc);
				$count = $cmd->queryScalar();
				if ($count > 0) {
					continue;
				}
				$sql  = "INSERT INTO {{tj_source}} SET ttime=:ttime,vcate='{$key}',plays=:plays ";
				$cmd  = Yii::app()->db->createCommand($sql);
				$bool = $cmd->bindvalues($value11)->execute();
			}
		}
		
		echo "统计{$date}的源播放数据完成 \n";
		$time = date('Y-m-d H:i:s');
		file_put_contents($this->_file_path . 'success.log', "统计{$date}的源播放数据完成  !	$time\n", FILE_APPEND);
		return true;
	}
	
	/**
	 * 统计内容增量
	 */
	public function tjcontent($date) {
		$lastday_time 	= strtotime($date);
		$time			= $lastday_time + 86400;
		$sql = "SELECT count(*) AS cnt FROM {{tj_content}} WHERE ttime=$lastday_time ";
		$count1 = Yii::app()->db->createCommand($sql)->queryScalar();
		if ($count1 > 0) {
			return false;
		}
		//截止昨天视频总数
		$sql = "SELECT count(*) FROM {{v_list}} WHERE ctime < $time";
		$total_video = Yii::app()->db->createCommand($sql)->queryScalar();
		
		//截止昨天分集总数
		$sql = "SELECT count(*) FROM {{v_tv}} WHERE ctime < $time";
		$total_tv = Yii::app()->db->createCommand($sql)->queryScalar();
		
		//截止昨天新增视频数
		$sql = "SELECT count(*) FROM {{v_list}} WHERE ctime < $time AND ctime >$lastday_time";
		$new_add_video = Yii::app()->db->createCommand($sql)->queryScalar();
		
		//截止昨天新增分集数
		$sql = "SELECT count(*) FROM {{v_tv}} WHERE ctime < $time AND ctime >$lastday_time";
		$new_add_tv = Yii::app()->db->createCommand($sql)->queryScalar();
		
		$value = array(
			':ttime'		=> $lastday_time,
			':newvideo'		=> $new_add_video,
			':newtv'		=> $new_add_tv,
			':totalvideo'	=> $total_video,
			':totaltv'		=> $total_tv,
		);
		$sql  = "INSERT INTO {{tj_content}} SET ttime=:ttime,newvideo=:newvideo,newtv=:newtv,totalvideo=:totalvideo,totaltv=:totaltv";
		$cmd  = Yii::app()->db->createCommand($sql);
		$bool = $cmd->bindvalues($value)->execute();
		return true;
	}

	public function tjsinglevideo($date) {
		$filename = $this->_file_path . $this->_files_prefix . $date . '.temp';//
		if (!file_exists($filename)) {
			echo "{$filename} 文件不存在!\n";
			return false;
		}
		$time = strtotime($date);
		$sql = "SELECT count(*) AS cnt FROM {{tj_singlevideo}} WHERE ttime=$time ";
		$count1 = Yii::app()->db->createCommand($sql)->queryScalar();
		if ($count1 > 0) {
			return false;
		}
		$handle = fopen($filename, "r");
		if ($handle) {
			$array = array();
			$array_time = array();
		     while (!feof($handle)) {
		       $buffer = fgets($handle, 4096);
		       $data = explode(' ', $buffer);
			   parse_str($data[3], $da);
			   if (!empty($da['action']) && $da['action'] == 'playstart') {
			   	  if (!empty($da['vid'])) {
			   	  	if (strpos($da['vid'], 't_') !== false) {
			   	  		$vid = trim($da['vid']);
						$times = $da['playtimes'];
						if (isset($array[$vid])) {
							$array[$vid] += 1;
							$array_time[$vid] += $times;
						} else {
							$array[$vid] = 1;
							$array_time[$vid] = $times;
						}
			   	  		
			   	  	} else {
			   	  		$vid = $da['vid'];
						$times = $da['playtimes'];
			   	  		if (isset($array[$vid])) {
							$array[$vid] += 1;
							$array_time[$vid] += $times;
						} else {
							$array[$vid] = 1;
							$array_time[$vid] = $times;
						}
			   	  	}
			   	  } else {
			   	  	  continue;
			   	  }
			   } else {
			   	  continue;
			   }
		    }
		    fclose($handle);
		}
		$i = 1;
		
		arsort($array);
		
		if (!empty($array)) {
			$nn = array();
			foreach ($array AS $t => $n) {
				if (!in_array($t, $nn)) {
					$nn[] = $t;
				} else {
					unset($array[$t]);
				}
			}
			foreach ($array AS $key => $value) {
				$value11 = array();
				$value22 = array();
				if (strpos($key, 't_') !== false) {
					$times = $array_time[$key];
					$key = str_replace('t_', '', $key);
					$name	  = self::getTname($key);
					
					$value11 = array(
						':ttime'		=> strtotime($date),
						':plays'		=> $value,
						':times'		=> $times,
						':category'		=> 13,
						':name'			=> @mysql_escape_string($name)
					);
					$value22 = array(
						':ttime'		=> strtotime($date),
						':category'		=> 13,
						':name'			=> @mysql_escape_string($name)
					);
					$sql  = "INSERT INTO {{tj_singlevideo}} SET ttime=:ttime,vid='{$key}',plays=:plays, mins=:times, vtype=3,category=:category,name=:name ";
					
					$sqlc = "SELECT COUNT(*) AS cnt FROM {{tj_singlevideo}} WHERE ttime=:ttime AND category=:category AND name=:name AND vtype=3 ";
					$cmd  = Yii::app()->db->createCommand($sqlc);
					$count = $cmd->bindvalues($value22)->queryScalar();
					if ($count >0) {
						continue;
					}
					if (!empty($name)) {
						$upsql = "UPDATE {{t_list}} SET views=views+{$value} WHERE id={$key}";
						$cmd  = Yii::app()->db->createCommand($upsql);
						$bool = $cmd->execute();
					}
					
				} else {
					$category = self::getCategory($key);
					$name	  = self::getVname($key);
					$value11 = array(
						':ttime'		=> strtotime($date),
						':plays'		=> $value,
						':times'		=> $array_time[$key],
						':category'		=> $category,
						':name'			=> mysql_escape_string($name)
					);
					$value22 = array(
						':ttime'		=> strtotime($date),
						':category'		=> 13,
						':name'			=> @mysql_escape_string($name)
					);
					$sql  = "INSERT INTO {{tj_singlevideo}} SET ttime=:ttime,vid='{$key}',plays=:plays, mins=:times,vtype=1,category=:category,name=:name ";
					$sqlc = "SELECT COUNT(*) AS cnt FROM {{tj_singlevideo}} WHERE ttime=:ttime AND category=:category AND name=:name AND vtype=1 ";
					$cmd  = Yii::app()->db->createCommand($sqlc);
					$bool = $cmd->bindvalues($value22)->queryScalar();
					if ($bool >0) {
						continue;
					}
					
				}
				
				$cmd  = Yii::app()->db->createCommand($sql);
				$bool = $cmd->bindvalues($value11)->execute();
				$i++;
			}
		}
		
		echo "统计{$date}的单个视频播放数据完成 \n";
		$time = date('Y-m-d H:i:s');
		file_put_contents($this->_file_path . 'success.log', "统计{$date}的单个视频播放数据完成  !	$time\n", FILE_APPEND);
		return true;
	}

	public function tjsinglesohu($date) {
		$filename = $this->_file_path . $this->_files_prefix . $date . '.temp';//
		if (!file_exists($filename)) {
			echo "{$filename} 文件不存在!\n";
			return false;
		}
		$time = strtotime($date);
		$sql = "SELECT count(*) AS cnt FROM {{tj_single}} WHERE ttime=$time ";
		$count1 = Yii::app()->db->createCommand($sql)->queryScalar();
		if ($count1 > 0) {
			return false;
		}
		$handle = fopen($filename, "r");
		if ($handle) {
			$array = array();
			$array_time = array();
		     while (!feof($handle)) {
		       $buffer = fgets($handle, 4096);
		       $data = explode(' ', $buffer);
			   parse_str($data[3], $da);
			   if (!empty($da['action']) && $da['action'] == 'playend' && $da['source'] == 'sohu') {
			   	  if (!empty($da['vid'])) {
			   	  	if (strpos($da['vid'], 't_') !== false) {
			   	  		$vid = $da['vid'];
						$times = (int) $da['playtimes'];
			   	  		$array[$vid] += 1;
						$array_time[$vid] += $times;
			   	  	} else {
			   	  		$vid = $da['vid'];
						$times = (int) $da['playtimes'];
			   	  		$array[$vid] += 1;
						$array_time[$vid] += $times;
			   	  	}
			   	  } else {
			   	  	  continue;
			   	  }
			   } else {
			   	  continue;
			   }
		    }
		    fclose($handle);
		}
		$i = 1;
		
		arsort($array);
		if (!empty($array)) {
			foreach ($array AS $key => $value) {
				if (strpos($key, 't_') !== false) {
					$key = str_replace('t_', '', $key);
					$name	  = self::getTname($key);
					$value = array(
						':ttime'		=> strtotime($date),
						':plays'		=> $value,
						':times'		=> $array_time[$key],
						':category'		=> 13,
						':name'			=> mysql_escape_string($name)
					);
					$sql  = "INSERT INTO {{tj_single}} SET ttime=:ttime,vid='{$key}',plays=:plays, mins=:times, vtype=3,category=:category,name=:name ";
				} else {
					$category = self::getCategory($key);
					$name	  = self::getVname($key);
					$value = array(
						':ttime'		=> strtotime($date),
						':plays'		=> $value,
						':times'		=> $array_time[$key],
						':category'		=> $category,
						':name'			=> mysql_escape_string($name)
					);
					$sql  = "INSERT INTO {{tj_single}} SET ttime=:ttime,vid='{$key}',plays=:plays, mins=:times,vtype=1,category=:category,name=:name ";
				}
				
				$cmd  = Yii::app()->db->createCommand($sql);
				$bool = $cmd->bindvalues($value)->execute();
				$i++;
			}
		}
		
		echo "统计{$date}的sohu播放源播放数据完成 \n";
		$time = date('Y-m-d H:i:s');
		file_put_contents($this->_file_path . 'success.log', "统计{$date}的sohu播放源播放数据完成  !	$time\n", FILE_APPEND);
		return true;
	}
	
	static public function getCategory($id) {
		static $arr_key_category = array();
		if (isset($arr_key_category[$id])) {
			return $arr_key_category[$id];
		} else {
			$sql 	= "SELECT category FROM {{v_list}} WHERE id=:id";
			$cate 	= Yii::app()->db->createCommand($sql)->bindValue(':id', $id)->queryScalar();
			$arr_key_category[$id] = $cate;
			return $arr_key_category[$id];
		}
	}
	
	static public function getVname($id) {
		$sql 	= "SELECT name FROM {{v_list}} WHERE id=:id";
		$name 	= Yii::app()->db->createCommand($sql)->bindValue(':id', $id)->queryScalar();
		return $name;
	}
	
	static public function getTname($id) {
		$sql 	= "SELECT name FROM {{t_list}} WHERE id=:id";
		$name 	= Yii::app()->db->createCommand($sql)->bindValue(':id', $id)->queryScalar();
		return $name;
	}
	
	public function writeDB($date) {
		$filename = $this->_file_path . $this->_files_prefix . $date;//
		if (!file_exists($filename)) {
			echo "{$filename} 文件不存在!\n";
			return false;
		}
		$time = strtotime($date);
		$sql = "SELECT count(*) AS cnt FROM {{plays_data}} WHERE ttime=$time ";
		
		$count = Yii::app()->db->createCommand($sql)->queryScalar();
		if ($count > 0) {
			echo "{$filename} 已经写过!\n";
			return false;
		}
		
		$handle = fopen($filename, "r");
		$ttime = strtotime($date);
		if ($handle) {
			$array = array();
			$array_time = array();
		     while (!feof($handle)) {
		       $buffer = fgets($handle, 4096);
		       $data = explode(' ', $buffer);
			   parse_str($data[4], $da);
			   if (!empty($da['action']) && $da['action'] == 'playend') {
			   	  if (!empty($da['vid'])) {
			   	  	$action = $da['action'];
					$vid	= $da['vid'];
					$num	= $da['num'];
					$uid	= $da['uid'];
					$mac    = $da['macaddress'];
					$ip 	= $data[0];
					if (!empty($ip)) {
						$sql = "SELECT COUNT(*) AS cnt FROM {{plays_data}} WHERE ttime={$ttime} AND ip='{$ip}' AND vid='{$vid}' AND action='{$action}' AND num='{$num}' AND uid='{$uid}' AND macaddress='{$mac}'";
					} else {
						$sql = "SELECT COUNT(*) AS cnt FROM {{plays_data}} WHERE ttime={$ttime}  AND vid='{$vid}' AND action='{$action}' AND num='{$num}' AND uid='{$uid}'";
					}
			   	  	//echo $sql;exit;
					$count = Yii::app()->db->createCommand($sql)->queryScalar();
					if ($count > 0) {
						continue;
					}
					$insql = '';
					$value = array();
					if (!empty($da['source'])) {
						$value[':source'] = $da['source'];
						$insql .= " source=:source,";
					}
					
					if (!empty($da['action'])) {
						$value[':action'] = $da['action'];
						$insql .= " action=:action,";
					}

					if (!empty($da['vid'])) {
						$value[':vid'] = $da['vid'];
						$insql .= " vid=:vid,";
					}
					
					if (!empty($da['ip'])) {
						$value[':ip'] = $da['ip'];
						$insql .= " ip=:ip,";
					}
					
					if (!empty($da['num'])) {
						$value[':num'] = $da['num'];
						$insql .= " num=:num,";
					}
					
					if (!empty($da['macaddress'])) {
						$value[':macaddress'] = $da['macaddress'];
						$insql .= " macaddress=:macaddress,";
					}


					if (!empty($da['uid'])) {
						$value[':uid'] = $da['uid'];
						$insql .= " uid=:uid,";
					}
					
					if (!empty($da['version'])) {
						$value[':version'] = $da['version'];
						$insql .= " version=:version,";
					}
					
					if (!empty($da['package'])) {
						$value[':package'] = $da['package'];
						$insql .= " package=:package,";
					}
					
					if (!empty($da['playtimes'])) {
						$value[':playtimes'] = $da['playtimes'];
						$insql .= " playtimes=:playtimes,";
					}
			   	  	$insql = trim($insql, ',');
					$sql  = "INSERT INTO {{plays_data}} SET $insql ";
					$cmd  = Yii::app()->db->createCommand($sql);
					$bool = $cmd->bindvalues($value)->execute();
			   	  } else {
			   	  	  continue;
			   	  }
			   } else {
			   	  continue;
			   }
		    }
		    fclose($handle);
		}
	}
	
	public function tudanViews() {
		$sql = "SELECT id FROM {{t_list}} ORDER BY views DESC ";
		$cmd  = Yii::app()->db->createCommand($sql);
		$tids = $cmd->queryColumn();
		$i = 0;
		foreach ($tids as $key => $value) {
			$sql = "SELECT SUM(plays) FROM {{tj_singlevideo}} WHERE vid=:vid AND vtype=3 ";
			$cmd = Yii::app()->db->createCommand($sql);
	 		$playCount = $cmd->bindValue(':vid', $value)->queryScalar();
			var_dump($value. '----' . $playCount);
			$sql = "UPDATE {{t_list}} SET views=:views WHERE id=:id";
			$cmd = Yii::app()->db->createCommand($sql);
	 		$bool = $cmd->bindValue(':id', $value)->bindValue(':views', $playCount)->execute();
	 		$i++;
		}
		echo "统计{$i}条兔单观看量 \n";
		return true;
	}
}