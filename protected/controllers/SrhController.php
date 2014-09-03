<?php
class SrhController extends Controller{
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
                    'Search',
                ),
            'users'=>array('?'),
            ),
        );
    }
    
    public function actionIndex(){
        $this->render('search');
    }
    
    public function actionSearch(){
        $letter = isset($_REQUEST['val'])?$_REQUEST['val']:'';
        $data = $this->getApiData($letter);
        //var_dump($data);die;
        $str = '<h3>总共搜索到'.$data["count"].'</h3><ul>';
        if($data["count"]!=0)foreach($data["videolist"] as $row){
             $str .= '<li><a href="/show/VideoResult/id/'.$row['id'].'"><img src="'.$row["pic"].'" /></a>
                          <p><a href="/show/VideoResult/id/'.$row['id'].'" title="'.$row["name"].'">'.mb_substr($row["name"],0,8,"UTF-8").'</a></p>
                      </li>';
        }
        $str .= '</ul>';
        echo $str;
    }

    //api 获取
    public function getApiData($letter=''){
        $arr = Yii::app()->curl->run('https://gw.16tree.com/api',false,array('oauth_token'=>'a1911a950c2ea7e3b1fb0696a60daec6','method'=>'api.puti.search','version'=>'1.1','condition'=>$letter));
        $data = json_decode($arr,true);
        return $data;
    }




}