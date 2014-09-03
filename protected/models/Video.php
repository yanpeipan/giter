<?php

/**
 * This is the model class for table "{{video}}".
 *
 * The followings are the available columns in table '{{video}}':
 * @property integer $id
 * @property string $name
 * @property string $director
 * @property string $main_actors
 * @property string $actors
 * @property string $desc
 * @property string $comment_desc
 * @property string $url
 * @property string $pic
 * @property integer $free
 * @property integer $genuine
 * @property integer $resolution
 * @property string $time_length
 * @property integer $area
 * @property string $year
 * @property integer $category
 * @property string $type
 * @property integer $play_count
 * @property double $score
 * @property integer $support
 * @property integer $opposition
 * @property string $source
 * @property string $tv_application_time
 * @property integer $time
 * @property integer $comment_count
 * @property string $third_video_id
 * @property integer $topic_id
 * @property string $letter
 */
class Video extends CActiveRecord
{
    public $geshi;
    
    /**
     * Returns the static model of the specified AR class.
     * @return Video the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{v_list}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        $scenario = $this->getScenario();
        if($scenario=='update')
        {
            return array(
                array('name,area, year, category, type', 'required'),
                array('free, genuine, resolution, category, support, opposition, time', 'numerical', 'integerOnly'=>true),
                array('name, director, main_actors, actors, url, time_length,source, tv_application_time, type', 'length', 'max'=>255),
                //array('pic', 'file', 'types'=>'jpg,png,gif'),
                // The following rule is used by search().
                // Please remove those attributes that should not be searched.
                array('id, name, director, main_actors, actors, desc, comment_desc, url, pic, free, genuine, resolution, time_length, area, year, category, type, play_count, score, support, opposition, source, tv_application_time, time, comment_count, third_video_id, topic_id', 'safe', 'on'=>'search'),
            );
        }else{
            return array(
                //array('name, director, main_actors, desc,time_length, url, pic, area, year, category, type, time', 'required'),
                array('name, area, year, category, type, time', 'required'),
                array('free, genuine, resolution, category, support, opposition, time', 'numerical', 'integerOnly'=>true),
                array('name, director, main_actors, actors, url, time_length, source, tv_application_time, type', 'length', 'max'=>255),
                //array('pic', 'file', 'types'=>'jpg,png,gif'),
                // The following rule is used by search().
                // Please remove those attributes that should not be searched.
                array('id, name, director, main_actors, actors, desc, comment_desc, url, pic, free, genuine, resolution, time_length, area, year, category, type, play_count, score, support, opposition, source, tv_application_time, time, comment_count, third_video_id, topic_id', 'safe', 'on'=>'search'),
            );
        }
        
        
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        /*
        return array(
            array('name, director, main_actors, actors, desc, comment_desc, url, pic, free, genuine, resolution, time_length, area, year, category, type, play_count, score, support, opposition, source, tv_application_time, time', 'required'),
            array('free, genuine, resolution, area, category, play_count, support, opposition, time, comment_count, topic_id', 'numerical', 'integerOnly'=>true),
            array('score', 'numerical'),
            array('name, director, main_actors, actors, url, pic, time_length, year, type, source, tv_application_time, third_video_id, letter', 'length', 'max'=>255),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, name, director, main_actors, actors, desc, comment_desc, url, pic, free, genuine, resolution, time_length, area, year, category, type, play_count, score, support, opposition, source, tv_application_time, time, comment_count, third_video_id, topic_id, letter', 'safe', 'on'=>'search'),
        );
         */
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'topic'=>array(self::BELONGS_TO, 'Topic', 'topic_id'),
            'categorys'=>array(self::BELONGS_TO, 'Category', 'category'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'name' => '视频名称',
            'director' => '导演',
            'main_actors' => '主演',
            'actors' => '演员',
            'desc' => '简介',
            'comment_desc' => '推荐描述',
            'url' => '播放地址',
            'pic' => '封面地址',
            'free' => '收费',
            'genuine' => '正片',
            'resolution' => '分辨率',
            'time_length' => '时长',
            'area' => '地区',
            'year' => '年份',
            'category' => '分类',
            'type' => '类型',
            'play_count' => '播放次数',
            'score' => '星',
            'support' => '顶',
            'opposition' => '踩',
            'source' => '来源',
            'tv_application_time' => '上映时间',
            'time' => '添加时间',
            'comment_count' => '评论',
            'third_video_id' => '第三方ID',
            'topic_id' => '所属专题',
            'letter' => '字母索引',
            'geshi' =>'视频格式',
            'is_show'=>'是否显示',
            'categorycount'=>'来源分集汇总',
            'user_id' => '更新人',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.
        $criteria=new CDbCriteria;
        $criteria->compare('t.id',$this->id);
        $criteria->compare('name',$this->name,true);
        $criteria->compare('director',$this->director,true);
        $criteria->compare('main_actors',$this->main_actors,true);
        $criteria->compare('actors',$this->actors,true);
        $criteria->compare('desc',$this->desc,true);
        $criteria->compare('comment_desc',$this->comment_desc,true);
        $criteria->compare('url',$this->url,true);
        $criteria->compare('pic',$this->pic,true);
        $criteria->compare('free',$this->free);
        if($this->genuine!=0){
            $criteria->compare('genuine',$this->genuine);
        }
        $criteria->compare('resolution',$this->resolution);
        $criteria->compare('time_length',$this->time_length,true);
        if($this->area!=0){
            $criteria->compare('area',$this->area);
        }
        $criteria->compare('year',$this->year,true);
        if($this->category!=0){
            $criteria->compare('t.category',$this->category);
        }
        $criteria->compare('type',$this->type,true);
        $criteria->compare('play_count',$this->play_count);
        $criteria->compare('score',$this->score);
        $criteria->compare('support',$this->support);
        $criteria->compare('opposition',$this->opposition);
        $criteria->compare('source',$this->source,true);
        $criteria->compare('tv_application_time',$this->tv_application_time,true);
        if($this->time!=0)$criteria->compare('t.time',$this->time);
        $criteria->compare('comment_count',$this->comment_count);
        $criteria->compare('third_video_id',$this->third_video_id,true);
        //$criteria->compare('topic_id',$this->topic_id);
        $criteria->compare('letter',$this->letter,true);
        $criteria->compare('soku_url',$this->soku_url,true);
        $criteria->compare('play_info',$this->play_info,true);

        //按专题查询时忽略分类
        
        if(empty($this->topic_id))
        {
            //处理分集列表
            if($this->getScenario()=='addtv')
            {  
               //$cate = Yii::app()->params['cate_arr'];
               //$criteria->addInCondition('t.category', $cate);
            }
        }
        $criteria->with = array('topic');
        //var_dump($_SERVER['REQUEST_URI']); 
        if($_SERVER['REQUEST_URI']=='/admin/video/view'||strpos($_SERVER['REQUEST_URI'],'Video_sort')===false){  
            $criteria->order = 't.id DESC';
        } 
        return new CActiveDataProvider(get_class($this), array(
            'criteria'=>$criteria,
            'pagination'=>array(
                'pageSize'=>100,
            ),
        ));
    }

    public function getcategorycount(){
        $source = $this->source;
        if($source){
            $source1 = explode(',',$source);
            $count = '';
            foreach($source1 as $key=>$row){
              $sql = "SELECT COUNT(*) as cnt FROM {{v_tv}} WHERE source=:source AND tv_parent_id=:tv_parent_id";
              $cmd_type = Yii::app()->db->createCommand($sql);
              $cmd_type->bindValue(':source',$row);
              $cmd_type->bindValue(':tv_parent_id',$this->id);
              $rows_type= $cmd_type->queryRow();
              $count .= $row."(".$rows_type['cnt'].")".' ';        
            } 
            echo $count;  
        }   
    }
    
    public function getshow(){
        if($this->is_show!=0){
           return $this->is_show = '否';
        }else{
           return $this->is_show = '是';
        }
    }
} 