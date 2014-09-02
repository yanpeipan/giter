<?php
/**
 * 抢购活动验证api
 */
class ActivityApi extends BaseApi{
	
	
	//T码验证
	public function verify(){
		$id = isset($_REQUEST['tcode']) ? trim($_REQUEST['tcode']) : '';
		if(!empty($id)){
			//$sql = "SELECT id,create_time FROM card WHERE id=:id";
			$sql = "SELECT c.id,o.create_time FROM card as c left join `order` as o on c.id=o.card_id WHERE c.id=:id";
			$cmd = Yii::app()->db_freebuy->createCommand($sql);
			$row = $cmd->bindValue(":id",$id)->queryRow();
			if($row && ((time()-$row['create_time']) <= 24*3600) || preg_match('/f/', $id)){
				//更新状态
				$values = array(
					':isVerify'=>1,
					':id'=>$id,
				);
				$sql = "UPDATE {{card}} SET isVerify=:isVerify WHERE id=:id";
				$cmd = Yii::app()->db_freebuy->createCommand($sql);
				$cmd->bindValues($values)->execute();
				$free = strpos($row['id'], 'f')===false ? '0' : '1';
				
				//更新order表
				$values = array(
					':isVerify'=>1,
					':card_id'=>$id
				);
				$sql = "UPDATE {{`order`}} SET isVerify=:isVerify WHERE card_id=:card_id";
				$cmd = Yii::app()->db_freebuy->createCommand($sql);
				$cmd->bindValues($values)->execute();
				
				$json_array = array(
					'status'=>'1',
					'free'=>"$free",
				);
			}else{
				$json_array = array(
					'status'=>'0',
					'error_no'=>'1002',
					'error_msg'=>'tcode not effective',//无效的tcode
				);
			}
		}else{
			$json_array = array(
				'status'=>'0',
				'error_no'=>'1001',
				'error_msg'=>'tcode empty',
			);
		}

		return $json_array;
	}
	
	
	
	
	
	
}
?>