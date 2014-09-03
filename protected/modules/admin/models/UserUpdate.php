<?php
/**
 * UserChangePassword class.
 * UserChangePassword is the data structure for keeping
 * user change password form data. It is used by the 'changepassword' action of 'UserController'.
 */
class UserUpdate extends CFormModel {
	public $username;
	public $oldPassword;
	public $password;
	public $verifyPassword;
	
	public function rules() {	
	return 	array(
				array('username,oldPassword, password, verifyPassword', 'required'),
				array('username,oldPassword, password, verifyPassword', 'length', 'max'=>128, 'min' => 4,'message' => AdminModule::t("Incorrect password (minimal length 4 symbols).")),
				array('verifyPassword', 'compare', 'compareAttribute'=>'password', 'message' => AdminModule::t("Retype Password is incorrect.")),
				//array('username', 'unique','message' => AdminModule::t("Username must be unique!")),
				array('oldPassword', 'verifyOldPassword'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'username'=>AdminModule::t("username"),
			'oldPassword'=>AdminModule::t("Old Password"),
			'password'=>AdminModule::t("password"),
			'verifyPassword'=>AdminModule::t("Retype Password"),
		);
	}
	
	/**
	 * Verify Old Password
	 */
	 public function verifyOldPassword($attribute, $params)
	 {
		 if (Admin::model()->findByPk($_GET['id'])->password != Yii::app()->getModule('admin')->encrypting($this->$attribute)){
		 	 $this->addError($attribute, AdminModule::t("Old Password is incorrect."));
		 }
			
	 }
}