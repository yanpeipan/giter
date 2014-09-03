<?php

class GetRedisCommand extends CConsoleCommand{

    public function actionRedisGet(){
        Yii::app()->db->setPersistent(true);
        require_once(Yii::app()->basePath."/vendors/RedisHandler.php");
        require_once(Yii::app()->basePath."/vendors/Rediska_0_5_6/Rediska.php");
        while(true){
            $info = RedisHandler::list_shift();
            if($info){
                if($info['type'] ==1){          // insert pt_cnt_list
                    $ip = RedisHandler::kv_get($info['ip']);
                    if($ip){
                        $place = $ip;
                    }else{
                        $return = $this->_convert_ip_full($info['ip']);     //ip=>地区
                        $place = iconv('GBK', 'UTF-8', $return);
                        RedisHandler::kv_set($info['ip'],$place);
                    }
                    $info['place'] = $this->_getIpId($place);
                    $info['method'] = $this->_convert_method_desc($info['method']);     //method=>descride
                    if(!isset($info['method'])){
                        continue;
                    }
                    $info['agent'] = $this->_agentInfo($info['user_agent']);
                    $this->_insert_info($info);
                }else if($info['type'] ==2){    //insert pt_cnt_playerinfo
                    $ip = RedisHandler::kv_get($info['ip']);
                    //var_dump($info['ip']);die;
                    if($ip){
                        $place = $ip;
                    }else{
                        $return = $this->_convert_ip_full($info['ip']);     //ip=>地区
                        $place = iconv('GBK', 'UTF-8', $return);
                        RedisHandler::kv_set($info['ip'],$place);
                    }
                    $info['place'] = $this->_getIpId($place);
                    $this->_add_playerinfo($info);
                }else if($info['type'] ==3){    //insert pt_cnt_equipment
                    $this->_add_equipment($info);
                }
            }
           // sleep($time);
        }
    }

    private function _add_equipment($info){
        $this->_insert_equipment($info);
    }
    
    private function _add_playerinfo($info){
        $this->_insert_playerinfo($info);
    }
    
    private function _insert_equipment($info){
        $sql = "INSERT INTO {{cnt_equipment}} SET 
                                            uid            =:uid,
                                            tid            =:tid,
                                            android_version=:android_version,
                                            core_version   =:core_version,
                                            mac_add        =:mac_add,
                                            model_num      =:model_num,
                                            sof_version    =:sof_version";
        $cmd = Yii::app()->db->createCommand($sql);
        $data = array(
                ':uid'              => $info['uid']!=''?$info['uid']:0,
                ':tid'              => $info['tid']!=''?$info['tid']:0,
                ':android_version'  => $info['android_version']!=''?$info['android_version']:'',
                ':core_version'     => $info['core_version']!=''?$info['core_version']:'',
                ':mac_add'          => $info['mac_add']!=''?$info['mac_add']:'',
                ':model_num'        => $info['model_num']!=''?$info['model_num']:'',
                ':sof_version'      => $info['sof_version']!=''?$info['sof_version']:'',
        );
        $bool = $cmd->bindValues($data)->execute();
        //var_dump($info['type'].','.$bool);
    }
    
    private function _insert_playerinfo($info){
        $sql = "INSERT INTO {{cnt_playerinfo}} SET 
                                            uid         =:uid,
                                            vid         =:vid,
                                            tv_id       =:tv_id,
                                            screen_ratio=:screen_ratio,
                                            play_time   =:play_time,
                                            date_time   =:date_time,
                                            play_ratio  =:play_ratio,
                                            place_id    =:place,
                                            definition  =:definition";
        $cmd = Yii::app()->db->createCommand($sql);
        $data = array(
                ':uid'          => $info['uid']!=''?$info['uid']:0,
                ':vid'          => $info['vid']!=''?$info['vid']:0,
                ':tv_id'        => $info['tv_id']!=''?$info['tv_id']:1,
                ':screen_ratio' => $info['screen_ratio']!=''?$info['screen_ratio']:'',
                ':play_time'    => $info['play_time']!=''?$info['play_time']:'',
                ':date_time'    => $info['date_time']!=''?$info['date_time']:'',
                ':play_ratio'   => $info['play_ratio']!=''?$info['play_ratio']:'',
                ':place'        => $info['place']!=''?$info['place']:'',
                ':definition'   => $info['definition']!=''?$info['definition']:'',
        );
        $bool = $cmd->bindValues($data)->execute();
        //var_dump($info['type'].','.$bool);
        
        //计算播放次数
        $vid = $info['vid']!=''?$info['vid']:0;
        $this->PlayTimesCount($vid);
    }
    
    private function _insert_info($info){
        $sql ="INSERT INTO {{cnt_list}} SET 
                                            uid=:uid,
                                            tid=:tid,
                                            ip=:ip,
                                            place_id=:place,
                                            time=:time,
                                            method_id=:method,
                                            user_agent=:user_agent,
                                            agent=:agent";
        $cmd = Yii::app()->db->createCommand($sql);
        $data = array(
                ':uid'          => $info['uid']!=''?$info['uid']:0,
                ':tid'          => $info['tid']!=''?$info['tid']:0,
                ':ip'           => $info['ip']!=''?$info['ip']:'',
                ':place'        => $info['place']!=''?$info['place']:'',
                ':time'         => $info['time']!=''?$info['time']:'',
                ':method'       => $info['method']!=''?$info['method']:'',
                ':user_agent'   => $info['user_agent']!=''?$info['user_agent']:'',
                ':agent'   => $info['agent']!=''?$info['agent']:'',
        );
        $bool = $cmd->bindValues($data)->execute();
        //var_dump($info['type'].','.$bool);
    }
    
    private function _agentInfo($agent){
        $agent_1 = explode(';',$agent);
        if(isset($agent_1[2])){
            return trim($agent_1[2]);
        }else{
            $agent_2 = explode('/',$agent_1[0]);
            return trim($agent_2[0]);
        }
        //return $agent_1;
        //return $agent_1[2];
    }
    
    private function _getIpId($place){
        $sql ="SELECT * FROM {{cnt_area}} WHERE area_name=:place";
        $cmd = Yii::app()->db->createCommand($sql)->bindValue(':place',$place);
        $result = $cmd->queryRow();
        if($result){        //get id
            $id = $result['id'];
        }else{              //create area
            $sql_cte = "INSERT INTO {{cnt_area}} SET area_name=:area_name";
            $res = Yii::app()->db->createCommand($sql_cte)->bindValue(':area_name',$place)->execute();
            $id = Yii::app()->db->getLastInsertID();
        }
        return $id;
    }
    
    private function _convert_method_desc($method){
        $sql = 'SELECT * FROM {{cnt_method}} WHERE method_name=:method';
        $cmd = Yii::app()->db->createCommand($sql)->bindValue(':method',$method);
        $result = $cmd->queryRow();
        return $result['id'];
    }
    private function _convert_ip_full($ip) {
        $ipdatafile = '../protected/extensions/ip/tinyipdata.dat';
        if (! $fd = @fopen ( $ipdatafile, 'rb' )) {
            return 'Invalid IP data file';
        }
        
        $ip = explode ( '.', $ip );
        $ipNum = $ip [0] * 16777216 + $ip [1] * 65536 + $ip [2] * 256 + $ip [3];
        
        if (! ($DataBegin = fread ( $fd, 4 )) || ! ($DataEnd = fread ( $fd, 4 )))
            return;
        @$ipbegin = implode ( '', unpack ( 'L', $DataBegin ) );
        if ($ipbegin < 0)
            $ipbegin += pow ( 2, 32 );
        @$ipend = implode ( '', unpack ( 'L', $DataEnd ) );
        if ($ipend < 0)
            $ipend += pow ( 2, 32 );
        $ipAllNum = ($ipend - $ipbegin) / 7 + 1;
        
        $BeginNum = $ip2num = $ip1num = 0;
        $ipAddr1 = $ipAddr2 = '';
        $EndNum = $ipAllNum;
        
        while ( $ip1num > $ipNum || $ip2num < $ipNum ) {
            $Middle = intval ( ($EndNum + $BeginNum) / 2 );
            
            fseek ( $fd, $ipbegin + 7 * $Middle );
            $ipData1 = fread ( $fd, 4 );
            if (strlen ( $ipData1 ) < 4) {
                fclose ( $fd );
                return 'System Error';
            }
            $ip1num = implode ( '', unpack ( 'L', $ipData1 ) );
            if ($ip1num < 0)
                $ip1num += pow ( 2, 32 );
            
            if ($ip1num > $ipNum) {
                $EndNum = $Middle;
                continue;
            }
            
            $DataSeek = fread ( $fd, 3 );
            if (strlen ( $DataSeek ) < 3) {
                fclose ( $fd );
                return 'System Error';
            }
            $DataSeek = implode ( '', unpack ( 'L', $DataSeek . chr ( 0 ) ) );
            fseek ( $fd, $DataSeek );
            $ipData2 = fread ( $fd, 4 );
            if (strlen ( $ipData2 ) < 4) {
                fclose ( $fd );
                return 'System Error';
            }
            $ip2num = implode ( '', unpack ( 'L', $ipData2 ) );
            if ($ip2num < 0)
                $ip2num += pow ( 2, 32 );
            
            if ($ip2num < $ipNum) {
                if ($Middle == $BeginNum) {
                    fclose ( $fd );
                    return 'Unknown';
                }
                $BeginNum = $Middle;
            }
        }
        
        $ipFlag = fread ( $fd, 1 );
        if ($ipFlag == chr ( 1 )) {
            $ipSeek = fread ( $fd, 3 );
            if (strlen ( $ipSeek ) < 3) {
                fclose ( $fd );
                return 'System Error';
            }
            $ipSeek = implode ( '', unpack ( 'L', $ipSeek . chr ( 0 ) ) );
            fseek ( $fd, $ipSeek );
            $ipFlag = fread ( $fd, 1 );
        }
        
        if ($ipFlag == chr ( 2 )) {
            $AddrSeek = fread ( $fd, 3 );
            if (strlen ( $AddrSeek ) < 3) {
                fclose ( $fd );
                return 'System Error';
            }
            $ipFlag = fread ( $fd, 1 );
            if ($ipFlag == chr ( 2 )) {
                $AddrSeek2 = fread ( $fd, 3 );
                if (strlen ( $AddrSeek2 ) < 3) {
                    fclose ( $fd );
                    return 'System Error';
                }
                $AddrSeek2 = implode ( '', unpack ( 'L', $AddrSeek2 . chr ( 0 ) ) );
                fseek ( $fd, $AddrSeek2 );
            } else {
                fseek ( $fd, - 1, SEEK_CUR );
            }
            
            while ( ($char = fread ( $fd, 1 )) != chr ( 0 ) )
                $ipAddr2 .= $char;
            
            $AddrSeek = implode ( '', unpack ( 'L', $AddrSeek . chr ( 0 ) ) );
            fseek ( $fd, $AddrSeek );
            
            while ( ($char = fread ( $fd, 1 )) != chr ( 0 ) )
                $ipAddr1 .= $char;
        } else {
            fseek ( $fd, - 1, SEEK_CUR );
            while ( ($char = fread ( $fd, 1 )) != chr ( 0 ) )
                $ipAddr1 .= $char;
            
            $ipFlag = fread ( $fd, 1 );
            if ($ipFlag == chr ( 2 )) {
                $AddrSeek2 = fread ( $fd, 3 );
                if (strlen ( $AddrSeek2 ) < 3) {
                    fclose ( $fd );
                    return '- System Error';
                }
                $AddrSeek2 = implode ( '', unpack ( 'L', $AddrSeek2 . chr ( 0 ) ) );
                fseek ( $fd, $AddrSeek2 );
            } else {
                fseek ( $fd, - 1, SEEK_CUR );
            }
            while ( ($char = fread ( $fd, 1 )) != chr ( 0 ) )
                $ipAddr2 .= $char;
        }
        fclose ( $fd );
        
        if (preg_match ( '/http/i', $ipAddr2 )) {
            $ipAddr2 = '';
        }
        $ipaddr = "$ipAddr1 $ipAddr2";
        $ipaddr = preg_replace ( '/CZ88\.NET/is', '', $ipaddr );
        $ipaddr = preg_replace ( '/^\s*/is', '', $ipaddr );
        $ipaddr = preg_replace ( '/\s*$/is', '', $ipaddr );
        if (preg_match ( '/http/i', $ipaddr ) || $ipaddr == '') {
            $ipaddr = 'Unknown';
        }
        
        return $ipaddr;
    }

    /**
     * 统计播放次数
     * @param $vid
     */
    private function PlayTimesCount($vid)
    {
        if(!empty($vid)){
            $sql = "SELECT COUNT(vid) AS cnt FROM {{cnt_playerinfo}} WHERE vid=:vid";
            $cmd = Yii::app()->db->createCommand($sql);
            $cmd->bindValue(':vid',$vid);
            $row = $cmd->queryRow();
            
            if($row)
            {
                $count = $row['cnt'];
                
                //更新{{v_list}}表
                $values = array(
                    ':play_count'=>$count,
                    ':id'=>$vid,
                );
                $sql = "UPDATE {{v_list}} SET play_count=:play_count WHERE id=:id";
                $cmd = Yii::app()->db->createCommand($sql);
                $cmd->bindValues($values);
                $cmd->execute();
            }
        }
    }



}