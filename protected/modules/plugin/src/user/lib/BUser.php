<?php

/**
 * This is the model class for table "{{user}}".
 *
 * The followings are the available columns in table '{{user}}':
 * @property integer $id
 * @property string $loginname
 * @property string $lntype
 * @property string $password
 * @property string $salt
 * @property integer $regtime
 * @property integer $lastlogintime
 * @property string $regsource
 * @property string $nickname
 * @property string $mobile
 * @property string $avatar
 * @property string $email
 * @property string $regip
 * @property string $realname
 * @property integer $province
 * @property integer $city
 * @property string $sex
 * @property integer $score
 * @property integer $birth_year
 * @property integer $birth_month
 * @property integer $birth_day
 */
class BUser extends CActiveRecord
{
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
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('regtime, lastlogintime', 'required'),
			array('regtime, lastlogintime, province, city, score, birth_year, birth_month, birth_day', 'numerical', 'integerOnly'=>true),
			array('loginname, password, regsource, nickname, mobile', 'length', 'max'=>128),
			array('salt', 'length', 'max'=>50),
			array('avatar', 'length', 'max'=>255),
			array('email', 'length', 'max'=>32),
			array('regip', 'length', 'max'=>15),
			array('realname', 'length', 'max'=>20),
			array('sex', 'length', 'max'=>45),
			array('lntype', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, loginname, lntype, password, salt, regtime, lastlogintime, regsource, nickname, mobile, avatar, email, regip, realname, province, city, sex, score, birth_year, birth_month, birth_day', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'loginname' => '用户名',
			'lntype' => '登录账号类型',
			'password' => '密码',
			'salt' => '二次加密后缀',
			'regtime' => '注册时间',
			'lastlogintime' => '上次登录时间',
			'regsource' => '注册来源',
			'nickname' => '昵称',
			'mobile' => '手机号',
			'avatar' => '头像',
			'email' => '邮箱',
			'regip' => '注册ip',
			'realname' => '真实姓名',
			'province' => '省份ID',
			'city' => '城市ID',
			'sex' => '性别',
			'score' => '积分',
			'birth_year' => '出生年',
			'birth_month' => '月份',
			'birth_day' => '出生日期',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('loginname',$this->loginname,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('salt',$this->salt,true);
		$criteria->compare('regtime',$this->regtime);
		$criteria->compare('lastlogintime',$this->lastlogintime);
		$criteria->compare('regsource',$this->regsource,true);
		$criteria->compare('nickname',$this->nickname,true);
		$criteria->compare('mobile',$this->mobile,true);
		$criteria->compare('avatar',$this->avatar,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('regip',$this->regip,true);
		$criteria->compare('realname',$this->realname,true);
		$criteria->compare('province',$this->province);
		$criteria->compare('city',$this->city);
		$criteria->compare('sex',$this->sex,true);
		$criteria->compare('score',$this->score);
		$criteria->compare('birth_year',$this->birth_year);
		$criteria->compare('birth_month',$this->birth_month);
		$criteria->compare('birth_day',$this->birth_day);
		
		if(!isset($_REQUEST['BUser_sort'])){
			$criteria->order = 'id DESC';
		}
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(  
                   'attributes'=>array(  
                        'score', 'id','ctime',
                   ),  
               ),  
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BUser the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function getAddress() {
		$url = "http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=php&ip=";
		$content = file_get_contents($url.trim($this->regip));
		$content = iconv("GBK", "UTF-8", $content);
		$array = explode("\t", $content);
		// print_r($array);
		$pr = $array[4];
		$ci = $array[5];
		$kuandai = $array[7];
		$cc = $pr.','.$ci.','.$kuandai;
		return $cc;
	}
	
	public function getFensi() {
		$sql = "SELECT count(id) AS cnt  FROM {{user_friends}} WHERE uid=:uid";
		$count = Yii::app()->db->createCommand($sql)->bindValue(':uid', $this->id)->queryRow();
		return $count['cnt'];
	}
	
	public function getHistory() {
		$sql = "SELECT count(id) AS cnt  FROM {{user_history}} WHERE uid=:uid";
		$count = Yii::app()->db->createCommand($sql)->bindValue(':uid', $this->id)->queryRow();
		return $count['cnt'];
	}
	
	public function getCollect() {
		$sql = "SELECT count(id) AS cnt  FROM {{user_favorite}} WHERE uid=:uid";
		$count = Yii::app()->db->createCommand($sql)->bindValue(':uid', $this->id)->queryRow();
		return $count['cnt'];
	}
	
	public function getShare() {
		
	}
}