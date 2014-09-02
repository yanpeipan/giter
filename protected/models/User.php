<?php

/**
 * This is the model class for table "{{user}}".
 *
 * The followings are the available columns in table '{{user}}':
 * @property integer $id
 * @property integer $uid
 * @property string $email
 * @property string $password
 * @property string $salt
 * @property integer $time
 */
class User extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @return User the static model class
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
        return '{{user}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        $scenario = $this->getScenario();
        if($scenario=='register')
        {
            return array(
                array('email, password, salt','required'),
                array('email', 'email'),//格式验证
                array('email', 'authentic_email'),//唯一性验证(uc_members表)
                array('email, password', 'length', 'max'=>32),
                array('password', 'length', 'min'=>6),//密码最小6位
                array('salt', 'length', 'max'=>6),
                array('uid, time', 'numerical', 'integerOnly'=>true),
            );
        }else{
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('uid, time', 'numerical', 'integerOnly'=>true),
                array('email, password', 'length', 'max'=>32),
                array('salt', 'length', 'max'=>6),
                // The following rule is used by search().
                // Please remove those attributes that should not be searched.
                array('id, uid, email, password, salt, time', 'safe', 'on'=>'search'),
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
            'uid' => 'Uid',
            'email' => 'Email',
            'password' => 'Password',
            'salt' => 'Salt',
            'time' => 'Time',
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
        $criteria->compare('uid',$this->uid);
        $criteria->compare('email',$this->email,true);
        $criteria->compare('password',$this->password,true);
        $criteria->compare('salt',$this->salt,true);
        $criteria->compare('time',$this->time);

        return new CActiveDataProvider(get_class($this), array(
            'criteria'=>$criteria,
        ));
    }
    
    /**
     * 检查email在uc_member表的唯一性
     */
    public function authentic_email()
    {
        $email = $this->email;
        
        $sql = "SELECT * FROM {{members}} WHERE email=:email";
        $cmd = Yii::app()->db_user->createCommand($sql);
        $cmd->bindValue(':email', $email);
        $row = $cmd->queryRow();
        if($row)
        {
            $this->addError('email','email已经被占用');
        }
    }
    
    
    
    
} 