<?php
class AreaCount{
    public function Area(){
            $this->_setAreaData();
       
    }
    private function _setAreaData(){
        //Lookup pt_cnt_playerinfo list if there is new data
        $sql="SELECT id,uid,place_id FROM {{cnt_playerinfo}} WHERE flg=0";
        $cmd = Yii::app()->db->createCommand($sql)->queryRow();
        unset($sql);
        
        //Update play times
        if($cmd){
            $sql="SELECT active_user,play_cnt FROM {{cnt_area_list}} WHERE area_id=:area_id";
            $result = Yii::app()->db->createCommand($sql)->bindValue(':area_id',$cmd['place_id'])->queryRow();
            unset($sql);
            if($result){
                $count=$result['play_cnt']+1;
                $upd_sql="UPDATE {{cnt_area_list}} SET play_cnt=:play_cnt WHERE area_id=:area_id";
                Yii::app()->db->createCommand($upd_sql)->bindValue(':play_cnt',$count)->bindValue(':area_id',$cmd['place_id'])->execute();
                unset($upd_sql,$count);
                $sel_sql="SELECT uids FROM {{cnt_area_actives}} WHERE area_id=:area_id";
                $ids = Yii::app()->db->createCommand($sel_sql)->bindValue(':area_id',$cmd['place_id'])->queryRow();
                if($ids){
                    $uids_arr=explode(',',$ids['uids']);
                    unset($sel_sql,$ids);
                    if(!in_array($cmd['uid'],$uids_arr)){
                        array_push($uids_arr,$cmd['uid']);
                        $new_str=implode(',', $uids_arr);
                        unset($uids_arr);
                        $sql="UPDATE {{cnt_area_actives}} SET uids=:uids WHERE area_id=:area_id";
                        Yii::app()->db->createCommand($sql)->bindValue(':uids',$new_str)->bindValue(':area_id',$cmd['place_id'])->execute();
                        unset($new_str,$sql);
                        //Update active_user
                        $count=$result['active_user']+1;
                        $sql="UPDATE {{cnt_area_list}} SET active_user=:active_user WHERE area_id=:area_id";
                        Yii::app()->db->createCommand($sql)->bindValue(':active_user',$count)->bindValue(':area_id',$cmd['place_id'])->execute();
                        unset($count,$sql);
                    }
                }else{
                    $active_sql="INSERT INTO {{cnt_area_actives}} SET area_id=:area_id,uids=:uids";
                    Yii::app()->db->createCommand($active_sql)->bindValue(':area_id',$cmd['place_id'])->bindValue(':uids',$cmd['uid'])->execute();
                    unset($active_sql);
                }
                unset($result);
            }else{
                $add_sql="INSERT INTO {{cnt_area_list}} SET area_id=:area_id,play_cnt=1,active_user=1";
                Yii::app()->db->createCommand($add_sql)->bindValue(':area_id',$cmd['place_id'])->execute();
                unset($add_sql);
                $active_sql="INSERT INTO {{cnt_area_actives}} SET area_id=:area_id,uids=:uids";
                Yii::app()->db->createCommand($active_sql)->bindValue(':area_id',$cmd['place_id'])->bindValue(':uids',$cmd['uid'])->execute();
                unset($active_sql);
            }
            $sql="UPDATE {{cnt_playerinfo}} SET flg=1 WHERE id=:id";
            Yii::app()->db->createCommand($sql)->bindValue(':id',$cmd['id'])->execute();
            unset($sql);
        }
        unset($cmd);
        
        //Lookup the list uc_members new users
        $day = strtotime('2012-05-01 00:00:00');
        //$day = strtotime(date('Y-m-d 00:00:00'));
        $sql="SELECT uid,regip FROM {{members}} WHERE regdate>'$day' AND flg=0 AND is_from in(3,4) ";
        $result = Yii::app()->db_user->createCommand($sql)->queryRow();
        unset($sql,$day);
        if($result){
            Yii::import("ext.ip.Convert");
            $convert = new Convert();
            $ipdatafile="../protected/extensions/ip/tinyipdata.dat";
            $return = $convert->convert_ip_full($result['regip'],$ipdatafile);
            $result['regip'] = iconv('GBK', 'UTF-8', $return);
            unset($convert,$ipdatafile,$return);
            $sql="SELECT id FROM {{cnt_area}} WHERE area_name=:name";
            $cmd = Yii::app()->db->createCommand($sql)->bindValue(':name',$result['regip'])->queryRow();
            if($cmd){
                $result['area_id']=$cmd['id'];
                unset($sql,$cmd);
                $sel_sql="SELECT new_user FROM {{cnt_area_list}} WHERE area_id=:area_id";
                $res = Yii::app()->db->createCommand($sel_sql)->bindValue(':area_id',$result['area_id'])->queryRow();
                if($res){
                    $count=$res['new_user']+1;
                    $upd_sql="UPDATE {{cnt_area_list}} SET new_user=:new_user WHERE area_id=:area_id";
                    Yii::app()->db->createCommand($upd_sql)->bindValue(':new_user',$count)->bindValue(':area_id',$result['area_id'])->execute();
                    unset($count,$upd_sql);
                    $sel_sql="SELECT uids FROM {{cnt_area_newusers}} WHERE area_id=:area_id";
                    $ids = Yii::app()->db->createCommand($sel_sql)->bindValue(':area_id',$result['area_id'])->queryRow();
                    if($ids){
                        $new_str=$ids['uids'].','.$result['uid'];
                        $upd_sql="UPDATE {{cnt_area_newusers}} SET uids=:uids WHERE area_id=:area_id";
                        Yii::app()->db->createCommand($upd_sql)->bindValue(':uids',$new_str)->bindValue(':area_id',$result['area_id'])->execute();
                        unset($new_str,$upd_sql);
                    }else{
                        $newusers_sql="INSERT INTO {{cnt_area_newusers}} SET area_id=:area_id,uids=:uids";
                        Yii::app()->db->createCommand($newusers_sql)->bindValue(':area_id',$result['area_id'])->bindValue(':uids',$result['uid'])->execute();
                        unset($newusers_sql);
                    }
                }else{
                    $add_sql="INSERT INTO {{cnt_area_list}} SET area_id=:area_id,new_user=1";
                    Yii::app()->db->createCommand($add_sql)->bindValue(':area_id',$result['area_id'])->execute();
                    unset($add_sql);
                    $newusers_sql="INSERT INTO {{cnt_area_newusers}} SET area_id=:area_id,uids=:uids";
                    Yii::app()->db->createCommand($newusers_sql)->bindValue(':area_id',$result['area_id'])->bindValue(':uids',$result['uid'])->execute();
                    unset($newusers_sql);
                }
            }
            $sql="UPDATE {{members}} SET flg=1 WHERE uid=:uid";
            Yii::app()->db_user->createCommand($sql)->bindValue(':uid',$result['uid'])->execute();
            unset($sql,$result);
        }
    }
}
?>