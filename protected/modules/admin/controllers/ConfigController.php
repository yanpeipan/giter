<?php

class ConfigController extends AdminBaseController
{
	public function actionChangeStatus(){
		$res['status'] = 0;
		$id   = $this->getParam('id');
		$item = SystemConfig::GetById($id);
		if($item){
			$status = $item['cfg_status'];
			if($status){
				$status = 0;
			}else{
				$status = 1;
			}
			$sql = "UPDATE {{s_config}} SET cfg_status=:status WHERE id=:id";
			$res['status'] = Yii::app()->db->createCommand($sql)->bindValues(array(':status'=>$status,':id'=>$item['id']))->execute();
			if($status){
				$res['msg'] = '是';
			}else{
				$res['msg'] = '否';
			}
		}
		echo json_encode($res);die;
	}
	
}



?>