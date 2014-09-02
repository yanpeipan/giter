<?php
/**
 * 按省和国家统计用户
 * author:guochao
 * date:2012-7-12
*/
Yii::import("ext.ip.Convert");
Class AreaStatExt{
    public function userStat(){
        $date=strtotime(date('Y-m-d 00:00:00'));
        $sql="SELECT ip FROM {{cnt_equipment}} WHERE ctime>=$date and flag=0";
        $result=Yii::app()->db->createCommand($sql)->queryAll();
        if(!empty($result)){
            foreach($result as $item){
                if(!empty($item['ip'])){
                    $areaname=$this->getAreaNameByIp($item['ip']);                  
                    $areaid=$this->getProvinceIdByName($areaname);
                    if($areaid){
                        $this->updateProvinceUser($areaid);
                    }else{
                        $areaid=$this->getCountryIdByName($areaname);
                        if($areaid){$this->updateCountryUser($areaid);}
                    }
                }
            }
            $this->updateFlag($date);
        }
    }
    //通过地名获取省id
    public function getProvinceIdByName($name){
        $areaname=mb_substr($name, 0,6);
        $sql="SELECT id FROM {{area_province}} WHERE area_name LIKE '{$areaname}%'";
        $result=Yii::app()->db->createCommand($sql)->queryRow();
        if(!empty($result)){return $result['id'];}
        return '';
    }
    //通过地名获取国家id
    public function getCountryIdByName($name){
        $areaname=mb_substr($name, 0,6);
        $sql="SELECT id FROM {{area_country}} WHERE area_name LIKE '{$areaname}%'";
        $result=Yii::app()->db->createCommand($sql)->queryRow();
        if(!empty($result)){return $result['id'];}
        else{
            $sql="INSERT INTO {{area_country}} SET area_name='$name'";
            Yii::app()->db->createCommand($sql)->execute();
            return Yii::app()->db->getLastInsertID();
        }
    }
    //更新省用户总数
    public function updateProvinceUser($areaid){
        $sql="SELECT user_cnt FROM {{area_province_stat}} WHERE area_id=$areaid";
        $result=Yii::app()->db->createCommand($sql)->queryRow();
        if(empty($result)){
            $sql="INSERT INTO {{area_province_stat}} SET area_id=$areaid,user_cnt=1";
        }else{
            $sql="UPDATE {{area_province_stat}} SET user_cnt={$result['user_cnt']}+1 WHERE area_id=$areaid";
        }
        Yii::app()->db->createCommand($sql)->execute();
    }
    //更新国家用户总数
    public function updateCountryUser($areaid){
        $sql="SELECT user_cnt FROM {{area_country_stat}} WHERE area_id=$areaid";
        $result=Yii::app()->db->createCommand($sql)->queryRow();
        if(empty($result)){
            $sql="INSERT INTO {{area_country_stat}} SET area_id=$areaid,user_cnt=1";
        }else{
            $sql="UPDATE {{area_country_stat}} SET user_cnt={$result['user_cnt']}+1 WHERE area_id=$areaid";
        }
        Yii::app()->db->createCommand($sql)->execute();
    }
    //更新统计状态 flag= 0：新增，1：已统计过
    public function updateFlag($date){
        $sql="UPDATE {{cnt_equipment}} SET flag=1 WHERE ctime>=$date";
        Yii::app()->db->createCommand($sql)->execute();
    }
    //通过IP获取地名
    public function getAreaNameByIp($ip){     
        
        $convert = new Convert();
        $ipdatafile=Yii::app()->basePath."/extensions/ip/tinyipdata.dat";
        $area_name = $convert->convert_ip_full($ip,$ipdatafile);
        /*if(stripos($area_name, 'Invalid')!== false){
            $this->CreateFile('/tmp/ipdata.txt',$ip);
        }*/
       //$name=iconv('GBK', 'UTF-8', $area_name);
       //$log='IP：'.$ip.'名称：'.$name;
        //$this->CreateFile('/tmp/ipdata.txt', $log);
        return iconv('GBK', 'UTF-8', $area_name);
    }
    
    public function CreateFile($fileName , $str , $type='a'){
    //打开文件
    $fp = @fopen($fileName , $type);
    if(!$fp) exit ;
    // 进行排它型锁定
    if (flock($fp, LOCK_EX)){
        fwrite($fp , $str."\n");
        // 释放锁定
        flock($fp, LOCK_UN);
    }
    flock($fp, LOCK_UN); // 释放锁定
}
    
}
