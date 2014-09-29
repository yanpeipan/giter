<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
    public $uid;
    public $encrypt;
    /**
     * Authenticates a user.
     * The example implementation makes sure if the username and password
     * are both 'demo'.
     * In practical applications, this should be changed to authenticate
     * against some persistent user identity storage (e.g. database).
     * @return boolean whether authentication succeeds.
     */
    public function getId(){
        return $this->uid;
    }
    
    public static function encrypt($password)
    {
	return substr(md5($password),8,16);
    }
    
    public function authenticate()
    {
        $model = new Admin;
        $model = $model->find("username=?",array($this->username));
        if($model)
        {
            $this->uid = $model->id;
            $password = self::encrypt($this->password);
            if($password !== $model->password)
            {
                $this->errorCode=self::ERROR_PASSWORD_INVALID;
            }else{
                $this->errorCode=self::ERROR_NONE;
            }
        }else{
            $this->errorCode=self::ERROR_PASSWORD_INVALID;
        }
        return !$this->errorCode;
    }

    
}
