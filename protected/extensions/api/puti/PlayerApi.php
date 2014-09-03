<?php
class PlayerApi extends BaseApi{
    public function GetPlayerInfo($params){
        if(!isset($params['uid']) 
        || !isset($params['vid']) 
        || !isset($params['tv_id']) 
        || !isset($params['screen_ratio']) 
        || !isset($params['play_ratio']) 
        || !isset($params['definition'])){
            $result = array("status"=>'0');
        }else{
            if($params['play_ratio']=='null'||$params['play_ratio']=='NULL')
            {
                $params['play_ratio'] = "";
            }
            
            $play_time = time();
            @$ip = $_SERVER['REMOTE_ADDR'];
            $type = array('type'=>2,'ip'=>$ip);
            $date = array('date_time'=>date('Y-m-d',$play_time),'play_time'=>$play_time);
            $playinfo = array_merge($params,$type,$date);
			$playinfo['api_version'] = isset($_POST['version']) ? $_POST['version'] : '';
            RedisHandler::list_append($playinfo);
            $result = array("status"=>'1');
        }
        return $result;
    }
    
}
