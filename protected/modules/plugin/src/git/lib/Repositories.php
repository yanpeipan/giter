<?php

/**
 * This is the model class for table "{{repositories}}".
 *
 * The followings are the available columns in table '{{repositories}}':
 * @property string $id
 * @property string $name
 * @property string $description
 * @property string $root_path
 * @property string $apache_group_file
 * @property string $apache_user_file
 * @property string $ip
 * @property string $apache_bin
 * @property integer $ssh_port
 * @property integer $url_port
 * @property string $htpasswd_bin
 */
class Repositories extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{repositories}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, description, root_path, apache_group_file, apache_user_file, url_host, apache_bin, ssh_port, url_port, htpasswd_bin, git_config_path', 'required'),
			array('ssh_port, url_port', 'numerical', 'integerOnly'=>true),
			array('name, url_schema', 'length', 'max'=>45),
			array('root_path', 'length', 'max'=>3841),
			array('apache_group_file, apache_user_file, apache_bin, htpasswd_bin', 'length', 'max'=>4096),
			array('ip', 'length', 'max'=>10),
			array('ipper', 'length', 'max'=>15),
			array('url_host', 'length', 'max'=>15),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, description, root_path, apache_group_file, apache_user_file, ip, apache_bin, ssh_port, url_port, htpasswd_bin', 'safe', 'on'=>'search'),
			//The following rule is used by path
			//array('apache_group_file, apache_user_file', 'application.modules.plugin.src.git.lib.Filename'),
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
			'name' => '名称',
			'description' => '描述',
			'root_path' => '版本库根目录',
			'apache_group_file' => 'Group文件',
			'apache_user_file' => 'User文件',
			'ip' => 'Ip',
			'ipper' => 'Ip',
			'apache_bin' => 'Apache命令文件',
			'ssh_port' => 'Ssh端口',
			'url_port' => 'HTTP(s)端口',
			'htpasswd_bin' => 'Htpasswd命令文件',
			'url_schema' => 'HTTP/HTTPS',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('root_path',$this->root_path,true);
		$criteria->compare('apache_group_file',$this->apache_group_file,true);
		$criteria->compare('apache_user_file',$this->apache_user_file,true);
		$criteria->compare('ip',$this->ip,true);
		$criteria->compare('apache_bin',$this->apache_bin,true);
		$criteria->compare('ssh_port',$this->ssh_port);
		$criteria->compare('url_port',$this->url_port);
		$criteria->compare('htpasswd_bin',$this->htpasswd_bin,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Repositories the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function getIpper()
	{
		return long2ip($this->ip);
	}

	public function setIpper($value='')
	{
		$this->ip = ip2long($value);
		return $this->ip;
	}
}
