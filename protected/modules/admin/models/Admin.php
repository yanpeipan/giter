<?php

/**
 * This is the model class for table "pt_admin".
 *
 * The followings are the available columns in table 'pt_admin':
 * @property integer $id
 * @property string $username
 * @property string $password
 */
class Admin extends CActiveRecord
{
    public $username;
    public $password;
    public $rememberMe;

    //public $encrypt;
    private $_identity;

    
    /**
     * Returns the static model of the specified AR class.
     * @return Admin the static model class
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
        return '{{admin}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        $scenario = Yii::app()->controller->action->id;
        if($scenario=='update'||$scenario=='add'){
            return array(
                array('username, password, encrypt', 'required'),
                array('username','unique'),
                array('username, password', 'length', 'max'=>255),
                // The following rule is used by search().
                // Please remove those attributes that should not be searched.
                array('id, username, password, encrypt', 'safe', 'on'=>'search'),
                array('rememberMe', 'boolean',),
                // password needs to be authenticated
                //array('password', 'authenticate', 'on'=>'login'),
                );
        }else{
            return array(
                array('username, password', 'required'),
                array('username','unique','on'=>'update'),
                array('username, password, encrypt', 'length', 'max'=>255),
                // The following rule is used by search().
                // Please remove those attributes that should not be searched.
                array('id, username, password,is_super_admin, encrypt', 'safe', 'on'=>'search'),
                array('rememberMe', 'boolean',),
                // password needs to be authenticated
                array('password', 'authenticate'),
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
            'username' => '用户名',
            'password' => '密码',
            'rememberMe' =>'记住我',
        	//'is_super_admin'=>'编辑类型',
            );
    }

    public function setEncrypt($value='')
    {
            var_dump($value);
            die;
    }
    
    
    /**
     * Authenticates the password.
     * This is the 'authenticate' validator as declared in rules().
     */
    public function authenticate($attribute,$params)
    {
        if(!$this->hasErrors())
        {
            $this->_identity=new UserIdentity($this->username,$this->password,$this->id);
            if(!$this->_identity->authenticate())
                $this->addError('password','用户名或密码不正确');
        }
    }

    /**
     * Logs in the user using the given username and password in the model.
     * @return boolean whether login is successful
     */
    public function login()
    {
        if($this->_identity===null)
        {
            $this->_identity=new UserIdentity($this->username,$this->password,$this->id);
            $this->_identity->authenticate();
        }
        if($this->_identity->errorCode===UserIdentity::ERROR_NONE)
        {
            $duration=$this->rememberMe ? 3600*24*30 : 0; // 30 days
            Yii::app()->user->login($this->_identity,$duration);
            return true;
        }
        else
            return false;
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
        $criteria->compare('username',$this->username,true);
        $criteria->compare('password',$this->password,true);
        //$criteria->compare('is_super_admin',$this->is_super_admin,true);

        return new CActiveDataProvider(get_class($this), array(
            'criteria'=>$criteria,
            ));
    }
    
    public function getisadmin(){
        if($this->is_super_admin==3){
         return $this->is_super_admin = AdminModule::t('超级管理员');
     }else{
        return $this->is_super_admin = AdminModule::t('普通管理员');
    }
}
public function getMyself(){
  $criteria=new CDbCriteria;

  $criteria->compare('id',$this->id);
  $criteria->compare('username',$this->username,true);
  $criteria->compare('password',$this->password,true);
  $criteria->condition='id='.Yii::app()->user->id;
  return new CActiveDataProvider(get_class($this), array(
    'criteria'=>$criteria,
    ));
}

    private  static  function key()
    {
       return pack('H*', "bcb04b7e103a0cd8b54763051cef08bc55abe029fdebae5e1d417e2ffb2a00a3"); 
    }
    public  static function encrypt($data)
    {
       $cipher  = MCRYPT_RIJNDAEL_128;
       $key = self::key();
       $model = MCRYPT_MODE_CBC;
       $iv = mcrypt_create_iv(mcrypt_get_iv_size($cipher, $model), MCRYPT_RAND); 
        $ciphertext = mcrypt_encrypt($cipher, $key, $data, $model, $iv);
        return base64_encode($iv . $ciphertext);
    }

    public static function decrypt($ciphertext)
    {

        $ciphertext_dec = base64_decode($ciphertext);
        $key = self::key();
        $model = MCRYPT_MODE_CBC;
        $cipher  = MCRYPT_RIJNDAEL_128;

        $iv_size = mcrypt_get_iv_size($cipher, $model);
        $iv_dec = substr($ciphertext_dec, 0, $iv_size);
        $ciphertext_dec = substr($ciphertext_dec, $iv_size);

        return rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $ciphertext_dec, MCRYPT_MODE_CBC, $iv_dec));
    }
} 