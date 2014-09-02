<?php
class Mergevideo{
	/**
	 * 合并某个视频
	 */
	public function Index($del_id,$retain_id, $sp_id, $sp_mergeid){
		/*-------------------------------vo 后台操作-----------------------------------------*/
		//
		$stdTable_video = '{{v_list}}';
		$stdTable_tv    = '{{v_tv}}';
		$res = array("status"=>0,"msg"=>"参数错误！");
		
		$source = Video::model()->findByPk($del_id)->source;

		$sql="SELECT id,url FROM {$stdTable_tv} WHERE vid=$del_id";
		$query1=Yii::app()->db->createCommand($sql)->queryAll();
		$sql2="SELECT url FROM {$stdTable_tv} WHERE vid=:id";
		$query2=Yii::app()->db->createCommand($sql2)->bindValue(':id',$retain_id)->queryAll();
		$url=array();
		if(!empty($query2)){
			
				foreach($query2 as $val){
					$url[]=$val['url'];
				}
			
				foreach($query1 as $v){
					if(!in_array($v['url'],$url)){
						$sql="UPDATE {$stdTable_tv} SET vid=:id WHERE id=:vid";
						Yii::app()->db->createCommand($sql)->bindValues(array(':vid'=>$v['id'],':id'=>$retain_id))->execute();
					}
				}
		}else{
			$sql="UPDATE {$stdTable_tv} SET vid=:id WHERE vid=:oid";
			Yii::app()->db->createCommand($sql)->bindValues(array(':id'=>$retain_id,'oid'=>$del_id))->execute();
		}
		
		//处理v_player表
		$sql='SELECT id FROM {{v_player}} WHERE video_id=:id';
		$id=Yii::app()->db->createCommand($sql)->bindValue(':id',$del_id)->queryRow();			
		if($id){
			$sql='UPDATE {{v_player}} SET video_id=:vid WHERE video_id=:id';
			Yii::app()->db->createCommand($sql)->bindValues(array(':vid'=>$retain_id,':id'=>$del_id))->execute();
		}
			
		
			
		if($del_id && $retain_id){
			try{
				//处理user_hidden 表
				$sql="UPDATE {{user_hidden}} SET vid=:retain_id WHERE vid=:del_id";
				Yii::app()->db->createCommand($sql)->bindValues(array(':retain_id'=>$retain_id,':del_id'=>$del_id))->execute();
				
				//第一步 处理{{v_list}} 表
				$sql = "SELECT dbscore as score_douban,source FROM {$stdTable_video} WHERE id=:id";
				$cmd = Yii::app()->db->createCommand($sql);
				$retain_source = $cmd->bindValue(':id',$retain_id)->queryRow();
				$del_source = $cmd->bindValue(':id',$del_id)->queryRow();
				////处理豆瓣评分
				if(empty($retain_source['score_douban']) && !empty($del_source['score_douban'])){
					$sql = "UPDATE {$stdTable_video} SET dbscore=:score_douban WHERE id=:id";
					Yii::app()->db->createCommand($sql)->bindValues(array(':score_douban'=>$del_source['score_douban'],':id'=>$retain_id))->execute();
				}
				
				//根据删除source字段判断以下操作
				if($del_source['source']){
					$del_source_array = explode(',',$del_source['source']);				
					$source_1 = array();
					$source_2 = array();
					foreach($del_source_array as $key=>$value){
						if(strrpos($retain_source['source'],$value) === false){
							if(!empty($retain_source['source'])){
								$retain_source['source'].= ','.$value;
								$source_1[] = $value;				
								
							}else{
								
								$source_1[] = $value;			
							}
																											
							
						}else{
							$source_2[] = $value;
						}
					}
					if($del_source['source'] && empty($retain_source['source'])){
						$retain_source['source']=$del_source['source'];
					}
					$data = array();
					$data1 = array();

					//修改保留数据的source字段
					$sql = "UPDATE {$stdTable_video} SET source=:source WHERE id=:id";
					Yii::app()->db->createCommand($sql)->bindValues(array(':source'=>$retain_source['source'],':id'=>$retain_id))->execute();
					//第二步处理{{tv_new}}表
					$__source1 = '(';
					foreach($source_1 as $key=>$value){
						$__source1 .= "'".$value."',";
					}
					$__source1 = substr($__source1,0,-1).')';
					if($__source1 != ')'){
						
						$sql = "UPDATE {$stdTable_tv} SET vid=:tv_parent_id WHERE vid=:id AND source in $__source1";			
						Yii::app()->db->createCommand($sql)->bindValues(array(':tv_parent_id'=>$retain_id,':id'=>$del_id))->execute();
					}
					$sql = "DELETE FROM {$stdTable_tv} WHERE vid=:tv_parent_id";
					Yii::app()->db->createCommand($sql)->bindValue(':tv_parent_id',$del_id)->execute();
					
				}
				//处理收藏，播放历史，推荐，频道数据
				$this->__dealMergereData($del_id,$retain_id);
				
				//处理sohu相关数据
				$this->__dealSohuData($del_id,$retain_id);	
				
				//处理剧照
				$this->__dealPicData($del_id,$retain_id);
				
					//删除要删除的数据
				$sql = "DELETE FROM {$stdTable_video} WHERE id=:id";
				Yii::app()->db->createCommand($sql)->bindValue(':id',$del_id)->execute();
			    $res['status'] = 1;
				$sql = "INSERT INTO {{merge_relation}} SET vid=".$retain_id.",sid=".$sp_id.",delvid=".$del_id.",delsid=".$sp_mergeid.',ctime='.time().',source="'.$source.'"';
				Yii::app()->db->createCommand($sql)->execute();
			}catch(exception $e){
				$res['status'] = 0;
				//$res['msg'] = $e->getMessage();
				var_dump( $e->getMessage());
			}
		}
		return $res['status'];
	}
	
	/**
	 * 处理历史，推荐，频道，收藏数据
	 */
	public function __dealMergereData($del_id,$retain_id){
		
		//处理history数据
		$sql = "UPDATE {{user_history}} SET vid=:vid WHERE vid=:vid_bak";
	    Yii::app()->db->createCommand($sql)->bindValues(array(':vid'=>$retain_id,':vid_bak'=>$del_id))->execute();
		
		/*处理推荐数据
		$sql = "UPDATE {{recommend}} SET vid=:vid WHERE vid=:vid_bak and topic_id>1";
		$cmd = Yii::app()->db->createCommand($sql)->bindValues(array(':vid'=>$retain_id,':vid_bak'=>$del_id))->execute();*/
		
		//处理频道数据
		$sql = "UPDATE {{v_channel}} SET vid=:v_id WHERE vid=:vid_bak";
		Yii::app()->db->createCommand($sql)->bindValues(array(':v_id'=>$retain_id,':vid_bak'=>$del_id))->execute();
		
		//处理收藏数据
		$sql = "UPDATE {{user_favorite}} SET vid=:vid WHERE vid=:vid_bak";
		Yii::app()->db->createCommand($sql)->bindValues(array(':vid'=>$retain_id,':vid_bak'=>$del_id))->execute();
		

	}
	/**
	 * 处理搜狐相关数据
	 */
	public function __dealSohuData($del_id,$retain_id){
		$sql_tv = "UPDATE {{v_ext}} SET tv_parent_id=:newid WHERE tv_parent_id=:oldid";
		$param  =array(':newid'=>$retain_id,':oldid'=>$del_id);
		Yii::app()->db->createCommand($sql_tv)->bindValues($param)->execute();
		
		/*$sql_video = "UPDATE {{video_ext}} SET video_id=:newid WHERE video_id=:oldid";
		Yii::app()->db->createCommand($sql_video)->bindValues($param)->execute();*/
	}
	
	/**
	 * 处理剧照
	 */
	public function __dealPicData($del_id,$retain_id){
		$sql = "SELECT * FROM {{v_pic}} WHERE vid=".$del_id;
		$del = Yii::app()->db->createCommand($sql)->queryRow();
		if($del){
			$add = json_decode($del['pic'],TRUE);
			$sql = "SELECT * FROM {{v_pic}} WHERE VID=".$retain_id;
			$ret = Yii::app()->db->createCommand($sql)->queryRow();
			$old = json_decode($ret['pic'],TRUE);
			$new = array_merge($add,$old);
			$new = json_encode($new);
			$sql = "UPDATE {{v_pic}} SET pic='".$new."' WHERE vid=".$retain_id;
			Yii::app()->db->createCommand($sql)->execute();
		}
		
	}
	public function s2t($name){
		switch ($name) {
			case '56':
				$name = 'wole';
				break;
			case '163':
				$name = "wangyi";
				break;
			case 'yyets':
				$name = 'renren';
				break;
		} 
		return $name;
	}
	
	public function t2s($name){
		switch ($name) {
			case 'wole':
				$name = '56';
				break;
			case 'wangyi':
				$name = "163";
				break;
			case 'renren':
				$name = 'yyets';
				break;	
		}
		return $name;
	}
}
