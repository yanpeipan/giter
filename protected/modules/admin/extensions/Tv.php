<?php
class Tv{
    static $_instance = null;
    public static function getInstance(){
        if(null == self::$_instance){
            $className = get_called_class();
            self::$_instance = new $className;
        }
        return self::$_instance;
    }
    
    public function getMovieData($category,$page,$pageSize){
        $params = array();
        $condition = 1;
        if(!empty($category)){
            $condition .= ' AND category=:category';
            $params[':category'] = $category;
        }
        $criteria = new CDbCriteria(array(
            'select' => '*',
            'condition' => $condition,
            'order' => 'id DESC',
            'params' => $params,
        ));
        $pagination = new CPagination(NewVideo::model()->count($criteria));
        $pagination->pageSize=$pageSize;
        $pagination->applyLimit($criteria);  
        $data = NewVideo::model()->findall($criteria);
        $params = array(
            'page' => $page,
            'pager' => $pagination,
            'pages' => ceil(NewVideo::model()->count($criteria)/$pageSize),
        );
    
      $result['data'] = $data;
      $result['params'] = $params;
      return $result;   
    }   
    
    //获取用户信息
    public function Load_admin_model($user_id)
    {
        $model = Admin::model()->findByPk((int)$user_id);
        if($model==NULL)
        {
            throw new CHttpException(404, '页面不存在');
        }
        return $model;
    }
    
    //获取分集信息
    public function get_tv_info($id){
        $sql = "SELECT * FROM {{v_tv}} WHERE id = :id";
        $model = Yii::app()->db-> createCommand($sql)->bindValue(':id',$id)->queryRow();
        if($model===null){
            throw new CHttpException(404,'页面不存在');     
        }
        return $model;
    }
   
   //获取视频分类
   public function get_category(){
        //视频分类
        $sql_cat  = "SELECT * FROM {{category}} ORDER BY id DESC";
        $cmd_cat  = Yii::app()->db->createCommand($sql_cat);
        $rows_cat = $cmd_cat->queryAll();
        return $rows_cat;
    }
   
   //获取分类对应的类型
   public function _type($mark){
        //类型(涉及分类)
        $sql_type = "SELECT * FROM {{type}} WHERE mark=:mark OR mark=1 ORDER BY id DESC";
        $cmd_type = Yii::app()->db->createCommand($sql_type);
        $cmd_type->bindValue(':mark', $mark);
        $rows_type= $cmd_type->queryAll();
        foreach($rows_type as $val)
        {
            $type[$val['id']] = $val['type_name'];
        }
        return $type;
    }
   
    //获取分类对应的地区
    public function _area($mark){
        $sql_area    = "SELECT * FROM {{area}} WHERE mark=:mark OR mark=1 ORDER BY id DESC";
        $cmd_area  = Yii::app()->db->createCommand($sql_area);
        $cmd_area->bindValue(":mark",$mark); 
        $rows_area = $cmd_area->queryAll();
        foreach($rows_area as $val)
        {
            $area[$val['id']] = $val['area_name'];
        }
        return $area;     
    }
    
    public function _InsertVideo(){
        //var_dump($_POST);die;
        $model = new NewVideo();
        $model->name = isset($_POST['v_name'])?$_POST['v_name']:'';
        if($model->name==''){
            return false;
        }
        $model->source = isset($_POST['v_source'])?$_POST['v_source']:'';
        $model->category = isset($_POST['v_category'])?$_POST['v_category']:'';
        $model->type = isset($_POST['select_box_hdn'])?','.$_POST['select_box_hdn'].',':'';
        $model->area = isset($_POST['select_box_area_hdn'])?','.$_POST['select_box_area_hdn'].',':'';
        $model->tv_application_time = isset($_POST['v_tv_time'])?$_POST['v_tv_time']:'';
        $model->main_actors = isset($_POST['v_main_actors'])&&($_POST['v_main_actors']!='')?$_POST['v_main_actors']:'未知';
        $model->director = isset($_POST['v_director'])&&($_POST['v_director']!='')?$_POST['v_director']:'未知';
        $model->free = isset($_POST['v_free'])?$_POST['v_free']:0;
        $model->genuine = isset($_POST['v_genuine'])?$_POST['v_genuine']:1;
        $model->resolution = isset($_POST['v_resolution'])?$_POST['v_resolution']:0;
        $model->hd_order = 0;
        if(isset($_POST['v_source'])&&$_POST['v_source']!=''){
                $data[$_POST['v_source']]= $_POST['v_resolution'];
                $model->source_resolution = json_encode($data);
        }
        $model->score = isset($_POST['v_score'])?$_POST['v_score']:0;
        $model->opposition = isset($_POST['v_opposition'])?$_POST['v_opposition']:0;
        $model->support = isset($_POST['v_support'])?$_POST['v_support']:0;
        $model->actors = isset($_POST['v_actors'])?$_POST['v_actors']:'';
        $model->time_length = isset($_POST['v_time_length'])?$_POST['v_time_length']:'';
        $model->desc = isset($_POST['v_desc'])?$_POST['v_desc']:'';
        $model->alias = isset($_POST['v_alias'])?$_POST['v_alias']:'';
        $model->pic = isset($_POST['v_pic'])?$_POST['v_pic']:$model->pic;
        if($model->pic!=''){
            $model->yun_img = Tvs_Act::_upyunimg($model->pic);
        }
        $model->year = isset($_POST['v_tv_time'])&&$_POST['v_tv_time']!="" ?substr($_REQUEST['v_tv_time'],0,4):date('Y',time());
        $model->user_id = Yii::app()->user->name;
        $model->letter = isset($_REQUEST['v_letter'])?$_REQUEST['v_letter']:'';
        $model->status = isset($_REQUEST['v_status'])?$_REQUEST['v_status']:0;
        $model->age = isset($_REQUEST['v_age'])?(int)$_REQUEST['v_age']:0;
        if($model->letter==''){
            $py = new Py();
            $letter = $py->Pinyin($_POST['v_name'],'utf8');
            $model->letter = $letter;
        }
        $model->time = time();
		$model->update_time = time();
        $model->is_show = 1;
		if(isset($_REQUEST['v_source'])&&$_REQUEST['vurl']){
        	$data1[$_REQUEST['v_source']]=$_REQUEST['vurl'];
            $model->update_url = json_encode($data1);
        }
        if($model->validate()){
            if($model->save()){
                @$this->ActionLog('ADD',$model->id,Yii::app()->user->name);
                return $model->id;
            }
        }
    }

    public function _InsertTv($id,$is_add=0){
        if(isset($_POST['tv_url'])){//var_dump($_POST['Tv']);die;
            for($i=0;$i<count($_POST["tv_url"]);$i++){
                $model_tv = new TvNew();
                $model_tv->tv_parent_id = $id;
                $model_tv->tv_name      = isset($_POST['tv_name'][$i])?$_POST['tv_name'][$i]:'';
                $model_tv->tv_id        = isset($_POST['tv_id'][$i])?$_POST['tv_id'][$i]:'';
                $model_tv->tv_url       = isset($_POST['tv_url'][$i])?trim($_POST['tv_url'][$i]):'';
                $model_tv->source       = isset($_POST['tv_source'])?$_POST['tv_source']:'';
                $model_tv->is_del       = 1;
                $model_tv->time         = time();
				$model_tv->update_time  = time();
                $model_tv->user_id = Yii::app()->user->name;
                if($model_tv->validate())
                {
                    if($model_tv->save())
                    {
                    	$re_1 = Tvs_Act::getInstance()->edit_record($model_tv->id,$type='ADD',$table_name='v_tv',$uname=Yii::app()->user->name);	
                        Tvs_Act::record_admin($model_tv->id,1);
                        if($is_add!=0){
                            $this->add_source($id,$_POST['tv_source']);
                        }
                        if($i==(count($_POST["tv_url"])-1)){
                            $data = Tvs_Act::source_resolution($id);
                            if($data!=''){
                                $data[$_POST['tv_source']] = $_POST['v_resolution'];
                                $r = max($data);
                                $sr = json_encode($data);
                                Tvs_Act::getInstance()->update_v($id,$r,$sr);
								
								$re1 = Tvs_Act::getInstance()->catRow('v_list',array('id'=>$id),'is_show');
								//进入高清影视频道
								if($re1['is_show']==0&&$r!=0){
									$re = Tvs_Act::getInstance()->put_rbjchannel($id,116);
								}
								if($re1['is_show']==3){
									$sql = "UPDATE {{v_list}} SET is_show=1 WHERE id=:id";
                               		$command = Yii::app()->db->createCommand($sql)->bindValue(':id',$id)->execute();
								}
                            }
							$data1 = Tvs_Act::get_updateurl_json($id);
							if($data1!=''){
								$data1[$_POST['tv_source']] = $_POST['vurl'];
								$data1 = json_encode($data1);
								Tvs_Act::getInstance()->update_source_url($id,$data1);
							}
                            return 1;die;
                        }
                    }else{
                        return 0;
                        Yii::app()->end();
                    }
                }
            }
        }
    }
    
    //判断source是否已经存在，如无则添加
    public function add_source($id,$source){
       $sql = "SELECT source FROM {{v_list}} WHERE id=:id";
            $cmd = Yii::app()->db->createCommand($sql);
            $cmd->bindValue(':id', $id);
            $row = $cmd->queryRow();
            if($row)
            {
                $tv_source = $row['source'];
            } 
       if(strpos($tv_source,$source)===false){
               $sql = "UPDATE {{v_list}} SET source=:source WHERE id=:id";
               $command = Yii::app()->db->createCommand($sql);
               if($tv_source!=''){
                    $v_source = $tv_source.','.$source;
               }else{
                    $v_source = $source;
               }
               $command->bindValues(array(':source'=>$v_source,':id'=>$id));
               $command->execute();
               $tv_source = $v_source;
               @$this->ActionLog('UPDATE',$id,Yii::app()->user->name);
       }  
    }

}