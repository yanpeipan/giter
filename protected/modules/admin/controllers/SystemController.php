<?php
class SystemController extends AdminBaseController
{
	public $url;
    public $layout = "//layouts/column2";
    //频道显示
    public function actionView()
    {
    	$model_s_config=SConfig::model();
	
	
		$data_tmp=$model_s_config->findAll('cfg_pid=:cfg_pid and cfg_name like :cfg_name',array(':cfg_pid'=>'17',':cfg_name'=>'SYSTEM%'));
		
		$config=array();
		foreach ($data_tmp as $key => $value) {
			$config[$value['cfg_name']]=$value;
		}		
		$this->render('update',array('config'=>$config));
    }
    public function actionUpdate(){
    	
    	if(isset($_GET['system_config']) && !empty($_GET['system_config'])){
    		$changed=json_decode($_GET['system_config'],true);
			
			$model_s_config=SConfig::model();
			
			$saved=array();
			foreach ($changed as  $row_s_config) {
				
				$row_s_config_id=intval($row_s_config['id']);
				if(empty($row_s_config_id)){
					//记录
					continue;
				}
				$row_s_config_cfg_value=$row_s_config['input_value'];
				if(is_array($row_s_config['input_value'])){
					$row_s_config_cfg_value=json_encode($row_s_config_cfg_value,true);
				}
				$tmp=$model_s_config->findbyPk($row_s_config_id);
				
				$tmp->cfg_value=$row_s_config_cfg_value;
				if($tmp->validate()){
					if($tmp->save()){
						$saved[]=$row_s_config_id;
					}
				}
				//var_dump($tmp);
				
			}
			$status='0';
			$info='修改失败';
			if(count($saved)>0){
				$status='1';
				$info='修改成功';
			}
    		$ajax_return=array(
    			'status'=>$status,
    			'info'=>$info,
    			'data'=>var_export($saved,true),
			);
			
			echo json_encode($ajax_return);
			
    	}else{
    		$ajax_return=array(
    			'status'=>0,
    			'info'=>'更改失败,没接收到相关参数',
    			'data'=>var_export($_REQUEST,true),
			);
			echo json_encode($ajax_return);
    	}		
    }

  
}



?>