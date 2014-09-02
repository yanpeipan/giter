<?php

/**
 * This is the model class for table "{{v_topic}}".
 *
 * The followings are the available columns in table '{{v_topic}}':
 * @property integer $id
 * @property integer $vid
 * @property integer $topic
 * @property integer $sort
 * @property integer $ctime
 * @property integer $category
 * @property string $imgurl
 */
class Recommend extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @return Recommend the static model class
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
        return '{{v_topic}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('vid, topic, sort, ctime, category', 'numerical', 'integerOnly'=>true),
            array('imgurl', 'length', 'max'=>255),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, vid, topic, sort, ctime, category', 'safe', 'on'=>'search'),
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
            'video'=>array(self::BELONGS_TO,'Video','vid'),
            'topic'=>array(self::BELONGS_TO,'Topic','vid'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'vid' => 'Vid',
            'topic' => 'Topic',
            'sort' => '排序',
            'ctime' => 'Time',
            'category' => 'Category',
            'imgurl' => 'Imgurl',
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
        $criteria->compare('vid',$this->vid);
        $criteria->compare('topic',$this->topic);
        $criteria->compare('sort',$this->sort);
        $criteria->compare('ctime',$this->ctime);
        $criteria->compare('category',$this->category);
        $criteria->compare('imgurl',$this->imgurl,true);

        $criteria->sort = '`sort` ASC';
        $criteria->limit = 10;
        return new CActiveDataProvider(get_class($this), array(
            'criteria'=>$criteria,
            'pagination'=>array(
                'pageSize'=>20,
            ),
        ));
    }
} 