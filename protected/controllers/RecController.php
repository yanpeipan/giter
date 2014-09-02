<?php
class RecController extends Controller{
    public $layout=false;
    
    public function filters()
    {
        // return the filter configuration for this controller, e.g.:
        return array(
            'accessControl',
        );
    }

    public function accessRules()
    {
        return array(
            array(
            'deny',
                'actions'=>array(
                    'index', 
                    'ajaxorder',
                    'RecDelete',
                    'addRec',
                    'Search',
                    'InsertRec',
                    'Ajax_rec',
                ),
            'users'=>array('?'),
            ),
        );
    }
    
    //推荐信息
    public function actionIndex(){
    	$data = array();
        //$arr = Yii::app()->curl->run('https://gw.16tree.com/api',false,array('oauth_token'=>'a1911a950c2ea7e3b1fb0696a60daec6','method'=>'api.puti.topicvideos','version'=>'1.1','cate'=>''));
        //剧集
        $data['rectl'] = $this->getApiData(3);
        //电影
        $data['recmovie'] = $this->getApiData(2); 
        //综艺
        $data['reczy'] = $this->getApiData(6); 
        //动漫
        $data['recdm'] = $this->getApiData(4); 
        //纪录片
        $data['recnews'] = $this->getApiData(5);
        //重磅
        $data['recheavy'] = $this->getHeavyRec();
        //热播
        $data['recnew'] = $this->getNewRec();
        //今日更新
        $data['rechot'] = array();
        $data['rechot'] = $this->getApiData(0,3);
        //var_dump($data['recheavy']);die;
        //var_dump($data['rechot']);die;
        $this->render('tv_tj',array('data'=>$data));
        
    }
    
    //api 获取
    public function getApiData($cate=0,$topic_id=0){
        $arr = Yii::app()->curl->run('https://gw.16tree.com/api',false,array('oauth_token'=>'a1911a950c2ea7e3b1fb0696a60daec6','method'=>'api.puti.topicvideos','version'=>'1.1','cate'=>$cate,'topic_id'=>$topic_id));
        $data = json_decode($arr,true);
        return $data;
    }
    
    //重磅推荐
    public function getHeavyRec(){
        $sql_type = "SELECT * FROM {{recommend}} WHERE topic_id=:topic_id ORDER BY `order` ASC";
        $cmd_type = Yii::app()->db->createCommand($sql_type);
        $cmd_type->bindValue(':topic_id',1);
        $rows = $cmd_type->queryAll();
        if($rows)foreach($rows as &$row){
            $list = $this->getVideoInfo($row['vid']);
            $row['name'] = $list['name'];
            $row['imgurl'] = Cover::getVideoImg($row['imgurl'],385,234); 
        }
        return $rows;
    }
    
    public function getNewRec(){
        $sql_type = "SELECT * FROM {{recommend}} WHERE topic_id=:topic_id ORDER BY `order` ASC LIMIT 20";
        $cmd_type = Yii::app()->db->createCommand($sql_type);
        $cmd_type->bindValue(':topic_id',2);
        $rows = $cmd_type->queryAll();
        if($rows)foreach($rows as &$row){
            $list = $this->getVideoInfo($row['vid']);
            $row['name'] = $list['name'];
            $row['pic'] = Cover::getVideoImg($list['pic']); 
        }
        return $rows;
    }
    
    //视频信息
    public function getVideoInfo($id){
        $sql = "SELECT * FROM {{v_list}} WHERE id = :id";
        $model = Yii::app()->db-> createCommand($sql)->bindValue(':id',$id)->queryRow();
        if($model){
            return $model;
        }
    }
    
     public function actionAjaxorder(){
       $id = isset($_GET['id'])?$_GET['id']:0;
       $topic_id = isset($_GET['topic_id'])?$_GET['topic_id']:0;
       $order = isset($_GET['orders'])?$_GET['orders']:0;
       $row_rec1 = $this->_recRow($id,$topic_id);
       if((int)$order<1){
           return false;
       }
       if((int)$order>(int)$row_rec1["order"]){
           $sql_rec = "SELECT id,`order` FROM {{recommend}} WHERE topic_id=:topic_id AND id!=:id AND `order`>:old_order AND `order`<=:order";
       }else{
           $sql_rec = "SELECT id,`order` FROM {{recommend}} WHERE topic_id=:topic_id AND id!=:id AND `order`>=:order AND `order`<:old_order";
       }
       $cmd_rec = Yii::app()->db->createCommand($sql_rec);
       $cmd_rec->bindValue(':topic_id',$topic_id);
       $cmd_rec->bindValue(':id',$id);
       $cmd_rec->bindValue(':old_order',$row_rec1["order"]);
       $cmd_rec->bindValue(':order',$order);
       $row_rec =$cmd_rec->queryAll();  
       
       
       $sql = "UPDATE {{recommend}} SET `order`=:order WHERE id=:id";
       $command = Yii::app()->db->createCommand($sql);
       $command->bindValues(array(':order'=>$order,':id'=>$id));
       $result = $command->execute(); 
       
       if($result!=0){
           if($row_rec)foreach($row_rec as $row){
               $sql = "UPDATE {{recommend}} SET `order`=:order WHERE id=:id";
               $command = Yii::app()->db->createCommand($sql);
               if((int)$order>=(int)$row_rec1["order"]){
                    $command->bindValues(array(':order'=>(int)$row['order']-1,':id'=>$row['id']));  
               }else{
                   $command->bindValues(array(':order'=>(int)$row['order']+1,':id'=>$row['id'])); 
               }
               
               $command->execute(); 
           }
       }
       echo $result;
    }
     
     public function _recRow($id,$topic_id){
       $sql_rec1 = "SELECT id,`order` FROM {{recommend}} WHERE topic_id=:topic_id AND id=:id";
       $cmd_rec1 = Yii::app()->db->createCommand($sql_rec1);
       $cmd_rec1->bindValue(':id',$id);
       $cmd_rec1->bindValue(':topic_id',$topic_id);
       $row_rec1 =$cmd_rec1->queryRow();
       if($row_rec1){
           return $row_rec1;
       }
     }
     
     /**
      * 删除重磅下电影
      * @param id
      */
     public function actionRecDelete($id,$vid)
     {
         $id = isset($_GET['id'])?$_GET['id']:0;
         $vid = isset($_GET['vid'])?$_GET['vid']:0;
         //删除recommend表中数据
         $sql = "DELETE FROM {{recommend}} WHERE id=:id";
         $cmd = Yii::app()->db->createCommand($sql);
         $cmd->bindValue(':id', $id);
         $result = $cmd->execute();
         
         //更新video表
         $sql_video = "UPDATE {{v_list}} SET topic_id=0 WHERE id=:id"; 
         $cmd_video = Yii::app()->db->createCommand($sql_video);
         $cmd_video->bindValue(':id', $vid);
         $cmd_video->execute();
         @$this->EditLog(Yii::app()->user->name,'RecDelete','UPDATE','v_list',$vid);
         echo $result;
     }
     
     public function actionaddRec(){
         $topic_id = isset($_REQUEST["topic_id"])?$_REQUEST["topic_id"]:0;
         $this->render('rec_add',array('topic_id'=>$topic_id));
     }
     
     public function actionSearch(){
        $name = isset($_REQUEST['name'])?trim($_REQUEST['name']):'';
        $sql_type = "SELECT id,name,pic,category FROM {{v_list}} WHERE is_show=0 AND name LIKE '%".$name."%'";
        $cmd_type = Yii::app()->db->createCommand($sql_type);
        $rows = $cmd_type->queryAll();
        $str = '<ul>';
        if($rows)foreach($rows as $row){
             $str .= '<li><a href="/show/VideoResult/id/'.$row['id'].'"><img src="'.Cover::getVideoImg($row["pic"]).'" /></a>
                      <p><a href="#" title="'.$row["name"].'">'.mb_substr($row["name"],0,8,"UTF-8").'</a></p>
                      <p><a href="javascript:;" onclick="addrec('.$row["id"].','.$row['category'].')">ID:'.$row['id'].'</a>(点击选择)</p>
                      </li>';
        }
        $str .= '</ul>';
        echo $str;
     }
     
     public function actionInsertRec(){
         $vid = isset($_REQUEST['id'])?$_REQUEST['id']:0;
         $topic_id = isset($_REQUEST['topic_id'])?$_REQUEST['topic_id']:0;
         $cate = isset($_REQUEST['cate'])?$_REQUEST['cate']:0;
         $img = isset($_REQUEST['img'])?$_REQUEST['img']:'';
         if($topic_id==1){
                $cnt = Tvs_Act::getInstance()->_countRec($topic_id);
                if($cnt>=7){
                    $this->redirect("/rec/addRec/topic_id/1/".$topic_id);
                    return false;
                }
         }
         if($vid!=0){
           $rec = $this->getRecCount($vid);
           if($rec==0){
               $row_rec=$this->selectRec(1); 
               $yun_img = '';
               if($img!=''){
                   $yun_img = Tvs_Act::_upyunimg($img); 
               } 
               $sql = "INSERT INTO {{recommend}} SET vid=:vid,time=:time,category=:cate,imgurl=:img,yunimg=:yunimg,topic_id=:topic_id,`order`=1";
               $command = Yii::app()->db->createCommand($sql);
               $command->bindValues(array(':vid'=>$vid,':time'=>time(),':cate'=>$cate,':img'=>$img,':yunimg'=>$yun_img,':topic_id'=>$topic_id));
               $result = $command->execute();
               if($result!=0){
                   $this->updateVideo($vid);
                   $min_order = Tvs_Act::getInstance()->_catRecMin($topic_id);
                   if($row_rec&&$min_order==1)foreach($row_rec as $row){
                       $this->updateRec($row['id'],$row['order']);
                   }
               }
               echo $result;
          }
        }
     }
     
     public function getRecCount($id){
        $sql = "SELECT COUNT(*) as cnt FROM {{recommend}} WHERE vid = :id";
        $row = Yii::app()->db-> createCommand($sql)->bindValue(':id',$id)->queryRow();
        if($row){
            return $row['cnt'];
        }
    }
     
     
     public function selectRec($order,$id=0){
        $sql_rec = "SELECT id,`order` FROM {{recommend}} WHERE topic_id=1 AND id!=:id AND `order`>=:order";
        $cmd_rec = Yii::app()->db->createCommand($sql_rec);
        $cmd_rec = $cmd_rec->bindValue(':order',$order);
        $cmd_rec = $cmd_rec->bindValue(':id',$id);
        $row_rec =$cmd_rec->queryAll();
        if($row_rec){
            return $row_rec; 
        }
     }
     
     public function updateRec($id,$order){
           $sql = "UPDATE {{recommend}} SET `order`=:order WHERE id=:id";
           $command = Yii::app()->db->createCommand($sql);
           $command->bindValues(array(':order'=>(int)$order+1,':id'=>$id));
           $command->execute(); 
     }
     
     public function updateVideo($id){
         $sql_video = "UPDATE {{v_list}} SET topic_id=1 WHERE id=:id";
         $cmd_video = Yii::app()->db->createCommand($sql_video);
         $cmd_video->bindValue(':id',$id);
         $cmd_video->execute();
         @$this->EditLog(Yii::app()->user->name,'updateVideo','UPDATE','v_list',$vid);
     }
     
     public function actionAjax_rec(){
         $topic_id = isset($_REQUEST['topic_id'])?$_REQUEST['topic_id']:0;
         $cnt = Tvs_Act::getInstance()->_countRec($topic_id);
         echo $cnt;
     }
}