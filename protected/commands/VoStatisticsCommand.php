<?php
ini_set("error_reporting",E_ALL ^ E_NOTICE);
include('Signfork.class.php');
include_once('py_class.php');
    
class VoStatisticsCommand extends CConsoleCommand{
  
   /**
	 * @param 在线用户统计
	 * 
	 */
    public function actionUserOnline(){
		while(1){
			$arr= RedisH::hash_hGetAll($key);
			if(count($arr)){
				foreach ($arr as $key => $value) 
				{
				$sql=" replace into {{stata_user}} set uid='$key' ,logintime='$value'";
				$cmd = Yii::app()->db->createCommand($sql)->execute();
			   }
				$time=time();
				$values=array(
					':shelldesc'=>'用于监控－在线用户统计',
					':name'        =>'UserOnline',
					':doeverytime' =>'10分钟／次',
					':ts'          => $time,
					':status'      =>'1',
					':dates'       => '',
					':pt_parentid' =>'1',
				);
				$sql="insert into {{shell_controller} set shelldesc=:shelldesc,name=:name,doeverytime=:doeverytime,ts=:ts,status=:status,dates=:dates";
				$res = Yii::app()->db->createCommand($sql)->execute();
				
			}else{
				die('Online_data is null');
			}
		}
    }
    
	/**
	 * @param 活跃用户统计
	 * 
	 */
    public function actionUserActiveuser(){
    	
		while(1){
			$arr= RedisH::hash_hGetAll($key);
			if(count($arr)){
				 foreach ($arr as $key => $value) {
					$sql="replace into {{stata_activeuser}} set uid='$key' ,logintime='$value'";
					$cmd = Yii::app()->db->createCommand($sql)->execute();
				 }
			 	$time=time();
				$values=array(
					':shelldesc'=>'用于监控－活跃用户统计',
					':name'        =>'UserActiveuser',
					':doeverytime' =>'10分钟／次',
					':ts'          => $time,
					':status'      =>'1',
					':dates'       => '',
					':pt_parentid' =>'2',
				);
				$sql="insert into {{shell_controller} set shelldesc=:shelldesc,name=:name,doeverytime=:doeverytime,ts=:ts,status=:status,dates=:dates";
				$res = Yii::app()->db->createCommand($sql)->execute();
				 
				 
			}else{
				die('Activedata is null');
			}
	
			  sleep(600);
		}
    }
    /**
	 * @param 统计播放次数
	 * @param pt_stats_playinfo_single
	 * @param pt_stats_playinfo_double
	 * 判断是单数日期还是双数日期。
	 * 单数日期 清除双数日期的数据。。。双数日期清除单数日期表的数据
	 */
    public function statsSumplayer($key){
    	Yii::app()->db->setPersistent(true);
    	$date=strtotime(date('Y-m-d',time()));
    	$sql="select status from {{shell_controller}} where name='statsSumplayer' and dates=$date";
		$res = Yii::app()->db->createCommand($sql)->queryrow();
		if( !$res){
	    	$d=date('d');
			if($d%2){
				$sql="delete form {{stats_playinfo_double}} where id >1";
			}else{
				$sql="delete form {{stats_playinfo_single}} where id >1";
			}
		    $res = Yii::app()->db->createCommand($sql)->execute();
			if( $res){
				$time=time();
				$values=array(
					':shelldesc'=>'用于监控－统计播放次数－判断是单数日期还是双数日期单数日期 清除双数日期的数据。。。双数日期清除单数日期表的数据',
					':name'        =>'statsSumplayer',
					':doeverytime' =>'1h／次',
					':ts'          => $time,
					':status'      =>'1',
					':dates'       => $date,
					':pt_parentid' =>'3',
				);
				$sql="insert into {{shell_controller} set shelldesc=:shelldesc,name=:name,doeverytime=:doeverytime,ts=:ts,status=:status,dates=:dates";
				$res = Yii::app()->db->createCommand($sql)->execute();
			}
		}
       
	    $sql="select id,category from {{v_list}}";
	    $result = Yii::app()->db->createCommand($sql)->queryall();
		foreach ( $result as $value) {
			 $id=$value["id"];
			 $category=$value["category"];
			 $arrresult[$id]=$value["category"];
		}
		$dates=strtotime(date('Y-m-d',time()));
		//sumplayer
        while(true){
        	//取出数据。 
              $info = RedisH::list_shift($key);
			  if($info){
			  $info=json_decode($info,true);
			  $values=array(
				     ':uid'        =>$info['uid'],
				     ':vid'        =>$info['vid'],
				     ':play_time'  =>$info['play_time'],
				     ':tv_id'      =>$info['tv_id'],
				     ':ip'         =>$info['ip'],
				     ':api_version'=>$info['api_version'],
				     ':soft'       =>$info['soft'],
				     ':category'   =>$info['category'],
				     'source'      =>$info['source'],
				     ':dates'      =>$dates,
				);
				$d=date('d');
				if($d%2){
					$sql="INSERT into {{stats_playinfo_single}} set category=:category,source=:source,dates=:dates,uid=:uid,vid=:vid,play_time=:play_time,tv_id=:tv_id,ip=:ip,api_version=:api_version,soft=:soft";
				}else{
					$sql="INSERT into {{stats_playinfo_double}} set category=:category,source=:source,dates=:dates,uid=:uid,vid=:vid,play_time=:play_time,tv_id=:tv_id,ip=:ip,api_version=:api_version,soft=:soft";
				}
				$cmd = Yii::app()->db->createCommand($sql);
			    $result = $cmd->bindvalues($values)->execute();
				
			  }else{
			  	$time=time();
				$values=array(
					':shelldesc'=>'用于监控－统计播放次数－队列没有数据写入时调用 ',
					':name'        =>'statsSumplayer',
					':doeverytime' =>'',
					':ts'          => $time,
					':status'      =>'1',
					':dates'       => $date,
					':pt_parentid' =>'3',
				);
				$sql="insert into {{shell_controller} set shelldesc=:shelldesc,name=:name,doeverytime=:doeverytime,ts=:ts,status=:status,dates=:dates";
				$res = Yii::app()->db->createCommand($sql)->execute();
				exit('list_shift is null');
				
			  }
          // sleep(10);
        }
    }
	/**
	 * @param 启动次数  使用时长  地区
	 * 
	 */
    public function actionStata_start(){
        while(true)
        {
        	$info = RedisH::list_shift($key);
			$ip=$info['ip'];
			if($ip){
			  $sql="select country ,province from {{ips}} where $ip >= ip_start_int  and $ip <= ip_end_int";
			  $res = Yii::app()->db->createCommand($sql)->queryrow();
			  $values=array(
				     ':uid'        =>$info['uid'],
				     ':times'      =>$info['time'],
				     ':ip'         =>$info['ip'],
				     ':api_version'=>$info['api_version'],
				     ':soft'       =>$info['soft'],
				     'country'     =>$res['country'],
				     'province'    =>$res['province'],
			          );
			  
	    	  $sql="INSERT INTO {{stata_start}} set country=:country,province=:province,uid=:uid,times=:times,ip=:ip,api_version=:api_version,soft=:soft";
			  $cmd = Yii::app()->db->createCommand($sql);
	          $result = $cmd->bindvalues($values)->execute();    
			  }else{
			  	$time=time();
				$values=array(
					':shelldesc'=>'用于监控－启动次数，使用时长，地区－队列没有数据写入时调用 ',
					':name'        =>'Stata_start',
					':doeverytime' =>'1h/次',
					':ts'          => $time,
					':status'      =>'1',
					':dates'       => '',
					':pt_parentid' =>'4',
				);
				$sql="insert into {{shell_controller} set shelldesc=:shelldesc,name=:name,doeverytime=:doeverytime,ts=:ts,status=:status,dates=:dates";
				$res = Yii::app()->db->createCommand($sql)->execute();
					die('ip is not found');
					
			  }
	     }	
    }




	/**
	 * @param 新增用户  地区
	 * 
	 */
    public function actionaddUser(){

	   while(true)
        {
        	$info = RedisH::list_shift($key);
			  $ip=$info['ip'];
			  if($ip){
			  $sql="select country ,province from {{ips}} where $ip >= ip_start_int  and $ip <= ip_end_int";
			  $res = Yii::app()->db->createCommand($sql)->queryrow();
			  $values=array(
				     ':uid'        =>$info['uid'],
				     ':regintime'  =>$info['regintime'],
				     ':country'     =>$res['country'],
				     ':province'    =>$res['province'],
			          );
			  
	    	  $sql="INSERT INTO {{stata_adduser}} set country=:country,province=:province,uid=:uid,times=:times,ip=:ip,api_version=:api_version,soft=:soft";
			  $cmd = Yii::app()->db->createCommand($sql);
	          $result = $cmd->bindvalues($values)->execute();
			  }else{
				$time=time();
				$values=array(
					':shelldesc'=>'用于监控－新增用户,地区.使用时长，地区－队列没有数据写入时调用 ',
					':name'        =>'addUser',
					':doeverytime' =>'',
					':ts'          => $time,
					':status'      =>'1',
					':dates'       =>'',
					':pt_parentid' =>'5',
				);
				$sql="insert into {{shell_controller} set shelldesc=:shelldesc,name=:name,doeverytime=:doeverytime,ts=:ts,status=:status,dates=:dates";
				$res = Yii::app()->db->createCommand($sql)->execute();
				die('addUser is not found');
			  }
	     }	

    }
}