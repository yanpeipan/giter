<?php
/**
 * PluginBaseController
 * 所有的插件都要继承此controller
 * @author xiongchuan <xiongchuan@luxtonenet.com>
 */
class PluginBaseController extends AdminBaseController
{
	protected $pluginName = "";
	
	public function init()
	{
		parent::init();
		$this->getPluginName();
	}
	
	public function getViewPath()
	{
		if(($module=$this->getModule())===null)
			$module=Yii::app();
		return $module->getViewPath();
	}
	
	/**
	 * 通过类名获取插件的名字
	 */
	public function getPluginName()
	{
		$className = get_called_class();
		$this->pluginName = str_replace("Controller", "", $className);
	}
}
