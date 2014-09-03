<?php
class Checkcfg 
{
    /**
     * 检查播放源配置
     */
    public function DoCheck()
    {
        //检查source
        $sql = "SELECT cfg_value FROM {{config}} WHERE cfg_name='source' AND is_complete='0'";
        $cmd = Yii::app()->db->createCommand($sql);
        $row = $cmd->queryRow();
        if($row)
        {
            $str_source = $row['cfg_value'];
            $arr_source = explode(',', $str_source);
            //查找上次source的内容,判断是开启还是关闭
            $sql = "SELECT cfg_value FROM {{config}} WHERE cfg_name='source_last_update'";
            $cmd = Yii::app()->db->createCommand($sql);
            $last_source = $cmd->queryRow();
            
            $result = array();
            $switch = 0;
            if($last_source)
            {
                $arr_last_source = explode(',', $last_source['cfg_value']);//上次更新后的数据

                //判断开关
                if(count($arr_source)>count($arr_last_source))
                {
                    //开启差集对应的source
                    $result = array_diff($arr_source, $arr_last_source);
                    $switch = 1;
                }elseif(count($arr_source)<count($arr_last_source)){
                    //关闭差集对应的source
                    $result = array_diff($arr_last_source, $arr_source);
                    $switch = 2;
                }
            }

            if(!empty($result))
            {
                if($switch==1)
                {
                    //开启接入点
                    foreach($result as $key=>$val)
                    {
                        $source = $val;
                        $sql = "UPDATE {{v_list}} SET is_show_source='1' WHERE source='{$source}'";
                        $cmd = Yii::app()->db->createCommand($sql);
                        $cmd->execute();
                        
                        //更新letter表对应的is_show_source字段
                        $this->update_is_show_source($source, $switch);
                    }
    
                    //标记配置完成
                    $this->mark();
                    
                    //更新source_last_update
                    $this->update_source_last($str_source);
                }elseif($switch==2){
                    //关闭接入点
                    foreach($result as $key=>$val)
                    {
                        $source = $val;
                        $sql = "UPDATE {{v_list}} SET is_show_source='0' WHERE source='{$source}'";
                        $cmd = Yii::app()->db->createCommand($sql);
                        $cmd->execute();
                        
                        //更新letter表对应的is_show_source字段
                        $this->update_is_show_source($source, $switch);
                    }
    
                    //标记配置完成
                    $this->mark();
                    
                    //更新source_last_update
                    $this->update_source_last($str_source);
                }
            }
        }
    }
    
    
    /**
     * 更新source_last_update
     * @param $str_source
     */
    private function update_source_last($str_source)
    {
        $sql_update = "UPDATE {{config}} SET cfg_value='{$str_source}' WHERE cfg_name='source_last_update'";
        $cmd_update = Yii::app()->db->createCommand($sql_update);
        $cmd_update->execute();   
    }
    
    /**
     * 标记配置完成
     */
    private function mark()
    {
        $sql_cfg = "UPDATE {{config}} SET is_complete='1' WHERE cfg_name='source'";
        $cmd_cfg = Yii::app()->db->createCommand($sql_cfg);
        $cmd_cfg->execute();
    }
    
    /**
     * 更新letter表is_show_source
     * @param $source
     * @param $switch
     */
    private function update_is_show_source($source, $switch)
    {
        if($switch==1)
        {
            //显示
            $sql = "UPDATE {{letter}} SET is_show_source='1' WHERE source='{$source}'";
        }elseif($switch==2){
            //隐藏
            $sql = "UPDATE {{letter}} SET is_show_source='0' WHERE source='{$source}'";
        }

        $cmd = Yii::app()->db->createCommand($sql);
        $cmd->execute();
    }
     /**      
     * 检查redis开关配置
     */
    public function getRedisConfig()
    {
    	$key = 'cahe_switch_new';
		//$switch = RedisHandler::kv_get($key);
		
		
		if(strval($switch)==''){
	        $sql = "SELECT cfg_value FROM {{config}} WHERE cfg_name='cache'";
	        $cmd = Yii::app()->db->createCommand($sql);
	        $row = $cmd->queryRow();
	        if($row)
	        {
	            $switch = $row['cfg_value'];
	        }else{
	            $switch = 0;
	        }
			
			
			//RedisHandler::kv_set_expire('cahe_switch_new',$switch,300);
		}

        return $switch; 
    }
    
    /**
     * 检查数据来源开关
     */
    public function getDataConfig()
    {
    	$key = 'data_switch_new';
		//$data_switch = RedisHandler::kv_get($key);
		
		
		if(strval($data_switch)==''){
	        $sql = "SELECT cfg_value FROM {{config}} WHERE cfg_name='data_switch'";
	        $cmd = Yii::app()->db->createCommand($sql);
	        $row = $cmd->queryRow();
	        if($row)
	        {
	            $data_switch = $row['cfg_value'];
	        }else{
	            $data_switch = 0;
	        }
			
			
			
		//	RedisHandler::kv_set_expire('data_switch_new',$data_switch,300);
			
			
			
			
		}
        return $data_switch;
    }
    
}

?>