<?php


class AdStat{
	public function test($name){
		echo "hello world,$name\n";
		
		
		print_r(file_get_contents("http://www.baidu.com/"));
		
	}
	
	/**
	 *  统计每个广告位每个月或者每天的展示次数、点击次数
	 * 
	 * @param $date  YYYYmm(201208)
	 */
	public function adstatinfo(){ 
		$isExists = $this->checkTableExistsAdinfo();
		if($isExists){
			$info = $this->statSum();
			if($info){
				for($i=0; $i<count($info); $i++){

					$statArr = $this->getStatInfo();
					
					$ad_position_id = isset($info[$i]['ad_position']) ? $info[$i]['ad_position'] : '';
					$params = array(
									':ad_position_id'  => $ad_position_id,
									':ad_showtimes'    => isset($info[$i]['ad_showtime']) ? $info[$i]['ad_showtime'] : '',
									':ad_clicktimes'   => isset($info[$i]['ad_clicktime']) ? $info[$i]['ad_clicktime'] : '',
								);
					//若此广告位id在statinfo表中已经存在，则更新，否则插入
					if(in_array($ad_position_id, $statArr)){
						$this->insertOrUpdateStatinfo(1, $params);
						echo "ad_position_id=". $ad_position_id." update ok!".PHP_EOL;
					}else{
						$this->insertOrUpdateStatinfo(0, $params);
						echo "ad_position_id=". $ad_position_id." insert ok!".PHP_EOL;
					}
					
				}
				
			}
			
		}
	}
	
	/**
	 * check table exists 
	 * 
	 * @return   boolean
	 */
	private function checkTableExistsAdinfo()
	{
		//$sql = "SHOW TABLES LIKE 'pt_ad_stat_adinfo"."_$date";
		$sql = "SHOW TABLES LIKE 'pt_ad_stat_adinfo'";
		$re  = Yii::app()->db->createCommand($sql)->queryRow();
		if($re){
			//存在
			return true;
		}else{
			//不存在
			return false;
		}
	}
	
	/**
	 * 获取ad_stat_adinfo表中每个广告位的总点击次数、总展示次数
	 * 
	 * @return array/false
	 */
	private function statSum()
	{
		//$sql  = "SELECT ad_position, SUM(ad_showtime) as ad_showtime,SUM(ad_clicktime) as ad_clicktime FROM pt_ad_stat_adinfo_$date GROUP BY ad_position;";
		$sql  = "SELECT ad_position, 
				SUM(ad_showtime) as ad_showtime,
				SUM(ad_clicktime) as ad_clicktime 
				FROM {{ad_stat_adinfo}} 
				GROUP BY ad_position;
				";
		$info = Yii::app()->db->createCommand($sql)->queryAll();
		if(count($info) > 0 ){
			return $info;
		}
		return false;	
	}
	
	/**
	 * 查询要插入的表中是否有信息
	 * 
	 * @return    Array
	 */
	private function getStatInfo(){
		$sql  = "SELECT ad_position_id FROM {{ad_statinfo}};";
		$re   = Yii::app()->db->createCommand($sql)->queryAll();
		$statArr =array();
		if(count($re) > 0){
			foreach($re as $v){
				$statArr[] = $v['ad_position_id'];
			}
		}else{
			$statArr[] = '';		
		}
		return $statArr;
	}
	
	/**
	 * 插入或更新统计信息
	 */
	private function insertOrUpdateStatinfo($flag, $params){
		$_options = array(
			':ad_position_id' => '',
			':ad_showtimes'   => '',
			':ad_clicktimes'  => '',
			':yyyy_mm' 		  => date('Ym'),
			':ctime'		  => date('Y-m-d H:i:s'),
		);
		
		if($flag){
			$sql = "UPDATE {{ad_statinfo}} 
       				SET ad_showtimes=:ad_showtimes,
       				ad_clicktimes=:ad_clicktimes,
       				ctime=:ctime,
       				yyyy_mm=:yyyy_mm
                	WHERE  ad_position_id=:ad_position_id
					";
		}else{
			$sql = "INSERT INTO {{ad_statinfo}} 
                  	SET ad_position_id=:ad_position_id,
                  	ad_showtimes=:ad_showtimes,
                  	ad_clicktimes=:ad_clicktimes,
                  	yyyy_mm=:yyyy_mm,
                  	ctime=:ctime
                  	";

		}
		$option = array_merge($_options, $params);
		
		$cmd = Yii::app()->db->createCommand($sql);
		$cmd->bindValues($option);
		$cmd->execute();
		
		return 1;
	}
	
	
}
