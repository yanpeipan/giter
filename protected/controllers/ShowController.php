<?php
class ShowController extends Controller{
    public $layout=false;
	public function actionChannel(){
		$sql = "SELECT * FROM {{channel}}";
		$ch  = Yii::app()->db_puti->createCommand($sql)->queryAll();
		foreach ($ch as $key => $value) {
			$pid = SystemConfig::GetCateId($value['category']);
			$config = array(
			    'cfg_value'   => $value['id'] ,
				'cfg_pid'     => $pid ,
				'cfg_order'   => $value['order_id'] ,
				'cfg_comment' => $value['ch_name'],
				'ctime'       => time(),
			);
			SystemConfig::set("VIDEO_CHANNEL",$config);
		}
	}
	public function actionTopic(){
		$sql = "SELECT * FROM {{topic}} where is_shows=1";
		$ch  = Yii::app()->db_puti->createCommand($sql)->queryAll();
		foreach ($ch as $key => $value) {
			$config = array(
			    'cfg_value'   => $value['id'] ,
				'cfg_pid'     => NULL,
				'cfg_order'   => $value['order'] ,
				'cfg_comment' => $value['topic_name'],
				'ctime'       => time(),
			);
			SystemConfig::set("VIDEO_TOPIC",$config);
		}
	}
	public function actionTags(){
		$sql = "SELECT * FROM {{type}}";
		$ch  = Yii::app()->db_puti->createCommand($sql)->queryAll();
		foreach ($ch as $key => $value) {
			if($value['mark']==4){
			$pid = SystemConfig::GetCateId($value['mark']);
			$config = array(
			    'cfg_value'   => $value['id'] ,
				'cfg_pid'     => $pid,
				'cfg_order'   => $value['orders'] ,
				'cfg_comment' => $value['type_name'],
				'ctime'       => time(),
			);
			SystemConfig::set("VIDEO_TAGS",$config);
			}
		}
	}
	public function actionArea(){
		$sql = "SELECT * FROM {{area}}";
		$ch  = Yii::app()->db_puti->createCommand($sql)->queryAll();
		foreach ($ch as $key => $value) {
			if($value['mark']==4){
				
			$pid = SystemConfig::GetCateId($value['mark']);
			$config = array(
			    'cfg_value'   => $value['id'] ,
				'cfg_pid'     => $pid,
				'cfg_order'   => $value['orders'] ,
				'cfg_comment' => $value['area_name'],
				'ctime'       => time(),
			);
			SystemConfig::set("VIDEO_AREA",$config);
			}
		}
	}
	public function actionYear(){
		$sql = "SELECT * FROM {{year}}";
		$ch  = Yii::app()->db_puti->createCommand($sql)->queryAll();
		
		foreach ($ch as $key => $value) {
			$config = array(
			    'cfg_value'   => $value['year_name'] ,
				'cfg_pid'     => null,
				'cfg_order'   => 1 ,
				'cfg_comment' => $value['year_name'],
				'ctime'       => time(),
			);
			SystemConfig::set("VIDEO_YEAR",$config);
		}
	}
}