<?php
class DateCount{
    public function Date($time){
        //while(true){
            $this->_setMembets($time);
          //  sleep(60);
        //}
    }
    private function _setMembets($time){
        //Lookup pt_cnt_date_list inside data
        //var_dump($time);
        $sql_sel = "SELECT new_user,active_user,play_cnt FROM {{cnt_date_list}} WHERE date_time='$time'";
        $row = Yii::app()->db->createCommand($sql_sel)->queryRow();
        //var_dump($row);
        if(!$row){
            unset($sql_sel);
            $sql_add="INSERT INTO {{cnt_date_list}} SET 
                                                date_time=:time";
            Yii::app()->db->createCommand($sql_add)->bindValue(':time',$time)->execute();
            unset($sql_add);
            $sql="INSERT INTO {{cnt_date_active}} SET date_time=:time";
            Yii::app()->db->createCommand($sql)->bindValue(':time',$time)->execute();
            unset($sql);
        }
        $today = strtotime($time.' 00:00:00');
        
        //Lookup the list uc_members new users
        $sql="SELECT count(*) cnt FROM {{members}} WHERE regdate>'$today' and is_from=3";
        $cnt = Yii::app()->db_user->createCommand($sql)->queryRow();
        //update new_user
        if($row['new_user'] != $cnt['cnt']){
            $sql_up="UPDATE {{cnt_date_list}} SET new_user=:new_user WHERE date_time=:time";
            Yii::app()->db->createCommand($sql_up)->bindValue(':new_user',$cnt['cnt'])->bindValue(':time',$time)->execute();
            unset($sql,$cnt,$sql_up,$today);
        }
        
        //Lookup the number of broadcast pt_cnt_playerinfo list
        $sql="SELECT uid FROM {{cnt_playerinfo}} WHERE date_time='$time'";
        $cnt = Yii::app()->db->createCommand($sql)->queryAll();
        
        //update play_cnt
        if($row['play_cnt'] != count($cnt)){
            $sql_up="UPDATE {{cnt_date_list}} SET play_cnt=:play_cnt WHERE date_time=:time";
            Yii::app()->db->createCommand($sql_up)->bindValue(':play_cnt',count($cnt))->bindValue(':time',$time)->execute();
            unset($play_sql,$sql_up);
        }
        
        //Lookup the list pt_cnt_playerinfo active users
        $actives=array();
        foreach($cnt as $key => $val){
            array_push($actives,$val['uid']);
        }
        $active_users=array_unique($actives);
        unset($cnt,$actives);
        
        //update active_user
        if($row['active_user'] != count($active_users)){
            $sql_up="UPDATE {{cnt_date_list}} SET active_user=:active_user WHERE date_time=:time";
            Yii::app()->db->createCommand($sql_up)->bindValue(':active_user',count($active_users))->bindValue(':time',$time)->execute();
            unset($sql_up);
            //update pt_cnt_date_active uid
            $uids=implode(',',$active_users);
            unset($active_users);
            $sql="UPDATE {{cnt_date_active}} SET uids=:uids WHERE date_time=:time";
            Yii::app()->db->createCommand($sql)->bindValue(':uids',$uids)->bindValue(':time',$time)->execute();
            unset($uids,$sql,$time);
        }
    }
    
}
?>