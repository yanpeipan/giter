<?php
class AdminModule extends CWebModule
{
	public $tableUsers = '{{admin}}';   
    public function init()
    {
        // this method is called when the module is being created
        // you may place code here to customize the module or the application

        // import the module-level models and components
        $this->setImport(array(
            'admin.models.*',
            'admin.components.*',
        ));
    }

    public function beforeControllerAction($controller, $action)
    {
        if(parent::beforeControllerAction($controller, $action))
        {
            return true;
        }
        else
            return false;
    }
		/**
	 * @param $str
	 * @param $params
	 * @param $dic
	 * @return string
	 */
	public static function t($str='',$params=array(),$dic='user') {
		if (Yii::t("UserModule", $str)==$str)
		    return Yii::t("AdminModule.".$dic, $str, $params);
        else
            return Yii::t("AdminModule", $str, $params);
	}
		/**
	 * @return hash string.
	 */
	public static function encrypting($string="") {
		 $password = substr(md5($string),8,16);
		 return $password;
	}
	
}