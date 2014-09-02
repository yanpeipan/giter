<?php

/**
 * This is the model class for table "{{v_list}}".
 *
 * The followings are the available columns in table '{{v_list}}':
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
 * @property string $soku_url
 * @property string $play_info
 */
class NewVideo extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @return NewVideo the static model class
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
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'name' => 'Name',
            'director' => 'Director',
            'main_actors' => 'Main Actors',
            'actors' => 'Actors',
            'desc' => 'Desc',
            'comment_desc' => 'Comment Desc',
            'url' => 'Url',
            'pic' => 'Pic',
            'free' => 'Free',
            'genuine' => 'Genuine',
            'resolution' => 'Resolution',
            'time_length' => 'Time Length',
            'area' => 'Area',
            'year' => 'Year',
            'category' => 'Category',
            'type' => 'Type',
            'play_count' => 'Play Count',
            'score' => 'Score',
            'support' => 'Support',
            'opposition' => 'Opposition',
            'source' => 'Source',
            'tv_application_time' => 'Tv Application Time',
            'time' => 'Time',
            'comment_count' => 'Comment Count',
            'third_video_id' => 'Third Video',
            'topic_id' => 'Topic',
            'letter' => 'Letter',
            'soku_url' => 'Soku Url',
            'play_info' => 'Play Info',
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
        $criteria->compare('name',$this->name,true);
        $criteria->compare('director',$this->director,true);
        $criteria->compare('main_actors',$this->main_actors,true);
        $criteria->compare('actors',$this->actors,true);
        $criteria->compare('desc',$this->desc,true);
        $criteria->compare('comment_desc',$this->comment_desc,true);
        $criteria->compare('url',$this->url,true);
        $criteria->compare('pic',$this->pic,true);
        $criteria->compare('free',$this->free);
        $criteria->compare('genuine',$this->genuine);
        $criteria->compare('resolution',$this->resolution);
        $criteria->compare('time_length',$this->time_length,true);
        $criteria->compare('area',$this->area);
        $criteria->compare('year',$this->year,true);
        $criteria->compare('category',$this->category);
        $criteria->compare('type',$this->type,true);
        $criteria->compare('play_count',$this->play_count);
        $criteria->compare('score',$this->score);
        $criteria->compare('support',$this->support);
        $criteria->compare('opposition',$this->opposition);
        $criteria->compare('source',$this->source,true);
        $criteria->compare('tv_application_time',$this->tv_application_time,true);
        $criteria->compare('time',$this->time);
        $criteria->compare('comment_count',$this->comment_count);
        $criteria->compare('third_video_id',$this->third_video_id,true);
        $criteria->compare('topic_id',$this->topic_id);
        $criteria->compare('letter',$this->letter,true);
        $criteria->compare('soku_url',$this->soku_url,true);
        $criteria->compare('play_info',$this->play_info,true);

        return new CActiveDataProvider(get_class($this), array(
            'criteria'=>$criteria,
        ));
    }
} 