<?php
abstract class ApiTestBase{
    private $url = 'https://gw.16tree.com/api';
    
    /**
     * 接口需要的字段数组
     */
    protected function getFieldsArr(){
        return array(
            'api.puti.getTid'=>array('tid'),
            'api.puti.video' =>array(
                'id',
                'name',
                'director',
                'main_actors',
                'actors',
                'desc',
                'comment_desc',
                'url',
                'pic',
                'free',
                'genuine',
                'resolution',
                'time_length',
                'area',
                'year',
                'category',
                'type',
                'play_count',
                'score',
                'support',
                'opposition',
                'source',
                'tv_application_time',
                'time',
                'comment_count',
                'third_video_id',
                'topic_id',
                'letter',
                'soku_url',
                'play_info',
                'is_show',
                'user_id',
            ),
            'api.puti.childvideo'=>array(
                'id',
                'tv_id',
                'tv_name',
                'pic',
                'tv_parent_id',
                'tv_url',
                'tv_play_count',
                'tv_support',
                'tv_opposition',
                'time_length',
                'source',
                'time',
                'user_id',
                'is_del',
            ),
            'api.puti.topicvideos'=>array(
                'id',
                'name',
                'director',
                'main_actors',
                'actors',
                'desc',
                'comment_desc',
                'url',
                'pic',
                'free',
                'genuine',
                'resolution',
                'time_length',
                'area',
                'year',
                'category',
                'type',
                'play_count',
                'score',
                'support',
                'opposition',
                'source',
                'tv_application_time',
                'time',
                'comment_count',
                'third_video_id',
                'topic_id',
                'letter',
                'soku_url',
                'play_info',
                'is_show',
                'user_id',   
                'pic_big',         
            ),
            'api.puti.likevideos'=>array(
                'id',
                'name',
                'director',
                'main_actors',
                'actors',
                'desc',
                'comment_desc',
                'url',
                'pic',
                'free',
                'genuine',
                'resolution',
                'time_length',
                'area',
                'year',
                'category',
                'type',
                'play_count',
                'score',
                'support',
                'opposition',
                'source',
                'tv_application_time',
                'time',
                'comment_count',
                'third_video_id',
                'topic_id',
                'letter',
                'soku_url',
                'play_info',
                'is_show',
                'user_id',
            ),
            'api.puti.playurllist'=>array(
                'id',
                'tv_id',
                'tv_name',
                'pic',
                'tv_parent_id',
                'tv_url',
                'tv_play_count',
                'tv_support',
                'tv_opposition',
                'time_length',
                'source',
                'time',
                'user_id',
                'is_del',
            ),
            'api.puti.mycollectlist'=>array(
                'id',
                'name',
                'director',
                'main_actors',
                'actors',
                'desc',
                'comment_desc',
                'url',
                'pic',
                'free',
                'genuine',
                'resolution',
                'time_length',
                'area',
                'year',
                'category',
                'type',
                'play_count',
                'score',
                'support',
                'opposition',
                'source',
                'tv_application_time',
                'time',
                'comment_count',
                'third_video_id',
                'topic_id',
                'letter',
                'soku_url',
                'play_info',
                'is_show',
                'user_id',            
            ),
            'api.puti.playhistorylist'=>array(
                'pic',
                'vid',
                'category',
                'play_status',
                'id',
                'star',
                'source',
                'tv_id',
                'url',
                'name',
                'is_collect',
            ),
            'api.puti.search'=>array(
                'id',
                'name',
                'director',
                'main_actors',
                'actors',
                'desc',
                'comment_desc',
                'url',
                'pic',
                'free',
                'genuine',
                'resolution',
                'time_length',
                'area',
                'year',
                'category',
                'type',
                'play_count',
                'score',
                'support',
                'opposition',
                'source',
                'tv_application_time',
                'time',
                'comment_count',
                'third_video_id',
                'topic_id',
                'letter',
                'soku_url',
                'play_info',
                'is_show',
                'user_id',     
            ),
        );
    }

    /**
     * @return array 
     */
    protected function getApis(){
        return array(
            'api.puti.getTid'=>array(
                'query'=>array(
                    'oauth_token'=>'12ef6c6ebcede1dbdde969c6cd3120ec',
                    'version'=>'1.1',
                    'method'=>'api.puti.getTid',
                ),
                'return_fields'=>array(),
            ),
            'api.puti.video'=>array(
                'query'=>array(
                    'cate'=>'2',
                    'type'=>'',
                    'area'=>'',
                    'year'=>'',
                    'page'=>'1',
                    'pageSize'=>'1',
                    'tid'=>'123123',
                    'oauth_token'=>'12ef6c6ebcede1dbdde969c6cd3120ec',
                    'version'=>'1.1',
                    'method'=>'api.puti.video',
                ),
                'return_fields'=>array(),
            ),
            'api.puti.childvideo'=>array(
                'query'=>array(
                    'vid'=>'821',
                    'oauth_token'=>'12ef6c6ebcede1dbdde969c6cd3120ec',
                    'version'=>'1.1',
                    'method'=>'api.puti.childvideo',
                ),
                'return_fields'=>array(),
            ),
            'api.puti.topicvideos'=>array(
                'query'=>array(
                    'cate'=>'2',
                    'topic_id'=>'3',
                    'oauth_token'=>'12ef6c6ebcede1dbdde969c6cd3120ec',
                    'version'=>'1.1',
                    'method'=>'api.puti.topicvideos',
                ),
                'return_fields'=>array(),
            ),
            'api.puti.likevideos'=>array(
                'query'=>array(
                    'cate'=>'2',
                    'vid' =>'821',
                    'oauth_token'=>'12ef6c6ebcede1dbdde969c6cd3120ec',
                    'version'=>'1.1',
                    'method'=>'api.puti.likevideos',
                ),
                'return_fields'=>array(),
            ),
            'api.puti.playurllist'=>array(
                'query'=>array(
                    'vid'=>'821',         
                    'oauth_token'=>'12ef6c6ebcede1dbdde969c6cd3120ec',
                    'version'=>'1.1',
                    'method'=>'api.puti.playurllist',
                ),
                'return_fields'=>array(),
            ),
            'api.puti.mycollectlist'=>array(
                'query'=>array(
                    'tid'=>'123123',
                    'oauth_token'=>'12ef6c6ebcede1dbdde969c6cd3120ec',
                    'version'=>'1.1',
                    'method'=>'api.puti.mycollectlist',
                ),
                'return_fields'=>array(),
            ),
            'api.puti.playhistorylist'=>array(
                'query'=>array(
                    'tid'=>'123123',
                    'oauth_token'=>'12ef6c6ebcede1dbdde969c6cd3120ec',
                    'version'=>'1.1',
                    'method'=>'api.puti.playhistorylist',
                ),
                'return_fields'=>array(),
            ),
            'api.puti.search'=>array(
                'query'=>array(
                    'condition'=>'a',
                    'page'=>'1',
                    'oauth_token'=>'12ef6c6ebcede1dbdde969c6cd3120ec',
                    'version'=>'1.1',
                    'method'=>'api.puti.search',
                ),
                'return_fields'=>array(),
            ),
        
        );
    }
    
    /**
     * @param array $params
     * @return array
     */
    protected function getData($params){
        $url    = $this->url;
        $method = $params['query']['method'];
        return Yii::app()->curl->run($url, false, $params['query']);
    }
    
    /**
     * @param string $method
     * @param array  $data
     * @return boolean
     */
    protected function chkFieldExists($method,$data){
        $fieldArr = self::getFieldsArr();
        $fields = $fieldArr[$method];
        
        $data = json_decode($data);
        $result = array();
        
        if($method == 'api.puti.getTid')
        {
            $result = self::dochkFields($fields, $data);
        }elseif($method == 'api.puti.video'||$method == 'api.puti.search'){
            if($data->videolist[0])
            {
                $data = $data->videolist[0];
                $result = self::dochkFields($fields, $data);
            }else{
                $result = $data->status;
            }
        }else{
            if(is_array($data))
            {
                if($data[0])
                {
                    $result = self::dochkFields($fields, $data[0]);
                }
            }else{
                $result = $data->status;
            }
        }
        
        /*
        if($method == 'api.puti.getTid')
        {
            $result = self::dochkFields($fields, $data);
        }elseif($method == 'api.puti.video'){
            if($data->videolist[0])
            {
                $data = $data->videolist[0];
                $result = self::dochkFields($fields, $data);
            }
        }elseif($method == 'api.puti.childvideo'){
            if($data[0])
            {
                $result = self::dochkFields($fields, $data[0]);
            }
        }elseif($method == 'api.puti.topicvideos'){
            if($data[0])
            {
                $result = self::dochkFields($fields, $data[0]);
            }
        }elseif($method == 'api.puti.likevideos'){
            if($data[0])
            {
                $result = self::dochkFields($fields, $data[0]);
            }
        }elseif($method == 'api.puti.playurllist'){
            if($data[0])
            {
                $result = self::dochkFields($fields, $data[0]);
            }
        }elseif($method == 'api.puti.mycollectlist'){
            if($data[0])
            {
                $result = self::dochkFields($fields, $data[0]);
            }
        }elseif($method == 'api.puti.playhistorylist'){
            if($data[0])
            {
                $result = self::dochkFields($fields, $data[0]);
            }
        }elseif($method == 'api.puti.search'){
            if($data->videolist[0])
            {
                $data = $data->videolist[0];
                $result = self::dochkFields($fields, $data);
            }
        }
         */ 
        
        return $result;
    }

    /**
     * 执行字段检查
     * @param $fields
     * @param $data
     */
    protected function dochkFields($fields, $data)
    {
        $result = array();
        foreach($fields as $val)
        {
            if(property_exists($data, $val))
            {
                if(empty($result['return_fields'])){
                    $result['return_fields'] = array($val);
                }else{
                    array_push($result['return_fields'],$val);
                }
            }else{
                if(empty($result['error_fields'])){
                    $result['error_fields'] = array($val);
                }else{
                    array_push($result['error_fields'],$val);
                }
            }
        }
        return $result;
    }
    
    abstract protected function testApi($params);
    
    
    abstract public function run();
    
}
