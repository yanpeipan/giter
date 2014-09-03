<?php

class Video{
    private $_errors;
    
    public function __construct(){
        $this->_init();
    }
    public function getErrorNo(){
        return $this->_errors;
    }
    /*
     * 添加视频
     */
    public function CheckVideo($data,$data_list){
        if($data && $data_list){
            $cate = $this->_checkCate($data['category']);//检测分类
            $area = $this->_checkArea($data['category'],$data['area']);//检测地区
            $type = $this->_checkType($data['category'],$data['type']);//检测类型
            if($cate && $area && $type){
                $bool = $this->_checkUrl($data['soku_url']);//检测是否有该数据
                if($bool){
                    $result = $this->_addVideo($data,$data_list);//添加数据
                    if($result){
                        $this->_setErrorNo(1);
                        return true;
                    }else{
                        $this->_setErrorNo(1004);
                        return false;
                    }
                }else{
                    //'数据已经存在';
                    $this->_setErrorNo(1001);
                    return false;
                }
            }else{
                //'地区或类型或分类有问题';
                $this->_setErrorNo(1002);
                return false;
            }
        }else{
            //return false;
            //return 'data或data_liset有问题';
            $this->_setErrorNo(1003);
            return false;
        }
    }
//**************************************************
    private function _init(){
        $this->_init_errors();
    }
    
    private function _init_errors(){
        $this->_errors=array(
            '1'   =>'true',
            '1001'=>'数据已经存在',
            '1002'=>'地区或类型或分类有问题',
            '1003'=>'data或data_liset有问题',
            '1004'=>'添加失败',
        );
    }
    
    private function _setErrorNo($error_no){
        $this->_errors = $error_no;
    }

    //检测类型
    private function _checkType($cate_id,$types){
        $type_id_arr = explode(',',$types);
        $type_id = substr($types,1,-1);
        foreach($type_id_arr as $key => $val){
            if(in_array($val, array(1))){
                return true;
            }
        }
        if(count($type_id_arr)>3){
            $sql = "SELECT * FROM {{type}} WHERE id in(:id) AND mark=:mark";
        }else{
            $sql = "SELECT * FROM {{type}} WHERE id=:id AND mark=:mark";
        }
        $cmd = Yii::app()->db->createCommand($sql);
        $cmd->bindValue(':id',$type_id)->bindValue(':mark',$cate_id);
        $result = $cmd->queryRow();
        if($result){
            return true;
        }else{
            return false;
        }
    }
    
    //检测该视频是否存在
    private function _checkUrl($url){
        $sql = "SELECT * FROM {{v_list}} WHERE soku_url=:url";
        $cmd = Yii::app()->db->createCommand($sql);
        $cmd->bindValue(':url',$url);
        $result = $cmd->queryRow();
        if($result){
            return false;
        }else{
            return true;
        }
    }
    
    //检测地区是否存在
    private function _checkArea($cate_id,$area_ids){
        $area_id_arr = explode(',',$area_ids);
        $area_id = substr($area_ids,1,-1);
        foreach($area_id_arr as $key => $val){
            if(in_array($val, array(1))){
                return true;
            }
        }
        if(count($area_id_arr)>3){
            //array_shift($area_id);
            //array_pop($area_id);
            $sql = "SELECT * FROM {{area}} WHERE id in(:id) AND mark=:mark";
        }else{
            $sql = "SELECT * FROM {{area}} WHERE id=:id AND mark=:mark";
        }
        $cmd = Yii::app()->db->createCommand($sql);
        $cmd->bindValue(':id',$area_id)->bindValue(':mark',$cate_id);
        $result = $cmd->queryRow();
        if($result){
            return true;
        }else{
            return false;
        }
    }
    
    //检测分类是否存在
    private function _checkCate($cate_id){
        $sql = "SELECT * FROM {{category}} WHERE id=:id";
        $cmd = Yii::app()->db->createCommand($sql);
        $cmd->bindValue(':id',$cate_id);
        $result = $cmd->queryRow();
        if($result){
            return true;
        }else{
            return false;
        }
    }
    
    //添加数据
    private function _addVideo($data,$data_list){
        $sql = "INSERT INTO {{v_list}} SET 
                                        name=:name,
                                        director=:director,
                                        main_actors=:main_actors,
                                        `desc`=:desc,
                                        pic=:pic,
                                        free=:free,
                                        area=:area,
                                        year=:year,
                                        tv_application_time=:tv_application_time,
                                        time=:time,
                                        category=:category,
                                        type=:type,
                                        play_count=:play_count,
                                        score=:score,
                                        source=:source,
                                        comment_count=:comment_count,
                                        letter=:letter,
                                        soku_url=:soku_url,
                                        user_id=:user_id,
                                        play_info=:play_info";
        $cmd = Yii::app()->db->createCommand($sql);
        $info = array(
                    ':name'=>$data['name'],
                    ':director'=>$data['director'],
                    ':main_actors'=>$data['main_actors'],
                    ':desc'=>$data['desc'],
                    ':pic'=>$data['pic'],
                    ':free'=>$data['free'],
                    ':area'=>$data['area'],
                    ':year'=>$data['year'],
                    ':tv_application_time'=>$data['tv_application_time'],
                    ':time'=>time(),
                    ':category'=>$data['category'],
                    ':type'=>$data['type'],
                    ':play_count'=>$data['play_count'],
                    ':score'=>$data['score'],
                    ':source'=>$data['source'],
                    ':comment_count'=>$data['comment_count'],
                    ':letter'=>$data['letter'],
                    ':soku_url'=>$data['soku_url'],
                    ':user_id'=>$data['user_id'],
                    ':play_info'=>$data['play_info']);
        $bool = $cmd->bindValues($info)->execute();
        $id = Yii::app()->db->getLastInsertID();
        $this->_addActionList(array($id),1);
        if($id && $bool){
            $sql_list ="INSERT INTO {{v_tv}} SET 
                                        tv_id=:tv_id,
                                        tv_name=:tv_name,
                                        tv_parent_id=:tv_parent_id,
                                        tv_url=:tv_url,
                                        user_id=:user_id,
                                        time=:time,
                                        source=:source";
            $result_list = Yii::app()->db->createCommand($sql_list);
            foreach($data_list as $key => $val){
                $info_list[] = array(
                    ':tv_id'=>$val['tv_id'],
                    ':tv_name'=>$val['tv_name'],
                    ':tv_parent_id'=>$id,
                    ':tv_url'=>$val['tv_url'],
                    ':user_id'=>$val['user_id'],
                    ':time'=>time(),
                    ':source'=>$val['source'],);
            }
            foreach($info_list as $key => $val){
                $bool_list[] = $result_list->bindValues($val)->execute();
                $list_ids[] = Yii::app()->db->getLastInsertID();
            }
            $this->_addActionList($list_ids,2);
            if($bool && $bool_list){
                return true;
            }else{
                return false;
            }
        }
    }
    
    private function _addActionList($ids,$type){
        if($type ==1){
            $table_name='v_list';
        }
        foreach($ids as $key => $val){
            @$this->ActionLog('ADD',$val,Yii::app()->user->name);
        }
    }
    
}