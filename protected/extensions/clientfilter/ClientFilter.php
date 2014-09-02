<?php
class ClientFilter
{
    private $cache_id = 'client_filter';
    
    /**
     * 获取不同操作系统接入点控制
     * @param $cache_switch
     */
    public function GetFilters($cache_switch)
    {
        $filters = '';
        $cache_id = $this->cache_id;
        if($cache_switch==1)
        {
            $filters = RedisHandler::kv_get($cache_id);
            if(!$filters) 
            {
                $sql = "SELECT cfg_value FROM {{config}} WHERE id=7";
                $cmd = Yii::app()->db->createCommand($sql);
                $row = $cmd->queryRow();
                if($row)
                {
                    $filters = $row['cfg_value'];
                    RedisHandler::kv_set_expire($cache_id, $filters, 300);
                }
            }
        }else{
            $sql = "SELECT cfg_value FROM {{config}} WHERE id=7";
            $cmd = Yii::app()->db->createCommand($sql);
            $row = $cmd->queryRow();
            if($row)
            {
                $filters = $row['cfg_value'];
            }
        }
        if($filters)
        {
            $filters = json_decode($filters, true);
        }
        return $filters;
    }
}

?>