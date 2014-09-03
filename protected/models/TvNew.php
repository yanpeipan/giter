<?php

/**
 * This is the model class for table "{{v_tv}}".
 *
 * The followings are the available columns in table '{{v_tv}}':
 * @property integer $id
 * @property string $tv_id
 * @property string $tv_name
 * @property string $pic
 * @property integer $tv_parent_id
 * @property string $tv_url
 * @property string $tv_play_count
 * @property integer $tv_support
 * @property integer $tv_opposition
 * @property string $time_length
 * @property string $source
 * @property integer $time
 */
class TvNew extends CActiveRecord
{
    public $tv_parent_name;   
    /**
     * Returns the static model of the specified AR class.
     * @return TvNew the static model class
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
        return '{{v_tv}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('tv_id,tv_url, source','required'),
            array('tv_name', 'length', 'max'=>255),
            //array('tv_parent_id, tv_support, tv_opposition, time', 'numerical', 'integerOnly'=>true),
            //array('tv_id, tv_name, pic, tv_url, time_length, source', 'length', 'max'=>255),
            //array('tv_play_count', 'length', 'max'=>11),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
           array('id, tv_id, tv_name, pic, tv_parent_id, tv_url, tv_play_count, tv_support, tv_opposition, time_length, source, time', 'safe', 'on'=>'search'),
           array('tv_url','unique'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'video'=>array(self::BELONGS_TO, 'Video', 'tv_parent_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'tv_id' => 'Tv(第几集)',
            'tv_name' => '分集名字',
            'pic' => 'Pic',
            'tv_parent_id' => '所属视频ID',
            'tv_url' => '播放地址',
            'tv_play_count' => 'Tv Play Count',
            'tv_support' => 'Tv Support',
            'tv_opposition' => 'Tv Opposition',
            'time_length' => '时长',
            'source' => '来源(eg:youku或tudou...)',
            'time' => '更新时间',
            'is_del'=>'是否显示',
            'user_id' => '更新人'
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
        $criteria->compare('id',$this->id);
        $criteria->compare('tv_id',$this->tv_id,true);
        $criteria->compare('tv_name',$this->tv_name,true);
        $criteria->compare('pic',$this->pic,true);
        $criteria->compare('tv_parent_id',$this->tv_parent_id,true);
        if($this->tv_parent_id){
           $criteria->condition = "tv_parent_id IN ($this->tv_parent_id)";
        }
        $criteria->compare('tv_url',$this->tv_url,true);
        $criteria->compare('tv_play_count',$this->tv_play_count,true);
        $criteria->compare('tv_support',$this->tv_support);
        $criteria->compare('tv_opposition',$this->tv_opposition);
        $criteria->compare('time_length',$this->time_length,true);
        $criteria->compare('source',$this->source,true);
        $criteria->compare('time',$this->time);
        if($_SERVER['REQUEST_URI']=='/admin/source/view'||strpos($_SERVER['REQUEST_URI'],'TvNew_sort')===false){  
            $criteria->order = 'time DESC';
        } 
        return new CActiveDataProvider(get_class($this), array(
            'criteria'=>$criteria,
            'pagination'=>array(
                'pageSize'=>30,
            ),
        ));
    }

    public function getisdel(){
        if($this->is_del!=0){
            return $this->is_del='否';
        }else{
            return $this->is_del='是';
        }
    }
} 