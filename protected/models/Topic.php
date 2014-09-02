<?php

/**
 * This is the model class for table "{{topic}}".
 *
 * The followings are the available columns in table '{{topic}}':
 * @property integer $id
 * @property string $topic_name
 * @property integer $time
 */
class Topic extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @return Topic the static model class
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
        return '{{topic}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('time', 'numerical', 'integerOnly'=>true),
            array('topic_name', 'length', 'max'=>255),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, topic_name, time', 'safe', 'on'=>'search'),
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
            'topic_name' => '专题名',
            'time' => '添加时间',
            'order'=>'排序',
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
        $criteria->compare('topic_name',$this->topic_name,true);
        $criteria->compare('time',$this->time);
        
        $criteria->order = 't.id DESC';

        return new CActiveDataProvider(get_class($this), array(
            'criteria'=>$criteria,
        ));
    }
} 











