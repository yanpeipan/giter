<?php
class VdController extends Controller{
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
                    'TvSeries',
                    'Animation',
                    'Arts',
                    'Record',
                    'SpRule',
                    'AppendV',
                    'AddVideos',
                    'AddsVideo',
                    'AdminAddVideo',
                ),
            'users'=>array('?'),
            ),
        );
    }
    //电影
    public function actionIndex(){
        $page = isset($_REQUEST['page'])?$_REQUEST['page']:1;
        $pageSize = isset($_REQUEST['pageSize'])?$_REQUEST['pageSize']:50;
        $data = Tv::getInstance()->getMovieData(2,$page,$pageSize);
        $this->render('tv_dy',array('data'=>$data,'category'=>2));
    }
    
    //剧集
    public function actionTvSeries(){
        $page = isset($_REQUEST['page'])?$_REQUEST['page']:1;
        $pageSize = isset($_REQUEST['pageSize'])?$_REQUEST['pageSize']:50;
        $data = Tv::getInstance()->getMovieData(3,$page,$pageSize);
        $this->render('tv_jj',array('data'=>$data,'category'=>3));
    }
    
    //剧集
    public function actionAnimation(){
        $page = isset($_REQUEST['page'])?$_REQUEST['page']:1;
        $pageSize = isset($_REQUEST['pageSize'])?$_REQUEST['pageSize']:50;
        $data = Tv::getInstance()->getMovieData(4,$page,$pageSize);
        $this->render('tv_animation',array('data'=>$data,'category'=>4));
    }
    
     //综艺
    public function actionArts(){
        $page = isset($_REQUEST['page'])?$_REQUEST['page']:1;
        $pageSize = isset($_REQUEST['pageSize'])?$_REQUEST['pageSize']:50;
        $data = Tv::getInstance()->getMovieData(6,$page,$pageSize);
        $this->render('tv_arts',array('data'=>$data,'category'=>6));
    }

     //纪录片
    public function actionRecord(){
        $page = isset($_REQUEST['page'])?$_REQUEST['page']:1;
        $pageSize = isset($_REQUEST['pageSize'])?$_REQUEST['pageSize']:50;
        $data = Tv::getInstance()->getMovieData(5,$page,$pageSize);
        $this->render('tv_record',array('data'=>$data,'category'=>5));
    }
    
    //视频地址规则
    public function actionSpRule(){
        $this->render('tc_qs');
    }
    
    public function actionAppendV(){ 
        $cate = isset($_REQUEST['category'])?$_REQUEST['category']:0;
        $vid = isset($_REQUEST['id'])?$_REQUEST['id']:0;
        
        $cate1 = Tv::getInstance()->get_category();
        $type1 = Tv::getInstance()->_type($cate);
        $area1 = Tv::getInstance()->_area($cate);
        $this->render('tv_add',array('cate'=>$cate,'vid'=>$vid,'cate1'=>$cate1,'type1'=>$type1,'area1'=>$area1));
    }
    
    public function actionAddVideos(){
        $url = isset($_REQUEST['v_url'])?$_REQUEST['v_url']:'';
        $cate = isset($_REQUEST['cate'])?$_REQUEST['cate'] :0;
        //var_dump($cate,$url);
        Yii::import('application.extensions.VideoSites.*'); 
        //$url = 'http://www.letv.com/ptv/pplay/76895/1.html'; //电影
        $source = new SourceFrom();
        $data = $source->fromSource($url,$cate);
        $str = '';
        if($data){
          $data['score'] = $data['score']/2;
            if(strpos($data['score'],'.')){
                $score_arr = explode('.',$data['score']);
                if('0.'.$score_arr[1] > 0.3){
                    $data['score'] = $score_arr[0].'.5';
                }else{
                    $data['score'] = $score_arr[0];
                }
            }
          $str = $this->v_info($data);    
        }
        //var_dump($data);die;
        echo $str;
        
        
    }
    
    public function actionAddsVideo(){
        $url = isset($_REQUEST['v_url'])?$_REQUEST['v_url']:'';
        $cate = isset($_REQUEST['cate'])?$_REQUEST['cate']:0;
        $vid = isset($_REQUEST['vid'])?$_REQUEST['vid']:0;
        Yii::import('application.extensions.VideoSites.*'); 
        //$url = 'http://www.letv.com/ptv/pplay/76895/1.html'; //电影
        $source = new SourceFrom();
        $data = $source->fromSource($url,$cate);
        $str = '';
        if($data&&$vid!=0){
          $str = $this->tv_info($data,$vid);    
        }
        //var_dump($data);die;
        echo $str;
    }
    
    public function actionAdminAddVideo(){
        $url = isset($_REQUEST['v_url'])?$_REQUEST['v_url']:'';
        $cate = isset($_REQUEST['cate'])?$_REQUEST['cate'] :0;
        Yii::import('application.extensions.VideoSites.*'); 
        //$url = 'http://www.letv.com/ptv/pplay/76895/1.html'; //电影
        $source = new SourceFrom();
        $data = $source->fromSource($url,$cate);
		//var_dump($data);die;
        $py = new Py();
        $data["letter"] = $py->Pinyin($data["name"],'utf8');
        $data['score'] = $data['score']/2;
        //var_dump($data['score'],strpos('.', $data['score']));die;
        if(strpos($data['score'],'.')!==false){
            $score_arr = explode('.',$data['score']);
            //var_dump('0.'.$score_arr[1] > 5);die;
            if('0.'.$score_arr[1] > 0.3){
                $data['score'] = $score_arr[0].'.5';
            }else{
                $data['score'] = $score_arr[0];
            }
        }
        $str = '';
        if($data["list"])foreach($data["list"] as $row){
            $str .='<tr style="display:none;"><td width="18%" height="30" align="center"><input name="tv_url[]" value="'.$row["tv_url"].'"></td>
                         <td width="40%" align="left"><input name="tv_id[]" value="'.$row["tv_id"].'"></td>
                         <td width="18%" height="30" align="center"><input name="tv_name[]" value="'.$row["tv_name"].'"></td>
                         <td width="18%" height="30" align="center"><input name="tv_source" value="'.$data["source"].'"></td>
                    </tr>';
        }
        $data["content"] = $str;
        $data["count"] = count($data["list"]);
		ini_set('display_errors',1);
        echo json_encode($data);
        Yii::app()->end();
    }
    
    public function v_info($data){
        //var_dump($data);die;
        $cate = Tv::getInstance()->get_category();
        $type1 = Tv::getInstance()->_type($data['category']);
        $area = Tv::getInstance()->_area($data['category']);
        $str = '<form method="post" action="/show/AddTvs/" enctype="multipart/form-data">
                <table cellpadding="0" cellspacing="0" border="0" width="690px">
                <tr>
                <td width="18%" height="30" align="center"><strong><label>视频名称： </label></strong></td>
                <td width="40%" align="left"><input type="text" name="v_name" value="'.$data["name"].'" /><input type="button" onclick="check_name()" value="请检测名称"></td>
                <td width="18%" height="30" align="center"><label>来源</label>：</td>
                    <td width="35%"><input type="text" name="v_source" value="'.$data["source"].'"/></td>
                </tr>';
                
         $str .='<tr><td width="18%" height="30" align="center" ><strong><label>分类</label>：</strong></td>
                 <td width="40%" align="left"><select id="v_category" onchange="changes()" name="v_category">';
                       foreach($cate as $ca){
                           if($data['category']==(int)$ca['id']){
                                $str .='<option selected="selected" value="'.$ca["id"].'">'.$ca['category_name'].'</option>'; 
                           }else{
                                $str .='<option value="'.$ca["id"].'">'.$ca['category_name'].'</option>';
                           }
                        }
                    
       $str .='</select></td>
                <td width="18%" height="30" align="center"><label>上映时间</label>：</td>
                <td width="35%"><input type="text" name="v_tv_time" value="'.$data["year"].'"/></td></tr>';
                  
       $str .='<tr><td width="18%" height="30" align="center"><strong><label>地区</label>：(请选择地区)&nbsp;&nbsp;&nbsp;</strong></td><td id="select_box_area">';
                foreach ($area as $key => $val){
                    $str .='<span style="width:180px;">';                                
                    $str .='<input type="checkbox" name="select_box_area" onclick="s.changeSelected(this,event);" value="'.$key.'" onblur="s.hiddenList()"/><span>'.$val.'</span>'; 
                    $str .='</span>';
                }
                $str .= '</td><td width="18%" height="30" align="center"><label>类型</label>：(请选择类型)&nbsp;&nbsp;&nbsp;</td><td id="select_box">';
                   foreach ($type1 as $key => $val){
                       $str .= '<span style="width:180px;">';                             
                       $str .= '<input type="checkbox" name="select_box" onclick="s.changeSelected(this,event);" value="'.$key.'" onblur="s.hiddenList()"/><span>'.$val.'</span>'; 
                       $str .= '</span>';
                  }
                  $str .= '</td></tr>';
      $str .='<tr>   
      <td width="18%" height="30" align="center"><label>参考地区：</label></td><td width="40%" align="left">'.$data["area"].'</td>
      <td width="18%" height="30" align="center"><label>参考类型：</label></td><td width="40%" align="left">'.$data["type"].'</td></tr>';
         
      $str .='<tr>
                <td width="18%" height="30" align="center"><strong><label>主演</label>：</strong></td>
                <td width="40%" align="left"><input type="text" name="v_main_actors" value="'.$data["main_actors"].'"/></td>
                <td width="18%" height="30" align="center"><label>导演</label>：</td>
                <td width="35%"><input type="text" name="v_director" value="'.$data["director"].'"/></td>
                </tr>';
        $str .='<tr>
                <td width="18%" height="30" align="center"><strong>
                  <label>收费</label>:</strong></td><td width="40%" align="left">
                        <select id="v_free" name="v_free">';
                        foreach(Yii::app()->params['free'] as $k=>$v){
                            if($data["free"]==$k){
                                $str .='<option value="'.$k.'" selected="selected" >'.$v.'</option>';
                            }else{
                                $str .='<option value="'.$k.'"  >'.$v.'</option>';
                            }
                            
                        }       
                       
        $str .= '</select></td>
                 <td width="18%" height="30" align="center"><label>视频状态</label>：</td><td width="35%"><select id="v_status" name="v_status">';
                      foreach(Yii::app()->params['status'] as $k=>$v){
                            if($k==1){
                                $str .='<option value="'.$k.'" selected="selected" >'.$v.'</option>';
                            }else{
                                $str .='<option value="'.$k.'"  >'.$v.'</option>';
                            }
                            
                      }  
                   $str .='</select></td>  
                    <!--<td width="18%" height="30" align="center"><label>正片</label>：</td><td width="35%"><select id="v_genuine" name="v_genuine">
                    <option value="1" selected="selected">正片</option>
                    <option value="0" >非正片</option>
                    <option value="2">其他</option></select></td>--></tr>';
        $str .='<tr>
                <td width="18%" height="30" align="center"><strong>
                  <label>分辨率</label>：</strong></td><td width="40%" align="left"><select id="v_resolution" name="v_resolution">';
                  foreach(Yii::app()->params["resolution"] as $k=>$res){
                        if($data["resolution"]==$k){
                          $str .= '<option value="'.$k.'" selected="selected">'.$res.'</option>';  
                        }else{
                            $str .= '<option value="'.$k.'">'.$res.'</option>';  
                        }
                        
                  }
        $str .='</select></td><td width="18%" height="30" align="center"><label>星级</label>：</td><td width="35%"><select id="v_score" name="v_score">';
                 foreach(Yii::app()->params["score"] as $k=>$sco){
                     if($data['score']==$k){
                         $str.='<option value="'.$k.'" selected="selected">'.$sco.'</option>';
                     }else{
                         $str.='<option value="'.$k.'">'.$sco.'</option>';
                     } 
                 }
       $str .= '</select></td></tr>';           
       $py = new Py();
       $letter = $py->Pinyin($data["name"],'utf8');     
       $str .='<tr>
                    <td width="18%" height="30" align="center"><strong><label>字母检索</label>：</strong></td>
                    <td width="40%" align="left"><input type="text" name="v_letter" value="'.$letter.'" /></td>
                    <td width="18%" height="30" align="center"><strong><label>别名</label>：</strong></td>
                    <td width="40%" align="left"><input type="text" name="v_alias" value="'.$data["alias"].'" /></td></tr>';
                    
           $str .= '<tr>
                    <td width="40%" height="30" align="center"><label>适合年龄段</label>：</td><td width="35%"><select id="v_age" name="v_age">';
                     foreach(array('0'=>'请选择')+Yii::app()->params["ages"] as $k=>$sco){
                         if($data['score']==$k){
                             $str.='<option value="'.$k.'" selected="selected">'.$sco.'</option>';
                         }else{
                             $str.='<option value="'.$k.'">'.$sco.'</option>';
                         } 
                     }
                
          $str .= '</select></td></tr></tr>';
           
           $str .='<tr>
                <td width="18%" height="30" align="center"><strong><label>简介</label>：</strong></td>
                <td width="40%" align="left"><textarea id="v_desc" name="v_desc" style="width:334px; height:142px;">'.$data["desc"].'</textarea></td>
                <td width="18%" height="30" align="center">&nbsp;</td>
                <td width="35%"><input type="hidden" id="v_pic" name="v_pic"  value="'.$data["pic"].'"/></td></tr>
                ';
   
          $str .='<tr><td></td>
               <td valign="bottom" align="right"><input type="submit" value="确定" onclick="return check_sub()" class="tv_button" /></td>
               <td style="padding:20px 0 0 0px;" id="img_add"><img src="" /></td>
               </tr>';
        if($data["list"])foreach($data["list"] as $row){
             $str .='<tr style="display:none;"><td width="18%" height="30" align="center"><input name="tv_url[]" value="'.$row["tv_url"].'"></td>
                         <td width="40%" align="left"><input name="tv_id[]" value="'.$row["tv_id"].'"></td>
                         <td width="18%" height="30" align="center"><input name="tv_name[]" value="'.$row["tv_name"].'"></td>
                         <td width="18%" height="30" align="center"><input name="tv_source" value="'.$data["source"].'"></td>
                     </tr>';
        }    
               
        $str .='</table></form><div>总共获取分集'.count($data["list"]).'</div>'; 
        return $str;   
    
    }

    public function tv_info($data,$vid){
        $str = '<div><tr><td>视频名称：</td><td>'.$data["name"].'</td></tr>
                     <tr><td>视频来源：</td><td>'.$data["source"].'</td></tr>
                     <tr><td>导演：</td><td>'.$data["director"].'</td></tr>
                     <tr><td>主演：</td><td>'.$data["main_actors"].'</td></tr>
                     <tr><td>上映时间：</td><td>'.$data["year"].'</td></tr>
                </div>';
        $str .= '<form method="post" action="/show/Adds_tv/" enctype="multipart/form-data">
                <table cellpadding="0" cellspacing="0" border="0" width="690px">';
        if($data["list"])foreach($data["list"] as $row){
             $str .='<tr style="display:none;"><td width="18%" height="30" align="center"><input name="tv_url[]" value="'.$row["tv_url"].'"></td>
                         <td width="40%" align="left"><input name="tv_id[]" value="'.$row["tv_id"].'"></td>
                         <td width="18%" height="30" align="center"><input name="tv_name[]" value="'.$row["tv_name"].'"></td>
                         <td width="18%" height="30" align="center"><input name="tv_source" value="'.$data["source"].'"></td>
                     </tr>';
        }
        $str .='<tr>
                <td width="18%" height="30" align="center"><strong>
                  <label>分辨率</label>：</strong></td><td width="40%" align="left"><select id="v_resolution" name="v_resolution">';
                  foreach(Yii::app()->params["resolution"] as $k=>$res){
                        if(0==$k){
                          $str .= '<option value="'.$k.'" selected="selected">'.$res.'</option>';  
                        }else{
                            $str .= '<option value="'.$k.'">'.$res.'</option>';  
                        }  
                  }
        $str .='</select></td><tr>';
        $str .='<tr><td><input type="hidden" name="vid" value="'.$vid.'"></td>
               <td valign="bottom" align="right"><input type="submit" value="确定" onclick="return check_sub()" class="tv_button" /></td>
               <td style="padding:20px 0 0 0px;" id="img_add"><img src="" /></td>
               </tr>';  
        $str .='</table></form><div>总共获取分集'.count($data["list"]).'</div>';
        return $str;
    }
    

}