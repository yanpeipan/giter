<?php
/**
 * Plugin
 * @author xiongchuan <xiongchuan@luxtonenet.com>
 */
class PluginModule extends CWebModule
{
    public function init()
    {
        $this->setImport(array(
        	'admin.components.AdminBaseController',
            'plugin.components.*',
        ));
		
    }

    public function beforeControllerAction($controller, $action)
    {
    	$this->setPluginViewPath();
        if(parent::beforeControllerAction($controller, $action))
        {
            return true;
        }
        else
            return false;
    }
	
	public function setPluginViewPath()
	{
		$pluginid = strtolower(Yii::app()->controller->id);
		$path = dirname(__FILE__).DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR.$pluginid.DIRECTORY_SEPARATOR.'views';
		$this->setViewPath($path);
	}
}